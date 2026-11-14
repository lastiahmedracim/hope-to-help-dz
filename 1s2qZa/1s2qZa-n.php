<?php
session_start();
if (isset($_SESSION['admin_email'])) {
    $label = "Les Notifications";
    require_once('global.php');  
    require_once('adb.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    $stmt = $conn->prepare("SELECT * FROM Notifications ORDER BY created_at DESC");
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header('Location: index.php');
    exit;
}
?>

<?php if ($admin_level === 'main_admin' || $admin_level === 'sub_admin') : ?>
    <section id="al">
        <div class="container">
            <div class="al-content">
                <button class="create-notification-btn">Créer une nouvelle notification</button>
                <table>
                    <thead>
                        <tr>
                            <th>Sujet</th>
                            <th>Message</th>
                            <th>Créé le</th>
                            <th>Expire le</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($notifications)) : ?>
                            <?php foreach ($notifications as $notification): ?>
                                <tr>
                                    <td>
                                        <?php
                                        $shortSubject = strlen($notification['subject']) > 50 ? substr($notification['subject'], 0, 50) . '...' : $notification['subject'];
                                        echo htmlspecialchars($shortSubject);
                                        ?>
                                        <div class="full-content"><?php echo htmlspecialchars($notification['subject']); ?></div>
                                    </td>
                                    <td>
                                        <?php
                                        $shortMessage = strlen($notification['message']) > 50 ? substr($notification['message'], 0, 50) . '...' : $notification['message'];
                                        echo htmlspecialchars($shortMessage);
                                        ?>
                                        <div class="full-content"><?php echo htmlspecialchars($notification['message']); ?></div>
                                    </td>
                                    <td><?php echo $notification['created_at']; ?></td>
                                    <td><?php echo $notification['expires_at']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6">Aucune notification trouvée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php endif; ?>

<div class="notification-modal">
    <div class="notification-modal-content">
        <span class="notification-close">&times;</span>
        <h3>Créer une nouvelle notification</h3>
        <form id="notificationForm" class="notification-form">
            <input type="text" id="subject" name="subject" placeholder="Sujet">
            <textarea id="message" name="message" placeholder="Message"></textarea>
            <select id="targetType" name="targetType">
                <option value="all">Tous les utilisateurs</option>
                <option value="specific">Utilisateurs spécifiques</option>
            </select>
            <div id="userSelection" style="display: none;">
                <select id="users" name="users[]" multiple>
                    <?php
                    $stmt = $conn->query("SELECT user_id, user_r_pseudo, user_email FROM Users");
                    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $user['user_id'] . "'>" . htmlspecialchars($user['user_r_pseudo'] . ' (' . $user['user_email'] . ')') . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="input-with-icon">
                <input type="datetime-local" id="expiresAt" name="expiresAt">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <button type="submit" class="notification-submit">Créer la notification</button>
        </form>
        <div id="n-message"></div>
    </div>
</div>

<script src="n.js"></script>
</body>
</html>