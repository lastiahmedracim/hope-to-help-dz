<?php
session_start();
require_once('db.php');

header('Content-Type: application/json');

header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self';");

function translateMessage($message) {
    $translations = [
        'fr' => [
            'Requête invalide' => 'Requête invalide',
            'Veuillez entrer un montant de profit valide.' => 'Veuillez entrer un montant de profit valide.',
            'Utilisateur non trouvé.' => 'Utilisateur non trouvé.',
            'Profit envoyé avec succès.' => 'Profit envoyé avec succès.',
            'Profit envoyé avec succès. Le montant excédant 150 000 a été ajouté à CProfit.' => 'Profit envoyé avec succès. Le montant excédant 150 000 a été ajouté à CProfit.',
            'Profit non payé insuffisant pour l\'envoi.' => 'Profit non payé insuffisant pour l\'envoi.',
            'Aucun profit non payé trouvé.' => 'Aucun profit non payé trouvé.',
            'Erreur de base de données:' => 'Erreur de base de données:',
            'Vous devez avoir acheté pour envoyer du profit.' => 'Vous devez avoir acheté pour envoyer du profit.',
            'Requête invalide.' => 'Requête invalide.'
        ],
        'ar' => [
            'Requête invalide' => 'طلب غير صالح',
            'Veuillez entrer un montant de profit valide.' => 'الرجاء إدخال مبلغ ربح صالح.',
            'Utilisateur non trouvé.' => 'لم يتم العثور على المستخدم.',
            'Profit envoyé avec succès.' => 'تم إرسال الربح بنجاح.',
            'Profit envoyé avec succès. Le montant excédant 150 000 a été ajouté à CProfit.' => 'تم إرسال الربح بنجاح. تمت إضافة المبلغ الزائد عن 150000 إلى CProfit.',
            'Profit non payé insuffisant pour l\'envoi.' => 'الربح غير المدفوع غير كافٍ للإرسال.',
            'Aucun profit non payé trouvé.' => 'لم يتم العثور على أرباح غير مدفوعة.',
            'Erreur de base de données:' => 'خطأ في قاعدة البيانات:',
            'Vous devez avoir acheté pour envoyer du profit.' => 'يجب أن تكون قد اشتريت لإرسال الربح.',
            'Requête invalide.' => 'طلب غير صالح.'
        ],
        'en' => [
            'Requête invalide' => 'Invalid request',
            'Veuillez entrer un montant de profit valide.' => 'Please enter a valid profit amount.',
            'Utilisateur non trouvé.' => 'User not found.',
            'Profit envoyé avec succès.' => 'Profit sent successfully.',
            'Profit envoyé avec succès. Le montant excédant 150 000 a été ajouté à CProfit.' => 'Profit sent successfully. The amount exceeding 150,000 has been added to CProfit.',
            'Profit non payé insuffisant pour l\'envoi.' => 'Insufficient unpaid profit for sending.',
            'Aucun profit non payé trouvé.' => 'No unpaid profit found.',
            'Erreur de base de données:' => 'Database error:',
            'Vous devez avoir acheté pour envoyer du profit.' => 'You must have bought to send profit.',
            'Requête invalide.' => 'Invalid request.'
        ]
    ];

    $lang = $_COOKIE['language'] ?? 'fr';
    return $translations[$lang][$message] ?? $message;
}

$response = ['success' => false, 'message' => translateMessage('Requête invalide')];

if (isset($_POST['descendant_id']) && isset($_POST['profit_amount']) && isset($_SESSION['user_r_pseudo'])) {
    $descendantId = filter_var($_POST['descendant_id'], FILTER_SANITIZE_NUMBER_INT);
    $profitAmount = filter_var($_POST['profit_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $userPseudo = $_SESSION['user_r_pseudo'];

    if (!is_numeric($profitAmount) || $profitAmount <= 0 || strpos($_POST['profit_amount'], '-') !== false) {
        $response['message'] = translateMessage('Veuillez entrer un montant de profit valide.');
        echo json_encode($response);
        exit;
    }

    error_log("ID Descendant: " . $descendantId);
    error_log("Montant du Profit: " . $profitAmount);
    error_log("Pseudo Utilisateur: " . $userPseudo);

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT user_id, bought, is_v FROM Users WHERE user_r_pseudo = ?");
        $stmt->execute([$userPseudo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            $response['message'] = translateMessage('Utilisateur non trouvé.');
            echo json_encode($response);
            exit;
        }
        
        if (!$user || (!$user['bought'] && !$user['is_v'])) {
            $response['message'] = translateMessage('Vous devez avoir acheté pour envoyer du profit.');
            echo json_encode($response);
            exit;
        }
        $userId = $user['user_id'];

        error_log("ID Utilisateur: " . $userId);

        $stmt = $pdo->prepare("
            SELECT 
                COALESCE(SUM(dp.claimed_profit), 0) + COALESCE(SUM(ip.claimed_profit), 0) as total_unpaid_profit
            FROM 
                Users u
            LEFT JOIN DirectProfit dp ON u.user_id = dp.user_id AND dp.paid = 'not_paid'
            LEFT JOIN IndirectProfit ip ON u.user_id = ip.user_id AND ip.paid = 'not_paid'
            WHERE 
                u.user_id = ?
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $totalUnpaidProfit = $result['total_unpaid_profit'];

        error_log("Total Unpaid Profit: " . $totalUnpaidProfit);

        if ($totalUnpaidProfit > 0) {
            if ($profitAmount <= $totalUnpaidProfit) {
                $remainingAmount = $profitAmount;
                
                $stmt = $pdo->prepare("
                    SELECT direct_profit_id, claimed_profit
                    FROM DirectProfit 
                    WHERE user_id = ? AND paid = 'not_paid'
                    ORDER BY claimed_date ASC
                ");
                $stmt->execute([$userId]);
                $directProfits = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($directProfits as $profit) {
                    if ($remainingAmount <= 0) break;

                    $currentProfit = min($profit['claimed_profit'], $remainingAmount);
                    $newProfit = $profit['claimed_profit'] - $currentProfit;

                    $stmt = $pdo->prepare("
                        UPDATE DirectProfit 
                        SET is_v = 1, paid = 'paid'
                        WHERE direct_profit_id = ?
                    ");
                    $stmt->execute([$profit['direct_profit_id']]);

                    if ($newProfit > 0) {
                        $stmt = $pdo->prepare("
                            INSERT INTO DirectProfit (user_id, claimed_profit, claimed_date, paid, is_v)
                            VALUES (?, ?, NOW(), 'not_paid', 0)
                        ");
                        $stmt->execute([$userId, $newProfit]);
                    }

                    $remainingAmount -= $currentProfit;
                }

                if ($remainingAmount > 0) {
                    $stmt = $pdo->prepare("
                        SELECT indirect_profit_id, claimed_profit
                        FROM IndirectProfit 
                        WHERE user_id = ? AND paid = 'not_paid'
                        ORDER BY claimed_date ASC
                    ");
                    $stmt->execute([$userId]);
                    $indirectProfits = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($indirectProfits as $profit) {
                        if ($remainingAmount <= 0) break;

                        $currentProfit = min($profit['claimed_profit'], $remainingAmount);
                        $newProfit = $profit['claimed_profit'] - $currentProfit;

                        $stmt = $pdo->prepare("
                            UPDATE IndirectProfit 
                            SET is_v = 1, paid = 'paid'
                            WHERE indirect_profit_id = ?
                        ");
                        $stmt->execute([$profit['indirect_profit_id']]);

                        if ($newProfit > 0) {
                            $stmt = $pdo->prepare("
                                INSERT INTO IndirectProfit (user_id, claimed_profit, claimed_date, paid, is_v)
                                VALUES (?, ?, NOW(), 'not_paid', 0)
                            ");
                            $stmt->execute([$userId, $newProfit]);
                        }

                        $remainingAmount -= $currentProfit;
                    }
                }

                if ($profitAmount <= 150000) {
                    $stmt = $pdo->prepare("UPDATE Users SET virtual_money = virtual_money + ? WHERE user_id = ?");
                    $stmt->execute([$profitAmount, $descendantId]);
                    error_log("Argent virtuel mis à jour pour le descendant");

                    $stmt = $pdo->prepare("INSERT INTO SendedProfit (sender_id, receiver_id, amount) VALUES (?, ?, ?)");
                    $stmt->execute([$userId, $descendantId, $profitAmount]);
                    error_log("Profit envoyé enregistré dans SendedProfit");

                    $response = ['success' => true, 'message' => translateMessage('Profit envoyé avec succès.')];
                } else {
                    $virtualMoneyAmount = 150000;
                    $stmt = $pdo->prepare("UPDATE Users SET virtual_money = virtual_money + ? WHERE user_id = ?");
                    $stmt->execute([$virtualMoneyAmount, $descendantId]);
                    error_log("Argent virtuel mis à jour pour le descendant (limité à 150000)");

                    $remainingAmount = $profitAmount - 150000;
                    $stmt = $pdo->prepare("INSERT INTO CProfit (user_id, c_amount, c_profit_date) VALUES (?, ?, ?)");
                    $stmt->execute([$descendantId, $remainingAmount, date('Y-m-d H:i:s')]);
                    error_log("Montant restant inséré dans CProfit");

                    $stmt = $pdo->prepare("INSERT INTO SendedProfit (sender_id, receiver_id, amount) VALUES (?, ?, ?)");
                    $stmt->execute([$userId, $descendantId, $profitAmount]);
                    error_log("Profit envoyé enregistré dans SendedProfit");

                    $response = ['success' => true, 'message' => translateMessage('Profit envoyé avec succès. Le montant excédant 150 000 a été ajouté à CProfit.')];
                }
            } else {
                $response['message'] = translateMessage('Profit non payé insuffisant pour l\'envoi.');
                error_log("Profit non payé insuffisant");
            }
        } else {
            $response['message'] = translateMessage('Aucun profit non payé trouvé.');
            error_log("Aucun profit non payé trouvé");
        }

        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        $response['message'] = translateMessage('Erreur de base de données:') . " " . $e->getMessage();
        error_log("Erreur de base de données: " . $e->getMessage());
    }
} else {
    $response['message'] = translateMessage('Requête invalide.');
    error_log("Paramètres de requête invalides");
}

echo json_encode($response);
?>