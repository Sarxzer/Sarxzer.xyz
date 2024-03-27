<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ubuntu|Ubuntu:b|Ubuntu:i|Ubuntu:500">
<link rel="stylesheet" href="/src/css/style.css">
<link rel="stylesheet" href="https://sarxzer.github.io/fontawesome/Font%20Awesome%20v6.2.0/css/all.css">
<link href="/src/css/prism.css" rel="stylesheet" />
<link rel="shortcut icon" href="/src/svg/logo.svg" type="image/x-icon">
<script src="/src/js/script.js"></script>
<script>
;(function () {
    var src = '//cdn.jsdelivr.net/npm/eruda';
    if (!/eruda=true/.test(window.location) && localStorage.getItem('active-eruda') != 'true') return;
    document.write('<scr' + 'ipt src="' + src + '"></scr' + 'ipt>');
    document.write('<scr' + 'ipt>eruda.init();</scr' + 'ipt>');
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/eruda-monitor@1.0.0"></script>
<? 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_POST['lang'])) {
        $_SESSION['lang'] = $_POST['lang'];
        $url = $_SERVER['REQUEST_URI'];
        $url = preg_replace('/(\?|&)lang=[^&]*(&|$)/', '$1', $url);

        header('Location: ' . $url);
        exit();
    }

    if (!isset($_translateIsLoaded)) {
        include __DIR__ . '/src/php/translate.php';
    } 

?>