-- =============================================
-- KCN Terminal Petikemas - Container Master
-- =============================================

CREATE TABLE IF NOT EXISTS `mst_containers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `container_no` VARCHAR(20) NOT NULL UNIQUE,
  `size` INT(2) DEFAULT 20,
  `type` VARCHAR(10) DEFAULT 'GP',
  `iso_code` VARCHAR(10),
  `owner_id` INT, -- Link to TID/Shipping Line
  `status` ENUM('EMPTY', 'FULL') DEFAULT 'EMPTY',
  `last_position` VARCHAR(50),
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dummy Data
INSERT INTO `mst_containers` (`container_no`, `size`, `type`, `iso_code`, `status`) VALUES
('MSKU1234567', 20, 'GP', '22G1', 'FULL'),
('TGHU9876543', 40, 'HC', '45G1', 'EMPTY'),
('CMAU1122334', 40, 'GP', '42G1', 'FULL');
