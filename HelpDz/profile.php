<?php
session_start();
if (isset($_SESSION['user_r_pseudo'])) {
    $pagetitle = "Profil";
    $cssfile1 = '../Css/profile.css';
    $cssfile2 = '../Css/global.css';
    $logo = '../HomeImg/logo.png';
    $homepage = '../index.php';
    $formation = 'formation.php';
    $finhelp = 'pro_assi.php';
    $mycourses = 'courses.php';
    $profile = 'profile.php';
    $networks = 'networks.php';
    $logout = 'logout.php';
    $contactus = 'contactus.php';
    $aboutus = 'aboutus.php';
    $vm = 'virtual_money.php';
    $newsletter = 'newsletter.php';
    $jsfile1 = '../Js/main.js';
    $jsfile2 = 'newsletter.js';
    $jsfile3 = '../Js/pf.js';
    $gun = 'g_u_n.js';
    $jq = '../Js/jquery-3.6.0.min.js';
    require_once('uheader.php');
} else {
    header('Location: signin.php');
    exit;
}

require_once('../functions/db.php');
$user_r_pseudo = $_SESSION['user_r_pseudo'];

$sql = "SELECT * FROM Users WHERE user_r_pseudo = ?";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(1, $user_r_pseudo);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

function getUserIdByPseudo($pseudo, $pdo)
{
    $stmt = $pdo->prepare("SELECT user_id FROM Users WHERE user_r_pseudo = ?");
    $stmt->execute([$pseudo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        return $user['user_id'];
    } else {
        exit('Utilisateur introuvable');
    }
}

$userPseudo = $_SESSION['user_r_pseudo'];
$userId = getUserIdByPseudo($userPseudo, $pdo);

$tree = fetchAllDescendants($userId, $pdo);
$descendants = flattenTree($tree);

$unpaidProfit = getUnpaidProfit($userId, $pdo);

function fetchAllDescendants($userId, $pdo)
{
    $stmt = $pdo->prepare("SELECT user_id, user_r_pseudo FROM Users WHERE parent_user_id = ?");
    $stmt->execute([$userId]);
    $children = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($children as $child) {
        $result[] = [
            'user_id' => $child['user_id'],
            'user_r_pseudo' => $child['user_r_pseudo'],
            'children' => fetchAllDescendants($child['user_id'], $pdo)
        ];
    }

    return $result;
}

function flattenTree($tree)
{
    $result = [];
    foreach ($tree as $node) {
        $result[] = $node;
        if (!empty($node['children'])) {
            $result = array_merge($result, flattenTree($node['children']));
        }
    }
    return $result;
}

function getUnpaidProfit($userId, $pdo) {
    $stmt = $pdo->prepare("
        SELECT SUM(claimed_profit) as unpaid_profit FROM (
            SELECT claimed_profit FROM DirectProfit WHERE user_id = ? AND paid = 'not_paid'
            UNION ALL
            SELECT claimed_profit FROM IndirectProfit WHERE user_id = ? AND paid = 'not_paid'
        ) as combined_profits
    ");
    $stmt->execute([$userId, $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['unpaid_profit'] : 0;
}
?>

<?php if ($user === false) : ?>
    <section id="error-message">
        <div class="container">
            <h2 class="title">Erreur</h2>
            <p>Utilisateur introuvable.</p>
        </div>
    </section>
<?php else : ?>

    <div class="sidebar">
    <button id="profil-tab-btn" class="tab-btn active" data-translate="Informations du Profil" data-target="profile">Informations du Profil</button>
    <button id="supplementaires-tab-btn" class="tab-btn" data-translate="Informations Supplémentaires" data-target="infos-supplementaires">Informations Supplémentaires</button>
</div>

<div class="content">
    <section id="infos-supplementaires" class="tab-content">
        <div class="container">
            <h2 class="title" data-translate="Informations Supplémentaires">Informations Supplémentaires</h2>

            <div class="info-group">
                <label class="info-label"><span data-translate="Email">Email</span> : <?php echo htmlspecialchars($user['user_email']); ?></label>
            </div>

            <div class="info-group">
                <label class="info-label"><span data-translate="Argent Virtuel">Argent Virtuel</span> : <?php echo htmlspecialchars($user['virtual_money']); ?></label>
            </div>

            <?php if ($unpaidProfit > 0): ?>
            <div class="info-group">
                <label class="info-label"><span data-translate="Profit Non Payé">Profit Non Payé</span> : <?php echo htmlspecialchars($unpaidProfit); ?></label>
            </div>
            <?php endif; ?>
            
            <div class="info-group">
                <label class="info-label"><span data-translate="Code d'invitation Gauche">Code d'invitation Gauche</span> : <?php echo htmlspecialchars($user['user_l_pseudo']); ?></label>
            </div>

            <div class="info-group">
                <label class="info-label"><span data-translate="Code d'invitation Droite">Code d'invitation Droite</span> : <?php echo htmlspecialchars($user['user_r_pseudo']); ?></label>
            </div>

            <div class="info-group">
                <label class="info-label"><span data-translate="Releve d'identite postal(RIP) :">Releve d'identite postal (RIP) :</span> : <?php echo htmlspecialchars($user['RIP']); ?></label><br>
                <button id="change-rip-btn" class="submit-btn" data-translate="Demande change RIP">Demande change RIP</button>
            </div>
            <div id="change-rip-form" style="display: none;">
                <input type="text" id="new-rip" data-translate="Nouveau RIP" placeholder="Nouveau RIP" class="input-field">
                <button id="submit-rip-change" class="submit-btn" data-translate="Envoyer la demande">Envoyer la demande</button>
            </div>
            <div id="demande-error"></div>
            <div id="demande-success"></div>
            
            <div class="info-group">
                <button type="submit" id="reclameButton" class="submit-btn" data-translate="Récupérer le profit">Récupérer le profit</button>
                <div id="profit"></div>
            </div>

            <div class="info-group">
                <label for="descendant" class="info-label"><span data-translate="Sélectionner Descendant">Sélectionner Descendant</span> :</label>
                <select id="descendant" name="descendant">
                    <?php foreach ($descendants as $descendant): ?>
                        <option value="<?php echo htmlspecialchars($descendant['user_id']); ?>">
                            <?php echo htmlspecialchars($descendant['user_r_pseudo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="info-group">
                <label for="profit_amount" class="info-label"><span data-translate="Montant du Profit">Montant du Profit</span> :</label>
                <input type="number" id="profit_amount" name="profit_amount" class="input-field" required>
            </div>

            <div class="info-group">
                <button type="button" id="sendProfitButton" class="submit-btn" data-translate="Envoyer Profit">Envoyer Profit</button>
                <div id="sending-error"></div>
            </div>

            <div class="info-group">
                <button type="button" id="reclaimProfitButton" class="submit-btn" data-translate="Récupérer le Profit en Argent Virtuel">Récupérer le Profit en Argent Virtuel</button><br>
                <div id="reclaiming-error"></div>
            </div>
        </div>
    </section>

    <section id="profile" class="tab-content active">
        <div class="container">
            <h2 class="title" data-translate="Informations du Profil">Informations du Profil</h2>
            <form id="updateUserForm" class="profile-info">
                <div class="info-group">
                    <label for="user_name" class="info-label"><span data-translate="Prénom">Prénom</span> :</label>
                    <input type="text" id="user_name" name="user_name" class="input-field" value="<?php echo htmlspecialchars($user['user_name']); ?>" required>
                </div>

                <div class="info-group">
                    <label for="user_last_name" class="info-label"><span data-translate="Nom">Nom</span> :</label>
                    <input type="text" id="user_last_name" name="user_last_name" class="input-field" value="<?php echo htmlspecialchars($user['user_last_name']); ?>" required>
                </div>

                <div class="info-group">
                    <label for="wilaya" class="info-label"><span data-translate="Wilaya">Wilaya</span> :</label>
                    <input type="text" id="wilaya" name="wilaya" class="input-field" value="<?php echo htmlspecialchars($user['wilaya']); ?>" required>
                </div>

                <div class="info-group">
                    <label for="city" class="info-label"><span data-translate="Ville">Ville</span> :</label>
                    <input type="text" id="city" name="city" class="input-field" value="<?php echo htmlspecialchars($user['city']); ?>" required>
                </div>

                <div class="info-group">
                    <label for="district" class="info-label"><span data-translate="La commune">La commune</span> :</label>
                    <input type="text" id="district" name="district" class="input-field" value="<?php echo htmlspecialchars($user['district']); ?>" required>
                </div>

                <div class="info-group">
                    <label for="user_address" class="info-label"><span data-translate="Adresse">Adresse</span> :</label>
                    <input type="text" id="user_address" name="user_address" class="input-field" value="<?php echo htmlspecialchars($user['user_address']); ?>" required>
                </div>

                <div class="info-group">
                    <label for="phone_number" class="info-label"><span data-translate="Numéro de Téléphone">Numéro de Téléphone</span> :</label>
                    <input type="text" id="phone_number" name="phone_number" class="input-field" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
                </div>

                <button type="button" onclick="updateUserData()" class="submit-btn" data-translate="Mettre à Jour">Mettre à Jour</button>
            </form>
            <div id="info-message"></div>
        </div>
    </section>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            const activeTab = getCookie('activeTab');

            if (activeTab) {
                tabContents.forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(activeTab).classList.add('active');

                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                document.querySelector(`[data-target="${activeTab}"]`).classList.add('active');
            } else {
                tabContents[0].classList.add('active');
                tabButtons[0].classList.add('active');
            }

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetId = button.getAttribute('data-target');
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                    });
                    const targetContent = document.getElementById(targetId);
                    targetContent.classList.add('active');

                    tabButtons.forEach(btn => {
                        btn.classList.remove('active');
                    });
                    button.classList.add('active');

                    setCookie('activeTab', targetId, 1);
                });
            });
        });


        document.addEventListener('DOMContentLoaded', function () {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            
            function setActiveTab(targetId) {
                tabContents.forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(targetId).classList.add('active');

                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                document.querySelector(`[data-target="${targetId}"]`).classList.add('active');

                setCookie('activeTab', targetId, 1);
            }

            const activeTab = getCookie('activeTab') || 'profile';
            setActiveTab(activeTab);

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetId = button.getAttribute('data-target');
                    setActiveTab(targetId);
                });
            });
        });
    </script>
    <script>
        const currentUserData = {
            user_r_pseudo: <?php echo json_encode($user['user_r_pseudo']); ?>,
            wilaya: <?php echo json_encode($user['wilaya']); ?>,
            city: <?php echo json_encode($user['city']); ?>,
            district: <?php echo json_encode($user['district']); ?>,
            user_address: <?php echo json_encode($user['user_address']); ?>,
            phone_number: <?php echo json_encode($user['phone_number']); ?>
        };
    </script>
    <script src='u_p.js'></script>
    <script src='profit.js'></script>
<?php endif; ?>

<?php require_once('footer.php'); ?>
