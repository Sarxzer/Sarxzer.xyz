<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title>title | Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>


    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('base page', 100, title);
    </script>
</body>
</html>