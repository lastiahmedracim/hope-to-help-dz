<?php
session_start();
if (isset($_SESSION['admin_email'])) {
    $label = "Les ventes totales";
    require_once('global.php');
    require_once('adb.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level === 'main_admin') {
        $directProfitData = getAllDirectProfit($conn);
        $indirectProfitData = getAllIndirectProfit($conn);
        $cProfitData = getAllCProfit($conn);
        $totalCourseProfitData = getAllTotalCourseProfit($conn);
        $totalDProfitData = getAllDProfit($conn);
        $totalPProfitData = getAllPProfit($conn);
    } else {
        $directProfitData = 0;
        $indirectProfitData = 0;
        $cProfitData = 0;
        $totalCourseProfitData = 0;
        $totalDProfitData = 0;
        $totalPProfitData = 0;
    }
} else {
    header('Location: index.php');
    exit;
}

function getAllDirectProfit($conn) {
    $sql = "SELECT SUM(claimed_profit) as total_direct_profit FROM DirectProfit";
    $stmt = $conn->query($sql);
    if (!$stmt) {
        echo "Erreur lors de l'exécution de la requête SQL : " . $conn->errorInfo()[2] . "<br>";
        return 0;
    }
    return $stmt->fetch(PDO::FETCH_ASSOC)['total_direct_profit'] ?? 0;
}

function getAllIndirectProfit($conn) {
    $sql = "SELECT SUM(claimed_profit) as total_indirect_profit FROM IndirectProfit";
    $stmt = $conn->query($sql);
    if (!$stmt) {
        echo "Erreur lors de l'exécution de la requête SQL : " . $conn->errorInfo()[2] . "<br>";
        return 0;
    }
    return $stmt->fetch(PDO::FETCH_ASSOC)['total_indirect_profit'] ?? 0;
}

function getAllCProfit($conn) {
    $sql = "SELECT SUM(c_amount) as total_c_profit FROM CProfit";
    $stmt = $conn->query($sql);
    if (!$stmt) {
        echo "Erreur lors de l'exécution de la requête SQL : " . $conn->errorInfo()[2] . "<br>";
        return 0;
    }
    return $stmt->fetch(PDO::FETCH_ASSOC)['total_c_profit'] ?? 0;
}

function getAllTotalCourseProfit($conn) {
    $sql = "SELECT SUM(c_profit) as total_course_profit FROM TotalCourses";
    $stmt = $conn->query($sql);
    if (!$stmt) {
        echo "Erreur lors de l'exécution de la requête SQL : " . $conn->errorInfo()[2] . "<br>";
        return 0;
    }
    return $stmt->fetch(PDO::FETCH_ASSOC)['total_course_profit'] ?? 0;
}

function getAllDProfit($conn) {
    $sql = "SELECT SUM(d_amount) as total_d_profit FROM DProfit";
    $stmt = $conn->query($sql);
    if (!$stmt) {
        echo "Erreur lors de l'exécution de la requête SQL : " . $conn->errorInfo()[2] . "<br>";
        return 0;
    }
    return $stmt->fetch(PDO::FETCH_ASSOC)['total_d_profit'] ?? 0;
}

function getAllPProfit($conn) {
    $sql = "SELECT SUM(P_amount) as total_p_profit FROM PProfit";
    $stmt = $conn->query($sql);
    if (!$stmt) {
        echo "Erreur lors de l'exécution de la requête SQL : " . $conn->errorInfo()[2] . "<br>";
        return 0;
    }
    return $stmt->fetch(PDO::FETCH_ASSOC)['total_p_profit'] ?? 0;
}
?>
    <?php if ($admin_level === 'main_admin') : ?>
        <section id="lvt">
            <div class="container">
                <h1><?php echo $label; ?></h1>
                <div class="lvt-info">
                    <div class="profit-item">
                        <h2>Revenus directs des utilisateurs issus des invitations</h2>
                        <p><?php echo number_format($directProfitData); ?> DZD</p>
                    </div>
                    <div class="profit-item">
                        <h2>Revenus indirects des utilisateurs issus des invitations</h2>
                        <p><?php echo number_format($indirectProfitData); ?> DZD</p>
                    </div>
                    <div class="profit-item">
                        <h2>Revenus excédentaires récupérés au-delà de 150 000 issus des invitations</h2>
                        <p><?php echo number_format($cProfitData); ?> DZD</p>
                    </div>
                    <div class="profit-item">
                        <h2>Revenus totaux des cours vendus</h2>
                        <p><?php echo number_format($totalCourseProfitData); ?> DZD</p>
                    </div>
                    <div class="profit-item">
                        <h2>Revenus totaux des tirages au sort (Drow)</h2>
                        <p><?php echo number_format($totalDProfitData); ?> DZD</p>
                    </div>
                    <div class="profit-item">
                        <h2>Revenus totaux des projets</h2>
                        <p><?php echo number_format($totalPProfitData); ?> DZD</p>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
</body>
</html>