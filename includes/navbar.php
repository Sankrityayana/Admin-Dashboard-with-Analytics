<?php
$unreadCount = getUnreadNotificationCount($_SESSION['user_id']);
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<script>
(function(){
    var t = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', t);
})();
</script>
<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="navbar-brand">
        <div class="navbar-brand-icon">📊</div>
        <a href="index.php"><?php echo SITE_NAME; ?></a>
    </div>

    <ul class="navbar-menu">
        <li><a href="index.php" class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">Dashboard</a></li>
        <li><a href="analytics.php" class="<?php echo $currentPage === 'analytics.php' ? 'active' : ''; ?>">Analytics</a></li>
        <?php if (isAdmin()): ?>
            <li><a href="users.php" class="<?php echo $currentPage === 'users.php' ? 'active' : ''; ?>">Users</a></li>
        <?php endif; ?>
        <li><a href="reports.php" class="<?php echo $currentPage === 'reports.php' ? 'active' : ''; ?>">Reports</a></li>
        <li><a href="activity.php" class="<?php echo $currentPage === 'activity.php' ? 'active' : ''; ?>">Activity</a></li>
        <?php if (isAdmin()): ?>
            <li><a href="settings.php" class="<?php echo $currentPage === 'settings.php' ? 'active' : ''; ?>">Settings</a></li>
        <?php endif; ?>
    </ul>

    <div class="navbar-actions">
        <button class="theme-toggle" id="themeToggle" title="Toggle light/dark mode" onclick="toggleTheme()">🌙</button>
        <a href="notifications.php" class="notif-btn" aria-label="Notifications">
            🔔
            <?php if ($unreadCount > 0): ?>
                <span class="notif-badge"><?php echo $unreadCount; ?></span>
            <?php endif; ?>
        </a>
        <div class="navbar-divider"></div>
        <div class="user-pill">
            <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?></div>
            <div class="user-info">
                <div class="user-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></div>
                <div class="user-role-tag"><?php echo ucfirst(str_replace('_', ' ', $_SESSION['role'])); ?></div>
            </div>
        </div>
        <a href="logout.php" class="btn btn-outline btn-sm">Sign out</a>
    </div>
</nav>
<script>
function toggleTheme() {
    var html = document.documentElement;
    var current = html.getAttribute('data-theme') || 'light';
    var next = current === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    var btn = document.getElementById('themeToggle');
    if (btn) btn.textContent = next === 'dark' ? '☀️' : '🌙';
}
// Set correct icon on load
(function(){
    var t = document.documentElement.getAttribute('data-theme');
    var btn = document.getElementById('themeToggle');
    if (btn) btn.textContent = t === 'dark' ? '☀️' : '🌙';
})();
</script>
