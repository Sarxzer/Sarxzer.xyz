<?

    require '../src/php/db.php';

    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $tokenQuery = $mysqlClient->prepare('SELECT * FROM users WHERE token = :token');
        $tokenQuery->execute(['token' => $token]);
        $tokenResult = $tokenQuery->fetch();

        if ($tokenResult) {
            $query = $mysqlClient->prepare('UPDATE users SET is_verified = 1, token = null WHERE token = :token');
            $query->execute(['token' => $token]);
            echo 'Account verified';
            echo '<a href="/account/login">Login</a>';
            if (isset($_SESSION['id'])) {
                $_SESSION['email'] = $tokenResult['email'];
            }
            include '../src/php/notify.php';
            createNotification($tokenResult['id'], 'normal', 'Your account has been verified', $mysqlClient);
        } else {
            echo 'Invalid token';
        }
    } else {
        echo 'Invalid token';
    }

?>