<?php
session_start();
if (!isset($_SESSION['user_r_pseudo'])) {
    header('Location: signin.php');
    exit;
}

$pagetitle = "Formation Professionnelle";
$cssfile1 = '../Css/global.css';
$cssfile2 = '../Css/formation.css';
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
$jsfile3 = '../Js/fp.js';
$gun = 'g_u_n.js';
$jq = '../Js/jquery-3.6.0.min.js';

require_once('uheader.php');
require_once('../functions/exp.php');
?>
<section id="courses-content">
    <div class="container">
        <button id="showFilterBtn" class="filter-btn" data-translate>Afficher les filtres</button>
        <div class="filter">
            <form action="">
                <div class="filter-group">
                    <div class="filter1">
                        <h3 data-translate>Catégories</h3>
                        <?php
                        require_once('../functions/db.php');
                        $stmt = $pdo->prepare("SELECT category_id, category_name FROM Categories");
                        $stmt->execute();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<div>";
                            echo "<input type='checkbox' id='category{$row['category_id']}' name='category[]' value='{$row['category_name']}'>";
                            echo "<label for='category{$row['category_id']}' data-translate>{$row['category_name']}</label><br>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                    <div class="filter1">
                        <h3 data-translate>La Langue</h3>
                        <?php
                        $stmt = $pdo->prepare("SELECT language_id, language_name FROM Languages");
                        $stmt->execute();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<div>";
                            echo "<input type='checkbox' id='language{$row['language_id']}' name='language[]' value='{$row['language_name']}'>";
                            echo "<label for='language{$row['language_id']}' data-translate>{$row['language_name']}</label><br>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
        <div class="content">
            <div class="search-result">
                <h1><span id="resultCount">0</span>&nbsp;<span data-translate>Résultats</span></h1>
                <div class="filter-content"></div>
            </div>
            <div class="filter-courses">
                <div class="pagination"></div>
                <div class="courses"></div>
                <div class="pagination"></div>
            </div>
        </div>
    </div>
</section>
<script src="./filter.js"></script>
<?php require_once('footer.php'); ?>