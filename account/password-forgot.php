<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title><? echo translate('account_password-forgot_title'); ?> | Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <form action="" method="post">
        <input type="text" name="email" placeholder="Email" required>
        <input type="submit" value="Reset password">
    </form>
    <?

        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $userQuery = $mysqlClient->prepare("SELECT * FROM users WHERE email = :email");
            $userQuery->execute(['email' => $email]);
            $user = $userQuery->fetch();

            if (!empty($user)) {
                $password = bin2hex(random_bytes(8));
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $updatePasswordQuery = $mysqlClient->prepare("UPDATE users SET password = :password WHERE email = :email");
                $updatePasswordQuery->execute(['password' => $passwordHash, 'email' => $email]);
                
                include '../src/php/mailer.php';

                sendMail($email, 'Password reset', 'Hello ' . $user['username'] . ', your password has been reset. Your new password is: ' . $password . '. This is a temporary password, please change it as soon as possible. <br> If you did not request a password reset, please contact us at <a href="mailto:nathan@sarxzer.xyz" target="_blank">nathan@sarxzer.xyz</a>');
                echo '<p class="text">Your password has been reset. Please check your emails.</p>';
        }
    }


    ?>

    <? include '../footer.php'; ?>




    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('<? echo translate('account_password-forgot_page_title'); ?>', 100, title);
    </script>
</body>
</html>