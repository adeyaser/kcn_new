-- =============================================
-- KCN Terminal Petikemas - Vessel Scheduling
-- =============================================

CREATE TABLE IF NOT EXISTS `opr_vessel_schedules` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `vessel_id` INT NOT NULL,
  `voyage_in` VARCHAR(50) NOT NULL,
  `voyage_out` VARCHAR(50) NOT NULL,
  `berth_id` INT,
  `eta` DATETIME,
  `etb` DATETIME, -- Estimate Berthing
  `etd` DATETIME,
  `status` ENUM('PLANNED', 'ARRIVED', 'BERTHED', 'DEPARTED', 'CANCELLED') DEFAULT 'PLANNED',
  `remarks` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`vessel_id`) REFERENCES `mst_vessels`(`id`),
  FOREIGN KEY (`berth_id`) REFERENCES `mst_berths`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dummy Data for Scheduler
INSERT INTO `opr_vessel_schedules` (`vessel_id`, `voyage_in`, `voyage_out`, `berth_id`, `eta`, `etd`, `status`) VALUES
(1, 'V001I', 'V001O', 1, DATE_ADD(NOW(), INTERVAL 2 DAY), DATE_ADD(NOW(), INTERVAL 3 DAY), 'PLANNED'),
(2, 'PAC22', 'PAC23', 2, DATE_ADD(NOW(), INTERVAL 5 DAY), DATE_ADD(NOW(), INTERVAL 7 DAY), 'PLANNED');
