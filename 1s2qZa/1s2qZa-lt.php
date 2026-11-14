<?php
session_start();
if (isset($_SESSION['admin_email'])) {
    $label = "Les transactions";
    require_once('global.php');  
    require_once('adb.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level === 'main_admin') {
        $sendedProfit = getAllSendedProfit($conn);
    } else {
        $sendedProfit = [];
    }
} else {
    header('Location: index.php');
    exit;
}

function getAllSendedProfit($conn) {
    $sql = "SELECT sp.*, sender.user_r_pseudo AS sender_pseudo, receiver.user_r_pseudo AS receiver_pseudo
            FROM SendedProfit sp
            JOIN Users sender ON sp.sender_id = sender.user_id
            JOIN Users receiver ON sp.receiver_id = receiver.user_id";
    $stmt = $conn->query($sql);
    if (!$stmt) {
        echo "Erreur lors de l'exécution de la requête SQL : " . $conn->errorInfo()[2] . "<br>";
        return [];
    }
    
    $sendedProfit = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sendedProfit[] = $row;
    }
    return $sendedProfit;
}
?>
<?php if ($admin_level === 'main_admin') : ?>
    <section id="al">
        <div class="container">
            <div class="al-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Pseudo Droite de l'expéditeur</th>
                                <th>Pseudo Droite du destinataire</th>
                                <th>Montant</th>
                                <th>Envoyé le</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($sendedProfit)) : ?>
                            <?php foreach ($sendedProfit as $transaction) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($transaction['sender_pseudo']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['receiver_pseudo']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['amount']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['sent_at']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <tr>
                        <td colspan="4">Aucun enregistrement trouvé.</td>
                    </tr>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
</body>
</html>