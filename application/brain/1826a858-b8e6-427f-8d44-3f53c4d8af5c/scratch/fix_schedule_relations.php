<?php
$c = new mysqli('localhost', 'root', '', 'db_op_container');

// Ambil ID kapal pertama yang ada di master
$vessel_res = $c->query("SELECT id FROM mst_vessels LIMIT 1");
if ($vessel_res->num_rows > 0) {
    $vessel = $vessel_res->fetch_assoc();
    $v_id = $vessel['id'];
    
    // Update semua jadwal agar menggunakan ID kapal yang valid ini (untuk testing agar muncul)
    $c->query("UPDATE opr_vessel_schedules SET vessel_id = $v_id WHERE vessel_id NOT IN (SELECT id FROM mst_vessels)");
    echo "Relasi data jadwal kapal telah diperbaiki.\n";
} else {
    echo "Master kapal kosong. Harap isi data master kapal terlebih dahulu.\n";
}
?>
