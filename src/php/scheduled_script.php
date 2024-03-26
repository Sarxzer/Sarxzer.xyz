<!--Auto delete unverified account after 30 day-->
<?

    require '/home/sarxzerwebsite/www/src/php/db.php';
    require '/home/sarxzerwebsite/www/src/php/mailer.php';

    $mails = [];

    $query = $mysqlClient->prepare('SELECT * FROM users WHERE is_verified = 0 OR is_verified IS NULL');
    $query->execute();
    $unverifiedUsers = $query->fetchAll();

    $json = file_get_contents('./src/secret.json');
    $secret = json_decode($json, true)['encrypt']['mail'];

    foreach ($unverifiedUsers as $user) {
        if (time() - strtotime($user['created_at']) > 2592000) {
            $backup = $mysqlClient->prepare('INSERT INTO deleted_users (id, username, email, password, birthday, country, website, bio, created_at) VALUES (:id, :username, :email, :password, :birthday, :country, :website, :bio, :created_at)');
            $backup->execute([
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => openssl_decrypt($user['email'], $secret['algo'], base64_decode($secret['key']), 0, $secret['iv']),
                'password' => $user['password'],
                'birthday' => $user['birthday'],
                'country' => $user['country'],
                'website' => $user['website'],
                'bio' => $user['bio'],
                'created_at' => $user['created_at']
            ]);
            $deleteQuery = $mysqlClient->prepare('DELETE FROM users WHERE id = :id');
            $deleteQuery->execute(['id' => $user['id']]);

            $mails[] = 'User ' . $user['username'] . 'was deleted because he didn\'t verified his account<br>';
        }
    }


    $query = $mysqlClient->prepare('SELECT * FROM users');
    $query->execute();
    $users = $query->fetchAll();

    $query = $mysqlClient->prepare("SELECT * FROM datas WHERE `name` = 'users_number'");
    $query->execute();
    $usersNumber = $query->fetchAll();

    
    $usersNumber = $usersNumber[0]['value'];


    $mails[] = 'Users: ' . count($users) . '<br>';

    $usersCount = (count($users) - $usersNumber);
    if ($usersCount > 0) {
        $mails[] = $usersCount . ' new users';
    } else if ($usersCount < 0) {
        $mails[] = abs($usersCount) .  ' gone users';
    } else {
        $mails[] = 'No new or gone user';
    }

    $mysqlClient->query("UPDATE `datas` SET `value` = '" . count($users) . "' WHERE `datas`.`name` = 'users_number'");

    #Recap mail
    $body = '';
    if (!empty($mails)) {
        $body = implode($mails);
    } else {
        $body = 'Error';
    }


    sendMail('nathan@sarxzer.xyz', 'Scheduled Script Recap', $body, 'scheduled_script');

?>