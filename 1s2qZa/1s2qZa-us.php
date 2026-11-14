<?php
session_start();

if (isset($_SESSION['admin_email'])) {
    $label = "Les utilisateurs";
    require_once('global.php');
    require_once('adb.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level !== 'main_admin' && $admin_level !== 'sub_admin') {
        header('Location: 1s2qZa-lf.php');
        exit;
    } 

    try {
        $stmt = $conn->prepare("SELECT user_name, user_last_name, user_email, wilaya, city, district, user_address, phone_number, user_r_pseudo, user_l_pseudo, rip, parrain_pseudo, virtual_money, is_v FROM Users");
        $stmt->execute();
        $users_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur de requête de base de données: " . $e->getMessage());
        $users_info = array();
    }

    try {
        $stmt = $conn->prepare("SELECT v.*, u.user_r_pseudo FROM ValidatedInfo v JOIN Users u ON v.user_id = u.user_id WHERE v.v_i_s = 0");
        $stmt->execute();
        $validated_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erreur de requête de base de données: " . $e->getMessage());
        $validated_info = array();
    }

} else {
    header('Location: index.php');
    exit;
}
?>
    <?php if ($admin_level === 'main_admin' || $admin_level === 'sub_admin') : ?>
        <section id="al">
            <div class="container">
                <div class="al-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Wilaya</th>
                                <th>Ville</th>
                                <th>District</th>
                                <th>Adresse</th>
                                <th>Téléphone</th>
                                <th>Pseudo Droite</th>
                                <th>Pseudo Gauche</th>
                                <th>Parrain</th>
                                <th>RIP</th>
                                <th>Monnaie Virtuelle</th>
                                <th>Modifier motepasse</th>
                                <th>Statut du compte</th>
                                <th>Modifier infos</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($users_info)): ?>
                            <?php foreach ($users_info as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['user_last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['wilaya']); ?></td>
                                    <td><?php echo htmlspecialchars($user['city']); ?></td>
                                    <td><?php echo htmlspecialchars($user['district']); ?></td>
                                    <td><?php echo htmlspecialchars($user['user_address']); ?></td>
                                    <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                                    <td><?php echo htmlspecialchars($user['user_r_pseudo']); ?></td>
                                    <td><?php echo htmlspecialchars($user['user_l_pseudo']); ?></td>
                                    <td><?php echo htmlspecialchars($user['parrain_pseudo']); ?></td>
                                    <td><?php echo htmlspecialchars($user['rip']); ?></td>
                                    <td><?php echo htmlspecialchars($user['virtual_money']); ?></td>
                                    <td>
                                        <button class="change-password-btn" data-user-pseudo="<?php echo htmlspecialchars($user['user_r_pseudo']); ?>">
                                            Changer MP
                                        </button>
                                    </td>
                                    <td>
                                        <button class="toggle-account-status" 
                                                data-user-pseudo="<?php echo htmlspecialchars($user['user_r_pseudo']); ?>" 
                                                data-status="<?php echo $user['is_v'] ? 'true' : 'false'; ?>">
                                            <?php echo $user['is_v'] ? 'Désactiver' : 'Activer'; ?>
                                        </button>
                                        <div class="status-message" data-user-pseudo="<?php echo htmlspecialchars($user['user_r_pseudo']); ?>"></div>
                                    </td>
                                    <?php
                                        $hasValidatedInfo = false;
                                        foreach ($validated_info as $info) {
                                            if ($info['user_r_pseudo'] === $user['user_r_pseudo']) {
                                                $hasValidatedInfo = true;
                                                break;
                                            }
                                        }
                                    ?>
                                    <td>
                                        <?php if ($hasValidatedInfo): ?>
                                            <button class="show-validated-info-btn" data-user-pseudo="<?php echo htmlspecialchars($user['user_r_pseudo']); ?>">
                                                Afficher les informations validées
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="16">Aucune information d'utilisateur trouvée.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <div class="change-password-modal modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Changer le mot de passe de l'utilisateur</h2>
            <form class="change-password-form">
                <input type="hidden" class="user-pseudo" name="userRPseudo">
                <label for="newPassword">Nouveau mot de passe :</label>
                <input type="password" class="new-password" name="newPassword">
                <label for="confirmPassword">Confirmer le mot de passe :</label>
                <input type="password" class="confirm-password" name="confirmPassword">
                <button type="submit">Changer le mot de passe</button>
                <p class="change-password-message"></p>
            </form>
        </div>
    </div>

    <div class="validated-info-modal modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Informations validées</h2>
            <div class="validated-info-content"></div>
            <button class="validate-changes-btn">Valider les modifications</button>
            <p class="validate-changes-message"></p>
        </div>
    </div>

<script>
 document.addEventListener('DOMContentLoaded', function() {
        var changePasswordModal = document.querySelector('.change-password-modal');
        var validatedInfoModal = document.querySelector('.validated-info-modal');
        var closeBtns = document.querySelectorAll('.close');
        var changePasswordBtns = document.querySelectorAll('.change-password-btn');
        var showValidatedInfoBtns = document.querySelectorAll('.show-validated-info-btn');
        var changePasswordForm = changePasswordModal.querySelector('.change-password-form');
        var changePasswordMessage = changePasswordModal.querySelector('.change-password-message');
        var validatedInfoContent = validatedInfoModal.querySelector('.validated-info-content');
        var validateChangesBtn = validatedInfoModal.querySelector('.validate-changes-btn');
        var validateChangesMessage = validatedInfoModal.querySelector('.validate-changes-message');
        var toggleAccountStatusBtns = document.querySelectorAll('.toggle-account-status');

        var validatedInfo = <?php echo json_encode($validated_info); ?>;

    changePasswordBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var userPseudo = this.getAttribute('data-user-pseudo');
            changePasswordModal.querySelector('.user-pseudo').value = userPseudo;
            changePasswordModal.style.display = 'block';
        });
    });

    showValidatedInfoBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var userPseudo = this.getAttribute('data-user-pseudo');
            var userValidatedInfo = validatedInfo.find(info => info.user_r_pseudo === userPseudo);
            
            if (userValidatedInfo) {
                var content = `
                    <p>Nom: ${userValidatedInfo.user_name}</p>
                    <p>Prénom: ${userValidatedInfo.user_last_name}</p>
                    <p>Wilaya: ${userValidatedInfo.wilaya}</p>
                    <p>Ville: ${userValidatedInfo.city}</p>
                    <p>District: ${userValidatedInfo.district}</p>
                    <p>Adresse: ${userValidatedInfo.user_address}</p>
                    <p>Numéro de téléphone: ${userValidatedInfo.phone_number}</p>
                `;
                validatedInfoContent.innerHTML = content;
                validateChangesBtn.setAttribute('data-v-i-id', userValidatedInfo.v_i_id);
                validatedInfoModal.style.display = 'block';
            } else {
                alert('Aucune information validée trouvée pour cet utilisateur.');
            }
        });
    });

    closeBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });

    window.addEventListener('click', function(event) {
        if (event.target == changePasswordModal || event.target == validatedInfoModal) {
            changePasswordModal.style.display = 'none';
            validatedInfoModal.style.display = 'none';
        }
    });

    changePasswordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        var newPassword = this.querySelector('.new-password').value;
        var confirmPassword = this.querySelector('.confirm-password').value;
        var userRPseudo = this.querySelector('.user-pseudo').value;

        if (!validatePasswords(newPassword, confirmPassword)) {
            return;
        }

        var formData = new FormData();
        formData.append('user_r_pseudo', userRPseudo);
        formData.append('newPassword', newPassword);

        fetch('c_u_p.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(changePasswordMessage, data.message, 'success');
                setTimeout(function() {
                    changePasswordModal.style.display = 'none';
                    resetForm(changePasswordForm);
                }, 2000);
            } else {
                showMessage(changePasswordMessage, data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showMessage(changePasswordMessage, 'Une erreur est survenue lors du changement de mot de passe : ' + error, 'error');
        });
    });

    validateChangesBtn.addEventListener('click', function() {
        var vIId = this.getAttribute('data-v-i-id');
        
        fetch('v_c.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'v_i_id=' + vIId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(validateChangesMessage, data.message, 'success');
                setTimeout(function() {
                    validatedInfoModal.style.display = 'none';
                    location.reload();
                }, 2000);
            } else {
                showMessage(validateChangesMessage, data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showMessage(validateChangesMessage, 'Une erreur est survenue lors de la validation des modifications : ' + error, 'error');
        });
    });

        toggleAccountStatusBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var userPseudo = this.getAttribute('data-user-pseudo');
                var currentStatus = this.getAttribute('data-status');
                var newStatus = currentStatus === 'true' ? 'false' : 'true';
                var statusMessageElement = document.querySelector(`.status-message[data-user-pseudo="${userPseudo}"]`);

                fetch('t_a_s.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'user_r_pseudo=' + encodeURIComponent(userPseudo) + '&new_status=' + newStatus
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.textContent = newStatus === 'true' ? 'Désactiver' : 'Activer';
                        this.setAttribute('data-status', newStatus);
                        showMessage(statusMessageElement, data.message, 'success');
                    } else {
                        showMessage(statusMessageElement, 'Erreur: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showMessage(statusMessageElement, 'Une erreur est survenue lors de la mise à jour du statut du compte.', 'error');
                });
            });
        });

        function validatePasswords(newPassword, confirmPassword) {
            if (newPassword.trim() === '' || confirmPassword.trim() === '') {
                showMessage(changePasswordMessage, 'Les deux champs de mot de passe ne doivent pas être vides.', 'error');
                return false;
            }

            if (newPassword !== confirmPassword) {
                showMessage(changePasswordMessage, 'Les mots de passe ne correspondent pas.', 'error');
                return false;
            }

            var passwordRegex = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
            if (!passwordRegex.test(newPassword)) {
                showMessage(changePasswordMessage, 'Le mot de passe doit contenir au moins une lettre majuscule, un chiffre et être composé d\'au moins 8 caractères. Les caractères spéciaux ne sont pas autorisés.', 'error');
                return false;
            }

            return true;
        }

        function showMessage(element, message, type) {
            element.textContent = message;
            element.className = 'status-message ' + type;
            element.style.display = 'block';

            setTimeout(() => {
                element.style.display = 'none';
            }, 5000);
        }

        function resetForm(form) {
            form.reset();
            form.querySelector('.change-password-message').textContent = '';
            form.querySelector('.change-password-message').style.display = 'none';
        }
    });
    </script>
</body>
</html>