<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pagetitle; ?></title>
    <link rel="icon" href="<?php echo $logo; ?>" type="image/png">
    <meta name="description" content="HopetohelpDZ vend des cours en ligne Reconnu par l'État, aide les gens à gagner de l'argent et offre des opportunités d'investissement dans de nouveaux projets.">
    <meta name="keywords" content="cours en ligne, gagner de l'argent, opportunité d'investissement, HopetohelpDZ">
    <meta name="author" content="HopetohelpDZ">
    <meta property="og:title" content="<?php echo $pagetitle; ?>">
    <meta property="og:description" content="HopetohelpDZ vend des cours en ligne, aide les gens à gagner de l'argent et offre des opportunités d'investissement dans de nouveaux projets.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.hopetohelp.dz">
    <meta property="og:image" content="https://www.hopetohelp.dz/HomeImg/logo.png">
    <link rel="canonical" href="https:///www.hopetohelp.dz">
    <link rel='stylesheet' type='text/css' media='screen' href='<?php echo $cssfile2; ?>'>
    <link rel='stylesheet' type='text/css' media='screen' href='<?php echo $cssfile1; ?>'>
    <script src="https://kit.fontawesome.com/eec7ca1019.js" crossorigin="anonymous"></script>
    <script src='<?php echo $jq; ?>'></script>
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
                <div class="auth-links">
                    <a href="<?php echo $signup; ?>" id="signup"><i class="fas fa-user-plus"></i> <span data-translate="S'Inscrire"></span></a>
                    <a href="<?php echo $signin; ?>" id="login"><i class="fas fa-sign-in-alt"></i> <span data-translate="Se Connecter"></span></a>                
                </div> 
            </div>           
        </div>
    </section>