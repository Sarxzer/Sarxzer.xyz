<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../header.php'; ?>
    <title>Admin"Emails" | Sarxzer</title>
    <?php

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

    <?php include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <?php 
    
        require '../src/php/db.php';
        $emailsQuery = $mysqlClient->prepare("SELECT * FROM emails");
        $emailsQuery->execute();
        $emails = $emailsQuery->fetchAll();

        echo '<table>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Email</th>';
        echo '<th>Sender</th>';
        echo '<th>Subject</th>';
        echo '<th>Date</th>';
        echo '<th>Actions</th>';
        echo '</tr>';
        foreach ($emails as $email) {
            echo '<tr>';
            echo '<td>' . $email['id'] . '</td>';
            echo '<td>' . $email['email'] . '</td>';
            echo '<td>' . $email['sender'] . '</td>';
            echo '<td>' . htmlspecialchars($email['subject']) . '</td>';
            echo '<td>' . $email['date'] . '</td>';
            echo '<td><a href="/admin/delete-email?id=' . $email['id'] . '">Delete</a></td>';
            echo '</tr>';
        }
        echo '</table>';

    ?>


    <?php include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('Emails', 100, title);
    </script>
</body>
</html>