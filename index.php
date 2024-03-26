<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="title" content="Sarxzer Portfolio">
        <meta name="description" content="This is my website. It's also a portfolio.">
        <meta name="keywords" content="Sarxzer, Sarxzer.xyz, Sarxzer website, Sarxzer portfolio, Sarxzer projects, Sarxzer github, Sarxzer discord">
        <meta name="google-site-verification" content="NU_OQdulJfsicIYCMxJ9WOx5OavzlG3k_djD3QK00t8">
        <script src="https://api.sarxzer.xyz/fw/snow/snow.js"></script>
        <link rel="stylesheet" href="https://api.sarxzer.xyz/fw/snow/snow.css">

        <? include 'header.php'; ?>


        <title><? echo translate('index_title') ?></title>
    </head>
    <body>

        <? include 'menu.php'; ?>
        
        
        <h1 class="title" id="title">.</h1>

        <? 
            if (isset($_SESSION['username'])) {
                echo '<p class="text right">' . translate('index_welcome_back') . $_SESSION['username'] . '.</p>';
            }
        ?>
        <p class="text left"><? echo translate('index_my_description_1') ?><span id="age"></span><? echo translate('index_my_description_2') ?></p>
        <p class="text left"><? echo translate('index_my_description_3') ?></p>
        <p class="text right"><? echo translate('index_my_description_4') ?></p>

        <p class="text left"><? echo translate('index_my_description_5') ?></p>
        <p class="text right"><? echo translate('index_my_description_6') ?></p>


        <h2 class="project-title left"><a href="/projects/" class="hidden"><? echo translate('index_my_projects') ?></a></h2>
        <div class="scrolling-list">
            <div class="scrolling-project-card">
                <a href="/projects/free-fa" class="long card">
                    <? echo translate('index_free_fa') ?>
                    <img src="/src/img/fa-pro-logo.png" alt="Free fontawesome logo">
                </a>
            </div>
            <div class="scrolling-project-card">
                <a href="/projects/sarxzerxyz" class="card">
                    <? echo translate('index_sarxzerxyz') ?>
                    <img src="/src/svg/logo.svg" alt="My website logo">
                </a>
            </div>
            <div class="scrolling-project-card">
                <a href="/projects/minexpanded" class="card">
                    <? echo translate('index_minexpanded') ?>
                    <img src="/src/img/minexpanded-logo.png" alt="Minexpanded logo">
                </a>
            </div>
            <div class="scrolling-project-card">
                <a href="/projects/magic-datapack" class="long card">
                    <? echo translate('index_magic_datapack') ?>
                    <img src="/src/img/magic-book.png" alt="Magic Datapack logo">
                </a>
            </div>
            <div class="scrolling-project-card">
                <a href="/projects/fiverr" class="card">
                    <? echo translate('index_fiverr') ?>
                    <img src="/src/img/logo-fiverr.png" alt="Fiverr logo">
                </a>
            </div>
            <div class="scrolling-project-card">
                <a href="/projects/pass-gen" class="long card">
                    <? echo translate('index_pass_gen') ?>
                    <img src="/src/png/pass-gen-logo.png" alt="Password Generator">
                </a>
            </div>
        </div>

        <? include 'footer.php'; ?>

        <script>
            //snow(150, 15, 25, 15, 5);

            document.getElementById('age').textContent = timeSince('2007-05-26', 'years')
            const title = document.getElementById('title');
            simulateDeleting(100, title);
            simulateTyping('<? echo translate('index_title') ?> ', 100, title);
        </script>
    </body>
</html>
