<?php
session_start();

if (isset($_SESSION['admin_email'])) {
    $label = "Arbre principal";
    $cssfile1 = "../Css/networks.css";
    require_once('global.php');
    require_once('../functions/db.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level !== 'main_admin') {
        header('Location: 1s2qZa-lf.php');
        exit;
    }

    function fetchAllDescendants($userId, $pdo)
    {
        $stmt = $pdo->prepare("SELECT user_id, user_name, user_last_name, left_child_id, right_child_id FROM Users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return [];
        }

        $result = [
            'user_id' => $user['user_id'],
            'user_name' => $user['user_name'],
            'user_last_name' => $user['user_last_name'],
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

        echo '<li>';
        echo '<div class="user">';
        echo '<p>' . $user['user_name'] . '</p>';
        echo '<p>' . $user['user_last_name'] . '</p>';
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

    $userId = 1;
    $stmt = $pdo->prepare("SELECT user_name, user_last_name FROM Users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $userName = $user['user_name'];
        $userLastName = $user['user_last_name'];
    } else {
        exit('User not found');
    }

    $tree = fetchAllDescendants($userId, $pdo);

} else {
    header('Location: index.php');
    exit;
}
?>

<section id="networks1">
    <div class="container">
        <h2>Tous Les Descendants De<?php echo htmlspecialchars($userName) . ' ' . htmlspecialchars($userLastName); ?></h2>
        <div class="tree">
            <ul>
                <?php renderUserWithDescendants($tree); ?>
            </ul>
        </div>
    </div>
</section>
</body>
</html>
