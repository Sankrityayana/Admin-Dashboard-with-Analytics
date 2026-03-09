<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
requireLogin();

$dashboardStats = getDashboardStats();
$userStats = getUserStats();
$recentActivity = getRecentActivity(8);
$salesData = getAnalyticsByMetric('Daily Sales', 7);
$trafficData = getAnalyticsByMetric('Page Views', 7);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <main class="container">
        <header class="page-header">
            <div>
                <h1>👋 Welcome back, <span><?php echo htmlspecialchars($_SESSION['full_name']); ?></span></h1>
                <p>Here's what's happening with your dashboard today.</p>
            </div>
            <div class="page-header-actions">
                <span class="current-time" id="currentTime" aria-label="Current time"></span>
            </div>
        </header>

        <section aria-label="Statistics" class="stats-grid">
                <div class="stat-card stat-blue">
                    <div class="stat-icon-wrap">💰</div>
                    <div class="stat-info">
                        <h3><?php echo formatCurrency($dashboardStats['today_sales']); ?></h3>
                        <p>Today's Sales</p>
                        <span class="stat-trend trend-up">↑ +12.5%</span>
                    </div>
                </div>
                <div class="stat-card stat-green">
                    <div class="stat-icon-wrap">📈</div>
                    <div class="stat-info">
                        <h3><?php echo formatCurrency($dashboardStats['monthly_revenue']); ?></h3>
                        <p>Monthly Revenue</p>
                        <span class="stat-trend trend-up">↑ +8.3%</span>
                    </div>
                </div>
                <div class="stat-card stat-purple">
                    <div class="stat-icon-wrap">👥</div>
                    <div class="stat-info">
                        <h3><?php echo formatNumber($dashboardStats['active_users']); ?></h3>
                        <p>Active Users</p>
                        <span class="stat-trend trend-up">↑ +5.2%</span>
                    </div>
                </div>
                <div class="stat-card stat-orange">
                    <div class="stat-icon-wrap">👤</div>
                    <div class="stat-info">
                        <h3><?php echo formatNumber($dashboardStats['new_users_today']); ?></h3>
                        <p>New Users Today</p>
                        <span class="stat-trend trend-up">↑ +15</span>
                    </div>
                </div>
                <div class="stat-card stat-cyan">
                    <div class="stat-icon-wrap">🌐</div>
                    <div class="stat-info">
                        <h3><?php echo formatNumber($dashboardStats['today_traffic']); ?></h3>
                        <p>Today's Traffic</p>
                        <span class="stat-trend trend-up">↑ +6.8%</span>
                    </div>
                </div>
                <div class="stat-card stat-yellow">
                    <div class="stat-icon-wrap">⚡</div>
                    <div class="stat-info">
                        <h3><?php echo formatNumber($dashboardStats['avg_response_time'], 1); ?>ms</h3>
                        <p>Avg Response Time</p>
                        <span class="stat-trend trend-down">↓ -18ms</span>
                    </div>
                </div>
                <div class="stat-card stat-pink">
                    <div class="stat-icon-wrap">📊</div>
                    <div class="stat-info">
                        <h3><?php echo formatNumber($dashboardStats['bounce_rate'], 1); ?>%</h3>
                        <p>Bounce Rate</p>
                        <span class="stat-trend trend-down">↓ -2.3%</span>
                    </div>
                </div>
                <div class="stat-card stat-success">
                    <div class="stat-icon-wrap">✅</div>
                    <div class="stat-info">
                        <h3><?php echo formatNumber($dashboardStats['server_uptime'], 2); ?>%</h3>
                        <p>Server Uptime</p>
                        <span class="stat-trend trend-stable">● Stable</span>
                    </div>
                </div>
        </section>

            <section aria-label="Charts" class="charts-row">
            <div class="card">
                    <div class="card-header">
                        <h3 class="text-primary"><span class="card-header-icon">📈</span> Sales Overview</h3>
                        <span class="badge badge-primary">Last 7 Days</span>
                    </div>
                    <div class="card-body">
                        <div class="chart-canvas-wrap">
                            <canvas id="salesChart" aria-label="Sales chart"></canvas>
                        </div>
                    </div>
                </div>
            <div class="card">
                    <div class="card-header">
                        <h3 class="text-purple"><span class="card-header-icon">🌐</span> Traffic Analytics</h3>
                        <span class="badge badge-purple">Last 7 Days</span>
                    </div>
                    <div class="card-body">
                        <div class="chart-canvas-wrap">
                            <canvas id="trafficChart" aria-label="Traffic chart"></canvas>
                        </div>
                    </div>
                </div>
        </section>

        <section class="bottom-row">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-success"><span class="card-header-icon">👥</span> User Statistics</h3>
                        <a href="users.php" class="btn btn-sm btn-ghost">View All →</a>
                    </div>
                    <div class="card-body">
                        <div class="user-stats-grid">
                            <div class="user-stat-item"><div class="stat-label">Total Users</div><div class="stat-value"><?php echo formatNumber($userStats['total_users']); ?></div></div>
                            <div class="user-stat-item"><div class="stat-label">Active</div><div class="stat-value text-success"><?php echo formatNumber($userStats['active_users']); ?></div></div>
                            <div class="user-stat-item"><div class="stat-label">Inactive</div><div class="stat-value text-secondary"><?php echo formatNumber($userStats['inactive_users']); ?></div></div>
                            <div class="user-stat-item"><div class="stat-label">Suspended</div><div class="stat-value text-danger"><?php echo formatNumber($userStats['suspended_users']); ?></div></div>
                            <div class="user-stat-item"><div class="stat-label">New Today</div><div class="stat-value text-primary"><?php echo formatNumber($userStats['new_today']); ?></div></div>
                            <div class="user-stat-item"><div class="stat-label">Logged In Today</div><div class="stat-value text-info"><?php echo formatNumber($userStats['logged_in_today']); ?></div></div>
                        </div>
                    </div>
                </div>
            <div class="card">
                    <div class="card-header">
                        <h3 class="text-warning"><span class="card-header-icon">⚡</span> Recent Activity</h3>
                        <a href="activity.php" class="btn btn-sm btn-ghost">View All →</a>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            <?php while ($activity = mysqli_fetch_assoc($recentActivity)): ?>
                                <div class="activity-item">
                                    <div class="activity-icon"><?php echo getActivityIcon($activity['activity_type']); ?></div>
                                    <div class="activity-content">
                                        <p><?php echo htmlspecialchars($activity['activity_description']); ?></p>
                                        <small><?php echo htmlspecialchars($activity['full_name'] ?? 'System'); ?> • <?php echo timeAgo($activity['created_at']); ?></small>
                                    </div>
                                    <span class="badge badge-<?php echo getStatusBadge($activity['activity_type']); ?>"><?php echo ucfirst($activity['activity_type']); ?></span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
        </section>
    </main>
    
    <script src="js/dashboard.js"></script>
    <script>
        // Current time display
        function updateTime() {
            const now = new Date();
            document.getElementById('currentTime').textContent = now.toLocaleString();
        }
        updateTime();
        setInterval(updateTime, 1000);
        
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        document.getElementById('salesChart').style.height = '100%';
        const salesData = <?php 
            $dates = [];
            $values = [];
            mysqli_data_seek($salesData, 0);
            while ($row = mysqli_fetch_assoc($salesData)) {
                $dates[] = date('M d', strtotime($row['recorded_date']));
                $values[] = $row['metric_value'];
            }
            echo json_encode(['dates' => array_reverse($dates), 'values' => array_reverse($values)]);
        ?>;
        
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesData.dates,
                datasets: [{
                    label: 'Sales',
                    data: salesData.values,
                    borderColor: '#00d4ff',
                    backgroundColor: 'rgba(0, 212, 255, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#e0e0e0' }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#e0e0e0' }
                    },
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#e0e0e0' }
                    }
                }
            }
        });
        
        // Traffic Chart
        const trafficCtx = document.getElementById('trafficChart').getContext('2d');
        const trafficData = <?php 
            $dates = [];
            $values = [];
            mysqli_data_seek($trafficData, 0);
            while ($row = mysqli_fetch_assoc($trafficData)) {
                $dates[] = date('M d', strtotime($row['recorded_date']));
                $values[] = $row['metric_value'];
            }
            echo json_encode(['dates' => array_reverse($dates), 'values' => array_reverse($values)]);
        ?>;
        
        new Chart(trafficCtx, {
            type: 'bar',
            data: {
                labels: trafficData.dates,
                datasets: [{
                    label: 'Page Views',
                    data: trafficData.values,
                    backgroundColor: '#bb86fc',
                    borderColor: '#bb86fc',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#e0e0e0' }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#e0e0e0' }
                    },
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#e0e0e0' }
                    }
                }
            }
        });
    </script>
</body>
</html>
