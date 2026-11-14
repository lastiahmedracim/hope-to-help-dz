<?php
session_start();
if (isset($_SESSION['admin_email'])) {
    $label = "Les formations";
    require_once('global.php'); 
    header('Content-Type: text/html; charset=utf-8'); 
    require_once('adb.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

} else {
    header('Location: index.php');
    exit;
}
?>
    <?php if ($admin_level === 'main_admin') : ?>
        <section id="al">
            <div class="container">
                <div class="al-content">
                    <div id="cc"><button onclick="window.location.href='1s2qZa-cc.php'">Créer une formation</button></div>
                    <?php if (isset($_SESSION['course_created']) && $_SESSION['course_created']): ?>
                        <div class="cc-success-message">
                            Votre formation a été créée avec succès !
                        </div>
                        <?php 
                        unset($_SESSION['course_created']);
                        ?>
                    <?php endif; ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Nom de la formation</th>
                                <th>Contact</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT c.course_id, c.course_name, co.contact_info 
                                      FROM Courses c 
                                      JOIN Contacts co ON c.contact_id = co.contact_id";
                            $result = $conn->query($query);
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['contact_info']) . "</td>";
                                echo "<td><button onclick='openEditModal(" . $row['course_id'] . ")'>Modifier</button></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>   
                </div>
            </div>
        </section>
        <div id="editCourseModal" class="Coursemodal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Modifier la formation</h2>
                <form id="editCourseForm" enctype="multipart/form-data" accept-charset="UTF-8">
                    <input type="hidden" id="courseId" name="courseId">
                    <input type="hidden" id="originalData" name="originalData">
                    <label for="courseName">Nom de la formation :</label>
                    <input type="text" id="courseName" name="courseName" required>
                    
                    <label for="coursePrice">Prix :</label>
                    <input type="number" id="coursePrice" name="coursePrice" required>
                    
                    <label for="courseContact">Contact :</label>
                    <input type="text" id="courseContact" name="courseContact" required>
                    
                    <label for="courseImage">Image de la formation :</label>
                    <input type="file" id="courseImage" name="courseImage" accept="image/*">
                    <div id="currentImage"></div>
                    
                    <div id="courseParts">
                    </div>
                    
                    <button type="button" onclick="addCoursePart()">Ajouter une nouvelle partie</button>
                    <input type="submit" value="Sauvegarder les modifications">
                    <p class="course-message" id="courseMessage"></p>
                </form>
            </div>
        </div>

        <script>
        function openEditModal(courseId) {
            $.ajax({
                url: 'g_c_d.php',
                method: 'GET',
                data: { courseId: courseId },
                dataType: 'json',
                success: function(course) {
                    $('#courseId').val(course.course_id);
                    $('#courseName').val(course.course_name);
                    $('#coursePrice').val(course.price);
                    $('#courseContact').val(course.contact_info);
                    $('#originalData').val(JSON.stringify(course));

                    $('#currentImage').html(course.course_img ? `Image actuelle : <img src="course_images/${course.course_img}" alt="Image de la formation" style="max-width: 200px;">` : 'Aucune image téléchargée');

                    $('#courseParts').empty();
                    course.parts.forEach((part, index) => {
                        addCoursePart(part);
                    });

                    $('#editCourseModal').css('display', 'block');
                },
                error: function(xhr, status, error) {
                    console.error("Erreur lors de la récupération des données de la formation :", error);
                    alert("Une erreur s'est produite lors de la récupération des données de la formation. Veuillez réessayer.");
                }
            });
        }

        function addCoursePart(part = null) {
            const partIndex = $('#courseParts').children().length;
            const partTitle = part ? part.part_title : '';
            const partId = part ? part.part_id : '';
            const videosHtml = part && part.videos ? part.videos.map((video, vIndex) => `
                <div class="video">
                    <input type="text" name="videoTitle[${partIndex}][]" placeholder="Titre de la vidéo" value="${video.video_title}" required>
                    <input type="text" name="videoId[${partIndex}][]" placeholder="ID de la vidéo YouTube" value="${video.video_url}" required>
                    <button type="button" onclick="removeVideo(this)">Supprimer la vidéo</button>
                </div>
            `).join('') : `
                <div class="video">
                    <input type="text" name="videoTitle[${partIndex}][]" placeholder="Titre de la vidéo" required>
                    <input type="text" name="videoId[${partIndex}][]" placeholder="ID de la vidéo YouTube" required>
                    <button type="button" onclick="removeVideo(this)">Supprimer la vidéo</button>
                </div>
            `;

            const partHtml = `
                <div class="course-part">
                    <h3>Partie ${partIndex + 1}</h3>
                    <input type="hidden" name="partId[]" value="${partId}">
                    <input type="text" name="partTitle[]" placeholder="Titre de la partie" value="${partTitle}" required>
                    <div class="videos">
                        ${videosHtml}
                    </div>
                    <button type="button" onclick="addVideo(this)">Ajouter une vidéo</button>
                    <button type="button" onclick="removePart(this)">Supprimer la partie</button>
                </div>
            `;
            $('#courseParts').append(partHtml);
        }

        function addVideo(button) {
            const videosContainer = $(button).siblings('.videos');
            const partIndex = $(button).closest('.course-part').index();
            const videoHtml = `
                <div class="video">
                    <input type="text" name="videoTitle[${partIndex}][]" placeholder="Titre de la vidéo" required>
                    <input type="text" name="videoId[${partIndex}][]" placeholder="ID de la vidéo YouTube" required>
                    <button type="button" onclick="removeVideo(this)">Supprimer la vidéo</button>
                </div>
            `;
            videosContainer.append(videoHtml);
        }

        function removeVideo(button) {
            $(button).closest('.video').remove();
        }

        function removePart(button) {
            $(button).closest('.course-part').remove();
            $('.course-part').each((index, part) => {
                $(part).find('h3').text(`Partie ${index + 1}`);
            });
        }

        $('#editCourseForm').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const originalData = JSON.parse($('#originalData').val());
            let hasChanges = false;

            if (formData.get('courseName') !== originalData.course_name ||
                formData.get('coursePrice') !== originalData.price.toString() ||
                formData.get('courseContact') !== originalData.contact_info ||
                formData.get('courseImage').size > 0) {
                hasChanges = true;
            }

            const newParts = [];
            $('.course-part').each((partIndex, partElement) => {
                const partId = $(partElement).find('input[name="partId[]"]').val();
                const partTitle = $(partElement).find('input[name="partTitle[]"]').val();
                const videos = [];
                $(partElement).find('.video').each((videoIndex, videoElement) => {
                    videos.push({
                        title: $(videoElement).find('input[name^="videoTitle"]').val(),
                        url: $(videoElement).find('input[name^="videoId"]').val()
                    });
                });
                newParts.push({ id: partId, title: partTitle, videos: videos });
            });

            if (JSON.stringify(newParts) !== JSON.stringify(originalData.parts)) {
                hasChanges = true;
            }

            if (!hasChanges) {
                $('#courseMessage').text('Aucune modification n\'a été effectuée.');
                $('#courseMessage').css('color', 'blue');
                return;
            }
            
            $.ajax({
                url: 'u_c.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(result) {
                    if (result.success) {
                        $('#courseMessage').text(result.message);
                        $('#courseMessage').css('color', 'green');
                        $('#editCourseModal').css('display', 'none');
                        location.reload();
                    } else {
                        $('#courseMessage').text('Erreur : ' + result.message);
                        $('#courseMessage').css('color', 'red');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erreur AJAX :", status, error);
                    let errorMessage = "Une erreur s'est produite lors de la mise à jour de la formation.";
                    if (xhr.responseText) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            errorMessage = response.message || errorMessage;
                        } catch (e) {
                            console.error("Erreur lors de l'analyse de la réponse JSON :", e);
                        }
                    }
                    $('#courseMessage').text(errorMessage);
                    $('#courseMessage').css('color', 'red');
                }
            });
        });

        $('.close').click(function() {
            $('#editCourseModal').css('display', 'none');
        });

        $(window).click(function(event) {
            if (event.target == $('#editCourseModal')[0]) {
                $('#editCourseModal').css('display', 'none');
            }
        });

        <?php if (isset($_SESSION['course_created']) && $_SESSION['course_created']): ?>
            $('#courseMessage').text('Votre formation a été créée avec succès !');
            $('#courseMessage').css('color', 'green');
            <?php unset($_SESSION['course_created']); ?>
        <?php endif; ?>
        </script>
    <?php endif; ?>
</body>
</html>