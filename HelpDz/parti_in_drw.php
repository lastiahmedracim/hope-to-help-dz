<?php
session_start();
if (!isset($_SESSION['user_r_pseudo'])) {
    header('Location: signin.php');
    exit;
}

$pagetitle = "Aide aux projets";
$cssfile1 = '../Css/global.css';
$cssfile2 = '../Css/parti_in_drw.css';
$logo = '../HomeImg/logo.png';
$homepage = '../index.php';
$formation = 'formation.php';
$finhelp = 'pro_assi.php';
$mycourses = 'courses.php';
$profile = 'profile.php';
$networks = 'networks.php';
$logout = 'logout.php';
$contactus = 'contactus.php';
$newsletter = 'newsletter.php';
$aboutus = 'aboutus.php';
$vm = 'virtual_money.php';
$jsfile1 = '../Js/main.js';
$jsfile2 = 'newsletter.js';
$jsfile3 = '../Js/pid.js';
$gun = 'g_u_n.js';
$jq = '../Js/jquery-3.6.0.min.js';

require_once('uheader.php');
require_once('../functions/db.php');

$user_r_pseudo = $_SESSION['user_r_pseudo'];
$stmt = $pdo->prepare("
    SELECT u.user_id, u.virtual_money, u.bought, u.is_v, COALESCE(p.is_d, FALSE) as is_d
    FROM Users u
    LEFT JOIN Paid p ON u.user_id = p.user_id
    WHERE u.user_r_pseudo = ?
");
$stmt->execute([$user_r_pseudo]);
$user = $stmt->fetch();

$has_access = $user['is_d'];
$virtual_money = $user['virtual_money'];

?>
<div class="project-draw-container">
    <?php if (!$has_access): ?>
    <section id="add-pro">
        <div class="container">
            <h1 data-translate="Participer au tirage au sort">Participer au tirage au sort</h1>
            <p data-translate="La possibilité de participer à un tirage au sort organisé par l'entreprise pour choisir parmi elle un projet à financer intégralement. Obtenir de nombreux privilèges, y compris une carte de fidélité, qui vous donne droit à des rabais si vous achetez auprès d'entrepreneurs dans différents domaines.">La possibilité de participer à un tirage au sort organisé par l'entreprise pour choisir parmi elle un projet à financer intégralement. Obtenir de nombreux privilèges, y compris une carte de fidélité, qui vous donne droit à des rabais si vous achetez auprès d'entrepreneurs dans différents domaines.</p>
            <p data-translate="Accès à des revenus supplémentaires conformément à la politique de l'entreprise.">Accès à des revenus supplémentaires conformément à la politique de l'entreprise.</p>
            <form id="payAccessForm" action="p_d_p.php" method="post">
                <input type="hidden" name="pay_access" value="1">
                <button type="submit" class="btn-pay-access" data-translate="Payer 9500 de monnaie virtuelle pour accéder aux tirages">Payer 9500 de monnaie virtuelle pour accéder aux tirages</button>
                <div id="pp-message"></div>
            </form>
        </div>
    </section>
    <?php else: ?>
    <?php
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM Drow WHERE user_id = ?
    ");
    $stmt->execute([$user['user_id']]);
    $already_participated = $stmt->fetchColumn() > 0;

    if ($already_participated): ?>
        <div class="participation-message">
            <p data-translate="Vous avez déjà participé au tirage au sort. Veuillez attendre le résultat. Merci pour votre participation!">Vous avez déjà participé au tirage au sort. Veuillez attendre le résultat. Merci pour votre participation!</p>
        </div>
    <?php else:
        $stmt = $pdo->prepare("
            SELECT project_id, project_name, project_idea, target_audience, technology_impact, marketing_strategy
            FROM Projects
            WHERE display_location IN ('drow', 'both')
        ");
        $stmt->execute();
        $projects = $stmt->fetchAll();

        if (empty($projects)): ?>
            <div class="no-projects"><p data-translate="Aucun projet n'est actuellement disponible pour le tirage au sort.">Aucun projet n'est actuellement disponible pour le tirage au sort.</p></div>
        <?php else: ?>
            <form id="projectSelectionForm" action="p_d_p.php" method="post">
                <?php foreach ($projects as $project): ?>
                    <div class="project-item">
                        <input type="radio" id="project_<?php echo $project['project_id']; ?>" name="selected_project" value="<?php echo $project['project_id']; ?>" required>
                        <label for="project_<?php echo $project['project_id']; ?>">
                            <h3><?php echo htmlspecialchars($project['project_name']); ?></h3>
                            <p><strong data-translate="Idée du projet:">Idée du projet:</strong> <?php echo htmlspecialchars($project['project_idea']); ?></p>
                            <p><strong data-translate="Audience cible:">Audience cible:</strong> <?php echo htmlspecialchars($project['target_audience']); ?></p>
                            <p><strong data-translate="Impact technologique:">Impact technologique:</strong> <?php echo htmlspecialchars($project['technology_impact']); ?></p>
                            <p><strong data-translate="Stratégie de marketing:">Stratégie de marketing:</strong> <?php echo htmlspecialchars($project['marketing_strategy']); ?></p>
                        </label>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="btn-participate-draw" data-translate="Participer au tirage avec le projet sélectionné">Participer au tirage avec le projet sélectionné</button>
                <div id="p-i-d-message"></div>
            </form>
        <?php endif;
    endif;
    ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var projectItems = document.querySelectorAll('.project-item');
    projectItems.forEach(function(item) {
        item.addEventListener('click', function() {
            this.querySelector('input[type="radio"]').checked = true;
        });
    });

    var payAccessForm = document.getElementById('payAccessForm');
    if (payAccessForm) {
        payAccessForm.addEventListener('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append('lang', getCookie('language') || 'fr');

            fetch('p_d_p.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                var ppMessage = document.getElementById('pp-message');
                ppMessage.textContent = data.message;
                ppMessage.className = data.success ? 'success-message' : 'error-message';
                ppMessage.style.display = 'block';

                if (data.success) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                var ppMessage = document.getElementById('pp-message');
                ppMessage.textContent = translations[currentLang]['Une erreur s\'est produite. Veuillez réessayer.'];
                ppMessage.className = 'error-message';
                ppMessage.style.display = 'block';
            });
        });
    }

    var projectSelectionForm = document.getElementById('projectSelectionForm');
    if (projectSelectionForm) {
        projectSelectionForm.addEventListener('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            fetch('p_d_p.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                var pidMessage = document.getElementById('p-i-d-message');
                pidMessage.textContent = data.message;
                pidMessage.className = data.success ? 'success-message' : 'error-message';
                pidMessage.style.display = 'block';

                if (data.success) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                var pidMessage = document.getElementById('p-i-d-message');
                pidMessage.textContent = translations[currentLang]['Une erreur s\'est produite. Veuillez réessayer.'];
                pidMessage.className = 'error-message';
                pidMessage.style.display = 'block';
            });
        });
    }
});
</script>

<?php
require_once('footer.php');
?>