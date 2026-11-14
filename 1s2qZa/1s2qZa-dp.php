<?php
session_start();

if (isset($_SESSION['admin_email'])) {
    $label = "Demande de diplome";
    $cssfile1 = "../..\Css/permission.css";
    require_once('global.php');
    require_once('adb.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level !== 'main_admin') {
        header('Location: 1s2qZa-lf.php');
        exit;
    } 

    $query = "SELECT 
                Certifications.certification_id,
                Users.user_r_pseudo,
                Users.user_email,
                Courses.course_name,
                Contacts.contact_info
            FROM Certifications
            JOIN Users ON Certifications.user_id = Users.user_id
            JOIN Courses ON Certifications.course_id = Courses.course_id
            LEFT JOIN Contacts ON Certifications.contact_id = Contacts.contact_id";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $certifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
    
} else {
    header('Location: index.php');
    exit;
}

?>
    <section id="al">
        <div class="container">
            <div class="al-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Pseudo Droite</th>
                                <th>Email</th>
                                <th>Course</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($certifications)): ?>
                            <?php foreach ($certifications as $certification): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($certification['user_r_pseudo']); ?></td>
                                    <td><?php echo htmlspecialchars($certification['user_email']); ?></td>
                                    <td><?php echo htmlspecialchars($certification['course_name']); ?></td>
                                    <td><?php echo htmlspecialchars($certification['contact_info']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <td>
                        <p class="no-item">Aucune information de demande de diplôme trouvée.</p>
                    </td>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>
</html>