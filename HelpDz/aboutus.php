<?php
session_start();
if (!isset($_SESSION['user_r_pseudo'])) {
    $pagetitle = "À propos d'Hope to Help Dz";
    $cssfile1 = '../Css/aboutus.css';
    $cssfile2 = '../Css/global.css';
    $logo = '../HomeImg/logo.png';
    $homepage = '../index.php';
    $formation = 'formation.php';
    $finhelp = 'pro_assi.php';
    $signup = 'signup.php';
    $signin = 'signin.php';
    $contactus = 'contactus.php';
    $aboutus = 'aboutus.php';
    $aboutus = 'aboutus.php';
    $newsletter = 'newsletter.php';
    $jsfile1 = '../Js/main.js';
    $jsfile2 = 'newsletter.js';
    $jsfile3 = '../Js/au.js';
    $jq = '../Js/jquery-3.6.0.min.js';
    require_once('header.php');
}elseif (isset($_SESSION['user_r_pseudo'])) {
    $pagetitle = "À propos d'Hope to Help Dz";
    $cssfile1 = '../Css/aboutus.css';
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
    $newsletter = 'newsletter.php';
    $vm = 'virtual_money.php';
    $jsfile1 = '../Js/main.js';
    $jsfile2 = 'newsletter.js';
    $jsfile3 = '../Js/au.js';
    $gun = 'g_u_n.js';
    $jq = '../Js/jquery-3.6.0.min.js';
    require_once('uheader.php');
}
?>
    <section class="about-section">
        <div class="about-content about1">
            <h2 class="about-title" data-translate>À propos d'Hope to Help Dz</h2>
            <p class="about-description" data-translate>Bienvenue chez Hope to Help Dz, où chaque personne mérite la chance de réaliser ses rêves. Fondée avec une vision de soutien et de progrès, nous sommes bien plus qu'une simple plateforme. Nous sommes votre partenaire dans la concrétisation de vos aspirations, que ce soit en trouvant un emploi dans un domaine de votre choix, en lançant votre propre entreprise ou en investissant dans des projets prometteurs.</p>
        </div>
    </section>
    
    <section class="about-section">
        <div class="about-content about2">
            <h3 class="sub-title" data-translate>Notre Histoire</h3>
            <p class="about-description" data-translate>L'histoire de Hope to Help Dz commence avec la passion de rendre les opportunités accessibles à tous. Depuis notre création, nous avons travaillé sans relâche pour développer des programmes de formation certifiés dans de nombreux domaines, permettant aux participants de trouver un emploi enrichissant ou de démarrer leur propre entreprise avec confiance.</p>
        </div>
    </section>
    
    <section class="about-section">
        <div class="about-content about3">
            <h3 class="sub-title" data-translate>Nos Services</h3>
            <ul class="about-description">
                <li><strong data-translate>Formations et Certifications:</strong> <span data-translate>Cours de haute qualité dans divers domaines avec des certifications reconnues.</span></li>
                <li><strong data-translate>Financement de Projets:</strong> <span data-translate>Aide au financement pour concrétiser vos projets entrepreneuriaux.</span></li>
                <li><strong data-translate>Investissement:</strong> <span data-translate>Opportunités d'investissement dans des projets innovants et rentables.</span></li>
            </ul>
        </div>
    </section>
    
    <section class="about-section">
        <div class="about-content about4">
            <h3 class="sub-title" data-translate>Nos Valeurs et Mission</h3>
            <p class="about-description" data-translate>Guidés par l'intégrité, l'empathie et le désir sincère d'apporter un changement positif dans la vie des gens. Notre mission est de fournir les outils, le soutien financier et les opportunités nécessaires pour que chacun puisse réaliser son potentiel et contribuer au développement de la société.</p>
        </div>
    </section>
    
    <section class="about-section">
        <div class="about-content about5">
            <h3 class="sub-title" data-translate>Pourquoi Choisir Hope to Help Dz?</h3>
            <ul class="about-description">
                <li data-translate>Formations de qualité avec certifications reconnues.</li>
                <li data-translate>Accès au financement pour démarrer des projets.</li>
                <li data-translate>Opportunités d'investissement dans des projets innovants.</li>
                <li data-translate>Engagement envers le succès et le bien-être de nos utilisateurs.</li>
            </ul>
            <a href="contactus.php" class="cta-button"><i class="fas fa-envelope"></i> <span data-translate>Contactez-nous</span></a>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const animatedSections = document.querySelectorAll('.about-content');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                    }
                });
            }, { threshold: 0.1 });

            animatedSections.forEach(section => {
                observer.observe(section);
            });
        });
    </script>

<?php require_once('footer.php'); ?>