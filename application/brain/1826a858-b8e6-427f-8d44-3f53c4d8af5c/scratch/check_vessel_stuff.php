<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');

echo "--- Tables ---\n";
$res = $c->query("SHOW TABLES LIKE 'mst_vessel%'");
while($r = $res->fetch_array()) echo $r[0] . "\n";

echo "\n--- Menus ---\n";
$res = $c->query("SELECT * FROM acl_menus WHERE url LIKE '%vessel%'");
while($r = $res->fetch_assoc()) {
    echo "ID: {$r['id']} | Title: {$r['title']} | URL: {$r['url']}\n";
}
?>
