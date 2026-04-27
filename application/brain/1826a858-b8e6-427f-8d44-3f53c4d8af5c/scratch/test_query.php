<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');

$sql = "SELECT s.*, v.vessel_name 
        FROM opr_vessel_schedules s 
        LEFT JOIN mst_vessels v ON v.id = s.vessel_id";

$res = $c->query($sql);
if ($res->num_rows > 0) {
    echo "Found " . $res->num_rows . " schedules with JOIN:\n";
    while ($r = $res->fetch_assoc()) {
        echo "ID: {$r['id']} | Vessel: {$r['vessel_name']} | Status: {$r['status']}\n";
    }
} else {
    echo "No schedules found with JOIN. Checking raw table...\n";
    $raw = $c->query("SELECT * FROM opr_vessel_schedules");
    echo "Raw table count: " . $raw->num_rows . "\n";
}
?>
