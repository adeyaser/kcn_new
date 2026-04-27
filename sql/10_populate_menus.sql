-- =============================================
-- KCN Terminal Petikemas - Populate Menus
-- =============================================

-- Master Data Parent (ID 2 usually)
-- Monitoring Parent (ID 3 usually)
-- Operations Parent (ID 4 usually)
-- Reports Parent (ID 5 usually)

INSERT IGNORE INTO `acl_menus` (`menu_name`, `menu_icon`, `menu_url`, `parent_id`, `menu_order`) VALUES
('Vessel Master', 'fas fa-ship', 'master/vessel', 2, 1),
('Berth Master', 'fas fa-anchor', 'master/berth', 2, 2),
('Vessel Scheduler', 'fas fa-calendar-alt', 'master/schedule', 2, 3),
('TID Master', 'fas fa-id-badge', 'master/tid', 2, 4),
('Container Master', 'fas fa-box', 'master/container', 2, 5),
('Yard Blocks', 'fas fa-th', 'setup/yard_block', 2, 6),

('Vessel Monitoring', 'fas fa-desktop', 'monitoring/vessel', 3, 1),
('Berth Plan Map', 'fas fa-map-marked-alt', 'monitoring/berth_plan', 3, 2),
('Berth Schedule', 'fas fa-stream', 'monitoring/berth_schedule', 3, 3),
('Gate Queue', 'fas fa-truck-loading', 'monitoring/queue', 3, 4),
('Equipment Status', 'fas fa-tools', 'monitoring/equipment', 3, 5),
('Trace & Track', 'fas fa-search-location', 'monitoring/trace', 3, 6),
('Weather & Interruption', 'fas fa-cloud-sun', 'monitoring/weather', 3, 7),
('Performance Center', 'fas fa-chart-line', 'monitoring/performance', 3, 8),

('Gate Operations', 'fas fa-door-open', 'operations/gate', 4, 1),
('Tally Operations', 'fas fa-clipboard-list', 'operations/tally', 4, 2),
('Yard Transfer', 'fas fa-exchange-alt', 'operations/housekeeping', 4, 3),
('Delivery Extension', 'fas fa-calendar-plus', 'operations/delivery_extension', 4, 4),

('Storage Billing', 'fas fa-file-invoice-dollar', 'billing/storage', 6, 1),

('Daily Report', 'fas fa-file-alt', 'reports/daily_report', 5, 1),
('Vessel SOF', 'fas fa-file-signature', 'reports/sof_report', 5, 2),
('Tally Productivity', 'fas fa-chart-bar', 'reports/tally_report', 5, 3),
('Truck Activity', 'fas fa-truck', 'reports/truck_report', 5, 4),

('System Settings', 'fas fa-cogs', 'setup/settings', 1, 99);

-- Re-run permission grant for admin
INSERT INTO `acl_permissions` (`role_id`, `menu_id`, `can_view`, `can_create`, `can_edit`, `can_delete`)
SELECT 1, id, 1, 1, 1, 1 FROM `acl_menus`
ON DUPLICATE KEY UPDATE 
`can_view` = 1, `can_create` = 1, `can_edit` = 1, `can_delete` = 1;
