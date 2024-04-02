<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <?

        require '../src/php/db.php';
        $blog = $mysqlClient->prepare("SELECT * FROM blogs WHERE id = :id");
        $blog->execute(['id' => $_GET['id']]);
        $blog = $blog->fetch();


        if (empty($blog)) {
            header('Location: /blogs/');
        } else {
            echo '<title>'.translate('blogs_blog_title').'"' . $blog['title'] . '" | Sarxzer</title>';
        }

    ?>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <div class="blog-post">
        <?

            require '../src/php/Parsedown.php';
            require '../src/php/script.php';

            $Parsedown = new Parsedown();

            if (isset($_SESSION['id'])) {
                $userQuery = $mysqlClient->prepare("SELECT * FROM users WHERE id = :id");
                $userQuery->execute(['id' => $_SESSION['id']]);
                $user = $userQuery->fetch();
            } else {
                $user = null;
            }

            $authorQuery = $mysqlClient->prepare("SELECT username FROM users WHERE id = :author_id");
            $authorQuery->execute(['author_id' => $blog['author_id']]);
            $authorResult = $authorQuery->fetch();

            if ($authorResult) {
                $author = $authorResult['username'];
            } else {
                $author = translate('blogs_blog_post_unknown');
            }

            
            if (isset($_SESSION['id'])) {
                if ($_SESSION['id'] == $blog['author_id']) {
                    echo '<p class="text right">'.translate('blogs_blog_post_by_you').'</p>';
                } else if ($author == translate('blogs_blog_post_unknown')) {
                    echo '<p class="text right">'.translate('blogs_blog_post_by') . $author . '</p>';
                } else {
                    echo '<p class="text right"><a href="/account/user?id=' . $blog['author_id'] . '" class="author">'.translate('blogs_blog_post_by') . $author . '</a></p>';
                }
            } else if ($author == translate('blogs_blog_post_unknown')) {
                echo '<p class="text right">'.translate('blogs_blog_post_by') . $author . '</p>';
            } else {
                echo '<p class="text right"><a href="/account/user?id=' . $blog['author_id'] . '" class="author">'.translate('blogs_blog_post_by') . $author . '</a></p>';
            }

            if ($blog['creation_date'] != $blog['edit_date']) {
                echo '<p class="text right">' . translate('blogs_blog_post_edited_on') . translateDate(date_format(date_create($blog['edit_date']), 'l d F Y')) . translate('blogs_blog_post_at') . date_format(date_create($blog['edit_date']), 'H:i') . '</p>';
            } else {
                echo '<p class="text right">'. translate('blogs_blog_post_created_on') . date_format(date_create($blog['creation_date']), 'l d F Y') . translate('blogs_blog_post_at') . date_format(date_create($blog['creation_date']), 'H:i') . '</p>';
            }
            echo '<div class="content">' . $Parsedown->text(htmlspecialcharsExceptFencedCodeBlocks($blog['content'])) . '</div>';
            

            if (isset($_SESSION['id'])) {
                if ($_SESSION['id'] == $blog['author_id'] || $user['role'] == 'admin') {
                    echo '<a href="/blogs/edit?id=' . $blog['id'] . '" class="btn">'.translate('blogs_blog_post_edit').'</a>';
                    echo '    <a href="/blogs/delete?id=' . $blog['id'] . '" class="btn">'.translate('blogs_blog_post_delete').'</a>';
                }
            }
        
        ?>
    </div>

    <h2>> Comments</h2>
    
    
    <div class="comment-form">
        <form action="" method="post">
            <input type="text" name="content" id="content" placeholder="Comment" class="input">
            <input type="text" name="honeypot" id="honeypot" style="display: none;">
            <button type="submit" name="submit" class="submit"><i class="fa-solid fa-paper-plane-top"></i></button>
            <div class="multilines">
                <input type="checkbox" name="multilines" id="multilines">
                <label for="multilines"><i class="fa-solid fa-text"></i></label>
            </div>
        </form>
    </div>

    <?

        if (isset($_POST['submit'])) {
            if (isset($_SESSION['id'])) {
                    if (empty($_POST['honeypot'])) {
                        if (isset($_SESSION['last_comment_time']) || $_SESSION['last_comment_time'] < time() - 10) {
                            $content = $_POST['content'];
                            if (empty($content)) {
                                echo '<p class="text">'.translate('blogs_blog_comment_please_fill').'</p>';
                            } else {
                                $insertQuery = $mysqlClient->prepare("INSERT INTO comments (blog_id, author_id, content) VALUES (:blog_id, :author_id, :content)");
                                $insertQuery->execute([
                                    'blog_id' => $blog['id'],
                                    'author_id' => $_SESSION['id'],
                                    'content' => $content
                                ]);
                            
                                $_SESSION['last_comment_time'] = time();

                                createNotificationForBlog($blog['id'], translate('blogs_blog_comment_normal'), translate('blogs_blog_comment_your_blog'), $mysqlClient);
                            
                            }
                        } else {
                            echo '<p class="text">'.translate('blogs_blog_comment_fast').'</p>';
                        }
                    } else {
                        echo '<p class="text">'.translate('blogs_blog_comment_bot').'</p>';
                    }
            } else {
                echo '<p class="text">'.translate('blogs_blog_comment_logged').'</p>';
            }
        }
    ?>

    <div class="comment-zone">
        <?

            if (isset($_POST['delete'])) {

                $commentQuery = $mysqlClient->prepare("SELECT * FROM comments WHERE id = :id");
                $commentQuery->execute(['id' => $_POST['comment_id']]);
                $comment = $commentQuery->fetch();
                if (empty($comment)) {
                    header('Location: /blogs/blog?id=' . $blog['id']);
                }
                if (isset($_SESSION['id'])) {
                    if ($_SESSION['id'] == $comment['author_id'] || $user['role'] == 'admin') {
                        $deleteQuery = $mysqlClient->prepare("UPDATE comments SET `state` = 'deleted' WHERE id = :id");
                        $deleteQuery->execute(['id' => $_POST['comment_id']]);
                    }
                }

            }

            $commentsQuery = $mysqlClient->prepare("SELECT * FROM comments WHERE blog_id = :blog_id AND state = 'normal' ORDER BY created_at DESC");
            $commentsQuery->execute(['blog_id' => $blog['id']]);
            $comments = $commentsQuery->fetchAll();

            if (empty($comments)) {
                echo '<p class="text">'.translate('blogs_blog_zone_no_comments').'</p>';
            } else {
                foreach ($comments as $comment) {
                    $authorQuery = $mysqlClient->prepare("SELECT username FROM users WHERE id = :author_id");
                    $authorQuery->execute(['author_id' => $comment['author_id']]);
                    $authorResult = $authorQuery->fetch();

                    if ($authorResult) {
                        $author = $authorResult['username'];
                    } else {
                        $author = translate('blogs_blog_post_unknown');
                    }

                    echo '<div class="comment">';
                    echo '<div class="header">';
                    if (isset($_SESSION['id'])) {
                        if ($_SESSION['id'] == $comment['author_id']) {
                            echo '<p class="author">'.translate('blogs_blog_post_by_you').'</p>';
                        } else if ($author == translate('blogs_blog_post_unknown')) {
                            echo '<p class="author">'.translate('blogs_blog_post_by') . $author . '</p>';
                        } else {
                            echo '<p class="author"><a href="/account/user?id=' . $comment['author_id'] . '" class="author">' .translate('blogs_blog_post_by') . $author . '</a></p>';
                        }
                    } else if ($author == translate('blogs_blog_post_unknown')) {
                        echo '<p class="author">' .translate('blogs_blog_post_by') . $author . '</p>';
                    } else {
                        echo '<p class="author"><a href="/account/user?id=' . $comment['author_id'] . '" class="author">'.translate('blogs_blog_post_by') . $author . '</a></p>';
                    }

                    echo '<p class="date">' . $comment['created_at'] . '</p>';
                    
                    echo '</div>';
                    echo '<div class="content">' . $Parsedown->text(htmlspecialcharsExceptFencedCodeBlocks($comment['content'])) . '</div>';

                    if (isset($_SESSION['id'])) {
                        if ($_SESSION['id'] == $comment['author_id'] || $user['role'] == 'admin') {
                            echo '<form action="" method="post">';
                            echo '<input type="text" name="comment_id" value="' . $comment['id'] . '" style="display: none;">';
                            echo '<button type="submit" name="delete" class="delete-button"><i class="fa-solid fa-trash-can"></i></button>';
                            echo '</form>';
                        }
                    }
                    echo '</div>';
                }
            }

        ?>
    </div>

    <? include '../footer.php'; ?>

    <?
        echo '<script>';
        echo 'const title = document.getElementById(\'title\');';
        echo 'simulateDeleting(100, title);';
        echo 'simulateTyping(\'' . $blog['title'] . '\', 100, title);';
        echo '</script>';
    ?>

    <script>
        $('#multilines').change(function() {
            if(this.checked) {
                var content = $('#content').val();
                $('#content').replaceWith('<textarea id="content">' + content + '</textarea>');
            } else {
                var content = $('#content').val();
                $('#content').replaceWith('<input type="text" name="content" id="content" placeholder="Comment" class="input" value="' + content + '">');
            }
        });
    </script>

</body>
</html>