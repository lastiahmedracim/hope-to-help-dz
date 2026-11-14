<?php
session_start();

if (isset($_SESSION['admin_email'])) {
    $label = "Les demandes de paiement";
    require_once('global.php');
    require_once('adb.php');
    require_once('ch_ad_l.php');
    
    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level !== 'main_admin') {
        header('Location: 1s2qZa-lf.php');
        exit;
    }

    $query = "SELECT 
                PaymentImages.id,
                PaymentImages.image_path,
                Users.user_id,
                Users.user_r_pseudo,
                Users.user_email,
                Users.virtual_money
            FROM PaymentImages
            JOIN Users ON PaymentImages.user_id = Users.user_id
            WHERE PaymentImages.is_v = 1";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $payment_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
    
} else {
    header('Location: index.php');
    exit;
}
?>
<section id="al">
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Pseudo Droite de l'utilisateur</th>
                    <th>Email</th>
                    <th>Argent virtuel</th>
                    <th>Preuve de paiement</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($payment_requests)): ?>
                <?php foreach ($payment_requests as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['user_r_pseudo']); ?></td>
                    <td><?php echo htmlspecialchars($request['user_email']); ?></td>
                    <td>
                        <input type="number" class="virtual-money" data-user-id="<?php echo $request['user_id']; ?>" value="<?php echo htmlspecialchars($request['virtual_money']); ?>">
                        <button class="update-virtual-money">Mettre à jour</button>
                        <div id="u-v-m-message"></div>
                    </td>
                    <td>
                        <button class="show-modal" data-payment-photo-url="<?php echo 'https://hopetohelp.dz/HelpDz/' . htmlspecialchars($request['image_path']); ?>">Voir la preuve</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <tr>
                <td colspan="4">Aucune demande de paiement trouvée.</td>                
            </tr>
        <?php endif; ?>
    </div>
</section>

<div id="order-model" class="order-model-container">
    <div class="order-item">
        <span class="close">&times;</span>
        <img id="payment-image" src="" alt="Payment Image" class="payment-img">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const modal = document.querySelector(".order-model-container");
    const closeModal = modal.querySelector(".close");

    document.querySelectorAll('.show-modal').forEach(button => {
        button.addEventListener('click', function() {
            const paymentPhotoUrl = this.getAttribute('data-payment-photo-url');
            const paymentImage = modal.querySelector("#payment-image");

            if (!paymentImage) {
                console.error("Payment image not found.");
                return;
            }

            paymentImage.src = paymentPhotoUrl;
            modal.style.display = "block";
        });
    });

    closeModal.addEventListener('click', () => {
        modal.style.display = "none";
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    document.querySelectorAll('.update-virtual-money').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const userId = input.getAttribute('data-user-id');
            const newValue = input.value;
            const messageDiv = this.nextElementSibling;

            fetch('u_v_m.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `user_id=${userId}&virtual_money=${newValue}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    messageDiv.textContent = 'Argent virtuel mis à jour avec succès !';
                } else {
                    messageDiv.textContent = 'Erreur lors de la mise à jour de l\'argent virtuel.';
                }
                setTimeout(() => {
                    messageDiv.textContent = '';
                    location.reload();
                }, 3000);
            })
            .catch(error => {
                console.error('Erreur:', error);
                messageDiv.textContent = 'Une erreur s\'est produite lors de la mise à jour.';
                setTimeout(() => {
                    messageDiv.textContent = '';
                    location.reload();
                }, 3000);
            });
        });
    });
});
</script>
</body>
</html>