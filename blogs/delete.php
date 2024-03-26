<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <?

        require '../src/php/db.php';
        $blog = $mysqlClient->prepare("SELECT * FROM blogs WHERE id = :id");
        $blog->execute(['id' => $_GET['id']]);
        $blog = $blog->fetch();


        if (empty($blog)) {
            header('Location: /blogs/');
        } else {
            echo '<title>'.translate('blogs_delete_title').'"' . $blog['title'] . '"? | Sarxzer</title>';
        }

    ?>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <?

        if (isset($_SESSION['id'])) {
            $userQuery = $mysqlClient->prepare("SELECT * FROM users WHERE id = :id");
            $userQuery->execute(['id' => $_SESSION['id']]);
            $user = $userQuery->fetch();
    

            if ($_SESSION['id'] == $blog['author_id']  || $user['role'] == 'admin') {
                echo '<form action="" method="post">';
                echo '<input type="submit" value="'.translate('blogs_delete_button').'" name="submit_blog">';
                echo '</form>';

                if (isset($_POST['submit_blog'])) {
                    $deleteQuery = $mysqlClient->prepare("DELETE FROM blogs WHERE id = :id");
                    $deleteQuery->execute(['id' => $_GET['id']]);
                    header('Location: /blogs/');
                }
            } else {
                echo translate("blogs_delete_can't");
            }
        } else { 
            echo translate("blogs_delete_logged");
        }

    ?>


    <? include '../footer.php'; ?>


    <?
        echo '<script>';
        echo 'const title = document.getElementById(\'title\');';
        echo 'simulateDeleting(100, title);';
        echo 'simulateTyping(\''.translate('blogs_delete_title').'"' . $blog['title'] . '"?\', 100, title);';
        echo '</script>';
    ?>
</body>
</html>