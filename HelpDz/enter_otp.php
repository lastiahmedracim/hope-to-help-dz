<?php
session_start();
$logo = './HomeImg/logo.png';

$lang = isset($_COOKIE['language']) ? $_COOKIE['language'] : 'fr';

$translations = [
    'fr' => [
        'title' => 'Entrer le code OTP',
        'email' => 'Email :',
        'right_pseudo' => 'Pseudonyme droit :',
        'otp' => 'Code OTP :',
        'new_password' => 'Nouveau mot de passe :',
        'confirm_password' => 'Confirmer le mot de passe :',
        'submit' => 'Réinitialiser le mot de passe',
        'email_placeholder' => 'Entrez votre adresse email',
        'right_pseudo_placeholder' => 'Entrez votre pseudonyme droit',
        'otp_placeholder' => 'Entrez le code OTP reçu par email',
        'new_password_placeholder' => 'Entrez votre nouveau mot de passe',
        'confirm_password_placeholder' => 'Confirmez votre nouveau mot de passe'
    ],
    'ar' => [
        'title' => 'أدخل رمز OTP',
        'email' => 'البريد الإلكتروني:',
        'right_pseudo' => 'الاسم المستعار الأيمن:',
        'otp' => 'رمز OTP:',
        'new_password' => 'كلمة المرور الجديدة:',
        'confirm_password' => 'تأكيد كلمة المرور:',
        'submit' => 'إعادة تعيين كلمة المرور',
        'email_placeholder' => 'أدخل عنوان بريدك الإلكتروني',
        'right_pseudo_placeholder' => 'أدخل اسمك المستعار الأيمن',
        'otp_placeholder' => 'أدخل رمز OTP الذي تلقيته عبر البريد الإلكتروني',
        'new_password_placeholder' => 'أدخل كلمة المرور الجديدة',
        'confirm_password_placeholder' => 'أكد كلمة المرور الجديدة'
    ],
    'en' => [
        'title' => 'Enter OTP Code',
        'email' => 'Email:',
        'right_pseudo' => 'Right Pseudonym:',
        'otp' => 'OTP Code:',
        'new_password' => 'New Password:',
        'confirm_password' => 'Confirm Password:',
        'submit' => 'Reset Password',
        'email_placeholder' => 'Enter your email address',
        'right_pseudo_placeholder' => 'Enter your right pseudonym',
        'otp_placeholder' => 'Enter the OTP code received by email',
        'new_password_placeholder' => 'Enter your new password',
        'confirm_password_placeholder' => 'Confirm your new password'
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
    <section id="enter-otp">
        <div class="container">
            <h1 data-translate="title"><?php echo t('title'); ?></h1>
            <form id="enter-otp-form" action="r_p.php" method="POST">
                <div class="row">
                    <div class="input-group">
                        <label for="user_email" data-translate="email"><?php echo t('email'); ?></label>
                        <input type="text" id="user_email" name="user_email" placeholder="<?php echo t('email_placeholder'); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="user_r_pseudo" data-translate="right_pseudo"><?php echo t('right_pseudo'); ?></label>
                        <input type="text" id="user_r_pseudo" name="user_r_pseudo" placeholder="<?php echo t('right_pseudo_placeholder'); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="otp" data-translate="otp"><?php echo t('otp'); ?></label>
                        <input type="text" id="otp" name="otp" placeholder="<?php echo t('otp_placeholder'); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="new_password" data-translate="new_password"><?php echo t('new_password'); ?></label>
                        <input type="password" id="new_password" name="new_password" placeholder="<?php echo t('new_password_placeholder'); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label for="confirm_password" data-translate="confirm_password"><?php echo t('confirm_password'); ?></label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="<?php echo t('confirm_password_placeholder'); ?>">
                    </div>
                </div>
                <div id="otp-messages"></div>
                <input type="submit" value="<?php echo t('submit'); ?>" data-translate="submit">
            </form>
        </div>
    </section>
    <script src="./enter_otp.js"></script>
</body>
</html>