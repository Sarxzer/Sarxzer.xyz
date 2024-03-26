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
            echo '<title>Edit"' . $user['username'] . '" | Sarxzer</title>';
        }

        $json = file_get_contents('../src/secret.json');
        $secret = json_decode($json, true)['encrypt']['mail'];
    ?>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <form action="" method="post">
        <label for="username">Username: </label>
        <input type="text" name="username" placeholder="Username" value="<? echo $user['username']; ?>">
        <label for="email">E-Mail: </label>
        <input type="email" name="email" placeholder="E-Mail" value="<? echo openssl_decrypt($user['email'], $secret['algo'], $secret['key'], 0, $secret['iv']); ?>">
        <label for="role">Role: </label>
        <select name="role">
            <option value="user" <? echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
            <option value="admin" <? echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
        </select>
        <label for="bio">Biography: </label>
        <input type="text" name="bio" placeholder="Biography" value="<? echo $user['bio']; ?>">
        <input type="submit" name="submit" value="Edit">
    </form>

    <form action="" method="post">
        <input type="submit" name="reset_password" value="Reset password">
    </form>

    <form action="" method="post">
        <input type="submit" name="delete" value="Delete">
    </form>


    <?

        include '../src/php/db.php';

        if (isset($_POST['submit'])) {
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $role = htmlspecialchars($_POST['role']);
            $bio = htmlspecialchars($_POST['bio']);

            if (empty($username) || empty($email) || empty($role)) {
                echo 'Please fill all the fields';
            } else if (strlen($username) < 5 || strlen($username) > 20) {
                echo 'Username must be between 5 and 20 characters';
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo 'Invalid email';
            } else if ($role != 'user' && $role != 'admin') {
                echo 'Invalid role';
            } else if (strlen($bio) > 100) {
                echo 'Biography must be less than 100 characters';
            } else {
                $updateQuery = $mysqlClient->prepare("UPDATE users SET username = :username, email = :email, role = :role, bio = :bio WHERE id = :id");
                $updateQuery->execute(['username' => $username, 'email' => $email, 'role' => $role, 'bio' => $bio, 'id' => $_GET['id']]);
                header('Location: /admin/users-list');
            }
        }

        if (isset($_POST['reset_password'])) {
            $password = bin2hex(random_bytes(8));
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $updateQuery = $mysqlClient->prepare("UPDATE users SET password = :password WHERE id = :id");
            $updateQuery->execute(['password' => $passwordHash, 'id' => $_GET['id']]);

            echo 'New password: ' . $password . '<br>';
            echo '(This is a temporary password, please change it as soon as possible)';

            include '../src/php/mailer.php';
            sendMail(openssl_decrypt($user['email'], $secret['algo'], $secret['key'], 0, $secret['iv']), 'Password reset', 'Hello ' . $user['username'] . ', your password has been reset. Your new password is: ' . $password . '. This is a temporary password, please change it as soon as possible. <br> If you did not request a password reset, please contact us at <a href="mailto:nathan@sarxzer.xyz" target="_blank">nathan@sarxzer.xyz</a>');
        }


    ?>


    <? include '../footer.php'; ?>


    <?

        echo '<script>';
        echo 'const title = document.getElementById(\'title\');';
        echo 'simulateDeleting(100, title);';
        echo 'simulateTyping(\'Edit"' . $user['username'] . '"\', 100, title);';
        echo '</script>';
    ?>
</body>
</html>