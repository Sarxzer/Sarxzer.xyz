<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
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

        $userQuery = $mysqlClient->prepare("SELECT * FROM users WHERE id = :id");
        $userQuery->execute(['id' => $_GET['id']]);
        $user = $userQuery->fetch();
        if (empty($user)) {
            header('Location: /admin/users-list');
        } else {
            echo '<title>Delete"' . $user['username'] . '"? | Sarxzer</title>';
        }

        $json = file_get_contents('../src/secret.json');
        $secret = json_decode($json, true)['encrypt']['mail'];

    ?>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <form action="" method="POST">
        <p class="text">Are you sure you want to delete "<? echo $user['username']; ?>"?</p>
        <input class="button" type="submit" name="submit" value="Delete">
    </form>

    <?

        if (isset($_POST['submit'])) {
            $backup = $mysqlClient->prepare("INSERT INTO deleted_users (id, token, email, username, password, is_verified, birthday, country, website, bio, created_at) VALUES (:id, :token, :email, :username, :password, :is_verified, :birthday, :country, :website, :bio, :created_at)");
            $backup->execute([
                'id' => $user['id'],
                'token' => $user['token'],
                'email' => openssl_decrypt($user['email'], $secret['algo'], base64_decode($secret['key']), 0, $secret['iv']),
                'username' => $user['username'],
                'password' => $user['password'],
                'is_verified' => $user['is_verified'],
                'birthday' => $user['birthday'],
                'country' => $user['country'],
                'website' => $user['website'],
                'bio' => $user['bio'],
                'created_at' => $user['created_at']
            ]);
            $delete = $mysqlClient->prepare("DELETE FROM users WHERE id = :id");
            $delete->execute(['id' => $user['id']]);
            header('Location: /admin/users-list');
        }


    ?>


    <? include '../footer.php'; ?>


    <?

        echo '<script>';
        echo 'const title = document.getElementById(\'title\');';
        echo 'simulateDeleting(100, title);';
        echo 'simulateTyping(\'Delete"' . $user['username'] . '"?\', 100, title);';
        echo '</script>';


    ?>
</body>
</html>