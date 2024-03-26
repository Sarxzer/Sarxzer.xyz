<?
    $_tempJson = file_get_contents(__DIR__ . '/../secret.json');
    $_tempSecret = json_decode($_tempJson, true)['db'];
    $mysqlClient = new PDO('mysql:host=' . $_tempSecret["host"] . ';dbname=' . $_tempSecret["dbname"] . ';charset=utf8',$_tempSecret['user'],$_tempSecret['password']);
?>