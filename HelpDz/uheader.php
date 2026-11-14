<?php
if (!isset($_SESSION['user_r_pseudo'])) {
    header('Location: signin.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pagetitle; ?></title>
    <link rel="icon" href="<?php echo $logo; ?>" type="image/png">
    <link rel='stylesheet' type='text/css' media='screen' href='<?php echo $cssfile1; ?>'>
    <link rel='stylesheet' type='text/css' media='screen' href='<?php echo $cssfile2; ?>'>
    <script src="https://kit.fontawesome.com/eec7ca1019.js" crossorigin="anonymous"></script>
    <script src='<?php echo $jq; ?>'></script>
    <script src='<?php echo $pf; ?>'></script>
</head>
<body>
<section id="twi">
        <div class="container">
            <div class="date-time"></div>
            <div class="motivational-quote">
                <p data-translate="Restez fort, restez positif et n'abandonnez jamais !"></p>
            </div>
            <div class="contact">
                <div class="head-icons">
                    <a href="https://www.facebook.com/profile.php?id=61560117170757&mibextid=rS40aB7S9Ucbxw6v" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/hopeto.help.dz?igsh=MTNwbG5yOHd1OGVlZw==" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                    <a href="mailto:support@hopetohelp.dz" target="_blank" rel="noopener noreferrer"><i class="fa-regular fa-envelope"></i></a>
                    <a href="https://youtube.com/@hopetohelp-dz?si=27UdLhLibvXG2tJ3" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
            <div class="custom-dropdown" id="dropdownContainer">
                <a href="#" id="settingsBtn" class="custom-dropbtn"><i class="fas fa-globe"></i></a>
                <div class="custom-dropdown-content" id="settingsDropdown">
                    <a href="#" id="fr-link">Français</a>
                    <a href="#" id="ar-link">العربية</a>
                    <a href="#" id="en-link">English</a>
                </div>
            </div>
        </div>
    </section>
    
    <section id="nav">
        <div class="container">
            <div class="burger-icon" id="burgerIcon" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </div>
            <div class="logo">
                <img src="<?php echo $logo; ?>" alt="Hope to Help Dz">
            </div>
            <div class="menu">
                <ul>
                    <li><a href="<?php echo $homepage; ?>" data-translate="Accueil"></a></li>
                    <li><a href="<?php echo $formation; ?>" data-translate="Formation Professionnelle"></a></li>
                    <li><a href="<?php echo $finhelp; ?>" data-translate="Aide aux projets"></a></li>
                    <li><a class="change-text" href="#" data-translate="Investissement"></a></li>
                </ul>
                <div class="auth-links" id="dropdownContainer">
                    <div class="notification-icon">
                        <a href="#" id="notificationIcon">
                            <i class="fas fa-bell"></i>
                            <span class="notification-count" id="notificationCount">0</span>
                        </a>
                        <div class="notification-dropdown" id="notificationDropdown"></div>
                    </div>
                    <div class="dropdown">
                        <a href="#" id="settingsBtn" class="white"><i class="fas fa-cog"></i>&nbsp;&nbsp;<span data-translate="Paramètres"></span></a>
                        <div class="dropdown-content" id="settingsDropdown">
                            <a href="<?php echo $profile; ?>"><i class="fas fa-user"></i> <span data-translate="Profil"></span></a>
                            <a href="<?php echo $networks; ?>"><i class="fas fa-network-wired"></i> <span data-translate="Réseaux"></span></a>
                            <a href="<?php echo $vm; ?>"><i class="fas fa-coins"></i> <span data-translate="Acheter l'argent virtuel"></span></a>
                            <a href="<?php echo $logout; ?>"><i class="fas fa-sign-out-alt"></i> <span data-translate="Déconnexion"></span></a>
                        </div>
                    </div>
                    <a href="<?php echo $mycourses; ?>" class="white"><i class="fas fa-book"></i>&nbsp;&nbsp;<span data-translate="Mes Courses"></span></a>
                </div>
            </div>
        </div>
    </section>
