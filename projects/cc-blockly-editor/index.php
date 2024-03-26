<!DOCTYPE html>
<html>
  <head>
    <? include '../../header.php'; ?>
    <meta charset="utf-8" />
    <title>ComputerCraft : Blockly Editor</title>
    <link rel="icon" href="favicon.ico" />
    <script defer="defer" src="bundle.js"></script>
  </head>
  <body>
    <? include '../../menu.php'; ?>
    <div id="pageContainer">
      <div id="outputPane">
        <pre
          id="generatedCode"
          style="min-height: 50%"
          class="language-lua"
        ><code></code></pre>
        <input
          name="fileName"
          id="fileName"
          value="program"
          style="margin-top: 1.5em"
        />
        <button id="copyButton">Copy</button>
        <button id="downloadButton">Download</button>
        <button id="downloadLuaButton">Download Lua</button>
        <button id="loadButton">Load</button>
      </div>
      <div id="blocklyDiv"></div>
    </div>
  </body>
</html>
