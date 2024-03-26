<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <script src="https://api.sarxzer.xyz/fw/snow/snow.js"></script>
    <link rel="stylesheet" href="https://api.sarxzer.xyz/fw/snow/snow.css">
    <title>sarxzer.xyz | Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>

    <h1 class="title small" id="title">.</h1>

    <p class="text left">Sarxzer.xyz is actually this website. This is my personal website and my portfolio. I code everything by myself without those website builder.</p>
    <p class="text right">My website is coded in html, and in scss, because css is ugly and... we can get lost easily. <br>Because I'm poor, I use a free hoster named Alwaysdata.</p>
    <p class="text left">Another version of my website is available at <a href="https://sarxzer.me">sarxzer.me</a> hosted on github. I didn't continue to host it in github because I wanted a custom <a href="/azerty" class="hidden">error404</a> page and we can't on github. So I paid 11€ for 2-years for my domain name, sarxzer.xyz.</p>

    
    <? include '../footer.php'; ?>


    <script>
        //snow(150, 15, 25, 15, 5);
        
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('sarxzer.xyz', 100, title);
    </script>
</body>
</html>