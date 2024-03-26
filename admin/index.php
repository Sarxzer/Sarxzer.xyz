<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title>Admin | Sarxzer</title>

    <?

        require '../src/php/db.php';

        if (!isset($_SESSION['id'])) {
            header('Location: /account/login');
        } else {
            $userQuery = $mysqlClient->prepare("SELECT * FROM users WHERE id = :id");
            $userQuery->execute(['id' => $_SESSION['id']]);
            $user = $userQuery->fetch();
            if ($user['role'] != 'admin') {
                header('Location: /');
            }
        }
    ?>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <a class="button" href="/admin/users-list">Users list</a>
    <a class="button" href="/admin/blogs-list">Blogs list</a>
    <a class="button" href="/admin/emails-list">Emails list</a>
    <a class="button" href="/admin/comments-list">Comments list</a>
    <a class="button" href="/admin/notification">Notification</a>
    


    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('Admin Menu', 100, title);
    </script>
</body>
</html>