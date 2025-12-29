<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
requireLogin();

$notifications = getUserNotifications($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Notifications</h1>
            <button class="btn btn-outline" onclick="markAllAsRead()">✓ Mark All as Read</button>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="notifications-list">
                    <?php while ($notif = mysqli_fetch_assoc($notifications)): ?>
                        <div class="notification-item <?php echo $notif['is_read'] ? 'read' : 'unread'; ?>">
                            <div class="notification-icon"><?php echo getNotificationIcon($notif['notification_type']); ?></div>
                            <div class="notification-content">
                                <h4><?php echo htmlspecialchars($notif['title']); ?></h4>
                                <p><?php echo htmlspecialchars($notif['message']); ?></p>
                                <small><?php echo timeAgo($notif['created_at']); ?></small>
                            </div>
                            <span class="badge badge-<?php echo getStatusBadge($notif['notification_type']); ?>">
                                <?php echo ucfirst($notif['notification_type']); ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function markAllAsRead() {
            alert('Mark all notifications as read');
        }
    </script>
</body>
</html>
