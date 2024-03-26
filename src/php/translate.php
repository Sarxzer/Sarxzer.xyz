<?  
    $_translateIsLoaded = true;

    $lang = 'en';
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }

    if (isset($_SESSION['lang'])) {
        $lang = $_SESSION['lang'];
    } else {
        $_SESSION['lang'] = $lang;
    }

    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
    }

    if ($lang != 'en' && $lang != 'fr' && $lang != 'llc') {
        $lang = 'en';
    }

    function translate($key) {
        global $lang;
        $translate = include __DIR__ . '/../lang/' . $lang . '.php';

        if (isset($translate[$key])) {
            return $translate[$key];
        } else {
            return $key;
        }
    }




?>