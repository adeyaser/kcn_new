-- =============================================
-- KCN Terminal Petikemas - Operations & Planning Tables
-- =============================================

-- 1. Planning Request (Vessel Visit & Planning Request)
CREATE TABLE IF NOT EXISTS `opr_planning_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `request_no` VARCHAR(50) NOT NULL UNIQUE,
  `vessel_id` INT NOT NULL,
  `voyage_in` VARCHAR(50) NOT NULL,
  `voyage_out` VARCHAR(50) NOT NULL,
  `service_type` ENUM('Domestic', 'International') DEFAULT 'Domestic',
  
  -- Time Configs
  `eta` DATETIME,
  `etd` DATETIME,
  `open_stack` DATETIME,
  `closing_time` DATETIME,
  `closing_time_doc` DATETIME,
  `start_shift_reefer` DATETIME,
  `end_shift_reefer` DATETIME,
  
  -- Capacity
  `booking_limit` INT,
  
  -- Ports
  `pod` VARCHAR(100), -- Port of Discharge
  `fpod` VARCHAR(100), -- Final Port of Discharge
  
  -- Customs (For International mostly)
  `customs_document_type` VARCHAR(100),
  `doc_bc_1_2` VARCHAR(100),
  `doc_npe` VARCHAR(100),
  `doc_pkbe` VARCHAR(100),
  `doc_sppbe_batal` VARCHAR(100),
  `doc_sppbe_pindah` VARCHAR(100),
  `doc_re_ekspor` VARCHAR(100),
  `doc_penegahan` VARCHAR(100),
  `doc_empty_ekspor` VARCHAR(100),
  `doc_sppb_kppt` VARCHAR(100),
  `doc_bc_1_1` VARCHAR(100),
  `doc_kek` VARCHAR(100),
  
  -- Files
  `manifest_file` VARCHAR(255),
  `bc_file` VARCHAR(255),
  
  -- Status
  `status` ENUM('DRAFT', 'REQUESTED', 'APPROVED', 'REJECTED', 'OPERATING', 'COMPLETED') DEFAULT 'DRAFT',
  `approval_note` TEXT,
  
  `created_by` INT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`vessel_id`) REFERENCES `mst_vessels`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. Manifest Container Data (Uploaded from Planning)
CREATE TABLE IF NOT EXISTS `opr_manifests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `planning_id` INT NOT NULL,
  `container_no` VARCHAR(20) NOT NULL,
  `size` INT(2), -- 20, 40, 45
  `type` VARCHAR(10), -- GP, HC, RF, OT, FR
  `status` ENUM('FCL', 'LCL', 'MTY') DEFAULT 'FCL',
  `weight` DECIMAL(10,2),
  `seal_no` VARCHAR(50),
  `hz` ENUM('Y', 'N') DEFAULT 'N',
  `imdg_class` VARCHAR(20),
  `un_no` VARCHAR(20),
  `temp_req` DECIMAL(5,2), -- For reefer
  `bl_no` VARCHAR(100),
  `consignee` VARCHAR(150),
  `pod` VARCHAR(100),
  `fpod` VARCHAR(100),
  FOREIGN KEY (`planning_id`) REFERENCES `opr_planning_requests`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
