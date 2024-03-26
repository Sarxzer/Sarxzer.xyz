<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <script src="https://api.sarxzer.xyz/fw/snow/snow.js"></script>
    <link rel="stylesheet" href="https://api.sarxzer.xyz/fw/snow/snow.css">
    <title>Free Fontawesome | Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <p class="text right small">Last edit :  <span id="date"></span> ago</p>

    <p class="text left long">So, as the title said, I made a version of Font Awesome 6 pro free for all.<br>I actually use in this website.<br>So, there is the tutorial to install it :</p>
    <p class="text right long">First, copy this</p>
    <pre class="right"><code class="language-html">&lt;link rel="stylesheet" href="https://api.sarxzer.xyz/fw/fontawesome/css/all.css"&gt;</code></pre>
    <p class="text left long">Then, paste it in the head of your html file</p>
    <p class="text right long">And that's it ! <br>You can now use Font Awesome 6 pro for free !</p>

    <p class="text right">If you want to see the github repository, click <a href="https://github.com/sarxzer/fontawesome/">here</a></p>

    <p class="text left small">You are free to use it, but please consider subscribing to support the developer and creator.</p>
    
    
    <? include '../footer.php'; ?>


    <script>
        snow(150, 15, 25, 15, 5);
        
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('Free pro version of Fontawesome', 100, title);

        document.getElementById("date").textContent = timeSince('2022-11-18','auto')
    </script>
</body>
</html>