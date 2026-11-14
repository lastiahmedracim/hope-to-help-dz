<?php
session_start();
if (isset($_SESSION['user_r_pseudo'])) {
    header('Location: ../index.php');
    exit;
}
$logo = '../HomeImg/logo.png';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-translate="signup_title">S'inscrire</title>
    <link rel='stylesheet' type='text/css' media='screen' href='..\Css/form.css'>
    <script src="https://www.google.com/recaptcha/api.js?render=6Ld2gA8qAAAAADdxY7b2fqykkxgs-dUDqd1PaMFU"></script>
</head>
<body>
    <div class="logo">
        <a href="../index.php">
            <img src="<?php echo $logo; ?>" alt="Hope to Help Dz">
        </a>
    </div>

    <section id="sign">
        <div class="container">
            <h1 data-translate="signup_form">Formulaire d'inscription</h1>
            <form id="signup-form" method="POST">
                <div class="row">
                    <div class="input-group">
                        <label for="user_last_name" data-translate="last_name">Nom :</label>
                        <input type="text" id="user_last_name" name="user_last_name">
                    </div>
                    <div class="input-group">
                        <label for="user_name" data-translate="first_name">Prénom :</label>
                        <input type="text" id="user_name" name="user_name">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="user_email" data-translate="email">Email :</label>
                        <input type="text" id="user_email" name="user_email">
                    </div>
                    <div class="input-group">
                        <label for="phone_number" data-translate="phone_number">Numéro de téléphone :</label>
                        <input type="tel" id="phone_number" name="phone_number">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="user_password" data-translate="password">Mot de passe :</label>
                        <input type="password" id="user_password" name="user_password">
                    </div>
                    <div class="input-group">
                        <label for="confirm_password" data-translate="confirm_password">Confirmer le mot de passe :</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="wilaya" data-translate="wilaya">Wilaya :</label>
                        <input type="text" id="wilaya" name="wilaya">
                    </div>
                    <div class="input-group">
                        <label for="city" data-translate="city">Ville :</label>
                        <input type="text" id="city" name="city">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="district" data-translate="district">La commune :</label>
                        <input type="text" id="district" name="district">
                    </div>
                    <div class="input-group">
                        <label for="user_address" data-translate="address">L'adresse :</label>
                        <input type="text" id="user_address" name="user_address">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="rip" data-translate="rip">Releve d'identite postal(RIP) :</label>
                        <input type="text" id="rip" name="rip">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="user_l_pseudo" data-translate="left_invitation_code">Code d'Invitation Gauche :</label>
                        <input type="text" id="user_l_pseudo" name="user_l_pseudo">
                    </div>
                    <div class="input-group">
                        <label for="user_r_pseudo" data-translate="right_invitation_code">Code d'Invitation Droite :</label>
                        <input type="text" id="user_r_pseudo" name="user_r_pseudo">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="parrain_pseudo" data-translate="invitation_code">Saisir le Code d'invitation :</label>
                        <input type="text" id="parrain_pseudo" name="parrain_pseudo">
                    </div>
                </div>
                <div class="user-tc">
                    <label for="terms_conditions">
                        <input type="checkbox" id="terms_conditions">
                        <span data-translate="accept_terms">&nbsp;J'accepte<a href="./الشروط و الاحكام و سياسة الخصوصية .docx"> les termes</a> et <a href="./الشروط و الاحكام و سياسة الخصوصية .docx">conditions</a></span>
                    </label>                   
                </div>
                <div id="error-messages"></div>
                <input type="submit" value="S'inscrire" data-translate="signup_button">
                <div class="user-option">
                    <p><span data-translate="already_registered">Déjà inscrit ?</span> <a href="./signin.php" data-translate="login_here">Connectez-vous ici</a></p>
                </div>
            </form>
        </div>
    </section>
    <script src='..\Js/signup.js'></script>
    <script src='..\Js/tsu.js'></script>
</body>
</html>