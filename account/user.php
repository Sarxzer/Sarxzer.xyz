<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <?

        require '../src/php/db.php';

        if (isset($_GET['id'])) {
            $pageUser = $mysqlClient->prepare("SELECT * FROM users WHERE id = :id");
            $pageUser->execute(['id' => $_GET['id']]);
            $pageUser = $pageUser->fetch();

            if (!$pageUser) {
                header('Location: /account/login');
            }

            if (isset($_SESSION['id'])) {
                $user = $mysqlClient->prepare("SELECT * FROM users WHERE id = :id");
                $user->execute(['id' => $_SESSION['id']]);
                $user = $user->fetch();
            }
            echo '<title>User"' . $pageUser['username'] . '" | Sarxzer</title>';
        } else if (isset($_SESSION['id'])) {
            if ($_SESSION['id'] == $_GET['id']) {
                $user = $mysqlClient->prepare("SELECT * FROM users WHERE id = :id");
                $user->execute(['id' => $_SESSION['id']]);
                $user = $user->fetch();
                $pageUser = $user;
        
                echo '<title>User"' . $user['username'] . '" | Sarxzer</title>';
            } else {
                header('Location: /account/user?id=' . $_SESSION['id']);
            }
        } else {
            header('Location: /account/login');
        }

        include '../src/secret.php';

        $secret = $secret['encrypt']['mail'];
        
    ?>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <div class="user-infos">
        <h2 class="user-info-title"><? echo translate('account_user_infos_title'); ?></h2>
        <?
            if (isset($_SESSION['id'])) {
                if ($user['role'] == 'admin') {
                    echo '<p class="user-info">'.translate('account_user_infos_id'). $pageUser['id'] . '</p>';
                    echo '<p class="user-info">'.translate('account_user_infos_username'). $pageUser['username'] . '</p>';
                    echo '<p class="user-info">'.translate('account_user_infos_email'). openssl_decrypt($pageUser['email'], $secret['algo'], base64_decode($secret['key']), 0, $secret['iv']) . '</p>';
                    echo '<p class="user-info">'.translate('account_user_infos_role'). $pageUser['role'] . '</p>';

                    if (isset($pageUser['website'])) {
                        echo '<p class="user-info">'.translate('account_user_infos_website').'<a href="' . $pageUser['website'] . '">' . $pageUser['website'] . '</a></p>';
                    }
                    echo '<p class="user-info">'.translate('account_user_infos_creation_date'). $pageUser['created_at'] . '</p>';
                    echo '<p class="user-info">'.translate('account_user_infos_verified'). $pageUser['is_verified'] . '</p>';

                    if (isset($pageUser['bio'])) {
                        echo '<h2 class="user-info-title">'.translate('account_user_infos_bio').'</h2>';

                        echo '<p class="bio">' . $pageUser['bio'] . '</p>';
                    }
                } else {
                    echo '<p class="user-info">'.translate('account_user_infos_username'). $pageUser['username'] . '</p>';
                    if (isset($pageUser['website'])) {
                        echo '<p class="user-info">'.translate('account_user_infos_website').'<a href="' . $pageUser['website'] . '">' . $pageUser['website'] . '</a></p>';
                    }
                    echo '<p class="user-info">'.translate('account_user_infos_creation_date'). $pageUser['created_at'] . '</p>';

                    if (isset($pageUser['bio'])) {
                        echo '<h2 class="user-info-title">'.translate('account_user_infos_bio').'</h2>';

                        echo '<p class="bio">' . $pageUser['bio'] . '</p>';
                    }
                }
            } else {
                echo '<p class="user-info">'.translate('account_user_infos_username'). $pageUser['username'] . '</p>';
                if (isset($pageUser['website'])) {
                    echo '<p class="user-info">'.translate('account_user_infos_website').'<a href="' . $pageUser['website'] . '">' . $pageUser['website'] . '</a></p>';
                }
                echo '<p class="user-info">'.translate('account_user_infos_creation_date'). $pageUser['created_at'] . '</p>';

                if (isset($pageUser['bio'])) {
                    echo '<h2 class="user-info-title">'.translate('account_user_infos_bio').'</h2>';

                    echo '<p class="bio">' . $pageUser['bio'] . '</p>';
                }
            }

            
            if (isset($_SESSION['id'])) {
                if ($_SESSION['id'] == $_GET['id']) {
                    echo '<a class="button" href="/account#userinfos">'.translate('account_user_infos_edit').'</a>';
                } else if ($user['role'] == 'admin') {
                    echo '<a class="button" href="/admin/edit-user?id=' . $_GET['id'] . '">'.translate('account_user_infos_edit').'</a>';
                }
            }

        ?>

        <h2 class="blogs-title"><? echo translate('account_user_blogs_title'); ?></h2>

        <div class="blogs">

            <?
                
                $blogs = $mysqlClient->prepare("SELECT * FROM blogs WHERE author_id = :author_id ORDER BY id DESC");
                $blogs->execute(['author_id' => $_GET['id']]);
                $blogs = $blogs->fetchAll();

                foreach ($blogs as $blog) {
                    echo '<div class="post">';
                    echo '<a class="title" href="/blogs/blog?id=' . $blog['id'] . '">' . $blog['title'] . '</a>';
                    $editDate = new DateTime($blog['edit_date']);
                    $creationDate = new DateTime($blog['creation_date']);
                    if ($blog['edit_date'] != $blog['creation_date']) {
                        echo '<p class="date">' . $editDate->format('Y-m-d') . '</p>';
                    } else {
                        echo '<p class="date">' . $creationDate->format('Y-m-d') . '</p>';
                    }
                    echo '</div>';
                }
            ?>
        </div>
    </div>

    <? include '../footer.php'; ?>


    <?
        echo '<script>';
        echo 'const title = document.getElementById(\'title\');';
        echo 'simulateDeleting(100, title);';
        echo 'simulateTyping(\'' . $pageUser['username'] . '\', 100, title);';
        echo '</script>';
    ?>
</body>
</html>