<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <script src="https://api.sarxzer.xyz/fw/snow/snow.js"></script>
    <link rel="stylesheet" href="https://api.sarxzer.xyz/fw/snow/snow.css">
    <title>Magic Datapack | Sarxzer</title>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <p class="text left">So, as the title said, I started a magic themed datapack for minecraft.<br> This is a project that I always wanted to do since I started an advanced skyblock with custom machineries on Minecraft Bedrock.</p>

    <p class="text right">This datapack is made for Minecraft Java Edition 1.20, and is still in development. I will post updates on my <a href="https://discord.gg/Q8r8YvWTxn">discord</a> server.</p>

    <p class="text left">The datapack is made with VSCode and the <a href="https://marketplace.visualstudio.com/items?itemName=SPGoding.datapack-language-server">Datapack Helper Plus</a> extension. I also use <a href="https://marketplace.visualstudio.com/items?itemName=HugeBlack.mcfdebugger">Debugger for MC Function</a> for the function debuging.</p>

    <p class="text right">I also use <a href="https://misode.github.io/" target="_blank">Misode's Datapack Generator</a> to generate the datapack json files.</p>

    <p class="text left">The datapack isn't available for download yet, but you can check the <a href="https://github.com/Sarxzer/magic-datapack">github repository</a> to see the progress.</p>


    <? include '../footer.php'; ?>


    <script>
        //snow(150, 15, 25, 15, 5);
        
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('> Magic Datapack', 100, title);
    </script>
</body>
</html>