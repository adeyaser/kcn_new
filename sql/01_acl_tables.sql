-- =============================================
-- KCN Terminal Petikemas - ACL Tables
-- =============================================

CREATE TABLE IF NOT EXISTS `acl_roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `role_name` VARCHAR(50) NOT NULL,
  `role_code` VARCHAR(20) NOT NULL,
  `description` TEXT,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `acl_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100),
  `phone` VARCHAR(20),
  `role_id` INT NOT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `last_login` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`role_id`) REFERENCES `acl_roles`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `acl_menus` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `menu_name` VARCHAR(100) NOT NULL,
  `menu_icon` VARCHAR(50) DEFAULT 'fas fa-circle',
  `menu_url` VARCHAR(255) DEFAULT '#',
  `parent_id` INT DEFAULT 0,
  `menu_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `acl_permissions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `role_id` INT NOT NULL,
  `menu_id` INT NOT NULL,
  `can_view` TINYINT(1) DEFAULT 0,
  `can_create` TINYINT(1) DEFAULT 0,
  `can_edit` TINYINT(1) DEFAULT 0,
  `can_delete` TINYINT(1) DEFAULT 0,
  FOREIGN KEY (`role_id`) REFERENCES `acl_roles`(`id`),
  FOREIGN KEY (`menu_id`) REFERENCES `acl_menus`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `app_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT,
  `setting_group` VARCHAR(50) DEFAULT 'general'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =============================================
-- SEED DATA
-- =============================================

INSERT INTO `acl_roles` (`role_name`, `role_code`, `description`) VALUES
('Administrator', 'ADMIN', 'Full system access'),
('Planner', 'PLANNER', 'Vessel & yard planning access'),
('Operator', 'OPERATOR', 'Field operations - tally, gate, stacking'),
('Supervisor', 'SUPERVISOR', 'Monitoring and approval access');

INSERT INTO `acl_users` (`username`, `password`, `full_name`, `email`, `role_id`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@kcn.co.id', 1),
('planner01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Planner Staff', 'planner@kcn.co.id', 2),
('operator01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Operator Staff', 'operator@kcn.co.id', 3);

-- Password default: password

INSERT INTO `acl_menus` (`id`, `menu_name`, `menu_icon`, `menu_url`, `parent_id`, `menu_order`) VALUES
(1, 'Dashboard', 'fas fa-tachometer-alt', 'dashboard', 0, 1),
(2, 'Master Data', 'fas fa-database', '#', 0, 2),
(3, 'Vessel Master', 'fas fa-ship', 'master/vessel', 2, 1),
(4, 'Berth Master', 'fas fa-anchor', 'master/berth', 2, 2),
(5, 'Equipment Master', 'fas fa-cogs', 'master/equipment', 2, 3),
(6, 'Truck Master', 'fas fa-truck', 'master/truck', 2, 4),
(7, 'Container Master', 'fas fa-cube', 'master/container', 2, 5),
(8, 'TID Master', 'fas fa-id-card', 'master/tid', 2, 6),
(9, 'Planning', 'fas fa-clipboard-list', '#', 0, 3),
(10, 'Request Planning', 'fas fa-file-alt', 'planning/request', 9, 1),
(11, 'Vessel Planning', 'fas fa-ship', 'planning/vessel', 9, 2),
(12, 'Yard Planning', 'fas fa-th', 'planning/yard', 9, 3),
(13, 'Operations', 'fas fa-hard-hat', '#', 0, 4),
(14, 'Gate In/Out', 'fas fa-door-open', 'operations/gate', 13, 1),
(15, 'Tally', 'fas fa-clipboard-check', 'operations/tally', 13, 2),
(16, 'Lift On/Off', 'fas fa-arrows-alt-v', 'operations/lift', 13, 3),
(17, 'Container Stacking', 'fas fa-layer-group', 'operations/stacking', 13, 4),
(18, 'Receiving/Delivery', 'fas fa-exchange-alt', 'operations/receiving', 13, 5),
(19, 'Monitoring', 'fas fa-tv', '#', 0, 5),
(20, 'Vessel Monitor', 'fas fa-satellite-dish', 'monitoring/vessel', 19, 1),
(21, 'Container Track', 'fas fa-search-location', 'monitoring/container', 19, 2),
(22, 'Reports', 'fas fa-chart-bar', '#', 0, 6),
(23, 'Daily Report', 'fas fa-calendar-day', 'reports/daily', 22, 1),
(24, 'Tally Report', 'fas fa-list-ol', 'reports/tally', 22, 2),
(25, 'TRT Report', 'fas fa-clock', 'reports/trt', 22, 3),
(26, 'Setup', 'fas fa-cog', '#', 0, 7),
(27, 'Terminal Profile', 'fas fa-building', 'setup/profile', 26, 1),
(28, 'User Management', 'fas fa-users', 'setup/users', 26, 2),
(29, 'Role Management', 'fas fa-user-shield', 'setup/roles', 26, 3),
(30, 'Menu Management', 'fas fa-bars', 'setup/menus', 26, 4),
(31, 'Permissions', 'fas fa-key', 'setup/permissions', 26, 5);

-- Admin gets all permissions
INSERT INTO `acl_permissions` (`role_id`, `menu_id`, `can_view`, `can_create`, `can_edit`, `can_delete`)
SELECT 1, id, 1, 1, 1, 1 FROM `acl_menus`;

-- Planner permissions (planning + monitoring)
INSERT INTO `acl_permissions` (`role_id`, `menu_id`, `can_view`, `can_create`, `can_edit`, `can_delete`)
VALUES (2, 1, 1, 0, 0, 0), (2, 9, 1, 0, 0, 0), (2, 10, 1, 1, 1, 0), (2, 11, 1, 1, 1, 0), (2, 12, 1, 1, 1, 0),
(2, 19, 1, 0, 0, 0), (2, 20, 1, 0, 0, 0), (2, 21, 1, 0, 0, 0);

-- Operator permissions (operations)
INSERT INTO `acl_permissions` (`role_id`, `menu_id`, `can_view`, `can_create`, `can_edit`, `can_delete`)
VALUES (3, 1, 1, 0, 0, 0), (3, 13, 1, 0, 0, 0), (3, 14, 1, 1, 1, 0), (3, 15, 1, 1, 1, 0), (3, 16, 1, 1, 1, 0),
(3, 17, 1, 1, 1, 0), (3, 18, 1, 1, 1, 0);

-- App settings
INSERT INTO `app_settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('app_name', 'KCN Terminal Petikemas', 'general'),
('app_short_name', 'KCN TOS', 'general'),
('app_logo', '', 'general'),
('app_favicon', '', 'general'),
('terminal_name', 'Terminal Petikemas KCN', 'terminal'),
('terminal_code', 'IDKCN', 'terminal'),
('terminal_address', 'Jl. Pelabuhan No. 1', 'terminal'),
('terminal_phone', '021-XXXXXXX', 'terminal'),
('terminal_email', 'info@kcn.co.id', 'terminal');
