-- =============================================
-- KCN Terminal Petikemas - Location & Yard Master
-- =============================================

-- 1. Yard Blocks
CREATE TABLE IF NOT EXISTS `mst_yard_blocks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `block_name` VARCHAR(50) NOT NULL UNIQUE,
  `block_type` ENUM('EXPORT', 'IMPORT', 'REEFER', 'EMPTY', 'DANGER') DEFAULT 'EXPORT',
  `max_bay` INT DEFAULT 10,
  `max_row` INT DEFAULT 6,
  `max_tier` INT DEFAULT 5,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. Yard Slots (Current inventory mapping)
CREATE TABLE IF NOT EXISTS `opr_yard_inventory` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `block_id` INT NOT NULL,
  `bay` INT NOT NULL,
  `row` INT NOT NULL,
  `tier` INT NOT NULL,
  `container_no` VARCHAR(20),
  `last_update` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`block_id`) REFERENCES `mst_yard_blocks`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dummy Data
INSERT INTO `mst_yard_blocks` (`block_name`, `block_type`, `max_bay`, `max_row`, `max_tier`) VALUES
('BLOCK-A', 'EXPORT', 20, 8, 5),
('BLOCK-B', 'IMPORT', 20, 8, 5),
('BLOCK-C', 'REEFER', 10, 4, 4),
('BLOCK-D', 'EMPTY', 15, 10, 6);
