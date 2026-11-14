<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récupération du mot de passe Administrateur</title>
    <link rel='stylesheet' type='text/css' media='screen' href='../..\Css/form.css'>
</head>
<body>
    <section id="sign">
        <div class="container">
            <h1>Récupération du mot de passe Administrateur</h1>
            <form id="adminRecoveryForm" action="s_a.php" method="POST">
                <div class="row">
                    <div class="input-group">
                        <label for="email">Email :</label>
                        <input type="text" id="email" name="email" placeholder="Entrez votre email">
                    </div>
                </div>
                <div id="error-messages"></div>
                <input type="submit" value="Envoyer le code de réinitialisation">
            </form>
            <div class="admin-option">
                <p>Souvenez-vous de votre mot de passe ? <a href="./index.php">Connectez-vous</a></p>
            </div>
        </div>
    </section>
    <script src="./ad_fp.js"></script>
</body>
</html>