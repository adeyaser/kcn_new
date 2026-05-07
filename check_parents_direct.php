<?php
$conn = new mysqli('localhost', 'root', '', 'db_op_container');
$res = $conn->query('SELECT menu_name FROM app_menus WHERE parent_id = 0');
while($row = $res->fetch_assoc()) { echo $row['menu_name'] . "\n"; }
$conn->close();
unlink(__FILE__);
