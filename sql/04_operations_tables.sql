-- =============================================
-- KCN Terminal Petikemas - Operations Tables (Gate, Tally, Stacking)
-- =============================================

-- 1. Gate Transactions (TCA System)
CREATE TABLE IF NOT EXISTS `opr_gate_transactions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `gate_no` VARCHAR(50) NOT NULL UNIQUE,
  `planning_id` INT, -- Link to planning request if any
  `truck_id` INT,
  `container_no` VARCHAR(20),
  `container_size` INT(2),
  `container_type` VARCHAR(10),
  `transaction_type` ENUM('GATE_IN', 'GATE_OUT') NOT NULL,
  `activity_type` ENUM('RECEIVING', 'DELIVERY', 'EMPTY_IN', 'EMPTY_OUT') NOT NULL,
  
  -- Gate In Info
  `gate_in_time` DATETIME,
  `gate_in_operator` INT,
  
  -- Gate Out Info
  `gate_out_time` DATETIME,
  `gate_out_operator` INT,
  
  -- Truck/Driver Info (Captured at gate if not in master)
  `police_number` VARCHAR(20),
  `driver_name` VARCHAR(100),
  `rfid_tag` VARCHAR(50),
  
  -- Status
  `status` ENUM('CHECKED_IN', 'IN_YARD', 'CHECKED_OUT', 'CANCELLED') DEFAULT 'CHECKED_IN',
  
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`planning_id`) REFERENCES `opr_planning_requests`(`id`),
  FOREIGN KEY (`truck_id`) REFERENCES `mst_trucks`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. Tally Operations (Bongkar Muat / Lift On Lift Off)
CREATE TABLE IF NOT EXISTS `opr_tally_activities` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `planning_id` INT NOT NULL,
  `vessel_id` INT,
  `container_no` VARCHAR(20) NOT NULL,
  `activity_type` ENUM('DISCHARGE', 'LOAD', 'LIFT_ON', 'LIFT_OFF', 'RE-STOW') NOT NULL,
  
  -- Equipment Used
  `equipment_id` INT, -- Crane or RS ID
  `operator_id` INT,
  
  -- Location (Yard or Vessel)
  `bay` INT,
  `row` INT,
  `tier` INT,
  `location_type` ENUM('YARD', 'VESSEL') NOT NULL,
  
  `activity_time` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `tally_operator_id` INT,
  
  `status` ENUM('PENDING', 'COMPLETED', 'CANCELLED') DEFAULT 'COMPLETED',
  `remarks` TEXT,
  
  FOREIGN KEY (`planning_id`) REFERENCES `opr_planning_requests`(`id`),
  FOREIGN KEY (`vessel_id`) REFERENCES `mst_vessels`(`id`),
  FOREIGN KEY (`equipment_id`) REFERENCES `mst_equipments`(`id`),
  FOREIGN KEY (`tally_operator_id`) REFERENCES `acl_users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 3. Operational Interruptions (Machine broken, Weather, etc.)
CREATE TABLE IF NOT EXISTS `opr_interruptions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `vessel_id` INT,
  `equipment_id` INT,
  `interruption_type` ENUM('MACHINE_BROKEN', 'WEATHER', 'POWER_OUTAGE', 'STRIKE', 'OTHER') NOT NULL,
  `start_time` DATETIME NOT NULL,
  `end_time` DATETIME,
  `duration_minutes` INT,
  `remarks` TEXT,
  `created_by` INT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
