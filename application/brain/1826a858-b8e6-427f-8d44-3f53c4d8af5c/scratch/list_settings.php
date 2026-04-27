<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');
$res = $c->query("SELECT * FROM app_settings");
while($r = $res->fetch_assoc()) {
    echo "Key: {$r['setting_key']} | Value: {$r['setting_value']}\n";
}
?>
