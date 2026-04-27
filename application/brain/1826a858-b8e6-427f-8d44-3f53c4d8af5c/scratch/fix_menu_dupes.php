<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_op_container';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Cleaning up duplicate menus (Safe Mode)...\n";

// 1. Find all menu IDs with the target URL
$sql_find = "SELECT id FROM acl_menus WHERE menu_url = 'master/schedule'";
$result = $conn->query($sql_find);
$menu_ids = [];
while ($row = $result->fetch_assoc()) {
    $menu_ids[] = $row['id'];
}

if (!empty($menu_ids)) {
    $ids_str = implode(',', $menu_ids);
    // 2. Delete permissions first
    $conn->query("DELETE FROM acl_permissions WHERE menu_id IN ($ids_str)");
    // 3. Then delete the menus
    $conn->query("DELETE FROM acl_menus WHERE id IN ($ids_str)");
}

// 4. Get the Master Data parent ID
$sql2 = "SELECT id FROM acl_menus WHERE menu_name = 'Master Data' AND parent_id = 0 LIMIT 1";
$result = $conn->query($sql2);
$parent_id = 0;
if ($row = $result->fetch_assoc()) {
    $parent_id = $row['id'];
}

if ($parent_id > 0) {
    // 5. Insert the single correct menu
    $sql3 = "INSERT INTO acl_menus (menu_name, menu_icon, menu_url, parent_id, menu_order, is_active) 
             VALUES ('Vessel Scheduler', 'fas fa-calendar-alt', 'master/schedule', $parent_id, 3, 1)";
    $conn->query($sql3);
    $menu_id = $conn->insert_id;

    // 6. Set permissions for admin (role_id 1)
    $sql5 = "INSERT INTO acl_permissions (role_id, menu_id, can_view, can_create, can_edit, can_delete) 
             VALUES (1, $menu_id, 1, 1, 1, 1)";
    $conn->query($sql5);

    echo "Success! Menu 'Vessel Scheduler' has been fixed and duplicates removed.\n";
} else {
    echo "Error: Could not find 'Master Data' parent menu.\n";
}

$conn->close();
?>
