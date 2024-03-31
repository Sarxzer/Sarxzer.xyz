<?php
    include '../src/php/db.php';

    if (isset($_POST['add'])) {
        $query = $mysqlClient->prepare('SELECT COUNT(*) FROM baffe');
        $query->execute();
        $count = $query->fetchColumn();
        $count++;
        $query = $mysqlClient->prepare('INSERT INTO baffe (reason) VALUES (?)');
        $query->execute([$_POST['reason']]);
        
        // Create an array with the data you want to output
        $output = array(
            'count' => $count,
            'reason' => $_POST['reason']
        );

        // Convert the array to a JSON string
        echo json_encode($output);
    } else {
        header('Location: /');
    }
?>