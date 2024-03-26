<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title>Admin"Users list"| Sarxzer</title>

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

        $json = file_get_contents('../src/secret.json');
        $secret = json_decode($json, true)['encrypt']['mail'];

    
    ?>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <? 
    
        require '../src/php/db.php';
        $usersQuery = $mysqlClient->prepare("SELECT * FROM users");
        $usersQuery->execute();
        $users = $usersQuery->fetchAll();

        echo '<table>';
        echo '<tr>';
        echo '<th>Id</th>';
        echo '<th>Username</th>';
        echo '<th>E-Mail</th>';
        echo '<th>Role</th>';
        echo '<th>is Verified</th>';
        echo '<th>Creation date</th>';
        echo '<th>Actions</th>';
        echo '</tr>';
        foreach ($users as $user) {
            echo '<tr>';
            echo '<td>' . $user['id'] . '</td>';
            echo '<td>' . htmlspecialchars($user['username']) . '</td>';
            echo '<td>' . openssl_decrypt($user['email'], $secret['algo'], base64_decode($secret['key']), 0, $secret['iv']) . '</td>';
            echo '<td>' . $user['role'] . '</td>';
            echo '<td>' . ($user['is_verified'] == 1 ? 'True' : 'False') . '</td>';
            echo '<td>' . $user['created_at'] . '</td>';
            echo '<td><a href="/admin/edit-user?id=' . $user['id'] . '">Edit</a> <a href="/admin/delete-user?id=' . $user['id'] . '">Delete</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    
    ?>


    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('Admin"Users list"', 100, title);
    </script>
</body>
</html>