-- =============================================
-- KCN Terminal Petikemas - Master Data Tables
-- =============================================

-- 1. Master Vessel / Kapal
CREATE TABLE IF NOT EXISTS `mst_vessels` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `vessel_code` VARCHAR(50) NOT NULL UNIQUE,
  `vessel_name` VARCHAR(150) NOT NULL,
  `call_sign` VARCHAR(50),
  `flag` VARCHAR(50),
  `vessel_type` VARCHAR(50) DEFAULT 'Container',
  `grt` DECIMAL(10,2), -- Gross Register Tonnage
  `nrt` DECIMAL(10,2), -- Net Register Tonnage
  `dwt` DECIMAL(10,2), -- Deadweight Tonnage
  `loa` DECIMAL(10,2), -- Length Overall
  `beam` DECIMAL(10,2), -- Lebar Kapal
  `draft` DECIMAL(10,2),
  `year_built` INT(4),
  `shipping_line_id` INT,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_by` INT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. Master Vessel Profile (For 3D visualization data or detailed bay layout config)
CREATE TABLE IF NOT EXISTS `mst_vessel_profiles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `vessel_id` INT NOT NULL,
  `bay_count` INT,
  `row_count` INT,
  `tier_count_under_deck` INT,
  `tier_count_on_deck` INT,
  `config_json` JSON, -- Store complex configuration for 3D drawing
  FOREIGN KEY (`vessel_id`) REFERENCES `mst_vessels`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 3. Master Berth / Kade / Dermaga
CREATE TABLE IF NOT EXISTS `mst_berths` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `berth_code` VARCHAR(20) NOT NULL UNIQUE,
  `berth_name` VARCHAR(100) NOT NULL,
  `length` DECIMAL(10,2),
  `draft_max` DECIMAL(10,2),
  `coordinate_polygon` TEXT, -- Store LatLng for Leaflet
  `is_active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 4. Master Equipment (Cranes, Reach Stackers, Forklifts, etc)
CREATE TABLE IF NOT EXISTS `mst_equipments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `equipment_code` VARCHAR(50) NOT NULL UNIQUE,
  `equipment_name` VARCHAR(100) NOT NULL,
  `equipment_type` ENUM('QCC', 'RTG', 'RS', 'FL', 'TRUCK') NOT NULL,
  `capacity` DECIMAL(10,2),
  `status` ENUM('READY', 'MAINTENANCE', 'BROKEN') DEFAULT 'READY',
  `is_active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 5. Master Truck
CREATE TABLE IF NOT EXISTS `mst_trucks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `police_number` VARCHAR(20) NOT NULL UNIQUE,
  `truck_company` VARCHAR(100),
  `driver_name` VARCHAR(100),
  `driver_phone` VARCHAR(20),
  `rfid_tag` VARCHAR(50),
  `is_active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 6. Master TID (Terminal ID for external entities/forwarders)
CREATE TABLE IF NOT EXISTS `mst_tids` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tid_number` VARCHAR(50) NOT NULL UNIQUE,
  `company_name` VARCHAR(150) NOT NULL,
  `address` TEXT,
  `phone` VARCHAR(20),
  `email` VARCHAR(100),
  `is_active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dummy Data for Vessels
INSERT INTO `mst_vessels` (`vessel_code`, `vessel_name`, `call_sign`, `flag`, `loa`, `is_active`) VALUES
('VSL001', 'MV. OCEAN NAVIGATOR', 'OCNV', 'Panama', 200.5, 1),
('VSL002', 'MV. PACIFIC TRADER', 'PCTR', 'Singapore', 180.2, 1),
('VSL003', 'MV. ASIAN GLORY', 'ASGL', 'Indonesia', 210.0, 1);

INSERT INTO `mst_vessel_profiles` (`vessel_id`, `bay_count`, `row_count`, `tier_count_under_deck`, `tier_count_on_deck`) VALUES
(1, 20, 10, 6, 4),
(2, 18, 8, 4, 4);

INSERT INTO `mst_berths` (`berth_code`, `berth_name`, `length`, `draft_max`) VALUES
('B1', 'Berth 1 KCN', 250, 12),
('B2', 'Berth 2 KCN', 200, 10);
