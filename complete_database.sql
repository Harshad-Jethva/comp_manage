-- =====================================================
-- FIXBOT Complaint Management System - Complete Database
-- =====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS `complain` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `complain`;

-- =====================================================
-- Table: complain
-- =====================================================
DROP TABLE IF EXISTS `complain`;
CREATE TABLE `complain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `sector` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `priority` varchar(10) DEFAULT 'medium',
  `assigned_to` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_priority` (`priority`),
  KEY `idx_sector` (`sector`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_assigned_to` (`assigned_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data for complain table
INSERT INTO `complain` (`name`, `email`, `phone`, `address`, `title`, `description`, `sector`, `status`, `priority`, `assigned_to`) VALUES
('John Doe', 'john.doe@email.com', '9876543210', '123 Main Street, City Center', 'Street Light Not Working', 'The street light near my house has been out for 3 days. It creates safety concerns during night time.', 'Infrastructure', 'pending', 'high', NULL),
('Jane Smith', 'jane.smith@email.com', '9876543211', '456 Oak Avenue, Downtown', 'Garbage Collection Issue', 'Garbage has not been collected from our area for the past week. The smell is becoming unbearable.', 'Sanitation', 'in_progress', 'medium', 'Officer Kumar'),
('Mike Johnson', 'mike.j@email.com', '9876543212', '789 Pine Road, Suburb', 'Water Supply Problem', 'No water supply in our locality since yesterday morning. Multiple families are affected.', 'Water Supply', 'resolved', 'high', 'Officer Sharma'),
('Sarah Wilson', 'sarah.w@email.com', '9876543213', '321 Elm Street, Old Town', 'Road Pothole Repair', 'Large pothole on Elm Street causing vehicle damage. Multiple complaints from residents.', 'Infrastructure', 'pending', 'medium', NULL),
('David Brown', 'david.brown@email.com', '9876543214', '654 Maple Drive, New Area', 'Noise Pollution', 'Construction work starting very early morning (5 AM) causing noise pollution in residential area.', 'Environment', 'pending', 'low', NULL),
('Lisa Davis', 'lisa.davis@email.com', '9876543215', '987 Cedar Lane, Hills', 'Electricity Outage', 'Frequent power cuts in our area. No electricity for 6+ hours daily affecting work from home.', 'Electricity', 'in_progress', 'high', 'Officer Patel'),
('Robert Miller', 'robert.m@email.com', '9876543216', '147 Birch Street, Valley', 'Stray Dog Problem', 'Increasing number of stray dogs in the area. Safety concern for children and elderly.', 'Animal Control', 'pending', 'medium', NULL),
('Emily Taylor', 'emily.t@email.com', '9876543217', '258 Spruce Road, Garden City', 'Drainage Issue', 'Water logging during rains due to blocked drainage system. Mosquito breeding concern.', 'Drainage', 'resolved', 'medium', 'Officer Singh'),
('Chris Anderson', 'chris.a@email.com', '9876543218', '369 Willow Avenue, Metro', 'Traffic Signal Malfunction', 'Traffic signal at main intersection not working properly. Causing traffic jams and accidents.', 'Traffic', 'in_progress', 'high', 'Officer Gupta'),
('Amanda White', 'amanda.w@email.com', '9876543219', '741 Ash Boulevard, Central', 'Public Toilet Maintenance', 'Public toilet facility in poor condition. Needs immediate cleaning and maintenance.', 'Sanitation', 'pending', 'low', NULL);

-- =====================================================
-- Table: photo
-- =====================================================
DROP TABLE IF EXISTS `photo`;
CREATE TABLE `photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `complain_id` int(11) NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `photo_name` varchar(100) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_photo_complain` (`complain_id`),
  CONSTRAINT `fk_photo_complain` FOREIGN KEY (`complain_id`) REFERENCES `complain` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data for photo table
INSERT INTO `photo` (`complain_id`, `photo_path`, `photo_name`) VALUES
(1, 'uploads/complaints/streetlight_001.jpg', 'streetlight_001.jpg'),
(2, 'uploads/complaints/garbage_002.jpg', 'garbage_002.jpg'),
(3, 'uploads/complaints/water_003.jpg', 'water_003.jpg'),
(4, 'uploads/complaints/pothole_004.jpg', 'pothole_004.jpg'),
(6, 'uploads/complaints/electricity_006.jpg', 'electricity_006.jpg'),
(8, 'uploads/complaints/drainage_008.jpg', 'drainage_008.jpg'),
(9, 'uploads/complaints/traffic_009.jpg', 'traffic_009.jpg');

-- =====================================================
-- Table: user
-- =====================================================
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data for user table (passwords are hashed)
INSERT INTO `user` (`name`, `email`, `phone`, `address`, `password`, `status`) VALUES
('John Doe', 'john.doe@email.com', '9876543210', '123 Main Street, City Center', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
('Jane Smith', 'jane.smith@email.com', '9876543211', '456 Oak Avenue, Downtown', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
('Mike Johnson', 'mike.j@email.com', '9876543212', '789 Pine Road, Suburb', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
('Sarah Wilson', 'sarah.w@email.com', '9876543213', '321 Elm Street, Old Town', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
('David Brown', 'david.brown@email.com', '9876543214', '654 Maple Drive, New Area', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'inactive'),
('Lisa Davis', 'lisa.davis@email.com', '9876543215', '987 Cedar Lane, Hills', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
('Robert Miller', 'robert.m@email.com', '9876543216', '147 Birch Street, Valley', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
('Emily Taylor', 'emily.t@email.com', '9876543217', '258 Spruce Road, Garden City', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
('Chris Anderson', 'chris.a@email.com', '9876543218', '369 Willow Avenue, Metro', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'suspended'),
('Amanda White', 'amanda.w@email.com', '9876543219', '741 Ash Boulevard, Central', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active');

-- =====================================================
-- Table: admin_users
-- =====================================================
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `role` enum('super_admin','admin','officer') DEFAULT 'officer',
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_username` (`username`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample admin users (password: admin123 for all)
INSERT INTO `admin_users` (`username`, `password`, `full_name`, `email`, `role`, `status`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@fixbot.com', 'super_admin', 'active'),
('officer_kumar', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Officer Kumar', 'kumar@fixbot.com', 'officer', 'active'),
('officer_sharma', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Officer Sharma', 'sharma@fixbot.com', 'officer', 'active'),
('officer_patel', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Officer Patel', 'patel@fixbot.com', 'officer', 'active'),
('officer_singh', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Officer Singh', 'singh@fixbot.com', 'officer', 'active'),
('officer_gupta', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Officer Gupta', 'gupta@fixbot.com', 'officer', 'active');

-- =====================================================
-- Table: complaint_history
-- =====================================================
DROP TABLE IF EXISTS `complaint_history`;
CREATE TABLE `complaint_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `complain_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `old_value` text,
  `new_value` text,
  `changed_by` varchar(50) NOT NULL,
  `change_reason` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_history_complain` (`complain_id`),
  KEY `idx_action` (`action`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_history_complain` FOREIGN KEY (`complain_id`) REFERENCES `complain` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample complaint history data
INSERT INTO `complaint_history` (`complain_id`, `action`, `old_value`, `new_value`, `changed_by`, `change_reason`) VALUES
(2, 'status_change', 'pending', 'in_progress', 'Officer Kumar', 'Started investigation'),
(3, 'status_change', 'pending', 'in_progress', 'Officer Sharma', 'Assigned to water department'),
(3, 'status_change', 'in_progress', 'resolved', 'Officer Sharma', 'Water supply restored'),
(6, 'assignment', NULL, 'Officer Patel', 'admin', 'Assigned to electricity department'),
(6, 'status_change', 'pending', 'in_progress', 'Officer Patel', 'Investigation started'),
(8, 'status_change', 'pending', 'resolved', 'Officer Singh', 'Drainage cleaned and repaired'),
(9, 'assignment', NULL, 'Officer Gupta', 'admin', 'Assigned to traffic department'),
(9, 'status_change', 'pending', 'in_progress', 'Officer Gupta', 'Traffic signal repair initiated');

-- =====================================================
-- Table: sectors
-- =====================================================
DROP TABLE IF EXISTS `sectors`;
CREATE TABLE `sectors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL UNIQUE,
  `description` text,
  `department_head` varchar(100),
  `contact_email` varchar(100),
  `contact_phone` varchar(15),
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_sector_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample sectors data
INSERT INTO `sectors` (`name`, `description`, `department_head`, `contact_email`, `contact_phone`, `status`) VALUES
('Infrastructure', 'Roads, bridges, street lights, and public infrastructure', 'Mr. Rajesh Kumar', 'infrastructure@city.gov', '9876501001', 'active'),
('Sanitation', 'Garbage collection, waste management, and cleanliness', 'Ms. Priya Sharma', 'sanitation@city.gov', '9876501002', 'active'),
('Water Supply', 'Water distribution, quality, and supply issues', 'Mr. Amit Patel', 'water@city.gov', '9876501003', 'active'),
('Electricity', 'Power supply, electrical infrastructure, and outages', 'Mr. Suresh Singh', 'electricity@city.gov', '9876501004', 'active'),
('Environment', 'Pollution control, environmental protection, and green initiatives', 'Dr. Meera Gupta', 'environment@city.gov', '9876501005', 'active'),
('Traffic', 'Traffic management, signals, and road safety', 'Mr. Vikram Yadav', 'traffic@city.gov', '9876501006', 'active'),
('Animal Control', 'Stray animal management and animal welfare', 'Ms. Sunita Joshi', 'animal@city.gov', '9876501007', 'active'),
('Drainage', 'Sewerage, drainage systems, and flood management', 'Mr. Ravi Verma', 'drainage@city.gov', '9876501008', 'active'),
('Public Health', 'Health services, medical facilities, and public hygiene', 'Dr. Kavita Reddy', 'health@city.gov', '9876501009', 'active'),
('Education', 'Schools, educational facilities, and learning resources', 'Mr. Deepak Agarwal', 'education@city.gov', '9876501010', 'active');

-- =====================================================
-- Table: notifications
-- =====================================================
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `complain_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_notification_user` (`user_id`),
  KEY `fk_notification_complain` (`complain_id`),
  KEY `idx_is_read` (`is_read`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_notification_complain` FOREIGN KEY (`complain_id`) REFERENCES `complain` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample notifications
INSERT INTO `notifications` (`user_id`, `complain_id`, `title`, `message`, `type`, `is_read`) VALUES
(2, 2, 'Complaint Status Updated', 'Your complaint about garbage collection has been assigned to Officer Kumar and is now in progress.', 'info', 0),
(3, 3, 'Complaint Resolved', 'Your water supply complaint has been resolved. Water supply has been restored to your area.', 'success', 1),
(6, 6, 'Complaint Assigned', 'Your electricity outage complaint has been assigned to Officer Patel for investigation.', 'info', 0),
(8, 8, 'Complaint Resolved', 'Your drainage issue complaint has been resolved. The drainage system has been cleaned and repaired.', 'success', 0),
(9, 9, 'Complaint In Progress', 'Your traffic signal complaint is being worked on by Officer Gupta. Repair work has been initiated.', 'info', 1);

-- =====================================================
-- Create Views for Analytics
-- =====================================================

-- View: Complaint Statistics by Status
CREATE OR REPLACE VIEW `complaint_stats_by_status` AS
SELECT 
    status,
    COUNT(*) as count,
    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM complain)), 2) as percentage
FROM complain 
GROUP BY status;

-- View: Complaint Statistics by Sector
CREATE OR REPLACE VIEW `complaint_stats_by_sector` AS
SELECT 
    sector,
    COUNT(*) as count,
    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM complain)), 2) as percentage
FROM complain 
GROUP BY sector 
ORDER BY count DESC;

-- View: Complaint Statistics by Priority
CREATE OR REPLACE VIEW `complaint_stats_by_priority` AS
SELECT 
    priority,
    COUNT(*) as count,
    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM complain)), 2) as percentage
FROM complain 
GROUP BY priority;

-- View: Monthly Complaint Trends
CREATE OR REPLACE VIEW `monthly_complaint_trends` AS
SELECT 
    YEAR(created_at) as year,
    MONTH(created_at) as month,
    MONTHNAME(created_at) as month_name,
    COUNT(*) as complaint_count
FROM complain 
GROUP BY YEAR(created_at), MONTH(created_at)
ORDER BY year DESC, month DESC;

-- =====================================================
-- Create Indexes for Performance
-- =====================================================

-- Additional indexes for better performance
CREATE INDEX idx_complain_email ON complain(email);
CREATE INDEX idx_complain_phone ON complain(phone);
CREATE INDEX idx_user_phone ON user(phone);
CREATE INDEX idx_photo_uploaded_at ON photo(uploaded_at);
CREATE INDEX idx_history_changed_by ON complaint_history(changed_by);
CREATE INDEX idx_notification_type ON notifications(type);

-- =====================================================
-- Create Triggers
-- =====================================================

-- Trigger to automatically create notification when complaint status changes
DELIMITER $$
CREATE TRIGGER `complaint_status_notification` 
AFTER UPDATE ON `complain`
FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO notifications (
            user_id, 
            complain_id, 
            title, 
            message, 
            type
        ) VALUES (
            (SELECT id FROM user WHERE email = NEW.email LIMIT 1),
            NEW.id,
            'Complaint Status Updated',
            CONCAT('Your complaint "', NEW.title, '" status has been changed from "', OLD.status, '" to "', NEW.status, '".'),
            CASE 
                WHEN NEW.status = 'resolved' THEN 'success'
                WHEN NEW.status = 'in_progress' THEN 'info'
                ELSE 'info'
            END
        );
    END IF;
END$$
DELIMITER ;

-- Trigger to log complaint history
DELIMITER $$
CREATE TRIGGER `complaint_history_log` 
AFTER UPDATE ON `complain`
FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO complaint_history (
            complain_id, 
            action, 
            old_value, 
            new_value, 
            changed_by
        ) VALUES (
            NEW.id,
            'status_change',
            OLD.status,
            NEW.status,
            COALESCE(NEW.assigned_to, 'system')
        );
    END IF;
    
    IF OLD.priority != NEW.priority THEN
        INSERT INTO complaint_history (
            complain_id, 
            action, 
            old_value, 
            new_value, 
            changed_by
        ) VALUES (
            NEW.id,
            'priority_change',
            OLD.priority,
            NEW.priority,
            COALESCE(NEW.assigned_to, 'system')
        );
    END IF;
    
    IF OLD.assigned_to != NEW.assigned_to THEN
        INSERT INTO complaint_history (
            complain_id, 
            action, 
            old_value, 
            new_value, 
            changed_by
        ) VALUES (
            NEW.id,
            'assignment_change',
            OLD.assigned_to,
            NEW.assigned_to,
            'admin'
        );
    END IF;
END$$
DELIMITER ;

-- =====================================================
-- Final Setup Complete
-- =====================================================

-- Set AUTO_INCREMENT values
ALTER TABLE `complain` AUTO_INCREMENT = 11;
ALTER TABLE `photo` AUTO_INCREMENT = 8;
ALTER TABLE `user` AUTO_INCREMENT = 11;
ALTER TABLE `admin_users` AUTO_INCREMENT = 7;
ALTER TABLE `complaint_history` AUTO_INCREMENT = 9;
ALTER TABLE `sectors` AUTO_INCREMENT = 11;
ALTER TABLE `notifications` AUTO_INCREMENT = 6;

-- Grant permissions (adjust as needed for your setup)
-- GRANT ALL PRIVILEGES ON complain.* TO 'your_username'@'localhost';
-- FLUSH PRIVILEGES;

-- =====================================================
-- Database Setup Complete!
-- =====================================================
-- 
-- This database includes:
-- ✅ Complete complaint management system
-- ✅ User management with authentication
-- ✅ Admin panel with role-based access
-- ✅ Photo upload functionality
-- ✅ Complaint history tracking
-- ✅ Notification system
-- ✅ Sector/Department management
-- ✅ Analytics views for reporting
-- ✅ Performance indexes
-- ✅ Automated triggers for logging
-- 
-- Default Login Credentials:
-- Username: admin
-- Password: admin123
-- 
-- To import this file:
-- mysql -u root -p < complete_database.sql
-- 
-- =====================================================
