<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <script src="https://api.sarxzer.xyz/fw/snow/snow.js"></script>
    <link rel="stylesheet" href="https://api.sarxzer.xyz/fw/snow/snow.css">
    <title>Projects | Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <p class="text left">Those are all my finished projects.</p>

    <div class="project-list">
        <div class="project-card">
            <a href="/projects/free-fa" class="long card">
                > Free pro version of <br> Fontawesome
                <img src="/src/img/fa-pro-logo.png" alt="Free Fontawesome Logo">
            </a>
        </div>
        <div class="project-card">
            <a href="/projects/sarxzerxyz" class="card">
                > sarxzer.xyz
                <img src="/src/svg/logo.svg" alt="My website logo">
            </a>
        </div>
        <div class="project-card">
            <a href="/projects/minexpanded" class="card">
                > Minexpanded
                <img src="/src/img/minexpanded-logo.png" alt="Minexpanded logo">
            </a>
        </div>
        <div class="project-card">
            <a href="/projects/magic-datapack" class="long card">
                > Magic Datapack
                <img src="/src/img/magic-book.png" alt="Magic Datapack logo">
            </a>
        </div>
        <div class="project-card">
            <a href="/projects/fiverr" class="card">
                > My Fiverr.
                <img src="/src/img/logo-fiverr.png" alt="Fiverr logo">
            </a>
        </div>
        <div class="project-card">
            <a href="/projects/pass-gen" class="card">
                > Password Generator
                <img src="/src/img/pass-gen.png" alt="Password Generator">
            </a>
        </div>
    </div>


    <? include '../footer.php'; ?>


    <script>
        //snow(150, 15, 25, 15, 5);
        
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('Projects', 100, title);
    </script>
</body>
</html>