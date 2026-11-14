<?php
session_start();

if (isset($_SESSION['admin_email'])) {
    $label = "Les administrateurs";
    require_once('global.php');
    require_once('adb.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level !== 'main_admin') {
        header('Location: 1s2qZa-lf.php');
        exit;
    } 

    try {
        $stmt = $conn->prepare("SELECT * FROM Admins");
        $stmt->execute();
        $admins_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database query error: " . $e->getMessage());
        $admins_info = array();
    }

} else {
    header('Location: index.php');
    exit;
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
                                <th>Niveau</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($admins_info)): ?>
                            <?php foreach ($admins_info as $admin): ?>
                                <?php if ($admin['admin_email'] !== $_SESSION['admin_email']): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($admin['admin_email']); ?></td>
                                        <td><?php echo htmlspecialchars($admin['admin_level']); ?></td>
                                        <td>
                                            <button class="permission-button" data-admin-email="<?php echo htmlspecialchars($admin['admin_email']); ?>">Change Permission</button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <td>
                        <p class="permission-item">Aucune information d'administrateur trouvée.</p>
                    </td>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>


<div class="permission-modal-container" id="permissionModal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Modifier les permissions</h3>
        <form id="permissionForm">
            <input type="hidden" id="adminId" name="admin_id">
            <input type="hidden" id="adminEmail" name="admin_email">
            <label>Administrateur Principal</label>
            <input type="radio" name="admin_level" value="main_admin">
            <label>Administrateur Secondaire</label>
            <input type="radio" name="admin_level" value="sub_admin">
            <label>Non Administrateur</label>
            <input type="radio" name="admin_level" value="non_admin">
            <div>
                <button type="submit">Enregistrer</button>
                <p class="permission-message"></p>
            </div>
        </form>
    </div>
</div>


<script src="per-modal.js"></script>
</body>
</html>
