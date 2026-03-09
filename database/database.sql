-- =========================================
-- DATABASE: ADMIN DASHBOARD
-- =========================================

CREATE DATABASE IF NOT EXISTS admin_dashboard;
USE admin_dashboard;


-- =========================================
-- USERS TABLE (ROLE BASED ACCESS)
-- =========================================

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('super_admin', 'admin', 'moderator', 'user') DEFAULT 'user',
    avatar VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    login_attempts INT DEFAULT 0,
    locked_until DATETIME DEFAULT NULL,
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
);


-- =========================================
-- ANALYTICS TABLE
-- =========================================

CREATE TABLE analytics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    metric_name VARCHAR(100) NOT NULL,
    metric_value DECIMAL(15,2) NOT NULL,
    metric_category ENUM(
        'sales',
        'traffic',
        'users',
        'revenue',
        'performance',
        'engagement'
    ) NOT NULL,

    time_period ENUM(
        'hourly',
        'daily',
        'weekly',
        'monthly',
        'yearly'
    ) DEFAULT 'daily',

    recorded_date DATE NOT NULL,
    recorded_time TIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_metric_name (metric_name),
    INDEX idx_category (metric_category),
    INDEX idx_date (recorded_date),
    INDEX idx_period (time_period)
);


-- =========================================
-- ACTIVITY LOGS
-- =========================================

CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT DEFAULT NULL,

    activity_type ENUM(
        'login',
        'logout',
        'create',
        'update',
        'delete',
        'view',
        'export',
        'import',
        'system'
    ) NOT NULL,

    activity_description TEXT NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,

    INDEX idx_user_id (user_id),
    INDEX idx_activity_type (activity_type),
    INDEX idx_created_at (created_at)
);


-- =========================================
-- SETTINGS TABLE
-- =========================================

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


-- =========================================
-- REPORTS TABLE
-- =========================================

CREATE TABLE reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    report_name VARCHAR(200) NOT NULL,

    report_type ENUM(
        'sales',
        'analytics',
        'users',
        'activity',
        'performance',
        'custom'
    ) NOT NULL,

    report_format ENUM(
        'pdf',
        'excel',
        'csv',
        'json'
    ) DEFAULT 'pdf',

    date_from DATE NOT NULL,
    date_to DATE NOT NULL,
    generated_by INT NOT NULL,
    file_path VARCHAR(255) DEFAULT NULL,

    status ENUM(
        'pending',
        'processing',
        'completed',
        'failed'
    ) DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE RESTRICT,

    INDEX idx_report_type (report_type),
    INDEX idx_status (status),
    INDEX idx_generated_by (generated_by)
);


-- =========================================
-- NOTIFICATIONS TABLE
-- =========================================

CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT DEFAULT NULL,

    notification_type ENUM(
        'info',
        'success',
        'warning',
        'error',
        'system'
    ) DEFAULT 'info',

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


-- =========================================
-- SAMPLE USERS
-- =========================================

INSERT INTO users
(username,email,password,full_name,role,status)
VALUES

('superadmin','superadmin@admin.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'Super Administrator','super_admin','active'),

('admin','admin@admin.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'Admin User','admin','active'),

('moderator','moderator@admin.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'Moderator User','moderator','active'),

('john_doe','john@example.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'John Doe','user','active'),

('jane_smith','jane@example.com',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'Jane Smith','user','active');


-- =========================================
-- SAMPLE SETTINGS
-- =========================================

INSERT INTO settings
(setting_key,setting_value,setting_category,description,updated_by)
VALUES

('site_name','Admin Dashboard Pro','general','Website name',1),
('site_email','admin@dashboard.com','general','Contact email',1),
('timezone','UTC','general','Default timezone',1),
('records_per_page','20','display','Records per page',1),
('maintenance_mode','off','system','Maintenance mode',1),
('backup_enabled','on','system','Automatic backups',1),
('email_notifications','on','notifications','Email notifications',1),
('theme_mode','dark','display','Dashboard theme',1);