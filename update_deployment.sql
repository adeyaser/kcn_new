-- ==============================================================================
-- SCRIPT UPDATE DATABASE DEPLOYMENT TOS KCN
-- Dijalankan pada: phpMyAdmin Hosting
-- ==============================================================================

-- 1. UPDATE STRUKTUR TABEL REQUEST PLANNING (Menambahkan Tipe BOTH)
ALTER TABLE `opr_planning_requests` 
MODIFY COLUMN `request_type` ENUM('INBOUND', 'OUTBOUND', 'BOTH') NOT NULL;

-- 2. MENAMBAHKAN MENU DOKUMENTASI (Jika belum ada)
INSERT INTO `acl_menus` (`menu_name`, `menu_url`, `menu_icon`, `parent_id`, `menu_order`) 
SELECT 'Dokumentasi', 'documentation', 'fas fa-book', 0, 99
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `acl_menus` WHERE `menu_url` = 'documentation');

-- 3. MEMBERIKAN IZIN AKSES DOKUMENTASI UNTUK ADMIN (Role ID: 1)
INSERT INTO `acl_permissions` (`role_id`, `menu_id`, `can_view`, `can_create`, `can_edit`, `can_delete`)
SELECT 1, id, 1, 0, 0, 0 
FROM `acl_menus` WHERE `menu_url` = 'documentation'
AND NOT EXISTS (
    SELECT 1 FROM `acl_permissions` p 
    JOIN `acl_menus` m ON p.menu_id = m.id 
    WHERE m.menu_url = 'documentation' AND p.role_id = 1
);

-- 4. MENAMBAHKAN MENU MASTER GATE (Ke dalam Parent Menu 'Master Data')
INSERT INTO `acl_menus` (`menu_name`, `menu_url`, `menu_icon`, `parent_id`, `menu_order`) 
SELECT 'Master Gate', 'master/gate', 'fas fa-door-open', 
       (SELECT id FROM `acl_menus` WHERE `menu_name` LIKE '%Master%' AND `parent_id` = 0 LIMIT 1), 
       10
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `acl_menus` WHERE `menu_url` = 'master/gate');

-- 5. MEMBERIKAN IZIN AKSES MASTER GATE UNTUK ADMIN (Role ID: 1)
INSERT INTO `acl_permissions` (`role_id`, `menu_id`, `can_view`, `can_create`, `can_edit`, `can_delete`)
SELECT 1, id, 1, 1, 1, 1 
FROM `acl_menus` WHERE `menu_url` = 'master/gate'
AND NOT EXISTS (
    SELECT 1 FROM `acl_permissions` p 
    JOIN `acl_menus` m ON p.menu_id = m.id 
    WHERE m.menu_url = 'master/gate' AND p.role_id = 1
);

-- 6. MEMASTIKAN MENU DOKUMENTASI BERADA DI PALING BAWAH
UPDATE `acl_menus` SET `menu_order` = 99 WHERE `menu_url` = 'documentation';
