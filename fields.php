<?php
require_once 'backend/conf.php';
require_once 'backend/api.php';
require_once 'html_components/head.php';

if (isset($_POST['field_id']) && isset($_POST['field_name']) and isset($_SESSION['user_id'])) {
    $GLOBALS['mysql']->query("update fields set name = '{$_POST['field_name']}' where id={$_POST['field_id']}");
    header("Location: /fields.php");
}
?>

<body>
    <?php
        require_once 'html_components/header.php';
    ?>
    <main>
        <div class="container">
<!--            Выбрать область знаний-->

            <form action="/fields.php" method="get">
                <select name="field_id">
                    <?php
                        foreach ($GLOBALS['mysql']->query("select * from fields") as $row) {
                            echo <<<HTML
<option value="{$row['id']}">{$row['name']} | {$row['data']}</option>
HTML;

                        }
                    ?>
                </select>
                <button class="btn btn-outline-secondary" type="submit">Выбрать</button>
            </form>
        </div>
        <main>
            <div class="container">
                <?php
                if (isset($_GET['field_id']) and $_GET['field_id'] != "") {
                    $data = $GLOBALS['mysql']->query("select * from fields where id = {$_GET['field_id']}")->fetch_assoc();
                    echo <<<HTML
<form action="/fields.php" method="post">
                    <input type="hidden" name="field_id" value="{$data['id']}">
                    <input type="text" name="field_name" value="{$data['name']}" placeholder="Название категории" required>
                    <input type="text" disabled value="{$data['data']}">
                    <button type="submit" class="btn btn-outline-primary">Изменить</button>
</form>
HTML;
                }
                ?>
            </div>
        </main>
    </main>
</body>