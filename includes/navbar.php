<?php
$unreadCount = getUnreadNotificationCount($_SESSION['user_id']);
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="navbar-brand">
        <span class="navbar-logo-icon">📊</span>
        <a href="index.php"><?php echo SITE_NAME; ?></a>
    </div>

    <ul class="navbar-menu">
        <li><a href="index.php" class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>"><span class="nav-icon">📊</span> Dashboard</a></li>
        <li><a href="analytics.php" class="<?php echo $currentPage === 'analytics.php' ? 'active' : ''; ?>"><span class="nav-icon">📈</span> Analytics</a></li>
        <?php if (isAdmin()): ?>
            <li><a href="users.php" class="<?php echo $currentPage === 'users.php' ? 'active' : ''; ?>"><span class="nav-icon">👥</span> Users</a></li>
        <?php endif; ?>
        <li><a href="reports.php" class="<?php echo $currentPage === 'reports.php' ? 'active' : ''; ?>"><span class="nav-icon">📄</span> Reports</a></li>
        <li><a href="activity.php" class="<?php echo $currentPage === 'activity.php' ? 'active' : ''; ?>"><span class="nav-icon">⚡</span> Activity</a></li>
        <?php if (isAdmin()): ?>
            <li><a href="settings.php" class="<?php echo $currentPage === 'settings.php' ? 'active' : ''; ?>"><span class="nav-icon">⚙️</span> Settings</a></li>
        <?php endif; ?>
    </ul>

    <div class="navbar-actions">
        <a href="notifications.php" class="notif-btn" aria-label="Notifications">
            🔔
            <?php if ($unreadCount > 0): ?>
                <span class="notif-badge"><?php echo $unreadCount; ?></span>
            <?php endif; ?>
        </a>
        <div class="user-pill">
            <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?></div>
            <div class="user-info">
                <div class="user-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></div>
                <div class="user-role-tag"><?php echo ucfirst(str_replace('_', ' ', $_SESSION['role'])); ?></div>
            </div>
        </div>
        <a href="logout.php" class="btn btn-ghost btn-sm">Logout</a>
    </div>
</nav>
