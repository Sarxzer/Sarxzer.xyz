<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title><? echo translate('account_index_page_title')?> | Sarxzer</title>
    <?
        
        require '../src/php/db.php';

        if (!isset($_SESSION['id'])) {
            header('Location: /account/login');
        }

        $query = $mysqlClient->prepare('SELECT * FROM users WHERE id = :id');
        $query->execute([
            'id' => $_SESSION['id']
        ]);
        $user = $query->fetch();

        $json = file_get_contents('../src/secret.json');
        $secret = json_decode($json, true)['encrypt']['mail'];
    ?>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <div class="settings">
        <h2><? echo translate('account_index_title') ?></h2>
        <h4><? echo translate('account_index_change_email') ?></h4>
        <form id="change_email" action="" method="post">
            <label for="email"><? echo translate('account_index_email') ?></label>
            <input type="email" name="email" id="email" placeholder="E-Mail" value="<? echo openssl_decrypt($user['email'], $secret['algo'], base64_decode($secret['key']), 0, $secret['iv']) ?>">
            <input class="submit" type="submit" name="change_email" value="<? echo translate('account_index_change_email') ?>">
        </form>
        
        <div class="horizontal-column">
            <div id="change_password">
                <h4><? echo translate('account_index_change_password') ?></h4>
                <form action="" method="post">
                    <label for="old_password"><? echo translate('account_index_old_password') ?></label>
                    <input type="password" name="old_password" id="old_password" placeholder="<? echo translate('account_index_old_password') ?>">
                    <label for="new_password"><? echo translate('account_index_new_password') ?></label>
                    <input type="password" name="new_password" id="new_password" placeholder="<? echo translate('account_index_new_password') ?>">
                    <div id="password_strength"></div>
                    <label for="new_password_confirm"><? echo translate('account_index_new_password_confirm') ?></label>
                    <input type="password" name="new_password_confirm" id="new_password_confirm" placeholder="<? echo translate('account_index_new_password_confirm') ?>">
                    <input class="submit" type="submit" name="change_password" id="new_passwors_submit" value="<? echo translate('account_index_change_password') ?>">
                </form>
            </div>

            <div id="delete_account">
                <h4><? echo translate('account_index_delete_account') ?></h4>
                <form action="" method="post">
                    <label for="password"><? echo translate('account_index_password') ?></label>
                    <input type="password" name="password" id="password" placeholder="<? echo translate('account_index_password') ?>">
                    <label for="confirm_password"><? echo translate('account_index_password_confirm') ?></label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="<? echo translate('account_index_password_confirm') ?>">
                    <input class="submit" type="submit" name="delete_account" value="Delete account">
                    <label><? echo translate('account_index_delete_account_note') ?></label>
                </form>
            </div>
        </div>

        <? 
            if (isset($_POST['change_email'])) {
                $email = htmlspecialchars($_POST['email']);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo translate('account_index_invalid_email');
                } else {
                    $token = bin2hex(random_bytes(32));

                    $encryptedEmail = openssl_encrypt($email, $secret['algo'], base64_decode($secret['key']), 0, $secret['iv']);

                    $query = $mysqlClient->prepare('UPDATE users SET token = :token, email = :email WHERE id = :id');
                    $query->execute([
                        'token' => $token,
                        'email' => $encryptedEmail,
                        'id' => $_SESSION['id']
                    ]);
                    require '../src/php/mailer.php';
                    
                    sendMail($email, 'Confirm your email', 'Thank you for changing your email on Sarxzer.xyz, please click on the link below to confirm your email:<br><a href="https://sarxzer.xyz/account/verify?token=' . $token . '">https://sarxzer.xyz/account/verify?token=' . $token . '</a><br><br>If you didn\'t change your email on Sarxzer.xyz, please ignore this email.', $user['username']);
                }
            }
            if (isset($_POST['change_password'])) {
                $old_password = htmlspecialchars($_POST['old_password']);
                $new_password = htmlspecialchars($_POST['new_password']);
                $new_password_confirm = htmlspecialchars($_POST['new_password_confirm']);
                if (password_verify($old_password, $user['password'])) {
                    if ($new_password == $new_password_confirm) {
                        if (strlen($new_password) >= 8 && strlen($new_password) <= 20) {
                            $query = $mysqlClient->prepare('UPDATE users SET password = :password WHERE id = :id');
                            $query->execute([
                                'password' => password_hash($new_password, PASSWORD_ARGON2ID),
                                'id' => $_SESSION['id']
                            ]);

                            require '../src/php/mailer.php';
                            
                            sendMail(openssl_decrypt($user['email'], $secret['algo'], base64_decode($secret['key']), 0, $secret['iv']), 'Password changed', 'Your password has been changed on Sarxzer.xyz, if you didn\'t change your password, please contact us at <a href="mailto:nathan@sarxzer.xyz" target="_blank">nathan@sarxzer.xyz</a>.', $user['username']);
                            
                            header('Location: /account/');
                        } else {
                            echo 'Password must be between 8 and 20 characters.';
                        }
                    } else {
                        echo translate('account_index_password_match');
                    }
                } else {
                    echo translate('account_index_password_incorrect');
                }
            }
            if (isset($_POST['delete_account'])) {
                $password = htmlspecialchars($_POST['password']);
                $confirm_password = htmlspecialchars($_POST['confirm_password']);
                if (password_verify($password, $user['password'])) {
                    if ($password == $confirm_password) {
                        $backup = $mysqlClient->prepare('INSERT INTO deleted_users (username, email, password, birthday, country, website, bio) VALUES (:username, :email, :password, :birthday, :country, :website, :bio)');
                        $backup->execute([
                            'username' => $user['username'],
                            'email' => openssl_encrypt($user['email'], $secret['algo'], base64_decode($secret['key']), 0, $secret['iv']),
                            'password' => $user['password'],
                            'birthday' => $user['birthday'],
                            'country' => $user['country'],
                            'website' => $user['website'],
                            'bio' => $user['bio']
                        ]);
                        $delete = $mysqlClient->prepare('DELETE FROM users WHERE id = :id');
                        $delete->execute([
                            'id' => $_SESSION['id']
                        ]);

                        session_destroy();
                        header('Location: /');
                    } else {
                        echo translate('account_index_password_match');
                    }
                } else {
                    echo translate('account_index_password_incorrect');
                }
            }
        ?>
    </div>

    <div class="user-infos">

        <?
            if (isset($_POST['change_user_infos'])) {
                $username = htmlspecialchars($_POST['username']);
                $birth_date = htmlspecialchars($_POST['birth_date']);
                $country = htmlspecialchars($_POST['country']);
                $website = htmlspecialchars($_POST['website']);
                
                $query = $mysqlClient->prepare('SELECT * FROM users WHERE username = :username AND id != :id');
                $query->execute([
                    'username' => $username,
                    'id' => $_SESSION['id']
                ]);
                $user = $query->fetch();

                if (!empty($user)) {
                    echo translate('account_index_user_infos_username_taken');
                } else {
                    $query = $mysqlClient->prepare('UPDATE users SET username = :username, birthday = :birth_date, country = :country, website = :website WHERE id = :id');
                    $query->execute([
                        'username' => $username,
                        'birth_date' => $birth_date,
                        'country' => $country,
                        'website' => $website,
                        'id' => $_SESSION['id']
                    ]);
                }
            }

            if (isset($_POST['change_bio'])) {
                $bio = htmlspecialchars($_POST['bio']);
            
                $query = $mysqlClient->prepare('UPDATE users SET bio = :bio WHERE id = :id');
                $query->execute([
                    'bio' => $bio,
                    'id' => $_SESSION['id']
                ]);
            }

            $query = $mysqlClient->prepare('SELECT * FROM users WHERE id = :id');
            $query->execute([
                'id' => $_SESSION['id']
            ]);
            $user = $query->fetch();
        ?>

        <h2 id="userinfos"><? echo translate('account_index_user_infos_title') ?></h2>

        <h4><? echo translate('account_index_change_user_infos') ?></h4>
        <form action="" method="post">
            <label for="username"><? echo translate('account_index_username') ?></label>
            <input type="text" name="username" id="username" placeholder="<? echo translate('account_index_username') ?>" value="<? echo $user['username'] ?>">
            <label for="birth_date"><? echo translate('account_index_user_infos_birthday') ?></label>
            <input type="date" name="birth_date" id="birth_date" placeholder="<? echo translate('account_index_user_infos_birthday') ?>" value="<? echo isset($user['birthday']) ? $user['birthday'] : ''; ?>">
            <label for="country"><? echo translate('account_index_user_infos_country') ?></label>
            <select name="country" id="country" value="<? echo isset($user['country']) ? htmlspecialchars($user['country']) : ''; ?>">
                <option value=""><? echo translate('account_index_user_infos_country_select') ?></option>
                <?
                    $countries = json_decode(file_get_contents('https://restcountries.com/v3.1/all'));

                    usort($countries, function($a, $b) {
                        return strcmp($a->name->common, $b->name->common);
                    });

                    foreach ($countries as $country) {
                        echo '<option value="' . $country->cca2 . '" ' . ($user['country'] == $country->cca2 ? "selected" : "") . '>' . $country->name->common . '</option>';
                    }
                ?>
            </select>
            <label for="website"><? echo translate('account_index_user_infos_website') ?></label>
            <input type="url" name="website" id="website" placeholder="<? echo translate('account_index_user_infos_website') ?>" value="<? echo isset($user['website']) ? htmlspecialchars($user['website']) : ''; ?>">
            <input type="submit" name="change_user_infos" value="<? echo translate('account_index_change_user_infos_submit') ?>">
        </form>

        <h4><? echo translate('account_index_user_infos_change_bio') ?></h4>
        <form action="" method="post">
            <label for="bio"><? echo translate('account_index_user_infos_change_bio') ?></label>
            <textarea name="bio" id="bio" cols="30" rows="10" placeholder="<? echo translate('account_index_user_infos_change_bio') ?>"><? echo isset($user['bio']) ? htmlspecialchars($user['bio']) : ''; ?></textarea>
            <input type="submit" name="change_bio" value="<? echo translate('account_index_user_infos_change_bio_submit') ?>">
        </form>
        </div>

        <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('<? echo translate('account_index_page_title') ?>', 100, title);
    </script>
        <script>
        const passwordStrength = document.getElementById('password_strength');
        const password = document.getElementById('new_password');
        const passwordConfirm = document.getElementById('new_password_confirm');
        const submit = document.getElementById('new_passwors_submit');
        const strength = {
            0: '<? echo translate('account_index_password_strength_0') ?>',
            1: '<? echo translate('account_index_password_strength_1') ?>',
            2: '<? echo translate('account_index_password_strength_2') ?>',
            3: '<? echo translate('account_index_password_strength_3') ?>',
            4: '<? echo translate('account_index_password_strength_4') ?>'
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
            passwordStrength.innerHTML += '<p><? echo translate('account_index_password_strength') ?>' + strength[result.score] + '</p>';
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
            if (result.score <= 2 || password.value != passwordConfirm.value) {
                passwordConfirm.setCustomValidity('<? echo translate('account_index_password_match') ?>');
                submit.disabled = true;
            } else {
                passwordConfirm.setCustomValidity('');
                submit.disabled = false;
            }
        });
    </script>
</body>
</html>