<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');

echo "--- Menu Entry ---\n";
$res = $c->query("SELECT * FROM acl_menus WHERE menu_url = 'master/vessel_profile'");
$menu = $res->fetch_assoc();
print_r($menu);

if ($menu) {
    echo "\n--- Permission Entry (Role 1) ---\n";
    $m_id = $menu['id'];
    $res = $c->query("SELECT * FROM acl_permissions WHERE menu_id = $m_id AND role_id = 1");
    print_r($res->fetch_assoc());
}
?>
