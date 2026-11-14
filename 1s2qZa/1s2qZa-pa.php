<?php
session_start();

if (isset($_SESSION['admin_email'])) {
    $label = "Projets et Tirages";
    require_once('global.php');
    require_once('adb.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level !== 'main_admin' && $admin_level !== 'sub_admin') {
        header('Location: 1s2qZa-lf.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>

<section id="al">
    <div class="container">
        <div class="al-content">
            <h2>Sélectionnez une catégorie :</h2>
            <button id="allProjectsBtn" class="category-btn">Les Projets</button>
            <button id="drawProjectsBtn" class="category-btn">Projets en Tirage</button>
            <div id="projectsList"></div>
        </div>
    </div>
</section>

<div id="projectModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="projectDetails"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allProjectsBtn = document.getElementById('allProjectsBtn');
        const drawProjectsBtn = document.getElementById('drawProjectsBtn');
        const projectsList = document.getElementById('projectsList');
        const projectModal = document.getElementById('projectModal');
        const projectDetails = document.getElementById('projectDetails');
        const closeBtn = document.getElementsByClassName('close')[0];

        function fetchProjects(type) {
            fetch(`get_projects.php?type=${type}`)
                .then(response => response.json())
                .then(projects => {
                    projectsList.innerHTML = '';
                    projects.forEach(project => {
                        const projectItem = document.createElement('div');
                        projectItem.className = 'project-item';
                        projectItem.textContent = project.project_name;
                        projectItem.onclick = () => showProjectDetails(project.project_id, type);
                        projectsList.appendChild(projectItem);
                    });
                })
                .catch(error => {
                    console.error('Error fetching projects:', error);
                    projectsList.innerHTML = '<p>Error loading projects. Please try again.</p>';
                });
        }

        function showProjectDetails(projectId, type) {
            fetch(`g_p_d.php?id=${projectId}&type=${type}`)
                .then(response => response.json())
                .then(details => {
                    if (details.error) {
                        throw new Error(details.error);
                    }

                    let content = `
                        <h3>${details.project_name || 'Sans nom'}</h3>
                        <p><strong>Idée du projet:</strong> ${details.project_idea || 'Non spécifié'}</p>
                        <p><strong>Propriétaire:</strong> ${details.project_owner || 'Non spécifié'}</p>
                        <p><strong>Compétences du propriétaire:</strong> ${details.owner_skills || 'Non spécifié'}</p>
                        <p><strong>Forme juridique:</strong> ${details.legal_form || 'Non spécifié'}</p>
                        <p><strong>Localisation:</strong> ${details.project_location || 'Non spécifié'}</p>
                        <p><strong>Raison du projet:</strong> ${details.reason_for_project || 'Non spécifié'}</p>
                        <p><strong>Caractéristiques uniques:</strong> ${details.unique_features || 'Non spécifié'}</p>
                        <p><strong>Valeur ajoutée:</strong> ${details.added_value || 'Non spécifié'}</p>
                        <p><strong>Public cible:</strong> ${details.target_audience || 'Non spécifié'}</p>
                        <p><strong>Impact technologique:</strong> ${details.technology_impact || 'Non spécifié'}</p>
                        <p><strong>Stratégie marketing:</strong> ${details.marketing_strategy || 'Non spécifié'}</p>
                        <p><strong>Nombre d'employés:</strong> ${details.employees_count || 'Non spécifié'}</p>
                        <p><strong>Coût de location:</strong> ${details.rental_cost || 'Non spécifié'}</p>
                        <p><strong>Coût d'équipement:</strong> ${details.equipment_cost || 'Non spécifié'}</p>
                        <p><strong>Détails d'équipement:</strong> ${details.equipment_details || 'Non spécifié'}</p>
                        <p><strong>Dépenses supplémentaires:</strong> ${details.additional_expenses || 'Non spécifié'}</p>
                        <p><strong>ID national du propriétaire:</strong> ${details.owner_national_id || 'Non spécifié'}</p>
                        <p><strong>Conditions de partenariat:</strong> ${details.partnership_terms || 'Non spécifié'}</p>
                        <p><strong>Taux d'intérêt du partenaire:</strong> ${details.partner_interest_rate ? details.partner_interest_rate + '%' : 'Non spécifié'}</p>
                        <p><strong>Coût total du projet:</strong> ${details.total_project_cost || 'Non spécifié'}</p>
                        <p><strong>Nombre de partenaires:</strong> ${details.number_of_partners || 'Non spécifié'}</p>
                        <p><strong>Montant de participation par personne:</strong> ${details.participation_amount_per_person || 'Non spécifié'}</p>
                    `;

                    if (details.partners) {
                        content += `<h4>Informations des partenaires</h4>`;
                        if (details.partners.length > 0) {
                            details.partners.forEach((partner, index) => {
                                content += `
                                    <div class="partner-info">
                                        <h5>Partenaire ${index + 1}</h5>
                                        <p><strong>Nom:</strong> ${partner.first_name || ''} ${partner.last_name || ''}</p>
                                        <p><strong>Email:</strong> ${partner.email || 'Non spécifié'}</p>
                                        <p><strong>Téléphone:</strong> ${partner.phone_number || 'Non spécifié'}</p>
                                        <p><strong>Adresse personnelle:</strong> ${partner.personal_address || 'Non spécifié'}</p>
                                        <p><strong>Statut du partenaire:</strong> ${partner.accp ? 'Accepté' : 'En attente'}</p>
                                        <button class="partner-action" data-id="${partner.partner_id}" data-action="accept">Accepter le partenaire</button>
                                        <button class="partner-action" data-id="${partner.partner_id}" data-action="refuse">Refuser le partenaire</button>
                                    </div>
                                `;
                            });
                        } else {
                            content += `<p>Aucun partenaire n'a encore été ajouté à ce projet.</p>`;
                        }
                    }

                    content += `
                        <h4>Informations de l'utilisateur</h4>
                        <p><strong>Nom:</strong> ${details.user_name || 'Non spécifié'} ${details.user_last_name || ''}</p>
                        <p><strong>Email:</strong> ${details.user_email || 'Non spécifié'}</p>
                        <p><strong>Téléphone:</strong> ${details.phone_number || 'Non spécifié'}</p>
                    `;

                    content += `
                        <button class="project-action" data-id="${projectId}" data-type="${type}" data-action="accept">
                            ${type === 'projects' ? 'Accepter' : 'Valider'}
                        </button>
                        <button class="project-action" data-id="${projectId}" data-type="${type}" data-action="reject">
                            ${type === 'projects' ? 'Rejeter' : 'Ne pas valider'}
                        </button>
                    `;

                    projectDetails.innerHTML = content;
                    projectModal.style.display = 'block';

                    document.querySelectorAll('.partner-action').forEach(button => {
                        button.addEventListener('click', function() {
                            handlePartnerAction(this.dataset.id, this.dataset.action);
                        });
                    });

                    document.querySelectorAll('.project-action').forEach(button => {
                        button.addEventListener('click', function() {
                            handleProjectAction(this.dataset.id, this.dataset.type, this.dataset.action);
                        });
                    });
                })
                .catch(error => {
                    console.error('Error fetching project details:', error);
                    projectDetails.innerHTML = '<p>Error loading project details. Please try again.</p>';
                    projectModal.style.display = 'block';
                });
        }

        function handlePartnerAction(partnerId, action) {
            fetch('h_p.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${partnerId}&action=${action}`
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert(result.message);
                    document.getElementById('projectModal').style.display = 'none';
                    fetchProjects('all');
                } else {
                    throw new Error(result.message || 'Unknown error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(`An error occurred: ${error.message}`);
            });
        }

        function handleProjectAction(projectId, type, action) {
            fetch('h_p_a.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${projectId}&type=${type}&action=${action}`
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert(result.message);
                    document.getElementById('projectModal').style.display = 'none';
                    fetchProjects(type);
                } else {
                    throw new Error(result.message || 'Une erreur inconnue est survenue');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(`Une erreur est survenue: ${error.message}`);
            });
        }

        allProjectsBtn.addEventListener('click', () => fetchProjects('all'));
        drawProjectsBtn.addEventListener('click', () => fetchProjects('draw'));

        closeBtn.addEventListener('click', () => projectModal.style.display = 'none');

        window.addEventListener('click', (event) => {
            if (event.target == projectModal) {
                projectModal.style.display = 'none';
            }
        });

        fetchProjects('all');
    });
</script>