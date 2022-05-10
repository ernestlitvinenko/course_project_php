<?php
    require_once 'backend/conf.php';
    require_once 'html_components/head.php';
?>
<header class="header">
    <div class="container d-flex justify-content-between">
        <ul class="list-unstyled d-inline-flex">
            <li class="me-3"><a href="/">На главную</a></li>
            <?php
            if (!isset($_SESSION['user_id'])) {
                echo <<<HTML

                <li class="me-3">
                    <a href="/registration.php">Регистрация</a>
                </li>
                <li class="me-3">
                    <a href="/login.php">Вход</a>
                </li>
HTML;
            }
            else {
                echo <<<HTML
<li class="me-3"><a href="/?log_out=1">Выйти</a></li>
<li class="me-3"><a href="/create_channel.php">Создать канал</a></li>
<li class="me-3"><a href="/fields.php">Редактировать область знаний</a></li>
<li class="me-3"><a href="#">Область знаний</a></li>
HTML;

            }



            ?>
        </ul>
        <div class="header__user">
            <?php
            if (isset($_SESSION['username'])) {
                echo <<<HTML
                <p class="header__username">{$_SESSION['username']}</p>
HTML;

            }
            ?>
        </div>
    </div>

</header>