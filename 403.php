<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error403</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ubuntu|Ubuntu:b|Ubuntu:i|Ubuntu:500">
    <link rel="stylesheet" href="/src/css/style.css">
    <link rel="shortcut icon" href="/src/svg/logo.svg" type="image/x-icon">
    <script src="/src/js/script.js"></script>
</head>
<body>
    <h1 class="title error" id="title">.</h1>

    <p class="text left long error">> Error403 : Access Refused</p>
    <p class="text left long error">> you need admin privilege</p>
    <a class="left error" onclick="history.back()">> go back</a>
    <br><br>
    <a href="/" class="left error">> go index</a>
    

    

    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('> Access Refused', 100, title);
    </script>
</body>
</html>