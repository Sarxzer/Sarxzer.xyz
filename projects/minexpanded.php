<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <script src="https://api.sarxzer.xyz/fw/snow/snow.js"></script>
    <link rel="stylesheet" href="https://api.sarxzer.xyz/fw/snow/snow.css">
    <title>Minexpanded | Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <p class="text left">So, Minexpanded is a Minecraft mod based on archaeologie. In this mod, you will be able to mine fossilized bones, get blood from mosquito in ambers and turn everything into dinosaurs.</p>
    <p class="text right">This mod is currently in development, so it's not available for download. But you can join our discord server to get news about the mod.</p>
    <p class="text left">The mod is coded in Java, and we use IntelliJ IDEA to code it. We also use Github to share our code.</p>
    <p class="text right">We are currently 3 developpers, one designer and one 3D designer on this mod. The main developper is <a href="">Dioxyde de Carbone</a>, and the other two are <a href="">yassine</a> and <a href="">Arkane</a>. The designer is <a href="">M0NS</a> and the 3D designer is <a href="">Frarix</a>. <br>I am one of the Game designer and I do a bit of design.</p>
    <p class="text left">We are currently looking for developpers, designers and 3D designers. If you are interested, you can join our discord server and contact us.</p>
    <p class="text right">The mod is available on <a href="http://discord.gg/zPa9WbGzNy">Discord</a>, and we have a <a href="https://github.com/Haze-Studios/minexpanded">Github</a> repository.</p>
    <p class="text left">We also have a <a href="https://www.patreon.com/Minexpanded">Patreon</a> for who want to support us</p>


    <? include '../footer.php'; ?>


    <script>
        //snow(150, 15, 25, 15, 5);
        
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('> Minexpanded', 100, title);
    </script>
</body>
</html>