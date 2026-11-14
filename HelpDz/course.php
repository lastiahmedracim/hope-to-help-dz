<?php
session_start();
if (!isset($_SESSION['user_r_pseudo'])) {
    header('Location: signin.php');
    exit;
}

$pagetitle = "Course";
$cssfile1 = '../Css/global.css';
$cssfile2 = '../Css/course.css';
$logo = '../HomeImg/logo.png';
$homepage = '../index.php';
$formation = 'formation.php';
$finhelp = 'pro_assi.php';
$mycourses = 'courses.php';
$profile = 'profile.php';
$networks = 'networks.php';
$logout = 'logout.php';
$contactus = 'contactus.php';
$newsletter = 'newsletter.php';
$aboutus = 'aboutus.php';
$vm = 'virtual_money.php';
$jsfile1 = '../Js/main.js';
$jsfile2 = 'newsletter.js';
$gun = 'g_u_n.js';
$jq = '../Js/jquery-3.6.0.min.js';

require_once('uheader.php');
require_once('../functions/db.php');
require_once('../functions/exp.php');

if ($_SERVER["REQUEST_METHOD"] !== "GET" || !isset($_GET['course_id'])) {
    echo "Course ID not provided.";
    exit;
}

$course_id = htmlspecialchars($_GET['course_id'], ENT_QUOTES, 'UTF-8');
$user_r_pseudo = htmlspecialchars($_SESSION['user_r_pseudo'], ENT_QUOTES, 'UTF-8');

if (!isset($_SESSION['video_token']) || empty($_SESSION['video_token'])) {
    $_SESSION['video_token'] = bin2hex(random_bytes(32));
}
$video_token = $_SESSION['video_token'];

try {
    $stmt = $pdo->prepare("
        SELECT 
            c.course_name,
            cp.part_id, 
            cp.part_title, 
            cv.video_id, 
            cv.video_title,
            cv.video_url,
            o.exp
        FROM Courses c
        INNER JOIN CourseParts cp ON c.course_id = cp.course_id
        INNER JOIN CourseVideos cv ON cp.part_id = cv.part_id
        INNER JOIN Users u ON u.user_r_pseudo = :user_r_pseudo
        INNER JOIN (
            SELECT course_id, user_id, MAX(order_id) as latest_order_id
            FROM Orders
            GROUP BY course_id, user_id
        ) lo ON lo.course_id = c.course_id AND lo.user_id = u.user_id
        INNER JOIN Orders o ON o.order_id = lo.latest_order_id
        WHERE c.course_id = :course_id AND o.exp >= CURDATE()
        ORDER BY cp.part_order, cv.video_order
    ");
    $stmt->execute(['course_id' => $course_id, 'user_r_pseudo' => $user_r_pseudo]);
    $course_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($course_data) || $course_data[0]['exp'] < date('Y-m-d')) {
        header("Location: courses.php");
        exit;
    }

    $course_name = $course_data[0]['course_name'];
} catch (PDOException $e) {
    error_log('Error fetching course details: ' . $e->getMessage());
    echo "An error occurred. Please try again later.";
    exit;
}
$first_video_id = $course_data[0]['video_id'] ?? null;
?>

    <section id="course-videos">
        <div class="container">
            <div class="sidebar">
                <h2 data-translate="Playlist du Cours">Playlist du Cours</h2>
                <h1><?php echo htmlspecialchars($course_name, ENT_QUOTES, 'UTF-8'); ?></h1>
                <ul class="video-list" id="videoList">
                    <?php
                    $current_part_id = null;
                    foreach ($course_data as $video) {
                        if ($current_part_id != $video['part_id']) {
                            echo '<li class="part-title">' . htmlspecialchars($video['part_title'], ENT_QUOTES, 'UTF-8') .' :</li>';
                            $current_part_id = $video['part_id'];
                        }
                        echo '<li class="video-list-item" data-video-id="' . htmlspecialchars($video['video_id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($video['video_title'], ENT_QUOTES, 'UTF-8') . '</li>';
                    }
                    ?>
                </ul>
                <button id="d-c" class="submit-btn" data-translate="Demandez la certification">Demandez la certification</button>
                <div id="d_c_message"></div>
            </div>
            <div class="main-content">
                <div class="video-container video-player" id="videoPlayer"></div>
            </div>
        </div>
    </section>

    <script src="https://www.youtube.com/iframe_api"></script>
    <script>
    (function() {
        function initializeCoursePage(courseId, userRPseudo, token) {
            let player;
            let currentVideoId = null;
            let playerReady = false;
            let videoQueue = [];
            let videoToken = token;
    
            const translations = {
                'fr': {
                    'Playlist du Cours': 'Playlist du Cours',
                    'Demandez la certification': 'Demandez la certification',
                    'Erreur de communication avec le serveur. Veuillez réessayer plus tard.': 'Erreur de communication avec le serveur. Veuillez réessayer plus tard.',
                    'Envoyez vos informations personnelles actuelles avec pièce d\'identité à cette adresse email :': 'Envoyez vos informations personnelles actuelles avec pièce d\'identité à cette adresse email :'
                },
                'ar': {
                    'Playlist du Cours': 'قائمة تشغيل الدورة',
                    'Demandez la certification': 'طلب الشهادة',
                    'Erreur de communication avec le serveur. Veuillez réessayer plus tard.': 'خطأ في الاتصال بالخادم. يرجى المحاولة مرة أخرى لاحقًا.',
                    'Envoyez vos informations personnelles actuelles avec pièce d\'identité à cette adresse email :': 'أرسل معلوماتك الشخصية الحالية مع إثبات الهوية إلى عنوان البريد الإلكتروني التالي:'
                },
                'en': {
                    'Playlist du Cours': 'Course Playlist',
                    'Demandez la certification': 'Request certification',
                    'Erreur de communication avec le serveur. Veuillez réessayer plus tard.': 'Error communicating with the server. Please try again later.',
                    'Envoyez vos informations personnelles actuelles avec pièce d\'identité à cette adresse email :': 'Send your current personal information with ID to this email address:'
                }
            };
    
            function onYouTubeIframeAPIReady() {
                player = new YT.Player('videoPlayer', {
                    height: '360',
                    width: '640',
                    videoId: '',
                    playerVars: {
                        'playsinline': 1,
                        'controls': 1,
                        'rel': 0,
                        'modestbranding': 1
                    },
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange,
                        'onError': onPlayerError
                    }
                });
            }
    
            function onPlayerReady(event) {
                playerReady = true;
                processVideoQueue();
            }
    
            function onPlayerStateChange(event) {
                if (event.data == YT.PlayerState.ENDED) {
                    player.seekTo(0);
                    player.playVideo();
                }
            }
    
            function onPlayerError(event) {
                document.getElementById('videoPlayer').innerHTML = '<p>Error loading video. Please try again later.</p>';
            }
    
            function processVideoQueue() {
                if (playerReady && videoQueue.length > 0) {
                    const nextVideo = videoQueue.shift();
                    player.loadVideoById(nextVideo);
                }
            }
    
            function playVideo(dbVideoId) {
                fetch('../functions/g_v_u.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        video_id: dbVideoId,
                        token: videoToken
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.video_url) {
                        const videoId = extractYouTubeId(data.video_url);
                        if (videoId) {
                            if (playerReady) {
                                player.loadVideoById(videoId);
                            } else {
                                videoQueue.push(videoId);
                            }
                            currentVideoId = dbVideoId;
                        } else {
                            throw new Error('Invalid YouTube URL');
                        }
                    } else {
                        throw new Error(data.message || 'Failed to load video');
                    }
                })
                .catch(error => {
                    document.getElementById('videoPlayer').innerHTML = '<p>Error: ' + error.message + '</p>';
                });
            }
    
            function extractYouTubeId(url) {
                const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                const match = url.match(regExp);
                return (match && match[2].length === 11) ? match[2] : null;
            }
    
            function setCookie(name, value, days) {
                let expires = "";
                if (days) {
                    let date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "") + expires + "; path=/";
            }
    
            function getCookie(name) {
                let nameEQ = name + "=";
                let ca = document.cookie.split(';');
                for(let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            }
    
            function translatePage(lang) {
                document.documentElement.lang = lang;
                document.querySelectorAll('[data-translate]').forEach(element => {
                    const key = element.getAttribute('data-translate');
                    if (translations[lang] && translations[lang][key]) {
                        element.textContent = translations[lang][key];
                    }
                });
    
                document.body.style.direction = lang === 'ar' ? 'rtl' : 'ltr';
    
                setCookie('language', lang, 30);
    
                const certificationMessage = document.getElementById('d_c_message');
                if (certificationMessage && certificationMessage.querySelector('p')) {
                    const messageKey = certificationMessage.querySelector('p').textContent.trim();
                    if (translations[lang] && translations[lang][messageKey]) {
                        certificationMessage.querySelector('p').textContent = translations[lang][messageKey];
                    }
                }
            }
    
            function initPage() {
                let lang = getCookie('language');
                if (!lang) {
                    lang = 'fr';
                    setCookie('language', lang, 30);
                }
                translatePage(lang);
            }
    
            function reloadPage() {
                window.location.reload();
            }
    
            function initializeEventListeners() {
                const videoList = document.getElementById('videoList');
                const videoPlayerContainer = document.getElementById('videoPlayer');
                const partTitles = document.querySelectorAll('.part-title');
                
                videoList.addEventListener('click', (event) => {
                    const target = event.target;
                    if (target && target.classList.contains('video-list-item')) {
                        const dbVideoId = target.getAttribute('data-video-id');
                        playVideo(dbVideoId);
                    }
                });
    
                partTitles.forEach(partTitle => {
                    partTitle.addEventListener('click', () => {
                        if (window.innerWidth <= 768) {
                            videoPlayerContainer.scrollIntoView({ behavior: 'smooth' });
                        }
                    });
                });
    
                document.getElementById('d-c').addEventListener('click', function() {
                    const messageDiv = document.getElementById('d_c_message');
                    const lang = document.documentElement.lang || 'fr';
    
                    fetch('../functions/r_c.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ course_id: courseId, user_r_pseudo: userRPseudo }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            messageDiv.innerHTML = '<p>' + translations[lang]['Envoyez vos informations personnelles actuelles avec pièce d\'identité à cette adresse email :'] + ' <a href="mailto:formation@hopetohelp.dz" style="color: #ccc;">formation@hopetohelp.dz</a></p>';
                        } else {
                            messageDiv.innerHTML = '<p>' + data.message + '</p>';
                        }
                    })
                    .catch(error => {
                        messageDiv.innerHTML = '<p>' + translations[lang]['Erreur de communication avec le serveur. Veuillez réessayer plus tard.'] + '</p>';
                    });
                });
    
                document.addEventListener('contextmenu', event => event.preventDefault());
                document.getElementById('videoPlayer').addEventListener('contextmenu', event => event.preventDefault());
    
                document.getElementById('fr-link').addEventListener('click', (e) => {
                    e.preventDefault();
                    translatePage('fr');
                    setTimeout(reloadPage, 100);
                });
    
                document.getElementById('ar-link').addEventListener('click', (e) => {
                    e.preventDefault();
                    translatePage('ar');
                    setTimeout(reloadPage, 100);
                });
    
                document.getElementById('en-link').addEventListener('click', (e) => {
                    e.preventDefault();
                    translatePage('en');
                    setTimeout(reloadPage, 100);
                });
            }
    
            function init() {
                initPage();
                initializeEventListeners();
    
                const firstVideoItem = document.querySelector('.video-list-item');
                if (firstVideoItem) {
                    const firstDbVideoId = firstVideoItem.getAttribute('data-video-id');
                    playVideo(firstDbVideoId);
                }
    
                if (typeof YT === 'undefined') {
                    const tag = document.createElement('script');
                    tag.src = "https://www.youtube.com/iframe_api";
                    const firstScriptTag = document.getElementsByTagName('script')[0];
                    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                } else {
                    onYouTubeIframeAPIReady();
                }
            }
    
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        }
    
        initializeCoursePage(
            <?php echo json_encode($course_id); ?>,
            <?php echo json_encode($user_r_pseudo); ?>,
            <?php echo json_encode($video_token); ?>
        );
    })();
    </script>

<?php require_once('footer.php'); ?>