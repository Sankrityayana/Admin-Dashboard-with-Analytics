<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = mysqli_prepare($conn, "SELECT id, username, password, full_name, role, status FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($user = mysqli_fetch_assoc($result)) {
        if ($user['status'] !== 'active') {
            $error = 'Your account is ' . $user['status'];
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            
            updateLastLogin($user['id']);
            logActivity($user['id'], ACTIVITY_LOGIN, 'User logged in');
            
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        (function(){
            var t = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
</head>
<body class="login-page">
    <div class="login-container">
        <!-- Left: branding panel -->
        <div class="login-branding">
            <div class="login-branding-icon">📊</div>
            <h2><?php echo SITE_NAME; ?></h2>
            <p><?php echo SITE_TITLE; ?></p>
            <ul class="login-branding-features">
                <li>Real-time analytics dashboard</li>
                <li>User &amp; role management</li>
                <li>Automated report generation</li>
                <li>Activity monitoring &amp; logs</li>
                <li>System settings &amp; configuration</li>
            </ul>
        </div>

        <!-- Right: form panel -->
        <div class="login-form-panel">
            <div class="login-card">
                <div class="login-header">
                    <h1>Welcome back</h1>
                    <p>Sign in to your account to continue</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="" class="login-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </form>

                <div class="demo-credentials">
                    <h4>Demo credentials</h4>
                    <div class="credential-grid">
                        <div><strong>Super Admin:</strong> superadmin / password</div>
                        <div><strong>Admin:</strong> admin / password</div>
                        <div><strong>Moderator:</strong> moderator / password</div>
                        <div><strong>User:</strong> john_doe / password</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
