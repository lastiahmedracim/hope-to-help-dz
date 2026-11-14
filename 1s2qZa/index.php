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
    <title>Connexion Administrateur</title>
    <link rel='stylesheet' type='text/css' media='screen' href='../Css/form.css'>
    <script src="https://www.google.com/recaptcha/api.js?render=6Ld2gA8qAAAAADdxY7b2fqykkxgs-dUDqd1PaMFU"></script>
</head>
<body>
    <section id="sign">
        <div class="container">
            <h1>Connexion Administrateurs</h1>
            <form id="adminLoginForm" action="1s2qZa-alp.php" method="POST">
                <div class="row">
                    <div class="input-group">
                        <label for="email_or_phone">Email ou Téléphone :</label>
                        <input type="text" id="email_or_phone" name="email_or_phone" placeholder="Entrez votre email ou téléphone">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">                               
                        <label for="password">Mot de passe :</label>
                        <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe">
                    </div>
                </div>
                <div class="admin-password">
                    <a href="ad_fp.php">Mot de passe oublié?</a>
                </div>
                <div id="error-messages"></div>
                <input type="submit" value="Se connecter">
            </form>
            <div class="admin-option">
                <p>Nouveau sur notre site ? <a href="./1s2qZa-as.php">Créez un compte</a></p>
            </div>
        </div>
    </section>
    <script src="./al.js"></script>
</body>
</html>