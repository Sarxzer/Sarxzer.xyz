<!DOCTYPE html>
<html lang="en">
<head>
    <?
        include 'header.php';

        require './src/php/db.php';

        if (isset($_SESSION['id'])) {
            $userQuery = $mysqlClient->prepare("SELECT * FROM users WHERE id = :id");
            $userQuery->execute(['id' => $_SESSION['id']]);
            $user = $userQuery->fetch();
        } else {
            $user = null;
        }
        
        include './src/secret.php';

        $secret = $secret['encrypt']['mail'];

    ?>
    <title><? echo translate('contact_title') ?> | Sarxzer</title>
</head>
<body>

    <? include 'menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <div class="contact">
        <p class="text"><? echo translate('contact_email') ?><a href="mailto:nathan@sarxzer.xyz">nathan@sarxzer.xyz</a></p>
        <p class="text"><? echo translate('contact_discord') ?><a href="https://discord.gg/Q8r8YvWTxn">https://discord.gg/Q8r8YvWTxn</a></p>
    </div>

    <h2><? echo translate('contact_form_title') ?></h2>

    <div id="contact-form">
        <form action="" method="POST">
            <label for="name"><? echo translate('contact_form_username') ?></label>
            <input type="text" name="username" placeholder="<? echo translate('contact_form_username_placeholder') ?>" required value="<? echo isset($_SESSION['id']) ? $user['username'] :'' ?>">
            <label for="email"><? echo translate('contact_form_email') ?></label>
            <input type="text" name="email" placeholder="<? echo translate('contact_form_email_placeholder') ?>" required value="<? echo isset($_SESSION['id']) ? openssl_decrypt($user['email'], $secret['algo'], base64_decode($secret['key']), 0, $secret['iv']) :'' ?>">
            <label for="category"><? echo translate('contact_form_category') ?></label>
            <select name="category" required>
                <option value="bug"><? echo translate('contact_form_category_bug') ?></option>
                <option value="suggestion"><? echo translate('contact_form_category_suggestion') ?></option>
                <option value="question"><? echo translate('contact_form_category_question') ?></option>
                <option value="email"><? echo translate('contact_form_category_email') ?></option>
                <option value="other"><? echo translate('contact_form_category_other') ?></option>
            </select>
            <label for="subject"><? echo translate('contact_form_subject') ?></label>
            <input type="text" name="subject" placeholder="<? echo translate('contact_form_subject_placeholder') ?>" required>
            <label for="message"><? echo translate('contact_form_message') ?></label>
            <textarea name="message" placeholder="<? echo translate('contact_form_message_placeholder') ?>" required></textarea>
            <input type="submit" name="submit" value="<? echo translate('contact_form_send') ?>">
        </form>
    </div>

    <?

        if (isset($_POST['submit'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $category = $_POST['category'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];

            if ($username == '' || $email == '' || $category == '' || $subject == '' || $message == '') {
                echo '<p class="error">Please fill in all the fields</p>';
                return;
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo '<p class="error">Please enter a valid email</p>';
                return;
            } else if (strlen($subject) > 100) {
                echo '<p class="error">Subject is too long, 100 characters max</p>';
                return;
            } else if (strlen($message) > 1000) {
                echo '<p class="error">Message is too long, 1000 characters max</p>';
                return;
            } else if (strlen($username) > 20) {
                echo '<p class="error">Username is too long, 20 characters max</p>';
                return;
            } else if (strlen($username) < 5) {
                echo '<p class="error">Username is too short, 3 characters min</p>';
                return;
            }
        
            include './src/php/mailer.php';
            $body = 'Username: ' . $username . '<br>Email: ' . $email . '<br>Category: ' . $category . '<br>Subject: ' . $subject . '<br>Message: ' . $message;
            
            sendMail('nathan@sarxzer.xyz', 'Contact form : ' . $category, $body, $email);
        }
    ?>

    <? include 'footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('Contact', 100, title);
    </script>
</body>
</html>