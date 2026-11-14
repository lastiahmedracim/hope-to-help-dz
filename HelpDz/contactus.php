<?php
session_start();
if (!isset($_SESSION['user_r_pseudo'])) {
    $pagetitle = "Contactez-nous";
    $cssfile1 = '../Css/contactus.css';
    $cssfile2 = '../Css/global.css';
    $logo = '../HomeImg/logo.png';
    $homepage = '../index.php';
    $formation = 'formation.php';
    $finhelp = 'pro_assi.php';
    $signup = 'signup.php';
    $signin = 'signin.php';
    $contactus = 'contactus.php';
    $aboutus = 'aboutus.php';
    $newsletter = 'newsletter.php';
    $jsfile1 = '../Js/main.js';
    $jsfile2 = 'newsletter.js';
    $jsfile3 = '../Js/cu.js';
    $rcp = 'https://www.google.com/recaptcha/api.js?render=6Ld2gA8qAAAAADdxY7b2fqykkxgs-dUDqd1PaMFU';
    $jq = '../Js/jquery-3.6.0.min.js';
    require_once('header.php'); 
}elseif (isset($_SESSION['user_r_pseudo'])) {
    $pagetitle = "Contactez-nous";
    $cssfile1 = '../Css/contactus.css';
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
    $jsfile3 = '../Js/cu.js';
    $gun = 'g_u_n.js';
    $rcp = 'https://www.google.com/recaptcha/api.js?render=6Ld2gA8qAAAAADdxY7b2fqykkxgs-dUDqd1PaMFU';
    $jq = '../Js/jquery-3.6.0.min.js';
    require_once('uheader.php');  
}
?>

<section id="contact-section">
    <h1 data-translate>Contactez-nous</h1>
    <div class="container">
        <div class="contact-wrapper">
            <div class="contact-info">
                <p data-translate>N'hésitez pas à nous contacter pour toute question, demande de renseignement ou suggestion. Remplissez le formulaire ci-dessous, et nous vous répondrons dès que possible.</p>
                <ul class="contact-details">
                    <li data-translate><i class="fa-solid fa-location-dot"></i> Adresse: EL QODS ALGER CHERAGA</li>
                    <li data-translate><i class="fa-solid fa-phone"></i> Téléphone: 0670123991</li>
                    <li data-translate><i class="fa-solid fa-envelope"></i> Email: support@hopetohelp.dz</li>
                </ul>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="contact-form">
                <form action="s_c.php" method="POST" class="form">
                    <div class="form-group">
                        <input type="text" id="name" name="name" placeholder="Nom" data-translate>
                    </div>
                    <div class="form-group">
                        <input type="text" id="email" name="email" placeholder="Email" data-translate>
                    </div>
                    <div class="form-group">
                        <input type="text" id="subject" name="subject" placeholder="Sujet" data-translate>
                    </div>
                    <div class="form-group">
                        <textarea id="message" name="message" rows="5" placeholder="Votre message" data-translate></textarea>
                    </div>
                    <button type="submit" class="submit-btn" data-translate>Envoyer</button>
                    <div id="contact-error"></div>
                </form>
            </div>
        </div>
    </div>
</section>

<script src="contact.js"></script>

<?php require_once('footer.php'); ?>