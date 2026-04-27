<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');
$res = $c->query("SELECT * FROM acl_menus WHERE parent_id = 2 ORDER BY menu_order ASC");
while($r = $res->fetch_assoc()) {
    echo "Order: {$r['menu_order']} | Name: {$r['menu_name']} | URL: {$r['menu_url']}\n";
}
?>
