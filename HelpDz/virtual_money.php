<?php
session_start();
if (!isset($_SESSION['user_r_pseudo'])) {
    header('Location: signin.php');
    exit;
}

$pagetitle = "Acheter l'argent virtuel";
$cssfile1 = '../Css/virtual_money.css';
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
$newsletter = 'newsletter.php';
$vm = 'virtual_money.php';
$jsfile1 = '../Js/main.js';
$jsfile2 = 'newsletter.js';
$jsfile3 = '../Js/ci.js';
$gun = 'g_u_n.js';
$jq = '../Js/jquery-3.6.0.min.js';

require('uheader.php');
?>

<section id="buy-virtual-money">
    <div class="container">
        <h1 data-translate="Acheter l'argent virtuel"></h1>
        <div class="bank-info">
            <h2 data-translate="Informations bancaires"></h2>
            <p><strong data-translate="Banque"></strong>: BADR</p>
            <p><strong data-translate="Nom & Raison Sociale"></strong>: EURL HOPE TO HELP DZ</p>
            <p><strong data-translate="Adresse"></strong>: EL QODS ETAGE13 LOT1373 CHERAGA</p>
            <p><strong data-translate="IBAN (R.I.B DU CLIENT)"></strong>: 00300630000076830039</p>
        </div>
        <div class="instructions">
            <h2 data-translate="Instructions"></h2>
            <ol>
                <li data-translate="Effectuez un virement vers le compte bancaire ci-dessus."></li>
                <li data-translate="Prenez une capture d'écran ou une photo du reçu de paiement."></li>
                <li data-translate="Cliquez sur le bouton ci-dessous pour télécharger votre preuve de paiement."></li>
            </ol>
        </div>
        <button onclick="openModal()" class="upload-button" data-translate="Télécharger le reçu de paiement"></button>
    </div>
</section>

<div id="uploadModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 data-translate="Télécharger le reçu de paiement"></h2>
        <form id="uploadForm" action="bc_p.php" method="post" enctype="multipart/form-data">
            <input type="file" id="ccpImage" name="ccpImage" accept="image/*">
            <button type="submit" data-translate="Envoyer"></button>
        </form>
        <div id="uploadMessage"></div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('uploadModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('uploadModal').style.display = 'none';
}

document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'bc_p.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            if (typeof response === 'string') {
                try {
                    response = JSON.parse(response);
                } catch (e) {
                    console.error('Failed to parse response:', response);
                    $('#uploadMessage').text('An error occurred. Please try again.');
                    return;
                }
            }
            if (response.debug_output) {
                console.log('Debug output:', response.debug_output);
            }
            $('#uploadMessage').text(response.message);
            if (response.status === 'success') {
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            }
        },
    });
});
</script>
<script src='../Js/vm.js'></script>
<?php require_once('footer.php'); ?>