<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');
$res = $c->query("DESCRIBE acl_menus");
while($r = $res->fetch_assoc()) {
    echo "Field: {$r['Field']} | Type: {$r['Type']}\n";
}
?>
