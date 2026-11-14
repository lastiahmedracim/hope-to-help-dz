<?php
session_start();
require_once('../functions/db.php');

if (!isset($_SESSION['user_email'])) {
    header('Location: signin.php');
    exit;
}

function getUserIdByEmail($email, $pdo) {
    $stmt = $pdo->prepare("SELECT user_id FROM Users WHERE user_email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        return $user['user_id'];
    } else {
        exit('Utilisateur non trouvé');
    }
}

function getRootUserPseudos($userId, $pdo) {
    $stmt = $pdo->prepare("SELECT user_l_pseudo, user_r_pseudo FROM Users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        return [$user['user_l_pseudo'], $user['user_r_pseudo']];
    } else {
        exit('Pseudonymes de l\'utilisateur racine non trouvés');
    }
}

function isReclaimed($rootEmail, $descendantEmail, $status, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM ReclaimedProfits WHERE root_email = ? AND descendant_email = ? AND status = ?");
    $stmt->execute([$rootEmail, $descendantEmail, $status]);
    $reclaimed = $stmt->fetch(PDO::FETCH_ASSOC);
    return !!$reclaimed;
}

function markDescendantsAsReclaimed($rootEmail, $descendantEmails, $pdo, $status) {
    $stmt = $pdo->prepare("INSERT INTO ReclaimedProfits (root_email, descendant_email, status) VALUES (?, ?, ?)");
    foreach ($descendantEmails as $email) {
        if (!isReclaimed($rootEmail, $email, $status, $pdo)) {
            $stmt->execute([$rootEmail, $email, $status]);
        }
    }
}

function fetchAllDescendantsWithLevel($userId, $level, &$treeLevels, $rootLeftPseudo, $rootRightPseudo, $rootEmail, $pdo, &$bonusCount) {
    $stmt = $pdo->prepare("SELECT user_id, user_email, parrain_pseudo, bought FROM Users WHERE parent_user_id = ?");
    $stmt->execute([$userId]);
    $children = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($children as $child) {
        if (!isset($treeLevels[$level])) {
            $treeLevels[$level] = ['count' => 0, 'bonusCount' => 0, 'descendantEmails' => [], 'bonusDescendantEmails' => []];
        }
        
        if ($child['bought']) {
            $reclaimedCount = isReclaimed($rootEmail, $child['user_email'], 'count', $pdo);
            
            if (!$reclaimedCount) {
                $treeLevels[$level]['count']++;
                $treeLevels[$level]['descendantEmails'][] = $child['user_email'];
            }
            
            if ($child['parrain_pseudo'] == $rootLeftPseudo || $child['parrain_pseudo'] == $rootRightPseudo) {
                $reclaimedBcount = isReclaimed($rootEmail, $child['user_email'], 'bcount', $pdo);
                
                if (!$reclaimedBcount) {
                    $treeLevels[$level]['bonusCount']++;
                    $treeLevels[$level]['bonusDescendantEmails'][] = $child['user_email'];
                }
            }
        }
        
        fetchAllDescendantsWithLevel($child['user_id'], $level + 1, $treeLevels, $rootLeftPseudo, $rootRightPseudo, $rootEmail, $pdo, $bonusCount);
    }
    
    if (isset($treeLevels[$level]['bonusCount']) && isset($treeLevels[$level]['bonusDescendantEmails'])) {
        if ($treeLevels[$level]['bonusCount'] > 0) {
            if (date('w') == 1)  {
                markDescendantsAsReclaimed($rootEmail, $treeLevels[$level]['bonusDescendantEmails'], $pdo, 'bcount');
            }
        }
    }
}

function calculateAdjustedProfit($treeLevels, $pdo, $rootEmail) {
    $profit = 0;

    foreach ($treeLevels as $level => $data) {
        $count = $data['count'];

        if ($count > 0) {
            $adjustedCount = max(0, $count - 1);
            $profit += $adjustedCount * 1000;

            if ($adjustedCount > 0) {
                if (date('w') == 1)  {
                    $descendantEmailsToMark = array_slice($data['descendantEmails'], 0, $adjustedCount);
                    markDescendantsAsReclaimed($rootEmail, $descendantEmailsToMark, $pdo, 'count');
                }
            }
        }

        $bcount = $data['bonusCount'];

        if ($bcount > 0) {
            $profit += $bcount * 3000;
        }
    }

    return $profit;
}

$userEmail = filter_var($_SESSION['user_email'], FILTER_SANITIZE_EMAIL);

$userId = getUserIdByEmail($userEmail, $pdo);

list($rootLeftPseudo, $rootRightPseudo) = getRootUserPseudos($userId, $pdo);

$treeLevels = [];

$bonusCount = 0;

fetchAllDescendantsWithLevel($userId, 0, $treeLevels, $rootLeftPseudo, $rootRightPseudo, $userEmail, $pdo, $bonusCount);

$profit = calculateAdjustedProfit($treeLevels, $pdo, $userEmail);

echo "Votre profit total : " . htmlspecialchars($profit) . " DZD<br>";

if (date('w') == 1)  {
    if ($profit > 1000) {
        $currentTimestamp = date('Y-m-d H:i:s');

        if ($profit <= 150000) {
            $stmt = $pdo->prepare("INSERT INTO UProfit (user_id, last_claimed_date, last_claimed_profit) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $currentTimestamp, $profit]);
        } else {
            $remainingProfit = $profit - 150000;

            $stmt = $pdo->prepare("INSERT INTO UProfit (user_id, last_claimed_date, last_claimed_profit) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $currentTimestamp, 150000]);

            $stmt = $pdo->prepare("INSERT INTO CProfit (user_id, c_amount, c_profit_date) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $remainingProfit, $currentTimestamp]);
        }

        echo "Votre profit a été réclamé avec succès.";
    } else {
        echo "Votre profit doit être supérieur à 1000 DZD pour pouvoir le réclamer.";
    }
} else {
    echo "Vous pouvez réclamer votre profit uniquement les jeudis.";
}
?>