<?php
$conn = new mysqli('localhost', 'root', '', 'db_op_container');
$res = $conn->query('DESC opr_lift_activities');
if(!$res) echo "Table not found: " . $conn->error . "\n";
else while($r = $res->fetch_assoc()) echo $r['Field'] . " | " . $r['Type'] . "\n";
echo "---\n";
$res2 = $conn->query("SELECT ENUM_COL FROM information_schema.COLUMNS WHERE TABLE_NAME='opr_gate_transactions' AND TABLE_SCHEMA='db_op_container' AND COLUMN_NAME='status'");
$res3 = $conn->query("SELECT status FROM opr_gate_transactions LIMIT 5");
while($r = $res3->fetch_assoc()) echo "gate status: " . $r['status'] . "\n";
$conn->close();
unlink(__FILE__);
