<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');
$c->query("ALTER TABLE opr_planning_requests ADD COLUMN IF NOT EXISTS schedule_id INT AFTER request_no");
echo "Column schedule_id added to opr_planning_requests.\n";
?>
