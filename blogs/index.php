<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title><? echo translate('blogs_index_title'); ?> | Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <div class="post-search">
        <form action="" method="get">
            <div class="name">
                <label for="search"><? echo translate('blogs_index_post_search'); ?></label>
                <input type="text" name="search" id="search" placeholder="<? echo translate('blogs_index_post_search_blog'); ?>" value="<? echo isset($_GET['search']) ? $_GET['search'] : ''?>">
            </div>
            <div class="author">
                <label for="author"><? echo translate('blogs_index_post_author'); ?></label>
                <input type="text" name="author" id="author" placeholder="<? echo translate('blogs_index_post_search_author'); ?>" value="<? echo isset($_GET['author']) ? $_GET['author'] : ''?>">
            </div>
            <select name="date-order" id="date-order">
                <option value="newest" <? echo isset($_GET['date-order']) && $_GET['date-order'] == 'newest' ? 'selected' : (!isset($_GET['date-order']) || empty($_GET['date-order']) ? '' : 'selected') ?>><? echo translate('blogs_index_newest'); ?></option>
                <option value="oldest" <? echo isset($_GET['date-order']) && $_GET['date-order'] == 'oldest' ? 'selected' : '' ?>><? echo translate('blogs_index_oldest'); ?></option>
            </select>
            <input class="submit" type="submit" value=<? echo translate('blogs_index_search'); ?> name="submit_search">
        </form>
    </div>
    <div class="blogs">
        <?

            require '../src/php/db.php';

            if (isset($_GET['submit_search'])) {
                $dateOrder = $_GET['date-order'];
                if ($dateOrder == 'newest') {
                    $dateOrder = 'DESC';
                } else if ($dateOrder == 'oldest') {
                    $dateOrder = 'ASC';
                }

                if (isset($_GET['author']) && !empty($_GET['author'])) {
                    $author = $_GET['author'];
                    $authorQuery = $mysqlClient->prepare("SELECT id FROM users WHERE username = :username");
                    $authorQuery->execute(['username' => $author]);
                    $author = $authorQuery->fetch();
                } else {
                    $author = null;
                }

                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $search = $_GET['search'];
                } else {
                    $search = null;
                }

                if ($search !== null && $author !== null) {
                    $blogsQuery = $mysqlClient->prepare("SELECT * FROM blogs WHERE title LIKE :title AND author_id = :author_id ORDER BY id $dateOrder");
                    $blogsQuery->execute(['title' => '%' . $search . '%', 'author_id' => $author['id']]);
                    $blogs = $blogsQuery->fetchAll();
                } else if ($search !== null && $author == null) {
                    $blogsQuery = $mysqlClient->prepare("SELECT * FROM blogs WHERE title LIKE :title ORDER BY id $dateOrder");
                    $blogsQuery->execute(['title' => '%' . $search . '%']);
                    $blogs = $blogsQuery->fetchAll();
                } else if ($search == null && $author !== null) {
                    $blogsQuery = $mysqlClient->prepare("SELECT * FROM blogs WHERE author_id = :author_id ORDER BY id $dateOrder");
                    $blogsQuery->execute(['author_id' => $author['id']]);
                    $blogs = $blogsQuery->fetchAll();
                } else {
                    $blogsQuery = $mysqlClient->prepare("SELECT * FROM blogs ORDER BY id $dateOrder");
                    $blogsQuery->execute();
                    $blogs = $blogsQuery->fetchAll();
                }

            } else {
                $blogsQuery = $mysqlClient->prepare("SELECT * FROM blogs ORDER BY id DESC");
                $blogsQuery->execute();
                $blogs = $blogsQuery->fetchAll();
            }

            foreach ($blogs as $blog) {
                $authorQuery = $mysqlClient->prepare("SELECT username FROM users WHERE id = :author_id");
                $authorQuery->execute(['author_id' => $blog['author_id']]);
                $authorResult = $authorQuery->fetch();

                $author = $authorResult ? $authorResult['username'] : 'Unknown';

                $post = '<div class="post">';
                $post .= '<a class="title" href="blog?id=' . $blog['id'] . '">' . $blog['title'] . '</p>';

                $isAuthorUnknown = $author == 'Unknown';
                $isUserLoggedIn = isset($_SESSION['id']);
                $isUserAuthor = $isUserLoggedIn && $_SESSION['id'] == $blog['author_id'];

                if ($isUserAuthor) {
                    $post .= '<a class="author" href="/account/user?id=' . $blog['author_id'] . '">'.translate('blogs_index_by_you').'</a>';
                } elseif ($isAuthorUnknown) {
                    $post .= '<a class="author" title="A deleted user">'.translate('blogs_index_by'). $author . '</a>';
                } else {
                    $post .= '<a href="/account/user?id=' . $blog['author_id'] . '" class="author">'.translate('blogs_index_by'). $author . '</a>';
                }

                $post .= '</div>';

                echo $post;
            }

        ?>
    </div>


    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping(<? echo translate('blogs_index_title'); ?>, 100, title);
    </script>
</body>
</html>