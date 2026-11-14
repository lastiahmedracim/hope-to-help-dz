<?php
session_start();
if (!isset($_SESSION['user_r_pseudo'])) {
    $pagetitle = "Hope to Help Dz";
    $cssfile1 = 'Css/main.css';
    $cssfile2 = 'Css/global.css';
    $logo = 'HomeImg/logo.png';
    $homepage = 'index.php';
    $formation = 'HelpDz/formation.php';
    $finhelp = 'HelpDz/pro_assi.php';
    $signup = 'HelpDz/signup.php';
    $signin = 'HelpDz/signin.php';
    $contactus = 'HelpDz/contactus.php';
    $aboutus = 'HelpDz/aboutus.php';
    $newsletter = 'HelpDz/newsletter.php';
    $jsfile1 = 'Js/main.js';
    $jsfile2 = 'HelpDz/newsletter.js';
    $jsfile3 = 'Js/hp.js';
    $jq = 'Js/jquery-3.6.0.min.js';
    require_once('HelpDz/header.php');
}elseif (isset($_SESSION['user_r_pseudo'])) {
    $pagetitle = "Hope To Help Dz";
    $cssfile1 = 'Css/main.css';
    $cssfile2 = 'Css/global.css';
    $logo = 'HomeImg/logo.png';
    $homepage = 'index.php';
    $formation = 'HelpDz/formation.php';
    $finhelp = 'HelpDz/pro_assi.php';
    $mycourses = 'HelpDz/courses.php';
    $profile = 'HelpDz/profile.php';
    $networks = 'HelpDz/networks.php';
    $logout = 'HelpDz/logout.php';
    $contactus = 'HelpDz/contactus.php';
    $aboutus = 'HelpDz/aboutus.php';
    $vm = 'HelpDz/virtual_money.php';
    $newsletter = 'HelpDz/newsletter.php';
    $jsfile1 = 'Js/main.js';
    $jsfile2 = 'HelpDz/newsletter.js';
    $jsfile3 = 'Js/hp.js';
    $gun = 'HelpDz/g_u_n.js';
    $jq = 'Js/jquery-3.6.0.min.js';
    require_once('HelpDz/uheader.php'); 
}

require_once('functions/db.php');
$stmt = $pdo->prepare("SELECT course_id, course_name, CONCAT('1s2qZa/course_images/', course_img) AS course_img FROM Courses ORDER BY RAND() LIMIT 6");
$stmt->execute();
$randomCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <section id="sp">
        <div class="container">
            <div class="slider-wrapper">
                <?php foreach($randomCourses as $index => $course): ?>
                    <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>" data-course-id="<?php echo htmlspecialchars($course['course_id']); ?>">
                        <img src="<?php echo htmlspecialchars($course['course_img']); ?>" alt="<?php echo htmlspecialchars($course['course_name']); ?>">
                        <div class="slide-caption">
                            <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="slider-indicators">
                <?php foreach($randomCourses as $index => $course): ?>
                    <span class="indicator <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></span>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="boxes">
        <div class="container">
            <div class="box">
                <div class="box-img">
                    <img src="HomeImg/f.png" alt="">
                </div>
                <div class="box-name">
                    <h3 data-translate>Cours et Certifications</h3>
                </div>
                <div class="box-text">
                    <p data-translate>Découvrez les cours professionnels accrédités Élevez votre expertise avec une certification reconnue!</p>
                </div>
                <div class="button-container">
                    <a href=".\HelpDz/formation.php"><button data-translate>Commencez à apprendre maintenant</button></a>
                </div>
            </div>
            <div class="box">
                <div class="box-img">
                    <img src="HomeImg/a-f.png" alt="">
                </div>
                <div class="box-name">
                    <h3 data-translate>Aide aux Projets</h3>
                </div>
                <div class="box-text">
                    <p data-translate>Soumettez vos idées innovantes. Recevez un soutien pour vos projets visionnaires.</p>
                </div>
                <div class="button-container">
                    <button data-translate>Lancez votre projet maintenant</button>
                </div>
            </div>
            <div class="box">
                <div class="box-img">
                    <img src="HomeImg/i.png" alt="">
                </div>
                <div class="box-name">
                    <h3 data-translate>Opportunités d'Investissement</h3>
                </div>
                <div class="box-text">
                    <p data-translate>Améliorez votre portefeuille d'investissement Explorez les opportunités dans les projets en croissance</p>
                </div>
                <div class="button-container">
                    <button data-translate>Bientôt disponible</button>
                </div>
            </div>
        </div>
    </section>

    <section id="info">
        <div class="container">
            <div class="img">
                <img src="HomeImg/hand.jpg" alt="hand">
            </div>
            <div class="ceo-title">
                <h1 data-translate>Notre entreprise se consacre à l'autonomisation des individus en proposant une gamme diversifiée de cours menant à de précieuses certifications. Grâce à nos programmes de soutien financier, nous offrons aux individus la possibilité d'accéder à du financement pour leurs projets, les aidant ainsi à transformer leurs idées en réalité. De plus, nous offrir des opportunités d'investissement à ceux qui cherchent à accroître leur patrimoine. Notre mission est de soutenir l'apprentissage, l'innovation et la croissance financière, en créant à terme une plateforme où les individus peuvent atteindre leurs objectifs et réussir.</h1>
                <h3 class="ceo" data-translate>PDG</h3>
            </div>            
        </div>
    </section>

    <section id="domain">
        <div class="container">
            <h1 data-translate>Explorez nos formations spécialisées : développez vos talents</h1>
            <div class="domain-container">
                <div class="domain-item">
                    <div class="domain-pic">
                        <img src="HomeImg/education.png" alt="Domaine de l'Éducation">
                    </div>
                    <div class="domain-name">
                        <h2><i class="fas fa-graduation-cap"></i>&nbsp;<span data-translate>Éducation</span></h2>
                    </div>
                    <div class="domain-info">
                        <p data-translate>Cours liés à l'éducation et à l'enseignement, avec des spécialisations telles que le guidage Umrah et touristique, la garde d'enfants, et la formation de secrétariat.</p>
                        <p data-translate>Certification : Guide Umrah et Touristique, Nanny Qualifiée, ou Secrétaire Professionnel, ou équivalent.</p>
                        <p data-translate>Qualification professionnelle : Opportunités dans le secteur du tourisme religieux, la garde d'enfants, l'assistance administrative, ainsi que dans l'éducation et la formation.</p>
                    </div>
                </div>

                <div class="domain-item">
                    <div class="domain-pic">
                        <img src="HomeImg/alimentation.png" alt="Domaine de l'Alimentation">
                    </div>
                    <div class="domain-name">
                        <h2><i class="fas fa-utensils"></i>&nbsp;<span data-translate>Alimentation</span></h2>
                    </div>
                    <div class="domain-info">
                        <p data-translate>Cours variés axés sur l'alimentation et la nutrition, comprenant des modules spécialisés tels que la boulangerie, la boucherie, la fabrication de pizzas, la production de fromage et d'autres compétences culinaires.</p>
                        <p data-translate>Certification : Spécialiste en Alimentation et Nutrition avec spécialisation en boulangerie, boucherie, pizza, fromage, ou équivalent.</p>
                        <p data-translate>Qualification professionnelle : Opportunités dans la gestion de boulangeries, boucheries, pizzerias, fromageries, et autre connexes de l'industrie alimentaire.</p>
                    </div>
                </div>

                <div class="domain-item">
                    <div class="domain-pic">
                        <img src="HomeImg/inforamtique.png" alt="Domaine de l'Informatique">
                    </div>
                    <div class="domain-name">
                        <h2><i class="fas fa-laptop-code"></i>&nbsp;<span data-translate>Informatique</span></h2>
                    </div>
                    <div class="domain-info">
                        <p data-translate>Cours liés à l'informatique et aux technologies de l'information.</p>
                        <p data-translate>Certification : Développeur Web Front-end, Back-end ou Full Stack, ou équivalent.</p>
                        <p data-translate>Qualification professionnelle : Opportunités dans le développement Web, la conception de l'interface utilisateur, la programmation côté serveur, la gestion de bases de données et d'autres domaines liés à la création d'applications et de sites Web.</p>
                    </div>
                </div>

                <div class="domain-item">
                    <div class="domain-pic">
                        <img src="HomeImg/maison.png" alt="Domaine de la Maison">
                    </div>
                    <div class="domain-name">
                        <h2><i class="fas fa-home"></i>&nbsp;<span data-translate>Maison</span></h2>
                    </div>
                    <div class="domain-info">
                        <p data-translate>Cours liés à la décoration intérieure et au design d'intérieur.</p>
                        <p data-translate>Certification : Designer d'Intérieur ou équivalent.</p>
                        <p data-translate>Qualification professionnelle : Carrières en design intérieur, mise en scène de maison, design de meubles, décoration intérieure et des domaines connexes.</p>                        
                    </div>
                </div>

                <div class="domain-item">
                    <div class="domain-pic">
                        <img src="HomeImg/beaute.png" alt="Domaine de la Beauté">
                    </div>
                    <div class="domain-name">
                        <h2><i class="fas fa-spa"></i>&nbsp;<span data-translate>Beauté</span></h2>
                    </div>
                    <div class="domain-info">
                        <p data-translate>Cours liés à la beauté et aux cosmétiques.</p>
                        <p data-translate>Certification : Expert en Beauté ou équivalent.</p>
                        <p data-translate>Qualification professionnelle : Opportunités en maquillage, conseil en beauté, soins de la peau, gestion de salon et des domaines connexes.</p>
                    </div>
                </div>

                <div class="domain-item">
                    <div class="domain-pic">
                        <img src="HomeImg/mode.png" alt="Domaine de la Mode">
                    </div>
                    <div class="domain-name">
                        <h2><i class="fas fa-tshirt"></i>&nbsp;<span data-translate>Mode</span></h2>
                    </div>
                    <div class="domain-info">
                        <p data-translate>Cours liés à la mode et au style.</p>
                        <p data-translate>Certification : Styliste de Mode ou équivalent.</p>
                        <p data-translate>Qualification professionnelle : Carrières en design de mode, stylisme, gestion de vente au détail, marketing de mode et des domaines connexes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="big-label">
        <div class="container">
            <div class="b-l">
                <h1 data-translate>Qu'attendez-vous pour obtenir votre certification et commencer à travailler ?</h1>
                <a href="./HelpDz/formation.php"><button data-translate>Obtenez votre certification maintenant</button></a>
            </div>
        </div>
    </section>

<?php require_once('HelpDz/footer.php'); ?>