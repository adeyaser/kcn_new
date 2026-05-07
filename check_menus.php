<?php
include 'index.php';
$menus = $this->db->get('app_menus')->result();
foreach($menus as $m) {
    echo $m->menu_name . " (" . $m->menu_url . ")\n";
}
unlink(__FILE__);
