<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title><? echo translate('account_logout_title'); ?>| Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <form method="post" action="">
        <button class="logout-button" type="submit" name="logout"><? echo translate('account_logout_button'); ?></button>
    </form>

    <?
        if (isset($_POST['logout'])) {
            $_tempLang = $_SESSION['lang'];
            $_SESSION = array();
            $_SESSION['lang'] = $_tempLang;
            header('Location: /');
        }
    ?>


    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping(<? echo translate('account_logout_page_title'); ?>, 100, title);
    </script>
</body>
</html>