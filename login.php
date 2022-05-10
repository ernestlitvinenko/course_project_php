<?php
require_once 'backend/conf.php';
$title = "Вход";
require_once 'html_components/head.php';
?>

<body>
<main>
    <section class="content">
        <form action="/backend/api.php" method="post" id="reg_form" class="container">
            <input type="hidden" name="method" value="login_user">
            <label>
                E-mail
                <input type="email" name="email" required>
            </label>
            <label>
                Password
                <input type="password" name="password" id="password" minlength="8" required>
            </label>

            <button class="btn btn-outline-secondary" type="submit" onclick="" id="register">
                Вход
            </button>
        </form>
    </section>
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>window.pub_key_pem = `<?=$GLOBALS['public_key_pem']?>`</script>
<script src="static/node-forge/dist/forge.min.js"></script>
<script src="static/encrypt.js"></script>
</body>
