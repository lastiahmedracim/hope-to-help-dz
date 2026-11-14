<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
if (isset($_SESSION['user_r_pseudo'])) {
    $pagetitle = "Course info";
    $cssfile1 = '../Css/course_info.css';
    $cssfile2 = '../Css/global.css';
    $logo = '../HomeImg/logo.png';
    $homepage = '../index.php';
    $formation = 'formation.php';
    $finhelp = 'pro_assi.php';
    $mycourses = 'courses.php';
    $profile = 'profile.php';
    $networks = 'networks.php';
    $logout = 'logout.php';
    $contactus = 'contactus.php';
    $aboutus = 'aboutus.php';
    $vm = 'virtual_money.php';
    $newsletter = 'newsletter.php';
    $jsfile1 = '../Js/main.js';
    $jsfile2 = 'newsletter.js';
    $jsfile3 = '../Js/ci.js';
    $gun = 'g_u_n.js';
    $jq = '../Js/jquery-3.6.0.min.js';
    require('uheader.php');
    require_once('../functions/db.php');

    $userRPseudo = htmlspecialchars($_SESSION['user_r_pseudo'], ENT_QUOTES, 'UTF-8');

    if (isset($_GET['course_id'])) {
        $courseId = intval($_GET['course_id']);

        $stmt = $pdo->prepare("SELECT course_id, course_name, price, CONCAT('../1s2qZa/course_images/', course_img) AS course_img, category_id FROM Courses WHERE course_id = ?");
        $stmt->execute([$courseId]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($course) {
            $stmtCategory = $pdo->prepare("SELECT category_name FROM Categories WHERE category_id = ?");
            $stmtCategory->execute([$course['category_id']]);
            $category = $stmtCategory->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?php echo isset($_COOKIE['language']) ? $_COOKIE['language'] : 'fr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pagetitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="<?php echo $cssfile1; ?>">
    <link rel="stylesheet" href="<?php echo $cssfile2; ?>">
</head>
<body>
<section id="course_info">
    <div class="container">
        <div class="course-details">
            <div class="course-image">
                <?php
                $course_img = basename($course['course_img']);
                $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                $file_extension = strtolower(pathinfo($course_img, PATHINFO_EXTENSION));
                if (!in_array($file_extension, $valid_extensions)) {
                    $course_img = 'default.jpg';
                }
                ?>
                <img src="<?php echo htmlspecialchars($course['course_img'], ENT_QUOTES, 'UTF-8'); ?>" alt="Image du Cours" data-translate>
            </div>
            <div class="course-info">
                <h1><?php echo htmlspecialchars($course['course_name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                <div class="course-parts">
                    <h2 data-translate>Éléments du Cours</h2>
                    <ul>
                        <?php
                        $stmtParts = $pdo->prepare("SELECT part_title FROM CourseParts WHERE course_id = ?");
                        $stmtParts->execute([$courseId]);
                        $parts = $stmtParts->fetchAll(PDO::FETCH_ASSOC);

                        if ($parts) {
                            foreach ($parts as $part) {
                                echo "<li>" . htmlspecialchars($part['part_title'], ENT_QUOTES, 'UTF-8') . "</li>";
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="course-details-bottom">
                    <p class="price"><span data-translate>Prix</span> : <?php echo htmlspecialchars($course['price'], ENT_QUOTES, 'UTF-8'); ?> <span data-translate>DA</span></p>
                    <p class="category"><span data-translate>Catégorie</span> : <span data-translate><?php echo htmlspecialchars($category['category_name'], ENT_QUOTES, 'UTF-8'); ?></span></p>
                    <button onclick="openVirtualModal()" class="buy-virtual-button" data-translate>Acheter le Cours avec l'argent virtuel</button>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="buyVirtualModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" onclick="closeVirtualModal()">&times;</span>
            <h2 data-translate>Acheter le Cours avec l'argent virtuel</h2>
        </div>
        <div class="modal-body">
            <form id="virtualMoneyForm" action="b_v.php?course_id=<?php echo $courseId; ?>" method="post">
                <div class="form-group">
                    <button type="submit" data-translate>Confirmer l'achat</button>
                </div>
                <div class="v_payment_message"></div>
            </form>
        </div>
    </div>
</div>

<script>
const t1 = {
    'fr': {
        'Erreur lors du traitement de la réponse du serveur.': 'Erreur lors du traitement de la réponse du serveur.',
        'Erreur de connexion au serveur.': 'Erreur de connexion au serveur.',
        'Acheter le Cours avec l\'argent virtuel': 'Acheter le Cours avec l\'argent virtuel',
        'Confirmer l\'achat': 'Confirmer l\'achat',
    },
    'en': {
        'Erreur lors du traitement de la réponse du serveur.': 'Error processing server response.',
        'Erreur de connexion au serveur.': 'Error connecting to server.',
        'Acheter le Cours avec l\'argent virtuel': 'Buy the Course with virtual money',
        'Confirmer l\'achat': 'Confirm purchase',
    },
    'ar': {
        'Erreur lors du traitement de la réponse du serveur.': 'خطأ في معالجة استجابة الخادم.',
        'Erreur de connexion au serveur.': 'خطأ في الاتصال بالخادم.',
        'Acheter le Cours avec l\'argent virtuel': 'شراء الدورة بالنقود الافتراضية',
        'Confirmer l\'achat': 'تأكيد الشراء',
    }
};

function openVirtualModal() {
    document.getElementById('buyVirtualModal').style.display = 'block';
}

function closeVirtualModal() {
    document.getElementById('buyVirtualModal').style.display = 'none';
    clearVirtualPaymentMessage();
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

function translate(key) {
    let lang = getCookie('language') || 'fr';
    if (t1[lang] && t1[lang][key]) {
        return t1[lang][key];
    }
    return key;
}

function showVirtualPaymentMessage(message, isSuccess) {
    var vPaymentMessage = document.querySelector('.v_payment_message');
    vPaymentMessage.style.display = 'block';
    vPaymentMessage.innerHTML = '<p>' + message + '</p>';

    if (isSuccess) {
        vPaymentMessage.classList.add('success');
        vPaymentMessage.classList.remove('error');
    } else {
        vPaymentMessage.classList.add('error');
        vPaymentMessage.classList.remove('success');
    }
}

function clearVirtualPaymentMessage() {
    var vPaymentMessage = document.querySelector('.v_payment_message');
    vPaymentMessage.style.display = 'none';
    vPaymentMessage.innerHTML = '';
    vPaymentMessage.classList.remove('success', 'error');
}

document.addEventListener('DOMContentLoaded', function() {
    var virtualForm = document.getElementById('virtualMoneyForm');
    virtualForm.addEventListener('submit', function(event) {
        event.preventDefault();

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'b_v.php?course_id=<?php echo $courseId; ?>', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            showVirtualPaymentMessage(response.message, true);
                            setTimeout(function() {
                                window.location.reload();
                            }, 3000);
                        } else {
                            showVirtualPaymentMessage(response.message, false);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        console.log('Raw response:', xhr.responseText);
                        showVirtualPaymentMessage(translate('Erreur lors du traitement de la réponse du serveur.'), false);
                    }
                } else {
                    console.error('Server error:', xhr.status);
                    showVirtualPaymentMessage(translate('Erreur de connexion au serveur.'), false);
                }
            }
        };
        xhr.send(new FormData(virtualForm));
    });
});
</script>

<?php require_once('footer.php'); ?>

<?php
        } else {
            echo "<p data-translate>Cours introuvable.</p>";
        }
    } else {
        header("Location: formation.php");
    }
} else {
    header('Location: signin.php');
    exit;
}
?>
</body>
</html>