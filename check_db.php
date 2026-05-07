<?php
include 'index.php';
$fields = $this->db->list_fields('opr_manifests');
echo json_encode($fields);
unlink(__FILE__);
