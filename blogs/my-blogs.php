<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title><? echo translate('blogs_my-blogs_title'); ?> | Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <div class="blogs">
        <?

            require '../src/php/db.php';
            if (isset($_SESSION['id'])) {
                $blogs = $mysqlClient->prepare("SELECT * FROM blogs WHERE author_id = :author_id ORDER BY id DESC");
                $blogs->execute(['author_id' => $_SESSION['id']]);

                foreach ($blogs as $blog) {
                    $author = $mysqlClient->prepare("SELECT username FROM users WHERE id = :author_id");
                    $author->execute(['author_id' => $blog['author_id']]);
                    $author = $author->fetch()['username'];
                    
                    echo '<div class="post">';
                    echo '<a class="title" href="/blogs/blog?id=' . $blog['id'] . '">' . $blog['title'] . '</a>';
                    if ($blog['edit_date'] == $blog['creation_date']) {
                        echo '<p class="date">'.translate('blogs_my-blogs_created'). $blog['creation_date'] . '</p>';
                    } else {
                        echo '<p class="date">'.translate('blogs_my-blogs_edited'). $blog['edit_date'] . '</p>';
                    }
                    echo '</div>';
                }
            } else {
                echo translate('blogs_my-blogs_logged');
            }

        ?>
    </div>


    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping(translate('blogs_my-blogs_title'), 100, title);
    </script>
</body>
</html>