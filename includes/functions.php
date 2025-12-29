<?php
// Security Functions
function escape($str) {
    global $conn;
    return mysqli_real_escape_string($conn, $str);
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Authentication Functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function isSuperAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === ROLE_SUPER_ADMIN;
}

function isAdmin() {
    return isset($_SESSION['role']) && ($_SESSION['role'] === ROLE_SUPER_ADMIN || $_SESSION['role'] === ROLE_ADMIN);
}

function isModerator() {
    return isset($_SESSION['role']) && in_array($_SESSION['role'], [ROLE_SUPER_ADMIN, ROLE_ADMIN, ROLE_MODERATOR]);
}

function requireSuperAdmin() {
    requireLogin();
    if (!isSuperAdmin()) {
        header('Location: index.php');
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: index.php');
        exit;
    }
}

function requireModerator() {
    requireLogin();
    if (!isModerator()) {
        header('Location: index.php');
        exit;
    }
}

// User Functions
function getUserById($userId) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function getAllUsers($status = null, $role = null) {
    global $conn;
    $sql = "SELECT * FROM users WHERE 1=1";
    
    if ($status) {
        $sql .= " AND status = '" . escape($status) . "'";
    }
    if ($role) {
        $sql .= " AND role = '" . escape($role) . "'";
    }
    
    $sql .= " ORDER BY created_at DESC";
    return mysqli_query($conn, $sql);
}

function createUser($data) {
    global $conn;
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password, full_name, role, status) 
                                    VALUES (?, ?, ?, ?, ?, ?)");
    
    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
    
    mysqli_stmt_bind_param($stmt, "ssssss", 
        $data['username'], $data['email'], $hashedPassword, $data['full_name'], 
        $data['role'], $data['status']
    );
    
    return mysqli_stmt_execute($stmt);
}

function updateUser($userId, $data) {
    global $conn;
    
    if (!empty($data['password'])) {
        $stmt = mysqli_prepare($conn, "UPDATE users SET username=?, email=?, password=?, full_name=?, 
                                        role=?, status=? WHERE id=?");
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        mysqli_stmt_bind_param($stmt, "ssssssi", 
            $data['username'], $data['email'], $hashedPassword, $data['full_name'], 
            $data['role'], $data['status'], $userId
        );
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET username=?, email=?, full_name=?, 
                                        role=?, status=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssssi", 
            $data['username'], $data['email'], $data['full_name'], 
            $data['role'], $data['status'], $userId
        );
    }
    
    return mysqli_stmt_execute($stmt);
}

function deleteUser($userId) {
    global $conn;
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    return mysqli_stmt_execute($stmt);
}

function updateLastLogin($userId) {
    global $conn;
    $stmt = mysqli_prepare($conn, "UPDATE users SET last_login = NOW() WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    return mysqli_stmt_execute($stmt);
}

function getUserStats() {
    global $conn;
    $sql = "SELECT 
            COUNT(*) as total_users,
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_users,
            SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_users,
            SUM(CASE WHEN status = 'suspended' THEN 1 ELSE 0 END) as suspended_users,
            SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as new_today,
            SUM(CASE WHEN DATE(last_login) = CURDATE() THEN 1 ELSE 0 END) as logged_in_today
            FROM users";
    
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

// Analytics Functions
function getAnalyticsData($category = null, $days = 7) {
    global $conn;
    $sql = "SELECT * FROM analytics WHERE recorded_date >= DATE_SUB(CURDATE(), INTERVAL $days DAY)";
    
    if ($category) {
        $sql .= " AND metric_category = '" . escape($category) . "'";
    }
    
    $sql .= " ORDER BY recorded_date DESC, id DESC";
    return mysqli_query($conn, $sql);
}

function getAnalyticsByMetric($metricName, $days = 30) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT * FROM analytics 
                                    WHERE metric_name = ? 
                                    AND recorded_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                                    ORDER BY recorded_date ASC");
    mysqli_stmt_bind_param($stmt, "si", $metricName, $days);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function addAnalyticsData($data) {
    global $conn;
    $stmt = mysqli_prepare($conn, "INSERT INTO analytics (metric_name, metric_value, metric_category, 
                                    time_period, recorded_date, recorded_time) 
                                    VALUES (?, ?, ?, ?, ?, ?)");
    
    mysqli_stmt_bind_param($stmt, "sdssss", 
        $data['metric_name'], $data['metric_value'], $data['metric_category'], 
        $data['time_period'], $data['recorded_date'], $data['recorded_time']
    );
    
    return mysqli_stmt_execute($stmt);
}

function getLatestMetricValue($metricName) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT metric_value FROM analytics 
                                    WHERE metric_name = ? 
                                    ORDER BY recorded_date DESC, id DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $metricName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row ? $row['metric_value'] : 0;
}

function getDashboardStats() {
    global $conn;
    
    $stats = [];
    
    // Get today's sales
    $result = mysqli_query($conn, "SELECT SUM(metric_value) as total FROM analytics 
                                    WHERE metric_category = 'sales' 
                                    AND recorded_date = CURDATE()");
    $row = mysqli_fetch_assoc($result);
    $stats['today_sales'] = $row['total'] ?? 0;
    
    // Get monthly revenue
    $result = mysqli_query($conn, "SELECT SUM(metric_value) as total FROM analytics 
                                    WHERE metric_category = 'revenue' 
                                    AND MONTH(recorded_date) = MONTH(CURDATE())");
    $row = mysqli_fetch_assoc($result);
    $stats['monthly_revenue'] = $row['total'] ?? 0;
    
    // Get today's traffic
    $result = mysqli_query($conn, "SELECT SUM(metric_value) as total FROM analytics 
                                    WHERE metric_name = 'Page Views' 
                                    AND recorded_date = CURDATE()");
    $row = mysqli_fetch_assoc($result);
    $stats['today_traffic'] = $row['total'] ?? 0;
    
    // Get active users
    $stats['active_users'] = getLatestMetricValue('Active Users');
    
    // Get new users today
    $result = mysqli_query($conn, "SELECT SUM(metric_value) as total FROM analytics 
                                    WHERE metric_name = 'New Users' 
                                    AND recorded_date = CURDATE()");
    $row = mysqli_fetch_assoc($result);
    $stats['new_users_today'] = $row['total'] ?? 0;
    
    // Get average response time
    $result = mysqli_query($conn, "SELECT AVG(metric_value) as avg FROM analytics 
                                    WHERE metric_name = 'Avg Response Time' 
                                    AND recorded_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
    $row = mysqli_fetch_assoc($result);
    $stats['avg_response_time'] = $row['avg'] ?? 0;
    
    // Get server uptime
    $stats['server_uptime'] = getLatestMetricValue('Server Uptime');
    
    // Get bounce rate
    $stats['bounce_rate'] = getLatestMetricValue('Bounce Rate');
    
    return $stats;
}

// Activity Log Functions
function logActivity($userId, $activityType, $description) {
    global $conn;
    $ip = getClientIP();
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    $stmt = mysqli_prepare($conn, "INSERT INTO activity_logs (user_id, activity_type, activity_description, 
                                    ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issss", $userId, $activityType, $description, $ip, $userAgent);
    return mysqli_stmt_execute($stmt);
}

function getActivityLogs($limit = 50, $userId = null, $activityType = null) {
    global $conn;
    $sql = "SELECT al.*, u.username, u.full_name 
            FROM activity_logs al 
            LEFT JOIN users u ON al.user_id = u.id 
            WHERE 1=1";
    
    if ($userId) {
        $sql .= " AND al.user_id = " . intval($userId);
    }
    if ($activityType) {
        $sql .= " AND al.activity_type = '" . escape($activityType) . "'";
    }
    
    $sql .= " ORDER BY al.created_at DESC LIMIT " . intval($limit);
    return mysqli_query($conn, $sql);
}

function getRecentActivity($limit = 10) {
    return getActivityLogs($limit);
}

// Settings Functions
function getSetting($key) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT setting_value FROM settings WHERE setting_key = ?");
    mysqli_stmt_bind_param($stmt, "s", $key);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row ? $row['setting_value'] : null;
}

function updateSetting($key, $value, $userId) {
    global $conn;
    $stmt = mysqli_prepare($conn, "UPDATE settings SET setting_value = ?, updated_by = ? WHERE setting_key = ?");
    mysqli_stmt_bind_param($stmt, "sis", $value, $userId, $key);
    return mysqli_stmt_execute($stmt);
}

function getAllSettings($category = null) {
    global $conn;
    $sql = "SELECT * FROM settings";
    
    if ($category) {
        $sql .= " WHERE setting_category = '" . escape($category) . "'";
    }
    
    $sql .= " ORDER BY setting_category, setting_key";
    return mysqli_query($conn, $sql);
}

// Notification Functions
function createNotification($userId, $type, $title, $message, $actionUrl = null) {
    global $conn;
    $stmt = mysqli_prepare($conn, "INSERT INTO notifications (user_id, notification_type, title, message, action_url) 
                                    VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issss", $userId, $type, $title, $message, $actionUrl);
    return mysqli_stmt_execute($stmt);
}

function getUserNotifications($userId, $unreadOnly = false) {
    global $conn;
    $sql = "SELECT * FROM notifications WHERE user_id = ?";
    
    if ($unreadOnly) {
        $sql .= " AND is_read = FALSE";
    }
    
    $sql .= " ORDER BY created_at DESC LIMIT 20";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function markNotificationAsRead($notificationId) {
    global $conn;
    $stmt = mysqli_prepare($conn, "UPDATE notifications SET is_read = TRUE WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $notificationId);
    return mysqli_stmt_execute($stmt);
}

function getUnreadNotificationCount($userId) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = FALSE");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

// Report Functions
function getAllReports($type = null, $generatedBy = null) {
    global $conn;
    $sql = "SELECT r.*, u.full_name as generated_by_name 
            FROM reports r 
            LEFT JOIN users u ON r.generated_by = u.id 
            WHERE 1=1";
    
    if ($type) {
        $sql .= " AND r.report_type = '" . escape($type) . "'";
    }
    if ($generatedBy) {
        $sql .= " AND r.generated_by = " . intval($generatedBy);
    }
    
    $sql .= " ORDER BY r.created_at DESC";
    return mysqli_query($conn, $sql);
}

function createReport($data) {
    global $conn;
    $stmt = mysqli_prepare($conn, "INSERT INTO reports (report_name, report_type, report_format, 
                                    date_from, date_to, generated_by, status) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    mysqli_stmt_bind_param($stmt, "sssssss", 
        $data['report_name'], $data['report_type'], $data['report_format'], 
        $data['date_from'], $data['date_to'], $data['generated_by'], $data['status']
    );
    
    return mysqli_stmt_execute($stmt);
}

// Utility Functions
function formatNumber($number, $decimals = 0) {
    return number_format($number, $decimals);
}

function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

function formatDate($date) {
    return date('M d, Y', strtotime($date));
}

function formatDateTime($datetime) {
    return date('M d, Y H:i', strtotime($datetime));
}

function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return $diff . ' seconds ago';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' days ago';
    } else {
        return formatDateTime($datetime);
    }
}

function getRoleBadge($role) {
    $badges = [
        'super_admin' => 'danger',
        'admin' => 'warning',
        'moderator' => 'info',
        'user' => 'secondary'
    ];
    return isset($badges[$role]) ? $badges[$role] : 'secondary';
}

function getStatusBadge($status) {
    $badges = [
        'active' => 'success',
        'inactive' => 'secondary',
        'suspended' => 'danger',
        'pending' => 'warning',
        'processing' => 'info',
        'completed' => 'success',
        'failed' => 'danger'
    ];
    return isset($badges[$status]) ? $badges[$status] : 'secondary';
}

function getNotificationIcon($type) {
    $icons = [
        'info' => '📘',
        'success' => '✅',
        'warning' => '⚠️',
        'error' => '❌',
        'system' => '⚙️'
    ];
    return isset($icons[$type]) ? $icons[$type] : '📌';
}

function getActivityIcon($type) {
    $icons = [
        'login' => '🔐',
        'logout' => '🚪',
        'create' => '➕',
        'update' => '✏️',
        'delete' => '🗑️',
        'view' => '👁️',
        'export' => '📤',
        'import' => '📥',
        'system' => '⚙️'
    ];
    return isset($icons[$type]) ? $icons[$type] : '📋';
}
?>
