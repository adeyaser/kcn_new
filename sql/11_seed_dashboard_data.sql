-- =============================================
-- KCN Terminal Petikemas - Dashboard Seeds (Ultra Fix)
-- =============================================

-- 1. Seed Vessels
INSERT IGNORE INTO `mst_vessels` (`id`, `vessel_name`, `vessel_type`, `vessel_code`) VALUES
(1, 'MV. OCEAN NAVIGATOR', 'Container Ship', 'ONAV01'),
(2, 'MV. PACIFIC TRADER', 'Container Ship', 'PTRD02'),
(3, 'MV. GLOBAL STAR', 'Bulk Carrier', 'GSTR03');

-- 2. Seed Berths
INSERT IGNORE INTO `mst_berths` (`id`, `berth_code`, `berth_name`, `length`) VALUES
(1, 'B01', 'Berth 01', 300),
(2, 'B02', 'Berth 02', 300);

-- 3. Seed Vessel Schedules
INSERT IGNORE INTO `opr_vessel_schedules` (`vessel_id`, `voyage_in`, `voyage_out`, `eta`, `etb`, `etd`, `berth_id`, `status`) VALUES
(1, 'V2401', 'V2401', NOW(), NOW(), DATE_ADD(NOW(), INTERVAL 2 DAY), 1, 'BERTHED'),
(2, 'V2402', 'V2402', DATE_ADD(NOW(), INTERVAL 1 DAY), NULL, DATE_ADD(NOW(), INTERVAL 3 DAY), 2, 'PLANNED'),
(3, 'V2403', 'V2403', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 HOUR), 1, 'DEPARTED');

-- 4. Seed Tally Activities
INSERT IGNORE INTO `opr_tally_activities` (`planning_id`, `vessel_id`, `container_no`, `activity_type`, `location_type`, `activity_time`) VALUES
(0, 1, 'CONT001', 'DISCHARGE', 'VESSEL', DATE_SUB(NOW(), INTERVAL 10 MINUTE)),
(0, 1, 'CONT002', 'DISCHARGE', 'VESSEL', DATE_SUB(NOW(), INTERVAL 25 MINUTE)),
(0, 1, 'CONT003', 'DISCHARGE', 'VESSEL', DATE_SUB(NOW(), INTERVAL 45 MINUTE));
