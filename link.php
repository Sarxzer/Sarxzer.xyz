<?
    include "src/php/db.php";

    if (isset($_GET['id'])) {
        $customLink = $_GET['id'];

        $link = $mysqlClient->prepare('SELECT * FROM links WHERE link = :link');
        $link->execute(['link' => $customLink]);
        $link = $link->fetch();

        if (empty($link)) {
            echo "Link doesn't exist";
            echo "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            echo "Modified URL: " . htmlspecialchars($_SERVER['REQUEST_URI']);
            exit();
        }

        

    } else {
        echo "Need a link id";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><? htmlspecialchars($link['title']) ?></title>
    <meta name="description" content="<? htmlspecialchars($link['description']) ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($link['title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($link['description']); ?>">
</head>
<body>
  
    <?php 
        echo "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    ?>
    <script>
        setTimeout(window.location.href = "<? echo $link['redirected_link'] ?>", 1);
    </script>
</body>
</html>