<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');

// Cek menu terakhir untuk menentukan urutan
$res = $c->query("SELECT MAX(menu_order) as max_order FROM acl_menus WHERE parent_id = 2");
$row = $res->fetch_assoc();
$new_order = $row['max_order'] + 1;

$sql = "INSERT INTO acl_menus (menu_name, menu_icon, menu_url, parent_id, menu_order, is_active) 
        VALUES ('Vessel Profile', 'fas fa-id-card', 'master/vessel_profile', 2, $new_order, 1)";

if ($c->query($sql)) {
    echo "Menu Vessel Profile registered successfully.\n";
} else {
    echo "Error: " . $c->error . "\n";
}
?>
