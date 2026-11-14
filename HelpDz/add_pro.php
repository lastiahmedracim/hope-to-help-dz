<?php
session_start();
if (!isset($_SESSION['user_r_pseudo'])) {
    header('Location: signin.php');
    exit;
}

$pagetitle = "Aide aux projets";
$cssfile1 = '../Css/global.css';
$cssfile2 = '../Css/add_pro.css';
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
$jsfile3 = '../Js/ap.js';
$gun = 'g_u_n.js';
$jq = '../Js/jquery-3.6.0.min.js';

require_once('uheader.php');
require_once('../functions/db.php');

$user_r_pseudo = $_SESSION['user_r_pseudo'];
$stmt = $pdo->prepare("
    SELECT u.virtual_money, u.user_id, u.bought, u.is_f, p.is_p 
    FROM Users u 
    LEFT JOIN Paid p ON u.user_id = p.user_id 
    WHERE u.user_r_pseudo = ?
");
$stmt->execute([$user_r_pseudo]);
$user = $stmt->fetch();

$can_create_free = $user['bought'] && !$user['is_f'];
$has_paid_access = $user['is_p'];

?>

<section id="add-pro">
    <div class="container">
        <?php if (!$has_paid_access && !$can_create_free): ?>
            <h1 data-translate>Développer un ou plusieurs projets pour leur présentation sur la plateforme.</h1>
            <p data-translate>La possibilité de financement de votre projet par des personnes ou institutions intéressées.</p>
            <p data-translate>Obtenir de nombreux privilèges, y compris une carte de fidélité, qui vous donne droit à des rabais si vous achetez auprès d'entrepreneurs dans différents domaines.</p>
            <p data-translate>Accès à des revenus supplémentaires conformément à la politique de l'entreprise.</p>
            <form id="payAccessForm" action="p_p_a.php" method="post">
                <input type="hidden" name="pay_access" value="1">
                <button type="submit" class="btn-pay-access" data-translate>Payer 30000 de monnaie virtuelle pour accéder à l'ajout de projet</button>
                <div id="pp-message"></div>
            </form>
        <?php else: ?>
            <?php if ($can_create_free): ?>
                <div class="free-project-info">
                    <h2 data-translate>Vous avez droit à un projet gratuit</h2>
                    <p data-translate>Vous pouvez créer un projet gratuitement ou choisir de payer pour un accès illimité.</p>
                </div>
            <?php endif; ?>

            <?php if (!$has_paid_access): ?>
                <div class="pay-access-section">
                    <h2 data-translate>Accès illimité aux projets</h2>
                    <p data-translate>Payez pour publier autant de projets que vous le souhaitez.</p>
                    <form id="payAccessForm" action="p_p_a.php" method="post">
                        <input type="hidden" name="pay_access" value="1">
                        <button type="submit" class="btn-pay-access" data-translate>Payer 30000 de monnaie virtuelle pour un accès illimité</button>
                        <div id="pp-message"></div>
                    </form>
                </div>
            <?php endif; ?>

            <h1 data-translate>Demande d'aide financière pour votre projet</h1>
            <form id="projectForm">
                <div class="form-group">
                    <label for="projectName" data-translate>Nom du projet (max 140 caractères) :</label>
                    <input type="text" id="projectName" name="projectName" maxlength="140">
                </div>

                <div class="form-group">
                    <label for="legalForm" data-translate>Forme juridique du projet :</label>
                    <input type="text" id="legalForm" name="legalForm">
                </div>

                <div class="form-group">
                    <label for="projectIdea" data-translate>Idée du projet (max 210 caractères) :</label>
                    <textarea id="projectIdea" name="projectIdea" maxlength="210"></textarea>
                </div>

                <div class="form-group">
                    <label for="projectOwner" data-translate>Nom du propriétaire du projet :</label>
                    <input type="text" id="projectOwner" name="projectOwner">
                </div>

                <div class="form-group">
                    <label for="ownerSkills" data-translate>Compétences du propriétaire :</label>
                    <textarea id="ownerSkills" name="ownerSkills"></textarea>
                </div>

                <div class="form-group">
                    <label for="partners" data-translate>Noms des partenaires (s'il y en a) :</label>
                    <textarea id="partners" name="partners"></textarea>
                </div>

                <div class="form-group">
                    <label for="projectReason" data-translate>Raison du choix de l'idée du projet :</label>
                    <textarea id="projectReason" name="projectReason"></textarea>
                </div>

                <div class="form-group">
                    <label for="projectLocation" data-translate>Emplacement du projet :</label>
                    <input type="text" id="projectLocation" name="projectLocation">
                </div>

                <div class="form-group">
                    <label for="uniqueFeatures" data-translate>Caractéristiques uniques de votre produit :</label>
                    <textarea id="uniqueFeatures" name="uniqueFeatures"></textarea>
                </div>

                <div class="form-group">
                    <label for="addedValue" data-translate>Valeur ajoutée de votre produit (max 140 caractères) :</label>
                    <input type="text" id="addedValue" name="addedValue" maxlength="140">
                </div>

                <div class="form-group">
                    <label for="targetAudience" data-translate>Public cible de votre produit :</label>
                    <textarea id="targetAudience" name="targetAudience"></textarea>
                </div>

                <div class="form-group">
                    <label for="technologyImpact" data-translate>Impact de la technologie sur votre produit :</label>
                    <textarea id="technologyImpact" name="technologyImpact"></textarea>
                </div>

                <div class="form-group">
                    <label for="marketingStrategy" data-translate>Stratégies marketing prévues :</label>
                    <textarea id="marketingStrategy" name="marketingStrategy"></textarea>
                </div>

                <div class="form-group">
                    <label for="employeesCount" data-translate>Nombre d'employés prévus :</label>
                    <input type="number" id="employeesCount" name="employeesCount" min="0">
                </div>

                <div class="form-group">
                    <label for="rentalCost" data-translate>Coût de location :</label>
                    <input type="number" id="rentalCost" name="rentalCost" min="0">
                </div>

                <div class="form-group">
                    <label for="equipmentCost" data-translate>Coût des équipements :</label>
                    <input type="number" id="equipmentCost" name="equipmentCost" min="0">
                </div>

                <div class="form-group">
                    <label for="equipmentDetails" data-translate>Détails des équipements :</label>
                    <textarea id="equipmentDetails" name="equipmentDetails"></textarea>
                </div>

                <div class="form-group">
                    <label for="additionalExpenses" data-translate>Dépenses supplémentaires :</label>
                    <input type="number" id="additionalExpenses" name="additionalExpenses" min="500000">
                </div>

                <div class="form-group">
                    <label for="ownerNationalId" data-translate>Numéro de carte d'identité nationale du propriétaire :</label>
                    <input type="text" id="ownerNationalId" name="ownerNationalId">
                </div>
                
                <div class="form-group">
                    <label for="displayLocation" data-translate>Où afficher le projet :</label>
                    <select id="displayLocation" name="displayLocation">
                        <option value="project" data-translate>Liste des projets</option>
                        <option value="drow" data-translate>Tirage au sort</option>
                        <option value="both" data-translate>Liste des projets Et Tirage au sort</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="partnershipTerms" data-translate>La durée de partenariat :</label>
                    <select id="partnershipTerms" name="partnershipTerms">
                        <option value="limited" data-translate>Limité</option>
                        <option value="unlimited" data-translate>Illimité</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="partnerInterestRate" data-translate>Taux d'intérêt du partenaire (%) :</label>
                    <input type="number" id="partnerInterestRate" name="partnerInterestRate" min="0" max="100" step="0.01">
                </div>

                <div class="form-group">
                    <label for="totalProjectCost" data-translate>Coût total du projet :</label>
                    <input type="number" id="totalProjectCost" name="totalProjectCost" min="0" step="0.01">
                </div>

                <div class="form-group">
                    <label for="numberOfPartners" data-translate>Nombre de partenaires :</label>
                    <input type="number" id="numberOfPartners" name="numberOfPartners" min="1">
                </div>

                <div class="form-group">
                    <label for="participationAmountPerPerson" data-translate>Montant de participation par personne :</label>
                    <input type="number" id="participationAmountPerPerson" name="participationAmountPerPerson" min="0" step="0.01">
                </div>

                <button class="submit-btn" type="submit" data-translate>Soumettre le projet</button>
                <div id="pro-message"></div>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php require_once('footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');
    const payAccessForm = document.getElementById('payAccessForm');
    const projectForm = document.getElementById('projectForm');

    if (payAccessForm) {
        console.log('Pay access form found');
        payAccessForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            formData.append('pay_access', '1');
            formData.append('lang', getCookie('language') || 'fr');

            fetch('p_p_a.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const messageDiv = document.getElementById('pp-message');
                messageDiv.textContent = data.message;
                messageDiv.className = data.status === 'success' ? 'success-message' : 'error-message';
                
                if (data.status === 'success') {
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('pp-message').textContent = 'Une erreur s\'est produite. Veuillez réessayer.';
                document.getElementById('pp-message').className = 'error-message';
            });
        });
    } else {
        console.log('Pay access form not found');
    }

    if (projectForm) {
        console.log('Project form found');
        projectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Project form submitted');
            
            let formData = new FormData(this);
            
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            console.log('Sending project data');
            fetch('s_p.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                console.log('Raw server response:', text);
                try {
                    return JSON.parse(text);
                } catch (error) {
                    throw new Error('Server returned invalid JSON: ' + text);
                }
            })
            .then(data => {
                console.log('Parsed server response:', data);
                const messageDiv = document.getElementById('pro-message');
                if (data.error) {
                    messageDiv.textContent = 'Server error: ' + data.error.message;
                    messageDiv.className = 'error-message';
                    console.error('Server error:', data.error);
                } else {
                    messageDiv.textContent = data.message;
                    messageDiv.className = data.success ? 'success-message' : 'error-message';
                }
                
                if (data.success) {
                    projectForm.reset();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('pro-message').textContent = 'Une erreur s\'est produite: ' + error.message;
                document.getElementById('pro-message').className = 'error-message';
            });
        });
    } else {
        console.log('Project form not found');
    }
});
</script>