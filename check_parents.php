<?php
include 'index.php';
$parents = $this->db->where('parent_id', 0)->get('app_menus')->result();
foreach($parents as $p) {
    echo $p->menu_name . " (ID: " . $p->id . ")\n";
}
unlink(__FILE__);
