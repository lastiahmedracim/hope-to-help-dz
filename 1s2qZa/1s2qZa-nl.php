<?php
session_start();
if (isset($_SESSION['admin_email'])) {
    $label = "Les abonnés à la newsletter";
    require_once('global.php');  
    require_once('adb.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level === 'main_admin') {
        $subscribers = getSubscribers($conn);
    } else {
        $subscribers = [];
    }
} else {
    header('Location: index.php');
    exit;
}

function getSubscribers($conn) {
    $sql = "SELECT sub_email, subscribed_at FROM subscribers";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $subscribers = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $subscribers[] = $row;
    }
    return $subscribers;
}
?>
    <?php if ($admin_level === 'main_admin') : ?>
        <section id="al">
            <div class="container">
                <div class="al-content">
                        <table>
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Abonné à</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($subscribers)) : ?>
                                <?php foreach ($subscribers as $subscriber) : ?>
                                    <tr>
                                        <td><?php echo $subscriber['sub_email']; ?></td>
                                        <td><?php echo $subscriber['subscribed_at']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <tr>
                            <td colspan="2">Aucun abonné trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</body>
</html>
