<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title>Admin"Comments list"| Sarxzer</title>

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

        if (isset($_GET['delete'])) {
            $commentsQuery = $mysqlClient->prepare("SELECT * FROM comments WHERE id = :id");
            $commentsQuery->execute(['id' => $_GET['delete']]);
            $comments = $commentsQuery->fetch();
            if ($comments['state'] == 'normal') {
                $deleteQuery = $mysqlClient->prepare("UPDATE comments SET state = 'deleted' WHERE id = :id");
                $deleteQuery->execute(['id' => $_GET['delete']]);
                echo '<p class="text">Comment deleted</p>';
            } else {
                echo '<p class="text">This comment is already deleted</p>';
            } 

        } else if (isset($_GET['restore'])) {
            $commentsQuery = $mysqlClient->prepare("SELECT * FROM comments WHERE id = :id");
            $commentsQuery->execute(['id' => $_GET['restore']]);
            $comments = $commentsQuery->fetch();
            if ($comments['state'] == 'deleted') {
                $restoreQuery = $mysqlClient->prepare("UPDATE comments SET state = 'normal' WHERE id = :id");
                $restoreQuery->execute(['id' => $_GET['restore']]);
                echo '<p class="text">Comment restored</p>';
            } else {
                echo '<p class="text">This comment is already restored</p>';
            }
        }
        $commentsQuery = $mysqlClient->prepare("SELECT * FROM comments");
        $commentsQuery->execute();
        $comments = $commentsQuery->fetchAll();

        echo '<table>';
        echo '<tr>';
        echo '<th>Id</th>';
        echo '<th>Author</th>';
        echo '<th>Blog</th>';
        echo '<th>Content</th>';
        echo '<th>Creation date</th>';
        echo '<th>Status</th>';
        echo '<th>Actions</th>';
        echo '</tr>';

        foreach ($comments as $comment) {
            $authorQuery = $mysqlClient->prepare("SELECT username FROM users WHERE id = :author_id");
            $authorQuery->execute(['author_id' => $comment['author_id']]);
            $authorResult = $authorQuery->fetch();

            $blogQuery = $mysqlClient->prepare("SELECT title FROM blogs WHERE id = :blog_id");
            $blogQuery->execute(['blog_id' => $comment['blog_id']]);
            $blogResult = $blogQuery->fetch();

            if ($authorResult) {
                $author = $authorResult['username'];
            } else {
                $author = 'Unknown';
            }

            if ($blogResult) {
                $blog = $blogResult['title'];
            } else {
                $blog = 'Unknown';
            }

            echo '<tr>';
            echo '<td>' . $comment['id'] . '</td>';
            echo '<td>' . htmlspecialchars($author) . '</td>';
            echo '<td>' . htmlspecialchars($blog) . '</td>';
            echo '<td>' . htmlspecialchars($comment['content']) . '</td>';
            echo '<td>' . $comment['created_at'] . '</td>';
            echo '<td>' . ($comment['state'] == 'normal' ? 'Normal' : 'Deleted') . '</td>';
            if ($comment['state'] == 'normal') {
                echo '<td><a href="?delete=' . $comment['id'] . '">Delete</a></td>';
            } else {
                echo '<td><a href="?restore=' . $comment['id'] . '">Restore</a></td>';
            }
            echo '</tr>';

        }

        echo '</table>';
    ?>


    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('Admin"Comments list"', 100, title);
    </script>
</body>
</html>