<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
requireLogin();

$activityLogs = getActivityLogs(100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Activity Log</h1>
            <button class="btn btn-outline" onclick="exportLogs()">📥 Export Logs</button>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="activity-list">
                    <?php while ($activity = mysqli_fetch_assoc($activityLogs)): ?>
                        <div class="activity-item">
                            <div class="activity-icon"><?php echo getActivityIcon($activity['activity_type']); ?></div>
                            <div class="activity-content">
                                <p><?php echo htmlspecialchars($activity['activity_description']); ?></p>
                                <small>
                                    <strong><?php echo htmlspecialchars($activity['full_name'] ?? 'System'); ?></strong> • 
                                    <?php echo timeAgo($activity['created_at']); ?> • 
                                    IP: <?php echo htmlspecialchars($activity['ip_address']); ?>
                                </small>
                            </div>
                            <span class="badge badge-<?php echo getStatusBadge($activity['activity_type']); ?>">
                                <?php echo ucfirst($activity['activity_type']); ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function exportLogs() {
            alert('Export logs functionality would be implemented here');
        }
    </script>
</body>
</html>
