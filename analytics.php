<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
requireLogin();

$category = $_GET['category'] ?? null;
$days = intval($_GET['days'] ?? 30);

$analyticsData = getAnalyticsData($category, $days);
$categories = ['sales', 'traffic', 'users', 'revenue', 'performance', 'engagement'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container">
        <div class="page-header">
            <h1>Analytics Dashboard</h1>
        </div>
        
        <!-- Filters -->
        <div class="card">
            <div class="card-body">
                <form method="GET" class="filters-form">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat; ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Time Period</label>
                        <select name="days" class="form-control">
                            <option value="7" <?php echo $days === 7 ? 'selected' : ''; ?>>Last 7 Days</option>
                            <option value="30" <?php echo $days === 30 ? 'selected' : ''; ?>>Last 30 Days</option>
                            <option value="90" <?php echo $days === 90 ? 'selected' : ''; ?>>Last 90 Days</option>
                            <option value="365" <?php echo $days === 365 ? 'selected' : ''; ?>>Last Year</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="analytics.php" class="btn btn-outline">Clear</a>
                </form>
            </div>
        </div>
        
        <!-- Analytics Data -->
        <div class="card">
            <div class="card-header">
                <h3>Analytics Data</h3>
                <button class="btn btn-sm btn-success" onclick="exportData()">📥 Export</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Metric Name</th>
                                <th>Value</th>
                                <th>Category</th>
                                <th>Period</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($data = mysqli_fetch_assoc($analyticsData)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($data['metric_name']); ?></td>
                                    <td><strong><?php echo formatNumber($data['metric_value'], 2); ?></strong></td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?php echo ucfirst($data['metric_category']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo ucfirst($data['time_period']); ?></td>
                                    <td><?php echo formatDate($data['recorded_date']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function exportData() {
            alert('Export functionality would be implemented here');
        }
    </script>
</body>
</html>
