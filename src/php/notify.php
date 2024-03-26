<?

    $_isLoaded = true;

    function createNotification($userId, $type, $message, $mysqlClient) {
        $notificationQuery = $mysqlClient->prepare("INSERT INTO notifications (user_id, type, message) VALUES (:user_id, :type, :message)");
        $notificationQuery->execute([
            'user_id' => $userId, 
            'type' => $type, 
            'message' => $message
        ]);
    }

    function createNotificationForAll($type, $message, $mysqlClient) {
        $usersQuery = $mysqlClient->prepare("SELECT * FROM users");
        $usersQuery->execute();
        $users = $usersQuery->fetchAll();

        foreach ($users as $user) {
            createNotification($user['id'], $type, $message, $mysqlClient);
        }
    }

    function createNotificationForRole($role, $type, $message, $mysqlClient) {
        $usersQuery = $mysqlClient->prepare("SELECT * FROM users WHERE role = :role");
        $usersQuery->execute(['role' => $role]);
        $users = $usersQuery->fetchAll();

        foreach ($users as $user) {
            createNotification($user['id'], $type, $message, $mysqlClient);
        }
    }

    function createNotificationForBlog($blogId, $type, $message, $mysqlClient) {
        $blogQuery = $mysqlClient->prepare("SELECT * FROM blogs WHERE id = :id");
        $blogQuery->execute(['id' => $blogId]);
        $blog = $blogQuery->fetch();

        if ($blog) {
            createNotification($blog['author_id'], $type, $message, $mysqlClient);
        }
    }

    function createNotificationForComment($commentId, $type, $message, $mysqlClient) {
        $commentQuery = $mysqlClient->prepare("SELECT * FROM comments WHERE id = :id");
        $commentQuery->execute(['id' => $commentId]);
        $comment = $commentQuery->fetch();

        if ($comment) {
            createNotification($comment['author_id'], $type, $message, $mysqlClient);
        }
    }

    function getNotifications($userId, $mysqlClient) {
        $notificationsQuery = $mysqlClient->prepare("SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC");
        $notificationsQuery->execute(['user_id' => $userId]);
        $notifications = $notificationsQuery->fetchAll();

        return $notifications;
    }

    function getNotificationsForAll($mysqlClient) {
        $notificationsQuery = $mysqlClient->prepare("SELECT * FROM notifications");
        $notificationsQuery->execute();
        $notifications = $notificationsQuery->fetchAll();

        return $notifications;
    }

    function countNotifications($userId, $mysqlClient) {
        $notificationsQuery = $mysqlClient->prepare("SELECT * FROM notifications WHERE user_id = :user_id");
        $notificationsQuery->execute(['user_id' => $userId]);
        $notifications = $notificationsQuery->fetchAll();

        return count($notifications);
    }

    function countNotificationsForAll($mysqlClient) {
        $notificationsQuery = $mysqlClient->prepare("SELECT * FROM notifications");
        $notificationsQuery->execute();
        $notifications = $notificationsQuery->fetchAll();

        return count($notifications);
    }

    function countUnreadNotifications($userId, $mysqlClient) {
        $notificationsQuery = $mysqlClient->prepare("SELECT * FROM notifications WHERE user_id = :user_id AND (`read_statut` = 'false' OR `read_statut` IS NULL OR `read_statut` = '0')");
        $notificationsQuery->execute(['user_id' => $userId]);
        $notifications = $notificationsQuery->fetchAll();

        return count($notifications);
    }

    function deleteNotification($notificationId, $mysqlClient) {
        $notificationQuery = $mysqlClient->prepare("SELECT * FROM notifications WHERE id = :id");
        $notificationQuery->execute(['id' => $notificationId]);
        $notification = $notificationQuery->fetch();

        if ($notification) {
            $deleteQuery = $mysqlClient->prepare("DELETE FROM notifications WHERE id = :id");
            $deleteQuery->execute(['id' => $notificationId]);
        }
    }

    function deleteUserNotifications($userId, $mysqlClient) {
        $notificationsQuery = $mysqlClient->prepare("SELECT * FROM notifications WHERE user_id = :user_id");
        $notificationsQuery->execute(['user_id' => $userId]);
        $notifications = $notificationsQuery->fetchAll();

        foreach ($notifications as $notification) {
            deleteNotification($notification['id'], $mysqlClient);
        }
    }

    function deleteAllNotifications($mysqlClient) {
        $notificationsQuery = $mysqlClient->prepare("SELECT * FROM notifications");
        $notificationsQuery->execute();
        $notifications = $notificationsQuery->fetchAll();

        foreach ($notifications as $notification) {
            deleteNotification($notification['id'], $mysqlClient);
        }
    }

    function deleteNotificationForBlog($blogId, $mysqlClient) {
        $blogQuery = $mysqlClient->prepare("SELECT * FROM blogs WHERE id = :id");
        $blogQuery->execute(['id' => $blogId]);
        $blog = $blogQuery->fetch();

        if ($blog) {
            $deleteQuery = $mysqlClient->prepare("DELETE FROM notifications WHERE user_id = :user_id AND type = 'blog' AND message = :message");
            $deleteQuery->execute(['user_id' => $blog['author_id'], 'message' => 'Blog ' . $blog['title'] . ' has been deleted']);
        }
    }

    function deleteNotificationForComment($commentId, $mysqlClient) {
        $commentQuery = $mysqlClient->prepare("SELECT * FROM comments WHERE id = :id");
        $commentQuery->execute(['id' => $commentId]);
        $comment = $commentQuery->fetch();

        if ($comment) {
            $deleteQuery = $mysqlClient->prepare("DELETE FROM notifications WHERE user_id = :user_id AND type = 'comment' AND message = :message");
            $deleteQuery->execute(['user_id' => $comment['author_id'], 'message' => 'Comment ' . $comment['content'] . ' has been deleted']);
        }
    }

    function readNotification($notificationId, $mysqlClient) {
        $notificationQuery = $mysqlClient->prepare("SELECT * FROM notifications WHERE id = :id");
        $notificationQuery->execute(['id' => $notificationId]);
        $notification = $notificationQuery->fetch();

        if ($notification) {
            $readQuery = $mysqlClient->prepare("UPDATE notifications SET `read_statut` = 'true' WHERE id = :id");
            $readQuery->execute(['id' => $notificationId]);
        }
    }

    function readUserNotifications($userId, $mysqlClient) {
        $notificationsQuery = $mysqlClient->prepare("SELECT * FROM notifications WHERE user_id = :user_id");
        $notificationsQuery->execute(['user_id' => $userId]);
        $notifications = $notificationsQuery->fetchAll();

        foreach ($notifications as $notification) {
            readNotification($notification['id'], $mysqlClient);
        }
    }

    function readAllNotifications($mysqlClient) {
        $notificationsQuery = $mysqlClient->prepare("SELECT * FROM notifications");
        $notificationsQuery->execute();
        $notifications = $notificationsQuery->fetchAll();

        foreach ($notifications as $notification) {
            readNotification($notification['id'], $mysqlClient);
        }
    }
?>