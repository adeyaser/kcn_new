<?php
include 'index.php';
$query = "ALTER TABLE opr_manifests ADD COLUMN category ENUM('DISCHARGE', 'LOADING') DEFAULT 'DISCHARGE' AFTER planning_id";
if ($this->db->query($query)) {
    echo "Success: category column added to opr_manifests\n";
} else {
    echo "Error or already exists\n";
}
unlink(__FILE__);
