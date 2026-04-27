<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');
$settings = [
    'map_lat' => '-6.0920',
    'map_lng' => '106.9530',
    'map_zoom' => '16',
    'terminal_logo' => 'assets/img/logo.png'
];

foreach ($settings as $key => $val) {
    $c->query("INSERT IGNORE INTO app_settings (setting_key, setting_value) VALUES ('$key', '$val')");
}
echo "Map settings initialized.\n";
?>
