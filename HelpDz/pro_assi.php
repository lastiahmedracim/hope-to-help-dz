<?php
session_start();

$pagetitle = "Aide aux projets";
$cssfile1 = '../Css/global.css';
$cssfile2 = '../Css/pro_assi.css';
$logo = '../HomeImg/logo.png';
$homepage = '../index.php';
$formation = 'formation.php';
$finhelp = 'pro_assi.php';
$contactus = 'contactus.php';
$newsletter = 'newsletter.php';
$aboutus = 'aboutus.php';
$jsfile1 = '../Js/main.js';
$jsfile2 = 'newsletter.js';
$jsfile3 = '../Js/pa.js';
$jq = '../Js/jquery-3.6.0.min.js';

if (isset($_SESSION['user_r_pseudo'])) {
    $mycourses = 'courses.php';
    $profile = 'profile.php';
    $networks = 'networks.php';
    $logout = 'logout.php';
    $vm = 'virtual_money.php';
    $gun = 'g_u_n.js';
    require_once('uheader.php');
} else {
    $signup = 'signup.php';
    $signin = 'signin.php';
    require_once('header.php');
}

require_once('../functions/db.php');

$stmt = $pdo->prepare("SELECT project_id, project_name, project_location, project_idea, total_project_cost, 
                              partnership_terms, number_of_partners, participation_amount_per_person, 
                              partner_interest_rate, target_audience, technology_impact, marketing_strategy 
                       FROM Projects 
                       WHERE display_location IN ('project', 'both') AND vl = TRUE");
$stmt->execute();
$projects = $stmt->fetchAll();
?>

    <div class="project-assistance-container">
        <h1 data-translate="Aide aux projets">Aide aux projets</h1>

        <div class="project-actions">
            <a href="add_pro.php" class="btn-add-project" data-translate="Ajouter un projet">Ajouter un projet</a>
            <a href="parti_in_drw.php" class="btn-participate-draw" data-translate="Participer au tirage au sort">Participer au tirage au sort</a>
        </div>

        <div class="project-list">
            <?php if (empty($projects)): ?>
                <div class="no-projects"><p data-translate="Aucun projet n'est actuellement disponible pour affichage.">Aucun projet n'est actuellement disponible pour affichage.</p></div>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <div class="project-item">
                        <h3 class="project-name"><?php echo htmlspecialchars($project['project_name']); ?></h3>
                        <div class="project-details" style="display: none;">
                            <p><strong data-translate="Nom du projet:">Nom du projet:</strong> <?php echo htmlspecialchars($project['project_name']); ?></p>
                            <p><strong data-translate="Lieu du projet:">Lieu du projet:</strong> <?php echo htmlspecialchars($project['project_location']); ?></p>
                            <p><strong data-translate="Idée du projet:">Idée du projet:</strong> <?php echo htmlspecialchars($project['project_idea']); ?></p>
                            <p><strong data-translate="Coût global du projet:">Coût global du projet:</strong> <?php echo number_format($project['total_project_cost'], 2, ',', ' '); ?> DA</p>
                            <p><strong data-translate="Durée du partenariat:">Durée du partenariat:</strong> <?php echo $project['partnership_terms'] === 'limited' ? '<span data-translate="Limitée">Limitée</span>' : '<span data-translate="Illimitée">Illimitée</span>'; ?></p>
                            <p><strong data-translate="Nombre de partenaires:">Nombre de partenaires:</strong> <?php echo htmlspecialchars($project['number_of_partners']); ?></p>
                            <p><strong data-translate="Montant de participation par personne:">Montant de participation par personne:</strong> <?php echo number_format($project['participation_amount_per_person'], 2, ',', ' '); ?> DA</p>
                            <p><strong data-translate="Taux d'intérêt du partenaire:">Taux d'intérêt du partenaire:</strong> <?php echo htmlspecialchars($project['partner_interest_rate']); ?>%</p>
                            <p><strong data-translate="Audience cible:">Audience cible:</strong> <?php echo htmlspecialchars($project['target_audience']); ?></p>
                            <p><strong data-translate="Impact technologique:">Impact technologique:</strong> <?php echo htmlspecialchars($project['technology_impact']); ?></p>
                            <p><strong data-translate="Stratégie de marketing:">Stratégie de marketing:</strong> <?php echo htmlspecialchars($project['marketing_strategy']); ?></p>
                            <button class="btn-participate" data-translate="Participer">Participer</button>
                        </div>
                        <div class="partner-form" style="display: none;">
                            <h4 data-translate="Formulaire de participation">Formulaire de participation</h4>
                            <form class="partner-info-form" action="par_s.php" method="post">
                                <input type="hidden" name="project_id" value="<?php echo $project['project_id']; ?>">
                                <div>
                                    <label for="first_name" data-translate="Prénom:">Prénom:</label>
                                    <input type="text" id="first_name" name="first_name" required>
                                </div>
                                <div>
                                    <label for="last_name" data-translate="Nom:">Nom:</label>
                                    <input type="text" id="last_name" name="last_name" required>
                                </div>
                                <div>
                                    <label for="phone_number" data-translate="Numéro de téléphone:">Numéro de téléphone:</label>
                                    <input type="tel" id="phone_number" name="phone_number" required>
                                </div>
                                <div>
                                    <label for="email" data-translate="Email:">Email:</label>
                                    <input type="email" id="email" name="email" required>
                                </div>
                                <div>
                                    <label for="personal_address" data-translate="Adresse personnelle:">Adresse personnelle:</label>
                                    <textarea id="personal_address" name="personal_address" required></textarea>
                                </div>
                                <button type="submit" data-translate="Soumettre">Soumettre</button>
                            </form>
                            <div class="form-message" style="display: none;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var projectNames = document.querySelectorAll('.project-name');
        projectNames.forEach(function(name) {
            name.addEventListener('click', function() {
                this.classList.toggle('active');
                var projectDetails = this.nextElementSibling;
                var partnerForm = projectDetails.nextElementSibling;
                
                if (projectDetails.style.display === 'none') {
                    projectDetails.style.display = 'block';
                } else {
                    projectDetails.style.display = 'none';
                    if (partnerForm.style.display === 'block') {
                        partnerForm.style.display = 'none';
                    }
                }
            });
        });

        var participateButtons = document.querySelectorAll('.btn-participate');
        participateButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var partnerForm = this.closest('.project-item').querySelector('.partner-form');
                if (partnerForm.style.display === 'none') {
                    partnerForm.style.display = 'block';
                } else {
                    partnerForm.style.display = 'none';
                }
            });
        });

        function getCookie(name) {
            let nameEQ = name + "=";
            let ca = document.cookie.split(';');
            for(let i=0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        function showMessage(element, message, type) {
            element.textContent = message;
            element.className = 'form-message ' + type;
            element.style.display = 'block';
            setTimeout(() => {
                element.style.display = 'none';
            }, 5000);
        }

        var partnerForms = document.querySelectorAll('.partner-info-form');
        partnerForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var messageDiv = this.nextElementSibling;

                formData.append('lang', getCookie('language') || 'fr');

                fetch('par_s.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Success:', data);
                    if (data.status === 'success') {
                        showMessage(messageDiv, data.message, 'success');
                        form.reset();
                    } else {
                        showMessage(messageDiv, data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage(messageDiv, 'Une erreur s\'est produite: ' + error.message, 'error');
                });
            });
        });
    });
    </script>
<?php
require_once('footer.php');
?>