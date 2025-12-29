-- Admin Dashboard with Analytics Database
-- Database: admin_dashboard

CREATE DATABASE IF NOT EXISTS admin_dashboard;
USE admin_dashboard;

-- Users table with role-based access
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('super_admin', 'admin', 'moderator', 'user') DEFAULT 'user',
    avatar VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
);

-- Analytics data table for tracking various metrics
CREATE TABLE analytics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    metric_name VARCHAR(100) NOT NULL,
    metric_value DECIMAL(15,2) NOT NULL,
    metric_category ENUM('sales', 'traffic', 'users', 'revenue', 'performance', 'engagement') NOT NULL,
    time_period ENUM('hourly', 'daily', 'weekly', 'monthly', 'yearly') DEFAULT 'daily',
    recorded_date DATE NOT NULL,
    recorded_time TIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_metric_name (metric_name),
    INDEX idx_category (metric_category),
    INDEX idx_date (recorded_date),
    INDEX idx_period (time_period)
);

-- Activity logs for tracking all system activities
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT DEFAULT NULL,
    activity_type ENUM('login', 'logout', 'create', 'update', 'delete', 'view', 'export', 'import', 'system') NOT NULL,
    activity_description TEXT NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_activity_type (activity_type),
    INDEX idx_created_at (created_at)
);

-- System settings table
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT NOT NULL,
    setting_category VARCHAR(50) DEFAULT 'general',
    description TEXT DEFAULT NULL,
    updated_by INT DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_setting_key (setting_key),
    INDEX idx_category (setting_category)
);

-- Reports table for generated reports
CREATE TABLE reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    report_name VARCHAR(200) NOT NULL,
    report_type ENUM('sales', 'analytics', 'users', 'activity', 'performance', 'custom') NOT NULL,
    report_format ENUM('pdf', 'excel', 'csv', 'json') DEFAULT 'pdf',
    date_from DATE NOT NULL,
    date_to DATE NOT NULL,
    generated_by INT NOT NULL,
    file_path VARCHAR(255) DEFAULT NULL,
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_report_type (report_type),
    INDEX idx_status (status),
    INDEX idx_generated_by (generated_by)
);

-- Notifications table
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT DEFAULT NULL,
    notification_type ENUM('info', 'success', 'warning', 'error', 'system') DEFAULT 'info',
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    action_url VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
);

-- Insert sample admin users
INSERT INTO users (username, email, password, full_name, role, status) VALUES
('superadmin', 'superadmin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator', 'super_admin', 'active'),
('admin', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin', 'active'),
('moderator', 'moderator@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Moderator User', 'moderator', 'active'),
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', 'user', 'active'),
('jane_smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', 'user', 'active');

-- Insert sample analytics data (last 30 days)
INSERT INTO analytics (metric_name, metric_value, metric_category, time_period, recorded_date) VALUES
-- Sales data
('Daily Sales', 15420.50, 'sales', 'daily', '2025-12-29'),
('Daily Sales', 18750.00, 'sales', 'daily', '2025-12-28'),
('Daily Sales', 14230.75, 'sales', 'daily', '2025-12-27'),
('Daily Sales', 16890.25, 'sales', 'daily', '2025-12-26'),
('Daily Sales', 19540.00, 'sales', 'daily', '2025-12-25'),
('Daily Sales', 21200.50, 'sales', 'daily', '2025-12-24'),
('Daily Sales', 17650.25, 'sales', 'daily', '2025-12-23'),

-- Revenue data
('Total Revenue', 125400.00, 'revenue', 'monthly', '2025-12-01'),
('Total Revenue', 118750.00, 'revenue', 'monthly', '2025-11-01'),
('Total Revenue', 132200.50, 'revenue', 'monthly', '2025-10-01'),

-- Traffic data
('Page Views', 5420, 'traffic', 'daily', '2025-12-29'),
('Page Views', 6230, 'traffic', 'daily', '2025-12-28'),
('Page Views', 5890, 'traffic', 'daily', '2025-12-27'),
('Unique Visitors', 1850, 'traffic', 'daily', '2025-12-29'),
('Unique Visitors', 2100, 'traffic', 'daily', '2025-12-28'),
('Unique Visitors', 1920, 'traffic', 'daily', '2025-12-27'),

-- User metrics
('New Users', 45, 'users', 'daily', '2025-12-29'),
('New Users', 52, 'users', 'daily', '2025-12-28'),
('New Users', 38, 'users', 'daily', '2025-12-27'),
('Active Users', 1250, 'users', 'daily', '2025-12-29'),
('Active Users', 1340, 'users', 'daily', '2025-12-28'),
('Active Users', 1180, 'users', 'daily', '2025-12-27'),

-- Performance metrics
('Avg Response Time', 245.5, 'performance', 'daily', '2025-12-29'),
('Avg Response Time', 198.2, 'performance', 'daily', '2025-12-28'),
('Avg Response Time', 267.8, 'performance', 'daily', '2025-12-27'),
('Server Uptime', 99.98, 'performance', 'daily', '2025-12-29'),
('Server Uptime', 99.99, 'performance', 'daily', '2025-12-28'),

-- Engagement metrics
('Avg Session Duration', 8.5, 'engagement', 'daily', '2025-12-29'),
('Avg Session Duration', 9.2, 'engagement', 'daily', '2025-12-28'),
('Avg Session Duration', 7.8, 'engagement', 'daily', '2025-12-27'),
('Bounce Rate', 42.5, 'engagement', 'daily', '2025-12-29'),
('Bounce Rate', 38.2, 'engagement', 'daily', '2025-12-28');

-- Insert sample activity logs
INSERT INTO activity_logs (user_id, activity_type, activity_description, ip_address) VALUES
(1, 'login', 'Super Administrator logged in', '192.168.1.1'),
(2, 'login', 'Admin User logged in', '192.168.1.2'),
(1, 'create', 'Created new user: Jane Smith', '192.168.1.1'),
(2, 'update', 'Updated system settings', '192.168.1.2'),
(3, 'login', 'Moderator User logged in', '192.168.1.3'),
(1, 'view', 'Viewed analytics dashboard', '192.168.1.1'),
(2, 'export', 'Exported sales report', '192.168.1.2'),
(4, 'login', 'John Doe logged in', '192.168.1.4'),
(5, 'login', 'Jane Smith logged in', '192.168.1.5'),
(1, 'system', 'Database backup completed', '192.168.1.1');

-- Insert sample system settings
INSERT INTO settings (setting_key, setting_value, setting_category, description, updated_by) VALUES
('site_name', 'Admin Dashboard Pro', 'general', 'Website name', 1),
('site_email', 'admin@dashboard.com', 'general', 'Contact email', 1),
('timezone', 'UTC', 'general', 'Default timezone', 1),
('records_per_page', '20', 'display', 'Number of records per page', 1),
('maintenance_mode', 'off', 'system', 'Maintenance mode status', 1),
('backup_enabled', 'on', 'system', 'Automatic backup status', 1),
('email_notifications', 'on', 'notifications', 'Email notification status', 1),
('theme_mode', 'dark', 'display', 'Theme mode (dark/light)', 1);

-- Insert sample notifications
INSERT INTO notifications (user_id, notification_type, title, message, is_read) VALUES
(1, 'success', 'Backup Completed', 'System backup has been completed successfully', FALSE),
(2, 'info', 'New User Registration', '5 new users registered today', FALSE),
(1, 'warning', 'High Server Load', 'Server CPU usage is above 80%', FALSE),
(2, 'success', 'Report Generated', 'Monthly sales report has been generated', TRUE),
(3, 'info', 'System Update', 'New system update available', FALSE);

-- Insert sample reports
INSERT INTO reports (report_name, report_type, report_format, date_from, date_to, generated_by, status) VALUES
('December Sales Report', 'sales', 'pdf', '2025-12-01', '2025-12-29', 1, 'completed'),
('User Analytics Report', 'analytics', 'excel', '2025-12-01', '2025-12-29', 2, 'completed'),
('Monthly Performance Report', 'performance', 'pdf', '2025-11-01', '2025-11-30', 1, 'completed'),
('Activity Log Report', 'activity', 'csv', '2025-12-20', '2025-12-29', 2, 'completed');
