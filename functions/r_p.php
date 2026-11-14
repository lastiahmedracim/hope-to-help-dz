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
            'Invalid request' => 'Requête invalide',
            'User not found.' => 'Utilisateur non trouvé.',
            'Profit successfully retrieved.' => 'Profit récupéré avec succès.',
            'No unpaid profit found to retrieve.' => 'Aucun profit non payé trouvé à récupérer.',
            'Database error:' => 'Erreur de base de données:',
            'You must have bought to reclaim profit.' => 'Vous devez avoir acheté pour réclamer votre profit.',
        ],
        'ar' => [
            'Invalid request' => 'طلب غير صالح',
            'User not found.' => 'لم يتم العثور على المستخدم.',
            'Profit successfully retrieved.' => 'تم استرداد الربح بنجاح.',
            'No unpaid profit found to retrieve.' => 'لم يتم العثور على أرباح غير مدفوعة لاستردادها.',
            'Database error:' => 'خطأ في قاعدة البيانات:',
            'You must have bought to reclaim profit.' => 'يجب أن تكون قد اشتريت لاسترداد الربح.',
        ],
        'en' => [
            'Invalid request' => 'Invalid request',
            'User not found.' => 'User not found.',
            'Profit successfully retrieved.' => 'Profit successfully retrieved.',
            'No unpaid profit found to retrieve.' => 'No unpaid profit found to retrieve.',
            'Database error:' => 'Database error:',
            'You must have bought to reclaim profit.' => 'You must have bought to reclaim profit.',
        ]
    ];

    $lang = filter_input(INPUT_COOKIE, 'language', FILTER_SANITIZE_STRING) ?? 'fr';
    return $translations[$lang][$message] ?? $message;
}

$response = ['success' => false, 'message' => translateMessage('Invalid request')];

if (isset($_SESSION['user_r_pseudo'])) {
    $userPseudo = filter_var($_SESSION['user_r_pseudo'], FILTER_SANITIZE_STRING);

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT user_id, bought, is_v FROM Users WHERE user_r_pseudo = ?");
        $stmt->execute([$userPseudo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception('User not found.');
        }

        if (!$user || (!$user['bought'] && !$user['is_v'])) {
            throw new Exception('You must have bought to reclaim profit.');
        }

        $userId = $user['user_id'];

        $stmt = $pdo->prepare("
            SELECT 'direct' as type, direct_profit_id as profit_id, claimed_profit
            FROM DirectProfit 
            WHERE user_id = ? AND paid = 'not_paid'
            UNION ALL
            SELECT 'indirect' as type, indirect_profit_id as profit_id, claimed_profit
            FROM IndirectProfit
            WHERE user_id = ? AND paid = 'not_paid'
        ");
        $stmt->execute([$userId, $userId]);
        $profits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalProfit = 0;
        foreach ($profits as $profit) {
            $totalProfit += $profit['claimed_profit'];
        }

        if ($totalProfit > 0) {
            $virtualMoneyAmount = min($totalProfit, 150000);
            $stmt = $pdo->prepare("UPDATE Users SET virtual_money = virtual_money + ? WHERE user_id = ?");
            $stmt->execute([$virtualMoneyAmount, $userId]);

            foreach ($profits as $profit) {
                if ($profit['type'] === 'direct') {
                    $stmt = $pdo->prepare("
                        UPDATE DirectProfit 
                        SET is_v = 1, paid = 'paid'
                        WHERE direct_profit_id = ?
                    ");
                    $stmt->execute([$profit['profit_id']]);

                    if ($profit['claimed_profit'] > $virtualMoneyAmount) {
                        $newProfit = $profit['claimed_profit'] - $virtualMoneyAmount;
                        $stmt = $pdo->prepare("
                            INSERT INTO DirectProfit (user_id, claimed_profit, claimed_date, paid, is_v)
                            VALUES (?, ?, NOW(), 'not_paid', 0)
                        ");
                        $stmt->execute([$userId, $newProfit]);
                    }
                } else {
                    $stmt = $pdo->prepare("
                        UPDATE IndirectProfit 
                        SET is_v = 1, paid = 'paid'
                        WHERE indirect_profit_id = ?
                    ");
                    $stmt->execute([$profit['profit_id']]);

                    if ($profit['claimed_profit'] > $virtualMoneyAmount) {
                        $newProfit = $profit['claimed_profit'] - $virtualMoneyAmount;
                        $stmt = $pdo->prepare("
                            INSERT INTO IndirectProfit (user_id, claimed_profit, claimed_date, paid, is_v)
                            VALUES (?, ?, NOW(), 'not_paid', 0)
                        ");
                        $stmt->execute([$userId, $newProfit]);
                    }
                }
                $virtualMoneyAmount -= $profit['claimed_profit'];
                if ($virtualMoneyAmount <= 0) break;
            }

            if ($totalProfit > 150000) {
                $remainingAmount = $totalProfit - 150000;
                $stmt = $pdo->prepare("INSERT INTO CProfit (user_id, c_amount, c_profit_date) VALUES (?, ?, ?)");
                $stmt->execute([$userId, $remainingAmount, date('Y-m-d H:i:s')]);
            }

            $response = ['success' => true, 'message' => translateMessage('Profit successfully retrieved.')];
        } else {
            $response['message'] = translateMessage('No unpaid profit found to retrieve.');
        }

        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        $response['message'] = translateMessage('Database error:') . " " . $e->getMessage();
        error_log("Database error: " . $e->getMessage());
    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = translateMessage($e->getMessage());
        error_log($e->getMessage());
    }
} else {
    $response['message'] = translateMessage('Invalid request');
}

echo json_encode($response);
?>