<?php
session_start();
if (isset($_SESSION['admin_email'])) {
    $label = "Contactez-nous";
    require_once('global.php');  
    require_once('adb.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level === 'main_admin') {
        $submissions = get_contact_submissions($conn);
    } else {
        $submissions = [];
    }
} else {
    header('Location: index.php');
    exit;
}

function get_contact_submissions($conn) {
    $stmt = $conn->prepare("SELECT id, name, email, subject, message, created_at FROM ContactSubmissions");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
    <?php if ($admin_level === 'main_admin') : ?>
        <section id="al">
            <div class="container">
                <div class="al-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Sujet</th>
                                <th>Message</th>
                                <th>Créé à</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($submissions)) : ?>
                                <?php foreach ($submissions as $submission): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($submission['name']); ?></td>
                                        <td><?php echo htmlspecialchars($submission['email']); ?></td>
                                        <td><?php echo htmlspecialchars($submission['subject']); ?></td>
                                        <td>
                                            <?php
                                            $shortMessage = strlen($submission['message']) > 50 ? substr($submission['message'], 0, 50) . '...' : $submission['message'];
                                            echo htmlspecialchars($shortMessage);
                                            ?>
                                            <div class="full-message"><?php echo htmlspecialchars($submission['message']); ?></div>
                                        </td>

                                        <td><?php echo htmlspecialchars($submission['created_at']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6">Aucune soumission de contact trouvée.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    <?php endif; ?>
</body>
</html>
