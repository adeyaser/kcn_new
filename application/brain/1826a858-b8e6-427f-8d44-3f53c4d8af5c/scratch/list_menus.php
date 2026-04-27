<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');
$res = $c->query("SELECT * FROM acl_menus");
while($r = $res->fetch_assoc()) {
    echo "ID: {$r['id']} | Name: {$r['menu_name']} | URL: {$r['menu_url']} | Parent: {$r['parent_id']}\n";
}
?>
