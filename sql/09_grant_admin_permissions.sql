-- =============================================
-- KCN Terminal Petikemas - Admin Permissions
-- =============================================

INSERT INTO `acl_permissions` (`role_id`, `menu_id`, `can_view`, `can_create`, `can_edit`, `can_delete`)
SELECT 1, id, 1, 1, 1, 1 FROM `acl_menus`
ON DUPLICATE KEY UPDATE 
`can_view` = 1, `can_create` = 1, `can_edit` = 1, `can_delete` = 1;
