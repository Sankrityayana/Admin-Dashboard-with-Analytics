<?php
$unreadCount = getUnreadNotificationCount($_SESSION['user_id']);
?>
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <span class="logo">📊</span>
            <a href="index.php"><?php echo SITE_NAME; ?></a>
        </div>
        
        <ul class="navbar-menu">
            <li><a href="index.php" class="active">Dashboard</a></li>
            <li><a href="analytics.php">Analytics</a></li>
            <?php if (isAdmin()): ?>
                <li><a href="users.php">Users</a></li>
            <?php endif; ?>
            <li><a href="reports.php">Reports</a></li>
            <li><a href="activity.php">Activity</a></li>
            <?php if (isAdmin()): ?>
                <li><a href="settings.php">Settings</a></li>
            <?php endif; ?>
        </ul>
        
        <div class="navbar-actions">
            <div class="notification-icon">
                <a href="notifications.php">
                    🔔
                    <?php if ($unreadCount > 0): ?>
                        <span class="badge"><?php echo $unreadCount; ?></span>
                    <?php endif; ?>
                </a>
            </div>
            
            <div class="user-menu">
                <span class="user-name"><?php echo $_SESSION['full_name']; ?></span>
                <span class="user-role badge badge-<?php echo getRoleBadge($_SESSION['role']); ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $_SESSION['role'])); ?>
                </span>
                <a href="logout.php" class="btn btn-outline btn-sm">Logout</a>
            </div>
        </div>
    </div>
</nav>
