<?php
require_once 'backend/conf.php';
require_once 'backend/api.php';

require_once 'html_components/head.php';



?>
<body>
<?php require_once 'html_components/header.php'?>
<div class="container">
    <form action="/channel.php" method="get">
        <input type="hidden" name="channel_id" value="<?=$_GET['channel_id']?>">
        <select name="field">
            <option value="" selected>Не выбрано</option>
            <?php
            $all_fields = $GLOBALS['mysql']->query("select DISTINCT fields.id, fields.name, fields.data from fields
join fields_sms_relations fsr
join sms on fsr._sms_id = sms.id AND sms._channel_id = {$_GET['channel_id']} AND fields.id = fsr._hashtag_id");

            foreach ($all_fields as $field) {
                echo <<<HTML
<option value="{$field['id']}">{$field['name']} | {$field['data']}</option>
HTML;

            }
            ?>
        </select>
        <button class="btn btn-outline-secondary" type="submit">Фильтр</button>
    </form>
</div>
    <main class="d-flex flex-column justify-content-between" style="min-height: 100vh">
        <section class="content">
            <div class="container">
                <?php
                    $query = "SELECT id, name FROM users";
                    $mysql = $GLOBALS['mysql'];
                    $data = $mysql->query($query);
                    $users = [];
                    foreach ($data as $user){
                        $users[$user['id']] = $user['name'];
                    }
                    if (isset($_GET['field']) and $_GET['field'] != "") {
                        foreach (get_messages($_GET['channel_id'], $_GET['field']) as $message) {
                            echo <<<HTML
<div class="message border p-2 mb-3">
    <p class="message__author text-secondary">{$users[$message['_user_id']]}</p>
    <p class="message__text">
    {$message['data']}
    </p>
</div>
HTML;
                        }
                    }
                    else {
                        foreach (get_messages($_GET['channel_id']) as $message) {
                            echo <<<HTML
<div class="message border p-2 mb-3">
    <p class="message__author text-secondary">{$users[$message['_user_id']]}</p>
    <p class="message__text">
    {$message['data']}
    </p>
</div>
HTML;
                        }
                    }
                ?>
            </div>
        </section>
        <form action="/backend/api.php" class="d-flex justify-content-center mb-2" method="post">
            <input type="hidden" name="method" value="send_message">
            <input type="hidden" name="channel_id" value="<?=$_GET['channel_id']?>">
            <input type="text" name="message" placeholder="Введите ваше сообщение">

            <button type="submit" class="btn btn-outline-primary">Отправить</button>
        </form>
    </main>
</body>
