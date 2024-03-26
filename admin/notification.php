<?
    include '../src/php/notify.php';

    include '../src/php/db.php';

    if (isset($_POST['submit'])) {
        if (isset($_POST['user']) && isset($_POST['type']) && isset($_POST['message'])) {
            $user = $_POST['user'];
            $type = $_POST['type'];
            $message = $_POST['message'];
            if ($user === 'all') {
                createNotificationForAll($type, $message, $mysqlClient);
            } else if ($user === 'admin') {
                createNotificationForRole('admin', $type, $message, $mysqlClient);
            } else {
                createNotification($user, $type, $message, $mysqlClient);
            }
            $message = '<p>Notification sent</p>';
        } else {
            $message = '<p>Fill the form to send a notification</p>';
        }
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title>Admin"Notification" | Sarxzer</title>

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

    <form action="" method="post">
        <label for="user">for :</label>
        <select name="user" id="user">
            <option value="all">all</option>
            <option value="admin">admin</option>
            <? 
                $usersQuery = $mysqlClient->query('SELECT * FROM users');
                $usersQuery->execute();
                $users = $usersQuery->fetchAll();

                foreach ($users as $user) {
                    echo '<option value="' . $user['id'] . '">' . $user['username'] . '</option>';
                }
            
            ?>
        </select>
        <label for="type">Type :</label>
        <select name="type" id="type">
            <option value="normal">normal</option>
            <option value="warning">warning</option>
            <option value="error">error</option>
        </select>
        <label for="message">Message :</label>
        <textarea name="message" id="message" cols="30" rows="10"></textarea>
        <input type="submit" name="submit" value="Send">
    </form>

    <? if (isset($message)) echo $message; ?>


    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('Admin"Notification"', 100, title);
    </script>
</body>
</html>