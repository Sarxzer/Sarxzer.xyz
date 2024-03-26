<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <script src="https://api.sarxzer.xyz/fw/snow/snow.js"></script>
    <link rel="stylesheet" href="https://api.sarxzer.xyz/fw/snow/snow.css">
    <title>Fiverr | Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title" id="title">.</h1>

    <p class="text left">Actually, I am selling my work on Fiverr. If you want a website, I can make you one. Just go check my <a href="https://www.fiverr.com/sarxzer">Fiverr</a> to see my price.</p>
    <p class="text right">I am selling website to pay me my new computer to do a dream, make my own video game. But my computer is not capable to handle most of game making IDE like Unity.</p>

    
    <? include '../footer.php'; ?>


    <script>
        //snow(150, 15, 25, 15, 5);
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('My Fiverr.', 100, title);
    </script>
</body>
</html>