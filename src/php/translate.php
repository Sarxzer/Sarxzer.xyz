<?  
    $_translateIsLoaded = true;

    $lang = 'en';

    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }

    if (isset($_GET['lang'])) {
        $lang = $_GET['lang'];
    }

    if (isset($_SESSION['lang']) && !isset($_GET['lang'])) {
        $lang = $_SESSION['lang'];
    } else {
        $_SESSION['lang'] = $lang;
    }

    if ($lang != 'en' && $lang != 'fr' && $lang != 'llc' && $lang != 'frmd') {
        $lang = 'llc';
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

    function translateDate($date) {
        global $lang;
        $translate = include __DIR__ . '/../lang/' . $lang . '.php';

        $months = $translate['months'];
        $days = $translate['days'];

        $date = explode(' ', $date);
        $weekDay = $date[0];
        $day = $date[1];
        $month = $date[2];
        $year = $date[3];

        return $days[$weekDay] . ' ' . $day . ' ' . $months[$month] . ' ' . $year;
    }