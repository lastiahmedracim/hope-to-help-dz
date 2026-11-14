<?php
session_start();
$logo = './HomeImg/logo.png';

$lang = isset($_COOKIE['language']) ? $_COOKIE['language'] : 'fr';

$translations = [
    'fr' => [
        'title' => 'Réinitialisation du mot de passe',
        'email' => 'Email :',
        'right_pseudo' => 'Pseudonyme droit :',
        'submit' => 'Envoyer le code de réinitialisation',
        'back_to_login' => 'Retour à la connexion'
    ],
    'ar' => [
        'title' => 'إعادة تعيين كلمة المرور',
        'email' => 'البريد الإلكتروني:',
        'right_pseudo' => 'الاسم المستعار الأيمن:',
        'submit' => 'إرسال رمز إعادة التعيين',
        'back_to_login' => 'العودة إلى تسجيل الدخول'
    ],
    'en' => [
        'title' => 'Password Reset',
        'email' => 'Email:',
        'right_pseudo' => 'Right Pseudonym:',
        'submit' => 'Send Reset Code',
        'back_to_login' => 'Back to Login'
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
    <link rel="icon" href="<?php echo $logo; ?>" type="image/png">
    <title><?php echo t('title'); ?></title>
    <link rel='stylesheet' type='text/css' media='screen' href='..\Css\form.css'>
</head>
<body>
    <section id="forgot-password">
        <div class="container">
            <h1 data-translate="title"><?php echo t('title'); ?></h1>
            <form id="forgot-password-form" action="send_otp.php" method="POST">
                <div class="row">
                    <div class="input-group">
                        <label for="user_email" data-translate="email"><?php echo t('email'); ?></label>
                        <input type="text" id="user_email" name="user_email" placeholder="<?php echo t('email'); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="user_r_pseudo" data-translate="right_pseudo"><?php echo t('right_pseudo'); ?></label>
                        <input type="text" id="user_r_pseudo" name="user_r_pseudo" placeholder="<?php echo t('right_pseudo'); ?>">
                    </div>
                </div>
                <div id="error-messages"></div>
                <input type="submit" value="<?php echo t('submit'); ?>" data-translate="submit">
            </form>
            <div class="user-option">
                <p><a href="./signin.php" data-translate="back_to_login"><?php echo t('back_to_login'); ?></a></p>
            </div>
        </div>
    </section>
    <script src="./fp_u.js"></script>
</body>
</html>