<?php
session_start();

if (isset($_SESSION['admin_email'])) {
    $label = "Les profits des utilisateurs";
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
            'direct' AS profit_type,
            DirectProfit.direct_profit_id AS profit_id,
            Users.user_r_pseudo,
            Users.RIP,
            DirectProfit.claimed_date AS last_claimed_date,
            DirectProfit.claimed_profit AS last_claimed_profit,
            DirectProfit.paid
          FROM DirectProfit
          JOIN Users ON DirectProfit.user_id = Users.user_id
          UNION ALL
          SELECT 
            'indirect' AS profit_type,
            IndirectProfit.indirect_profit_id AS profit_id,
            Users.user_r_pseudo,
            Users.RIP,
            IndirectProfit.claimed_date AS last_claimed_date,
            IndirectProfit.claimed_profit AS last_claimed_profit,
            IndirectProfit.paid
          FROM IndirectProfit
          JOIN Users ON IndirectProfit.user_id = Users.user_id
          ORDER BY last_claimed_date DESC";

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $profits = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    <th>Type de Profit</th>
                    <th>Pseudo Droite de l'utilisateur</th>
                    <th>Date de la dernière réclamation</th>
                    <th>Dernier profit réclamé</th>
                    <th>Statut du paiement</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($profits)): ?>
                <?php foreach ($profits as $profit): ?>
                <tr>
                    <td><?php echo htmlspecialchars(ucfirst($profit['profit_type'])); ?></td>
                    <td><?php echo htmlspecialchars($profit['user_r_pseudo']); ?></td>
                    <td><?php echo htmlspecialchars($profit['last_claimed_date']); ?></td>
                    <td><?php echo htmlspecialchars($profit['last_claimed_profit']); ?></td>
                    <td><?php echo htmlspecialchars($profit['paid']); ?></td>
                    <td>
                        <button class="show-modal" data-profit-id="<?php echo $profit['profit_id']; ?>" data-profit-type="<?php echo $profit['profit_type']; ?>">Valider</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <tr>
                <td colspan="6">Aucune information des profits trouvée.</td>
            </tr>
        <?php endif; ?>
    </div>
</section>

<div id="profit-modal" class="uprofit-modal-container">
    <div class="uprofit-item">
        <span class="close">&times;</span>
        <p>Voulez-vous valider ce profit ?</p>
        <div>
            <label>
                <input type="radio" name="payment_status" value="paid"> Paid
            </label>
            <label>
                <input type="radio" name="payment_status" value="not_paid"> Not Paid
            </label>
        </div>
        <button id="validate-profit" class="uprofit-button">Valider</button>
        <div id="uprofit-error"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const modal = document.querySelector(".uprofit-modal-container");
        const closeModal = modal.querySelector(".close");
        const validateButton = modal.querySelector(".uprofit-button");
        let currentProfitId;
        let currentProfitType;

        document.querySelectorAll('.show-modal').forEach(button => {
            button.addEventListener('click', function() {
                currentProfitId = this.getAttribute('data-profit-id');
                currentProfitType = this.getAttribute('data-profit-type');
                modal.style.display = "block";
            });
        });

        closeModal.addEventListener('click', () => {
            modal.style.display = "none";
        });

        validateButton.addEventListener('click', () => {
            const selectedPaymentStatus = document.querySelector('input[name="payment_status"]:checked');
            if (!selectedPaymentStatus) {
                const uprofitErrorDiv = modal.querySelector("#uprofit-error");
                uprofitErrorDiv.textContent = "Veuillez choisir une option de paiement.";
                return;
            }
            
            fetch(`u_p.php?profit_id=${currentProfitId}&profit_type=${currentProfitType}&paid=${selectedPaymentStatus.value}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('La réponse du réseau n\'était pas correcte.');
                    }
                    return response.text();
                })
                .then(data => {
                    const uprofitErrorDiv = modal.querySelector("#uprofit-error");
                    if (uprofitErrorDiv) {
                        if (data === 'succès') {
                            uprofitErrorDiv.textContent = 'Profit validé avec succès !';
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            uprofitErrorDiv.textContent = data;
                        }
                    }
                })
                .catch(error => {
                    console.error('Il y a eu un problème avec l\'opération fetch :', error);
                });
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    });
</script>