<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <title>Baffe | Sarxzer</title>

    <?
        if (isset($_SESSION['id'])) {
            if ($_SESSION['id'] !== 37 && $_SESSION['id'] !== 26) {
                header('Location: /');
            } elseif ($_SESSION['id'] === 26) {
                echo '<style>button {display: none;} textarea {display: none;} </style>';
            }
        } else {
            header('Location: /');
        }
        include '../src/php/db.php';
        $query = $mysqlClient->prepare('SELECT COUNT(*) FROM baffe');
        $query->execute();
        $count = $query->fetchColumn();
    ?>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <h2 style="text-align: center;">Baffe pour Sarxzer : <span id="count"><? echo $count; ?></span></h2>
    <textarea name="reasonText" id="reasonText" cols="30" rows="10" placeholder="Raison"></textarea>
    <button style="margin-left: 50%; transform: translateX(-50%);" onclick="addHit()">Ajouter une baffe</button>

    <div id="reason">
        <?
            $query = $mysqlClient->prepare('SELECT * FROM baffe');
            $query->execute();
            $reasons = $query->fetchAll();
            foreach ($reasons as $index => $reason) {
                echo $index+1 . '. ' . $reason['reason'] . '<br>';
            }
        ?>
    </div>


    <? include '../footer.php'; ?>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function addHit() {
            $.ajax({
                url: 'addHits.php',
                type: 'POST',
                data: {
                    add: true,
                    reason: $('#reasonText').val()
                },
                dataType: 'json',
                success: function(data) {
                    $('#count').text(data.count);
                    $('#reason').append(data.count + '. ' + data.reason + '<br>');
                }
            });
        }
    </script>

        
    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping('Baffe', 100, title);
    </script>
</body>
</html>