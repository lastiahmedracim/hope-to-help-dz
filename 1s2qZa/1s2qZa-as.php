<?php
session_start();

if (isset($_SESSION['admin_email'])) {
    header('Location: 1s2qZa-lf.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Administrateurs</title>
    <link rel='stylesheet' type='text/css' media='screen' href='../Css/form.css'>
    <script src="https://www.google.com/recaptcha/api.js?render=6Ld2gA8qAAAAADdxY7b2fqykkxgs-dUDqd1PaMFU"></script>
</head>
<body>

    <section id="sign">
        <div class="container">
            <h1>Formulaire d'inscription Administrateurs</h1>
            <form id="asignup" action="1s2qZa-asp.php" method="POST">
                <div class="row">
                    <div class="input-group">
                        <label for="admin_last_name">Nom :</label>
                        <input type="text" id="admin_last_name" name="admin_last_name" placeholder="Votre nom">
                    </div>
                    <div class="input-group">
                        <label for="admin_name">Prénom :</label>
                        <input type="text" id="admin_name" name="admin_name" placeholder="Votre prénom">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="admin_email">Email :</label>
                        <input type="text" id="admin_email" name="admin_email" placeholder="Votre Email">
                    </div>
                    <div class="input-group">
                        <label for="phone_number">Numéro de téléphone :</label>
                        <input type="tel" id="phone_number" name="phone_number" placeholder="+213">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="admin_password">Mot de passe :</label>
                        <input type="password" id="admin_password" name="admin_password" placeholder="Mot de passe">
                    </div>
                    <div class="input-group">
                        <label for="confirm_password">Confirmer le mot de passe :</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmer le mot de passe">
                    </div>
                </div>
                <div class="admin-tc">
                    <label for="terms_conditions">
                        <input type="checkbox" id="terms_conditions">
                        <span>&nbsp;J'accepte<a href="#"> les termes</a> et <a href="#">conditions</a></span>
                    </label>                    
                </div>
                <div id="error-messages"></div>
                <input type="submit" value="S'inscrire">
                <div class="admin-option">
                    <p>Déjà inscrit ? <a href="./index.php">Connectez-vous ici</a></p>
                </div>
            </form>
        </div>
    </section>
    <script src="./as.js"></script>
</body>
</html>
