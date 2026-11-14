<?php
if (isset($_SESSION['admin_email'])) {
    require_once('adb.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);
 
} else {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Panneau d'administration</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" type="text/css" href="../Css/adminpanel.css">
    <?php if (isset($cssfile1)) { ?>
        <link rel='stylesheet' type='text/css' media='screen' href='<?php echo $cssfile1; ?>'>
    <?php } ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<section id="top-content">
    <div class="container">
        <div class="top">
            <div class="menu-icon">
                <i class="fas fa-bars" id="menu-toggle"></i>
            </div>
            <div class="label-t">
                <h3><?php echo $label; ?></h3>
            </div>
            <div class="logout">
                <a href="adlogout.php" class="logout-icon"><i class="fas fa-sign-out-alt"></i> Se déconnecter</a>
            </div>
        </div>
    </div>
</section>
    <?php

    if ($admin_level === 'main_admin') {
    ?>
    <section id="sidebar">
        <div class="close-btn">
            <i class="fas fa-times"></i>
        </div>
        <div class="container">
            <div class="side-bar">
                <div class="side-menu">
                    <ul>
                        <a href="./1s2qZa-ad.php"><li>Les administrateurs</li></a>
                        <a href="./1s2qZa-us.php"><li>Les utilisateurs</li></a>
                        <a href="./1s2qZa-orders.php"><li>Les commandes</li></a>
                        <a href="./1s2qZa-tree.php"><li>Arbre principal</li></a>
                        <a href="./1s2qZa-lvt.php"><li>Les ventes totales</li></a>
                        <a href="./1s2qZa-lf.php"><li>Les formations</li></a>
                        <a href="./1s2qZa-dc.php"><li>Demande change R.I.P</li></a>
                        <a href="./1s2qZa-dp.php"><li>Demande de diplome</li></a>
                        <a href="./1s2qZa-pa.php"><li>Les Projets</li></a>
                        <a href="./1s2qZa-up.php"><li>Les profits des utilisateurs</li></a>
                        <a href="./1s2qZa-lt.php"><li>Les transactions</li></a>
                        <a href="./1s2qZa-n.php"><li>Les notifications</li></a>
                        <a href="./1s2qZa-cu.php"><li>Contactez-nous</li></a>
                        <a href="./1s2qZa-nl.php"><li>Les abonnés à la newsletter</li></a>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <?php
    } elseif ($admin_level === 'sub_admin') {
    ?>
    <section id="sidebar">
        <div class="container">
            <div class="side-bar">
                <div class="side-menu">
                    <ul>
                        <a href="./1s2qZa-lf.php"><li>Les formations</li></a>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <?php
    }
    ?>
<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const closeBtn = document.querySelector('.close-btn');

    function toggleSidebar() {
        sidebar.classList.toggle('active');
    }

    menuToggle.addEventListener('click', toggleSidebar);
    closeBtn.addEventListener('click', toggleSidebar);

    document.addEventListener('click', (event) => {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnMenuToggle = menuToggle.contains(event.target);
        const isSidebarActive = sidebar.classList.contains('active');

        if (isSidebarActive && !isClickInsideSidebar && !isClickOnMenuToggle) {
            toggleSidebar();
        }
    });

    sidebar.addEventListener('click', (event) => {
        event.stopPropagation();
    });
});
</script>

