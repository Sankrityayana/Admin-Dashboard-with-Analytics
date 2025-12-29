# Admin Dashboard with Analytics

A comprehensive admin dashboard system with real-time analytics, user management, activity logging, and reporting capabilities. Built with PHP and MySQL, featuring a modern dark multicolor theme.

## 🎯 Features

- **Dashboard Analytics**: Real-time metrics with interactive Chart.js visualizations
- **User Management**: Complete CRUD operations with role-based access control
- **Analytics Tracking**: Monitor sales, traffic, users, revenue, performance, and engagement
- **Activity Logging**: Comprehensive audit trail with IP tracking and user agent capture
- **Report Generation**: Create and download reports in multiple formats (PDF, Excel, CSV, JSON)
- **System Settings**: Configurable system parameters for customization
- **Notifications**: Real-time notification system with read/unread status
- **Role-Based Access**: 4-tier hierarchy (Super Admin, Admin, Moderator, User)
- **Dark Theme**: Modern dark multicolor interface with bright accent colors

## 📊 Analytics Categories

- **Sales**: Daily sales tracking and revenue monitoring
- **Traffic**: Page views, unique visitors, and traffic patterns
- **Users**: New registrations, active users, and user growth
- **Revenue**: Financial metrics and revenue trends
- **Performance**: Response time, server uptime, and system performance
- **Engagement**: Session duration, bounce rate, and user engagement

## 🚀 Installation

### Prerequisites

- XAMPP (or any Apache/PHP/MySQL stack)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- **MySQL Port: 3307** (Important!)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/Sankrityayana/Admin-Dashboard-with-Analytics.git
   cd Admin-Dashboard-with-Analytics
   ```

2. **Configure MySQL Port**
   - Ensure MySQL is running on port 3307
   - Update XAMPP MySQL configuration if needed

3. **Create Database**
   ```sql
   CREATE DATABASE admin_dashboard;
   ```

4. **Import Database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Select the `admin_dashboard` database
   - Import `database/database.sql`

5. **Update Configuration** (if needed)
   - Edit `includes/config.php`
   - Verify database credentials and port

6. **Access the Dashboard**
   - Open: http://localhost/Admin-Dashboard-with-Analytics/login.php

## 🔐 Demo Credentials

The system includes 4 user roles with different permission levels:

| Username    | Password | Role         | Access Level                          |
|-------------|----------|--------------|---------------------------------------|
| superadmin  | password | Super Admin  | Full system access and control        |
| admin       | password | Admin        | User and settings management          |
| moderator   | password | Moderator    | Moderate access to content            |
| john_doe    | password | User         | Basic user access                     |

## 📁 Database Schema

### Users Table
- 4-tier role system (super_admin, admin, moderator, user)
- Status tracking (active, inactive, suspended)
- Last login tracking
- Avatar support

### Analytics Table
- 6 metric categories (sales, traffic, users, revenue, performance, engagement)
- 5 time periods (hourly, daily, weekly, monthly, yearly)
- Precise date/time tracking

### Activity Logs
- 9 activity types (login, logout, create, update, delete, view, export, import, system)
- IP address tracking (IPv6 compatible)
- User agent capture for browser identification

### Settings
- System configuration with categories
- Updated by tracking
- Description support

### Reports
- 6 report types (sales, analytics, users, activity, performance, custom)
- 4 formats (PDF, Excel, CSV, JSON)
- Date range support
- Status tracking (pending, processing, completed, failed)

### Notifications
- 5 notification types (info, success, warning, error, system)
- Read/unread tracking
- Action URLs for clickable notifications

## 🎨 Theme

The dashboard features a **dark multicolor theme** with:
- Dark backgrounds (#121212, #1e1e1e, #2a2a2a)
- Bright accent colors (cyan, purple, pink, teal, orange, yellow)
- **NO gradients** - all solid colors
- Colored borders for visual hierarchy
- Smooth transitions and hover effects

## 👥 Role Hierarchy

1. **Super Admin** (Highest)
   - Full system access
   - User management
   - System settings
   - All analytics and reports

2. **Admin**
   - User management
   - System settings
   - Analytics viewing
   - Report generation

3. **Moderator**
   - Moderate content access
   - View analytics
   - Generate reports
   - View activity logs

4. **User** (Basic)
   - View own dashboard
   - View limited analytics
   - View own notifications

## 📈 Usage Guide

### Dashboard
- View 8 key metrics with trend indicators
- Interactive sales and traffic charts
- User statistics at a glance
- Recent activity feed

### Analytics
- Filter by category and time period
- View detailed metrics
- Export data for analysis

### User Management
- Add, edit, and delete users (Admin+)
- Assign roles and status
- Track last login

### Reports
- Generate reports with date ranges
- Choose from multiple formats
- Download completed reports
- Track generation status

### Activity Log
- Monitor all system activities
- Filter by user and activity type
- Track IP addresses
- Export logs

### Settings
- Configure site name and email
- Set timezone and display preferences
- Manage maintenance mode
- Enable/disable features

### Notifications
- View all notifications
- Mark as read/unread
- Click action links
- Delete notifications

## 🛠️ Technologies

- **Backend**: PHP 7.4+ with mysqli
- **Database**: MySQL 5.7+ (Port 3307)
- **Frontend**: HTML5, CSS3, JavaScript
- **Charts**: Chart.js for data visualization
- **Security**: Bcrypt password hashing, SQL injection prevention
- **Server**: Apache (XAMPP)

## 🔧 Troubleshooting

### Database Connection Issues
- Verify MySQL is running on port 3307
- Check database credentials in `includes/config.php`
- Ensure `admin_dashboard` database exists

### Login Problems
- Verify database import completed successfully
- Check user credentials (case-sensitive)
- Clear browser cache and cookies

### Chart Display Issues
- Ensure internet connection (Chart.js CDN)
- Check browser console for JavaScript errors
- Verify analytics data exists in database

### Port Issues
- If port 3307 is unavailable, update:
  - `includes/config.php` DB_PORT constant
  - XAMPP MySQL configuration
  - Restart MySQL service

## 📄 License

MIT License - See LICENSE file for details

## 👨‍💻 Author

Created for comprehensive admin dashboard and analytics management.

---

**Note**: This is a demonstration project. For production use, implement additional security measures, use environment variables for credentials, and enable HTTPS.
