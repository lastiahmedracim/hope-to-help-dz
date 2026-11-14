<?php
session_start();
if (isset($_SESSION['user_r_pseudo'])) {
    header('Location: ../index.php');
    exit;
}
$logo = '../HomeImg/logo.png';

$lang = isset($_COOKIE['language']) ? $_COOKIE['language'] : 'fr';

$translations = [
    'fr' => [
        'title' => 'Se Connecter',
        'connection' => 'Connexion Utilisateur',
        'invitation_code' => 'Code d\'Invitation Droite :',
        'password' => 'Mot de passe :',
        'forgot_password' => 'Mot de passe oublié?',
        'login' => 'Se connecter',
        'new_user' => 'Nouveau sur notre site ?',
        'create_account' => 'Créez un compte'
    ],
    'ar' => [
        'title' => 'تسجيل الدخول',
        'connection' => 'تسجيل دخول المستخدم',
        'invitation_code' => 'رمز الدعوة اليمين:',
        'password' => 'كلمة المرور:',
        'forgot_password' => 'نسيت كلمة المرور؟',
        'login' => 'تسجيل الدخول',
        'new_user' => 'جديد على موقعنا؟',
        'create_account' => 'إنشاء حساب'
    ],
    'en' => [
        'title' => 'Sign In',
        'connection' => 'User Login',
        'invitation_code' => 'Right Invitation Code:',
        'password' => 'Password:',
        'forgot_password' => 'Forgot password?',
        'login' => 'Sign In',
        'new_user' => 'New to our site?',
        'create_account' => 'Create an account'
    ]
];

function t($key) {
    global $translations, $lang;
    return $translations[$lang][$key] ?? $translations['fr'][$key];
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $lang === 'ar' ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo t('title'); ?></title>
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
            <h1><?php echo t('connection'); ?></h1>
            <form id="signin-form" method="POST">
                <div class="row">
                    <div class="input-group">
                        <label for="right_pseudo"><?php echo t('invitation_code'); ?></label>
                        <input type="text" id="user_r_pseudo" name="user_r_pseudo" placeholder="<?php echo t('invitation_code'); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">                               
                        <label for="password"><?php echo t('password'); ?></label>
                        <input type="password" id="password" name="password" placeholder="<?php echo t('password'); ?>">
                    </div>
                </div>
                <div class="user-password">
                    <a href="fp_u.php"><?php echo t('forgot_password'); ?></a>
                </div>
                <div id="error-messages"></div>
                <input type="submit" value="<?php echo t('login'); ?>">
            </form>
            <div class="user-option">
                <p><?php echo t('new_user'); ?> <a href="./signup.php"><?php echo t('create_account'); ?></a></p>
            </div>
        </div>
    </section>
    <script src="../Js/signin.js"></script>
</body>
</html>