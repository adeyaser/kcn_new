<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');

// Cari ID menu Vessel Profile yang baru dibuat
$res = $c->query("SELECT id FROM acl_menus WHERE menu_url = 'master/vessel_profile'");
if ($res->num_rows > 0) {
    $menu = $res->fetch_assoc();
    $menu_id = $menu['id'];
    $role_id = 1; // Admin

    // Cek apakah sudah ada di permissions
    $check = $c->query("SELECT * FROM acl_permissions WHERE role_id = $role_id AND menu_id = $menu_id");
    if ($check->num_rows == 0) {
        $c->query("INSERT INTO acl_permissions (role_id, menu_id, can_view, can_create, can_edit, can_delete) 
                   VALUES ($role_id, $menu_id, 1, 1, 1, 1)");
        echo "Permissions granted for Vessel Profile menu.\n";
    } else {
        $c->query("UPDATE acl_permissions SET can_view=1, can_create=1, can_edit=1, can_delete=1 
                   WHERE role_id = $role_id AND menu_id = $menu_id");
        echo "Permissions updated for Vessel Profile menu.\n";
    }
} else {
    echo "Menu Vessel Profile not found. Please register it first.\n";
}
?>
