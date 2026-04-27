<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');

// Check existing schedules
$res = $c->query("SELECT COUNT(*) as count FROM opr_vessel_schedules");
$row = $res->fetch_assoc();

if ($row['count'] == 0) {
    echo "No schedules found. Seeding dummy schedules...\n";
    // Get some vessel IDs
    $vessels = $c->query("SELECT id FROM mst_vessels LIMIT 2");
    while ($v = $vessels->fetch_assoc()) {
        $vid = $v['id'];
        $voyIn = rand(100, 999) . 'N';
        $voyOut = rand(100, 999) . 'S';
        $eta = date('Y-m-d H:i:s', strtotime('+1 day'));
        $etd = date('Y-m-d H:i:s', strtotime('+3 days'));
        
        $c->query("INSERT INTO opr_vessel_schedules (vessel_id, voyage_in, voyage_out, eta, etd, status) 
                   VALUES ($vid, '$voyIn', '$voyOut', '$eta', '$etd', 'PLANNED')");
    }
    echo "Seeded 2 dummy schedules.\n";
} else {
    echo "Found " . $row['count'] . " schedules.\n";
    // Check their status
    $res = $c->query("SELECT status, COUNT(*) as count FROM opr_vessel_schedules GROUP BY status");
    while ($r = $res->fetch_assoc()) {
        echo "Status: {$r['status']} | Count: {$r['count']}\n";
    }
}
?>
