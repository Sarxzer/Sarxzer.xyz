<?
include '../src/php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $message = $_POST['message'];

    $stmt = $mysqlClient->prepare('INSERT INTO messages (user_id, message) VALUES (?, ?)');
    $stmt->execute([$username, $message]);
} else {
    $stmt = $mysqlClient->prepare('SELECT * FROM messages ORDER BY timestamp DESC');
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);
}