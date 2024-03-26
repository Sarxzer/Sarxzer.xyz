<?
    $json = file_get_contents('./src/secret.json');
    $secret = json_decode($json, true)['db'];
    $mysqlClient = new PDO('mysql:host=$secret["host"];dbname=$secret["dbname"];charset=utf8',$secret['user'],$secret['password']);
?>