<?php
include 'index.php';
$queries = [
    "ALTER TABLE opr_manifests ADD COLUMN planned_block_id INT NULL AFTER pod",
    "ALTER TABLE opr_manifests ADD COLUMN planned_bay INT NULL AFTER planned_block_id",
    "ALTER TABLE opr_manifests ADD COLUMN planned_row INT NULL AFTER planned_bay",
    "ALTER TABLE opr_manifests ADD COLUMN planned_tier INT NULL AFTER planned_row"
];

foreach ($queries as $q) {
    if ($this->db->query($q)) {
        echo "Success: $q\n";
    } else {
        echo "Error or already exists: $q\n";
    }
}
unlink(__FILE__);
