-- =============================================
-- KCN Terminal Petikemas - Settings & Profiles
-- =============================================

CREATE TABLE IF NOT EXISTS `sys_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT,
  `setting_group` VARCHAR(50) DEFAULT 'GENERAL',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Initial Settings
INSERT INTO `sys_settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('terminal_name', 'KCN TERMINAL PETIKEMAS', 'PROFILE'),
('terminal_address', 'Jl. Raya Marunda No. 1, Jakarta Utara', 'PROFILE'),
('terminal_phone', '+62 21 12345678', 'PROFILE'),
('terminal_email', 'info@kcn-terminal.co.id', 'PROFILE'),
('free_storage_days', '3', 'BILLING'),
('storage_rate_20', '30000', 'BILLING'),
('storage_rate_40', '60000', 'BILLING'),
('storage_rate_45', '75000', 'BILLING'),
('currency_symbol', 'Rp', 'BILLING');
