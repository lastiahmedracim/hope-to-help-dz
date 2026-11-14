<?php
session_start();
require_once('../functions/db.php');

if (!isset($_SESSION['user_r_pseudo'])) {
    header('Location: signin.php');
    exit;
}

$userRPseudo = $_SESSION['user_r_pseudo'];
$userId = getUserIdByRPseudo($userRPseudo, $pdo);

$tree = fetchAllDescendants($userId, $pdo);

$translations = [
    'fr' => [
        'Tous Les Descendants De' => 'Tous Les Descendants De',
    ],
    'ar' => [
        'Tous Les Descendants De' => 'جميع أحفاد',
    ],
    'en' => [
        'Tous Les Descendants De' => 'All Descendants Of',
    ]
];

function translate($key, $lang) {
    global $translations;
    return $translations[$lang][$key] ?? $key;
}

// Get the language from the cookie set by main.js
$currentLang = isset($_COOKIE['language']) ? $_COOKIE['language'] : 'fr';

// Ensure the language is valid, otherwise default to French
if (!array_key_exists($currentLang, $translations)) {
    $currentLang = 'fr';
}

function getUserIdByRPseudo($rPseudo, $pdo)
{
    $stmt = $pdo->prepare("SELECT user_id FROM Users WHERE user_r_pseudo = ?");
    $stmt->execute([$rPseudo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        return $user['user_id'];
    } else {
        exit('User not found');
    }
}

function fetchAllDescendants($userId, $pdo)
{
    $stmt = $pdo->prepare("SELECT user_id, user_name, user_last_name, user_r_pseudo, left_child_id, right_child_id, bought, is_v FROM Users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return [];
    }

    $result = [
        'user_id' => $user['user_id'],
        'user_name' => $user['user_name'],
        'user_last_name' => $user['user_last_name'],
        'user_r_pseudo' => $user['user_r_pseudo'],
        'bought' => $user['bought'],
        'is_v' => $user['is_v'],
        'left_child' => null,
        'right_child' => null
    ];

    if ($user['left_child_id']) {
        $result['left_child'] = fetchAllDescendants($user['left_child_id'], $pdo);
    }

    if ($user['right_child_id']) {
        $result['right_child'] = fetchAllDescendants($user['right_child_id'], $pdo);
    }

    return $result;
}

function renderUserWithDescendants($user)
{
    if (!$user) {
        return;
    }

    $statusClass = ($user['bought'] || $user['is_v']) ? 'online' : 'offline';

    echo '<li>';
    echo '<div class="user ' . $statusClass . '">';
    echo '<p>' . htmlspecialchars($user['user_name']) . '</p>';
    echo '<p>' . htmlspecialchars($user['user_last_name']) . '</p>';
    echo '<p>' . htmlspecialchars($user['user_r_pseudo']) . '</p>';
    echo '</div>';

    if ($user['left_child'] || $user['right_child']) {
        echo '<ul>';
        if ($user['left_child']) {
            renderUserWithDescendants($user['left_child']);
        }
        if ($user['right_child']) {
            renderUserWithDescendants($user['right_child']);
        }
        echo '</ul>';
    }

    echo '</li>';
}

function getUserInfoByRPseudo($rPseudo, $pdo)
{
    $stmt = $pdo->prepare("SELECT user_id, user_name, user_last_name, user_r_pseudo FROM Users WHERE user_r_pseudo = ?");
    $stmt->execute([$rPseudo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        return $user;
    } else {
        exit('User not found');
    }
}

$userData = getUserInfoByRPseudo($userRPseudo, $pdo);
$userName = $userData['user_name'];
$userLastName = $userData['user_last_name'];
$userId = $userData['user_id'];

$logo = '../HomeImg/logo.png';
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Arbre</title>
    <link rel='stylesheet' type='text/css' media='screen' href='../Css/networks.css'>
    <script src='../Js/jquery-3.6.0.min.js'></script>
</head>
<body>

<section id="networks">
    <div class="container">
        <h2>
            <span id="translatedText"><?php echo translate('Tous Les Descendants De', $currentLang); ?></span>
            <?php echo ' ' . htmlspecialchars($userName . ' ' . $userLastName . ' (' . $userRPseudo . ')'); ?>
        </h2>
        <div class="tree">
            <ul>
                <?php renderUserWithDescendants($tree); ?>
            </ul>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateText() {
        const lang = getCookie('language') || 'fr';
        const translations = <?php echo json_encode($translations); ?>;
        const translatedText = translations[lang]['Tous Les Descendants De'] || 'Tous Les Descendants De';
        document.getElementById('translatedText').textContent = translatedText;
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    window.addEventListener('languageChanged', updateText);
    updateText();
});
</script>
</body>
</html>