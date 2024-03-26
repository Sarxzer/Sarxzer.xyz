<?

    require '../src/php/translate.php';
    require '../src/php/db.php';
    if (isset($_POST['register'])) {
        $username = htmlspecialchars($_POST['register_username']);
        $email = htmlspecialchars($_POST['register_email']);
        $password = htmlspecialchars($_POST['register_password']);
        $passwordConfirm = htmlspecialchars($_POST['register_password_confirm']);

        if (empty($username) || empty($email) || empty($password) || empty($passwordConfirm)) {
            $message = translate('account_login_register_empty_fields');
        } else if (strlen($username) < 5 || strlen($username) > 20) {
            $message = translate('account_login_register_username_length');
        } else if (strlen($password) < 8 || strlen($password) > 20) {
            $message = translate('account_login_register_password_error_length');
        } else if (!preg_match('/[A-Z]/', $password)) {
            $message = translate('account_login_register_password_error_upper');
        } else if (!preg_match('/[a-z]/', $password)) {
            $message = translate('account_login_register_password_error_lower');
        } else if (!preg_match('/[0-9]/', $password)) {
            $message = translate('account_login_register_password_error_num');
        } else if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $message = translate('account_login_register_password_error_spe');
        } else if ($password != $passwordConfirm) {
            $message = 'Passwords do not match';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Invalid email';
        } else {

            $json = file_get_contents('../src/secret.json');
            $secret = json_decode($json, true)['encrypt']['mail'];
            $EncryptedEmail = openssl_encrypt($email, $secret['algo'], base64_decode($secret['key']), 0, $secret['iv']);

            $query = $mysqlClient->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
            $query->execute([
                'username' => $username, 
                'email' => $EncryptedEmail
            ]);

            $user = $query->fetch();
            if (empty($user)) {
                $date = date('Y-m-d');

                $password = password_hash($password, PASSWORD_ARGON2ID);
                $token = bin2hex(random_bytes(32));


                $insertQuery = $mysqlClient->prepare("INSERT INTO users (token, username, email, password, created_at) VALUES (:token, :username, :email, :password, :created_at)");
                $insertQuery->execute([
                    'token' => $token,
                    'username' => $username, 
                    'email' => $EncryptedEmail, 
                    'password' => $password,
                    'created_at' => $date
                ]);

                require '../src/php/mailer.php';

                sendMail($email, 'Confirm your account', 'Thank you for registering on Sarxzer.xyz, please click on the link below to confirm your account:<br><a href="https://sarxzer.xyz/account/verify?token=' . $token . '">https://sarxzer.xyz/account/verify?token=' . $token . '</a><br><br>If you didn\'t register on Sarxzer.xyz, please ignore this email.', 'Sarxzer.xyz : ' . $username);

                $message = translate('account_login_register_success');
            } else {
                $message = translate('account_login_register_username_taken');
            }
        }
    }



    if (isset($_POST['login'])) {
        $username = htmlspecialchars($_POST['login_username']);
        $password = htmlspecialchars($_POST['login_password']);

        $json = file_get_contents('../src/secret.json');
        $secret = json_decode($json, true)['encrypt']['mail'];
        $email = openssl_encrypt($username, $secret['algo'], base64_decode($secret['key']), 0, $secret['iv']);

        $query = $mysqlClient->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
        $query->bindParam(':username', $username);
        $query->bindParam(':email', $email);
        $query->execute();

        $user = $query->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, start the session
            if ($user['is_verified'] == 1) {
                session_start();
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = openssl_decrypt($user['email'], $secret['algo'], base64_decode($secret['key']), 0, $secret['iv']);
                $_SESSION['last_comment_time'] = time();
                // Redirect to a protected page
                header('Location: /');
            } else {
                $message =  translate('account_login_confirm_account');
            }
        } else {
            $message = translate('account_login_invalid_username_or_password');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
    <title><? echo translate('account_login_page_title') ?> | Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <!--Login-->
    <div class="connection">
        <div class="login">
        <h2><? echo translate('account_login_title') ?></h2>
            <form method="post" action="">
                <div class="username">
                    <label for="login_username"><? echo translate('account_login_username') ?></label>
                    <input type="text" name="login_username" id="login_username" placeholder="<? echo translate('account_login_username_placeholder') ?>" required value="<? echo isset($_POST['login_username']) ? htmlspecialchars($_POST['login_username']) : ''; ?>">
                </div>
                <div class="password">
                    <label for="login_password"><? echo translate('account_login_password') ?></label>
                    <input type="password" name="login_password" id="login_password" placeholder="Ex: *********" required>
                </div>
                <input class="submit" type="submit" name="login" value="<? echo translate('account_login_submit') ?>">
            </form>
            <a href="/account/password-forgot"><? echo translate('account_login_password_forgot') ?></a>
        </div>

        <!--Register-->
        <div class="register">
            <h2><? echo translate('account_login_register_title') ?></h2>
            <form method="post" action="">
                <div class="username">
                    <label for="register_username"><? echo translate('account_login_register_username') ?></label>
                    <input type="text" name="register_username" id="register_username" placeholder="<? echo translate('account_login_register_username_placeholder') ?>" required value="<? echo isset($_POST['register_username']) ? htmlspecialchars($_POST['register_username']) : ''; ?>">
                </div>
                <div class="email">
                    <label for="register_email"><? echo translate('account_login_register_email') ?></label>
                    <input type="email" name="register_email" id="register_email" placeholder="Ex: nathan@sarxzer.xyz" required value="<? echo isset($_POST['register_email']) ? htmlspecialchars($_POST['register_email']) : ''; ?>">
                </div>
                <div class="password">
                    <label for="register_password"><? echo translate('account_login_register_password') ?></label>
                    <input type="password" name="register_password" id="register_password" placeholder="Ex: *********" required>
                </div>
                <div id="password_strength"></div>
                <div class="password-confirm">
                    <label for="register_password_confirm"><? echo translate('account_login_register_password_confirm') ?></label>
                    <input type="password" name="register_password_confirm" id="register_password_confirm" placeholder="<? echo translate('account_login_register_password_confirm_placeholder') ?>" required>
                </div>
                <input class="submit"id="register_submit" type="submit" name="register" value="<? echo translate('account_login_register_submit') ?>" disabled>
            </form>
        </div>
    </div>

    <?
        if (isset($message)) {
            echo '<p class="message">' . $message . '</p>';
        }
    ?>

    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping("<? echo translate('account_login_page_title') ?>", 100, title);
    </script>
    <script>
        const passwordStrength = document.getElementById('password_strength');
        const password = document.getElementById('register_password');
        const passwordConfirm = document.getElementById('register_password_confirm');
        const submit = document.getElementById('register_submit');
        const strength = {
            0: '<? echo translate('account_login_strength_0') ?>',
            1: '<? echo translate('account_login_strength_1') ?>',
            2: '<? echo translate('account_login_strength_2') ?>',
            3: '<? echo translate('account_login_strength_3') ?>',
            4: '<? echo translate('account_login_strength_4') ?>'
        }
        const colors = {
            0: '#ff0000',
            1: '#ff0000',
            2: '#ffa500',
            3: '#7fff00',
            4: '#00ff00'
        }
        password.addEventListener('input', function() {
            const val = password.value;
            const result = zxcvbn(val);
            passwordStrength.innerHTML = '';
            passwordStrength.innerHTML += '<p>Password strength: ' + strength[result.score] + '</p>';
            if (result.feedback.warning != '') {
                passwordStrength.innerHTML += '<p style="color: ' + colors[result.score] + '; border: ' + colors[result.score] + ' solid 2px;">' + result.feedback.warning + '</p>';
            }
            if (result.score <= 2 || password.value != passwordConfirm.value) {
                submit.disabled = true;
            } else {
                submit.disabled = false;
            }
        });
        passwordConfirm.addEventListener('input', function() {
            const val = password.value;
            const result = zxcvbn(val);
            if (result.score <= 2) {
                if (password.value != passwordConfirm.value) {
                    passwordConfirm.setCustomValidity('<? echo translate('account_login_register_password_match') ?>');
                    submit.disabled = true;
                } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password.value)) {
                    passwordConfirm.setCustomValidity('<? echo translate('account_login_register_password_error_spe') ?>');
                    submit.disabled = true;
                } else if (!/[0-9]/.test(password.value)) {
                    passwordConfirm.setCustomValidity('<? echo translate('account_login_register_password_error_num') ?>');
                    submit.disabled = true;
                } else if (!/[A-Z]/.test(password.value)) {
                    passwordConfirm.setCustomValidity('<? echo translate('account_login_register_password_error_upper') ?>');
                    submit.disabled = true;
                } else if (!/[a-z]/.test(password.value)) {
                    passwordConfirm.setCustomValidity('<? echo translate('account_login_register_password_error_lower') ?>');
                    submit.disabled = true;
                } else if (password.value.length < 8) {
                    passwordConfirm.setCustomValidity('<? echo translate('account_login_register_password_error_length') ?>');
                    submit.disabled = true;
                } else if (password.value.length > 20) {
                    passwordConfirm.setCustomValidity('<? echo translate('account_login_register_password_error_length') ?>');
                    submit.disabled = true;
                }
            } else {
                passwordConfirm.setCustomValidity('');
                submit.disabled = false;
            }
        });
    </script>
    
</body>
</html>