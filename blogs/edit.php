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
            echo '<title>'.translate('blogs_edit_title').' "' . $blog['title'] . '" | Sarxzer</title>';
        }
?>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <?

        $userQuery = $mysqlClient->prepare("SELECT * FROM users WHERE id = :id");
        $userQuery->execute(['id' => $_SESSION['id']]);
        $user = $userQuery->fetch();

        if (isset($_SESSION['id'])) {
            if ($_SESSION['id'] == $blog['author_id']  || $user['role'] == 'admin') {
                ?>
                <form action="" method="post">
                    <input type="text" name="title" id="title" placeholder="<? echo translate('blogs_edit_form_title') ; ?>" value=" <? echo $blog['title'] ?>" required>
                    <textarea name="content" id="content" cols="30" rows="10" placeholder="<? echo translate('blogs_edit_form_content') ; ?>" required> <? echo $blog['content'] ?> </textarea>
                    <input type="submit" value=<? echo translate('blogs_edit_form_button') ?> name="submit_blog">
                </form>
                <?

                if (isset($_POST['submit_blog'])) {
                    $title = $_POST['title'];
                    $content = $_POST['content'];

                    if (empty($title) || empty($content)) {
                        echo translate('blogs_edit_please');
                    } else if (strlen($title) < 5 || strlen($title) > 50) {
                        echo translate('blogs_edit_title_between');
                    } else if (strlen($content) < 10 || strlen($content) > 1000) {
                        echo translate('blogs_edit_content_between');
                    } else {
                        $updateQuery = $mysqlClient->prepare("UPDATE blogs SET title = :title, content = :content WHERE id = :id");
                        $updateQuery->execute([
                            'title' => $title, 
                            'content' => $content, 
                            'id' => $_GET['id']
                        ]);

                        header('Location: /blogs/blog?id=' . $_GET['id']);
                    }
                }
            } else {
                echo translate("blogs_edit_can't");
            }
        } else { 
            echo translate('blogs_edit_logged');
        }

    ?>


    <? include '../footer.php'; ?>


    <?
        echo '<script>';
        echo 'const title = document.getElementById(\'title\');';
        echo 'simulateDeleting(100, title);';
        echo 'simulateTyping(\''.translate('blogs_edit_title').'"' . $blog['title'] . '"\', 100, title);';
        echo '</script>';
    ?>
</body>
</html>