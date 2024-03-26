<!DOCTYPE html>
<html lang="en">
<head>
    <? include '../header.php'; ?>
    <?
        if (!isset($_SESSION['id'])) {
            header('Location: /');
        }

        include '../src/php/db.php';
        include '../src/php/notify.php';

        $notifications = countNotifications($_SESSION['id'], $mysqlClient);

        echo '<title>'.translate('account_notification_title');

        if ($notifications !== 0) {
            echo '"' . $notifications . '" |Sarxzer</title>';
        } else {
            echo ' | Sarxzer</title>';
        }

        readUserNotifications($_SESSION['id'], $mysqlClient);
    ?>
</head>
<body>

    <? include '../menu.php'; ?>


    <h1 class="title small" id="title">.</h1>

    <?

        $notifications = getNotifications($_SESSION['id'], $mysqlClient);

        if (count($notifications) === 0) {
            echo '<p class="text">'.translate('account_notification_no_notif').'</p>';
        } else {
            foreach ($notifications as $notification) {
                echo '<div class="notification ' . $notification['type'] . '">';
                if ($notification['type'] == 'normal') {
                    echo '<i class="fa-solid fa-bell"></i>';
                } else if ($notification['type'] == 'warning') {
                    echo '<i class="fa-solid fa-bell-on"></i>';
                } else if ($notification['type'] == 'error') {
                    echo '<i class="fa-solid fa-bell-exclamation"></i>';
                }
                echo '<p class="message">' . $notification['message'] . '</p>';
                echo '<p class="date">' . $notification['created_at'] . '</p>';
                echo '</div>';
            }
        }


    ?>


    <? include '../footer.php'; ?>


    <script>
        const title = document.getElementById('title');
        simulateDeleting(100, title);
        simulateTyping("<? echo translate('account_notification_page_title'); ?>", 100, title);
    </script>
</body>
</html>