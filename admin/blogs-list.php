<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title>Admin"Blogs list" | Sarxzer</title>
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

    <?

        require '../src/php/db.php';

        $blogsQuery = $mysqlClient->prepare("SELECT * FROM blogs");
        $blogsQuery->execute();
        $blogs = $blogsQuery->fetchAll();

        echo '<table>';
        echo '<tr>';
        echo '<th>Id</th>';
        echo '<th>Title</th>';
        echo '<th>Author</th>';
        echo '<th>Creation Date</th>';
        echo '<th>Last Edit Date</th>';
        echo '<th>Actions</th>';
        echo '</tr>';
        foreach ($blogs as $blog) {

            $authorQuery = $mysqlClient->prepare("SELECT * FROM users WHERE id = :id");
            $authorQuery->execute(['id' => $blog['author_id']]);
            $authorList = $authorQuery->fetch();

            if (empty($authorList)) {
                $author = 'Unknown';
            } else {
                $author = $authorList['username'];
            }
            $blog['title'] = htmlspecialchars($blog['title']);
            $author = htmlspecialchars($author);



        
            echo '<tr>';
            echo '<td>' . $blog['id'] . '</td>';
            echo '<td><a href="/blogs/blog?id=' . $blog['id'] . '">' . $blog['title'] . '</a></td>';
            echo '<td><a href="/account/profile?id=' . $blog['author_id'] . '">' . $author . '</a></td>';
            echo '<td>' . $blog['creation_date'] . '</td>';
            echo '<td>' . $blog['edit_date'] . '</td>';
            echo '<td><a href="/admin/edit-blog?id=' . $blog['id'] . '">Edit</a> <a href="/admin/delete-blog?id=' . $blog['id'] . '">Delete</a></td>';
            echo '</tr>';
        }
        echo '</table>';



    ?>


    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('Blogs list', 100, title);
    </script>
</body>
</html>