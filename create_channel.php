<?php
require_once 'backend/conf.php';
require_once 'backend/api.php';
require_once 'html_components/head.php';
$mysql = $GLOBALS['mysql'];

if (isset($_POST['name']) and isset($_POST['description']) and isset($_SESSION['user_id'])){
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $mysql->query("INSERT INTO channel (name, description) values ('$name', '$desc')");
    header("Location: /");
}
?>

<body>
<?php
    require_once 'html_components/header.php';
?>
    <main>
        <section class="content">
            <form action="/create_channel.php" class="container" method="post">
                <input class="form-control" type="text" name="name" placeholder="Имя канала">
                <input class="form-control" type="text" name="description" placeholder="Описание канала">
                <button class="btn btn-outline-secondary" type="submit">Создать канал</button>
            </form>
        </section>
    </main>
</body>
