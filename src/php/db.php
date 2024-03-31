<?
    include __DIR__ . '/../secret.php';

    $_tempSecret = $secret['db'];

    $mysqlClient = new PDO('mysql:host=' . $_tempSecret["host"] . ';dbname=' . $_tempSecret["dbname"] . ';charset=utf8',$_tempSecret['user'],$_tempSecret['password']);
?>