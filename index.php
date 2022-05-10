<?php
require_once 'backend/conf.php';
require_once 'html_components/head.php';

if (isset($_GET['log_out'])){
    if (isset($_SESSION['user_id'])) {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        header("Refresh:0");
    }
}

?>

<body>

<?php
    require_once "html_components/header.php"
?>

<main>
    <session class="content">
        <?php
        if (isset($_SESSION['user_id'])) {
            require_once 'lk.php';
        }
        ?>
    </session>
</main>
</body>