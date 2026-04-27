<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');

$sql = "CREATE TABLE IF NOT EXISTS `opr_job_orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `job_no` VARCHAR(50) NOT NULL UNIQUE,
    `job_type` VARCHAR(50),
    `doc_no` VARCHAR(100),
    `truck_no` VARCHAR(20),
    `container_no` VARCHAR(20),
    `size` INT,
    `type` VARCHAR(10),
    `weight` DECIMAL(10,2),
    `status` ENUM('PENDING', 'GATE-IN', 'YARD', 'GATE-OUT') DEFAULT 'PENDING',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

if ($c->query($sql)) {
    echo "Table opr_job_orders created successfully.\n";
} else {
    echo "Error: " . $c->error . "\n";
}
?>
