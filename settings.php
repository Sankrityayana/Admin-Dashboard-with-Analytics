<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
requireAdmin();

$message = '';
$settings = getAllSettings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['setting'] as $key => $value) {
        updateSetting($key, $value, $_SESSION['user_id']);
    }
    $message = 'Settings updated successfully!';
    logActivity($_SESSION['user_id'], ACTIVITY_UPDATE, 'Updated system settings');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <main class="container">
        <header class="page-header">
            <div>
                <h1>System Settings</h1>
                <p>Configure and manage application preferences</p>
            </div>
        </header>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="card">
                <div class="card-header">
                    <h3>General Settings</h3>
                </div>
                <div class="card-body">
                    <?php while ($setting = mysqli_fetch_assoc($settings)): ?>
                        <div class="form-group">
                            <label><?php echo htmlspecialchars($setting['setting_key']); ?></label>
                            <input type="text"
                                   name="setting[<?php echo htmlspecialchars($setting['setting_key']); ?>]"
                                   value="<?php echo htmlspecialchars($setting['setting_value']); ?>"
                                   class="form-control">
                            <span class="form-hint"><?php echo htmlspecialchars($setting['description']); ?></span>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </div>
        </form>
    </main>
</body>
</html>
