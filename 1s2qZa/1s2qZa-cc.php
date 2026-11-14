<?php
session_start();

if (isset($_SESSION['admin_email'])) {
    require_once('adb.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level !== 'main_admin' && $admin_level !== 'sub_admin') {
        header('Location: 1s2qZa-lf.php');
        exit;
    } 
} else {
    header('Location: index.php');
    exit;
}

$conn->exec("SET NAMES 'utf8'");
$conn->exec("SET CHARACTER SET utf8");
$conn->exec("SET SESSION collation_connection = 'utf8_general_ci'");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer Un Cours</title>
    <link rel="stylesheet" type="text/css" href="../..\Css/cc.css">
</head>
<body>
<section id="create-course">
        <div class="course-container">
            <div class="course-label">
                <h1>Créer un cours</h1>            
            </div>
            <form action="c_c_wp_v.php" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                <label for="course_name">Nom du cours :</label>
                <input type="text" id="course_name" name="course_name"><br><br>

                <label for="course_image">Image du cours :</label>
                <input type="file" id="course_image" name="course_image" accept="image/*"><br><br>

                <label for="price">Prix :</label>
                <input type="number" id="price" name="price"><br><br>

                <label for="category_id">Catégorie :</label>
                <select id="category_id" name="category_id">
                    <?php
                    $query = "SELECT * FROM Categories";
                    $result = $conn->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['category_id'] . "'>" . htmlspecialchars($row['category_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                    ?>
                </select><br><br>

                <label for="language_id">Langue :</label>
                <select id="language_id" name="language_id">
                    <?php
                    $query = "SELECT * FROM Languages";
                    $result = $conn->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['language_id'] . "'>" . htmlspecialchars($row['language_name'], ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                    ?>
                </select><br><br>

                <label for="contact_info">Contact :</label>
                <input type="text" id="contact_info" name="contact_info"><br><br>

                <label for="parts">Nombre de parties :</label>
                <input type="number" id="parts" name="parts" min="1"><br><br>
                <div id="parts-container">
                </div>

                <input type="submit" value="Créer le cours">

                <div class="course-message" id="error-message"></div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const partsContainer = document.getElementById('parts-container');
                    const partsInput = document.getElementById('parts');

                    partsInput.addEventListener('input', () => {
                        partsContainer.innerHTML = '';

                        const partsCount = parseInt(partsInput.value);
                        for (let i = 1; i <= partsCount; i++) {
                            const partDiv = document.createElement('div');
                            partDiv.innerHTML = `
                                <h3>Partie ${i}</h3>
                                <label for="part_title_${i}">Titre de la partie ${i} :</label>
                                <input type="text" id="part_title_${i}" name="part_title_${i}"><br><br>

                                <div id="videos-container-${i}">
                                    <label for="video_title_${i}_1">Titre de la vidéo 1 :</label>
                                    <input type="text" id="video_title_${i}_1" name="video_title_${i}_1"><br>
                                    <label for="video_id_${i}_1">ID de la vidéo YouTube 1 :</label>
                                    <input type="text" id="video_id_${i}_1" name="video_id_${i}[]"><br><br>
                                </div>
                                <button type="button" id="add-video-${i}" onclick="addVideo(${i})">Ajouter une autre vidéo</button><br><br>
                            `;
                            partsContainer.appendChild(partDiv);
                        }
                    });
                });

                function addVideo(partNumber) {
                    const videosContainer = document.getElementById(`videos-container-${partNumber}`);
                    const videoCount = videosContainer.querySelectorAll('input[type="text"]').length / 2 + 1;

                    const newVideoDiv = document.createElement('div');
                    newVideoDiv.innerHTML = `
                        <label for="video_title_${partNumber}_${videoCount}">Titre de la vidéo ${videoCount} :</label>
                        <input type="text" id="video_title_${partNumber}_${videoCount}" name="video_title_${partNumber}_${videoCount}"><br>
                        <label for="video_id_${partNumber}_${videoCount}">ID de la vidéo YouTube ${videoCount} :</label>
                        <input type="text" id="video_id_${partNumber}_${videoCount}" name="video_id_${partNumber}[]"><br><br>
                    `;

                    videosContainer.appendChild(newVideoDiv);
                }

                document.addEventListener('DOMContentLoaded', () => {
                    const form = document.querySelector('form');
                    const errorMessage = document.getElementById('error-message');

                    form.addEventListener('submit', (event) => {
                        event.preventDefault();

                        const nomCours = document.getElementById('course_name').value;
                        const prix = document.getElementById('price').value;
                        const contactInfo = document.getElementById('contact_info').value;
                        const parties = document.getElementById('parts').value;
                        const imageCours = document.getElementById('course_image').value;

                        let erreurs = [];

                        if (!nomCours.trim()) {
                            erreurs.push("Le nom du cours est requis.");
                        }

                        if (!imageCours) {
                            erreurs.push("L'image du cours est requise.");
                        }

                        if (!prix.trim() || isNaN(prix) || prix <= 0) {
                            erreurs.push("Le prix doit être un nombre positif valide.");
                        }

                        if (!contactInfo.trim()) {
                            erreurs.push("Les informations de contact sont requises.");
                        }

                        if (!parties.trim() || isNaN(parties) || parties <= 0 || !Number.isInteger(+parties)) {
                            erreurs.push("Le nombre de parties doit être un entier positif valide.");
                        }

                        const tousTitresParties = Array.from(document.querySelectorAll('input[id^="part_title_"]')).map(input => input.value.trim());
                        if (tousTitresParties.some(titre => !titre)) {
                            erreurs.push("Tous les titres des parties sont requis.");
                        }

                        let videoErrorsFound = false;
                        for (let i = 1; i <= parties; i++) {
                            const videosContainer = document.getElementById(`videos-container-${i}`);
                            const videoTitles = Array.from(videosContainer.querySelectorAll('input[id^="video_title_"]')).map(input => input.value.trim());
                            const videoIds = Array.from(videosContainer.querySelectorAll('input[id^="video_id_"]')).map(input => input.value.trim());
                            if (videoTitles.some(titre => !titre) || videoIds.some(id => !id)) {
                                erreurs.push(`Les titres et IDs des vidéos pour la partie ${i} sont requis.`);
                                videoErrorsFound = true;
                                break;
                            }
                        }

                        if (erreurs.length > 0) {
                            errorMessage.textContent = erreurs[0];
                        } else {
                            form.submit();
                        }
                    });
                });
            </script>
        </div>
    </section>
</body>
</html>