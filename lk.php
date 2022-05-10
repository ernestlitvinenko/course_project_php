<?php
require_once 'backend/conf.php';
require_once 'backend/api.php';
$title = "Чаты";
require_once 'html_components/head.php';
?>

<div class="container row">
    <?php
    foreach (get_channels() as $channel) {

        echo <<<HTML
<div class="card col-md-4 col-12 p-3">
    <h2 class="text-center mb-4">{$channel['name']}</h2>
    <p class="text-start mb-4">{$channel['description']}</p>
    <a href="/channel.php?channel_id={$channel['id']}" class="btn btn-outline-primary mb-2">Перейти</a>
</div>
HTML;
    }
    ?>

</div>
