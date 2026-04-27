<div class="row g-4">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card-custom shadow-sm p-4 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="text-muted extra-small text-uppercase fw-bold mb-1">Antrean Truk (Yard)</h6>
                    <h3 class="text-dark fw-bold mb-0"><?= count($pending_lifts) ?></h3>
                    <span class="badge bg-primary-glow text-primary border border-primary border-opacity-10 mt-2">Menunggu Lift</span>
                </div>
                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                    <i class="fas fa-truck-loading text-primary fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card-custom shadow-sm p-4 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="text-muted extra-small text-uppercase fw-bold mb-1">Total Gerakan Hari Ini</h6>
                    <h3 class="text-dark fw-bold mb-0"><?= $stats['total_today'] ?></h3>
                    <span class="text-success extra-small"><i class="fas fa-check-circle me-1"></i>Live Updates</span>
                </div>
                <div class="bg-success bg-opacity-10 rounded-3 p-3">
                    <i class="fas fa-history text-success fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-12">
        <div class="card-custom shadow-sm p-4 h-100 bg-primary text-white border-0">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="fw-bold mb-1">Mode Operasional Terencana</h5>
                    <p class="small opacity-75 mb-0">Sistem mendeteksi truk yang sudah Gate In secara otomatis. Silakan klik tombol "Proses" pada antrean di bawah.</p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-light fw-bold px-4" onclick="location.reload()"><i class="fas fa-sync-alt me-2"></i>Refresh Data</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Queue -->
    <div class="col-xl-7">
        <div class="card-custom shadow-sm">
            <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center p-4">
                <h6 class="mb-0 text-dark fw-bold"><i class="fas fa-clock me-2 text-warning"></i>Antrean Lift Aktif (Planning)</h6>
                <span class="badge bg-light text-muted border px-2">Update: <?= date('H:i:s') ?></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Truk / Polisi</th>
                                <th>Kontainer</th>
                                <th>Jenis</th>
                                <th>Waktu Gate In</th>
                                <th width="120" class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($pending_lifts)): ?>
                                <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada truk di dalam terminal yang menunggu lift</td></tr>
                            <?php else: ?>
                                <?php foreach($pending_lifts as $p): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-primary"><?= $p->police_number ?></td>
                                    <td>
                                        <div class="fw-medium"><?= $p->container_no ?></div>
                                        <small class="text-muted"><?= $p->container_size ?>' <?= $p->container_type ?></small>
                                    </td>
                                    <td>
                                        <?php if($p->transaction_type == 'IN'): ?>
                                            <span class="badge bg-info-glow text-info border border-info border-opacity-10 px-3">RECEIVING</span>
                                        <?php else: ?>
                                            <span class="badge bg-success-glow text-success border border-success border-opacity-10 px-3">DELIVERY</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small text-muted"><?= date('H:i', strtotime($p->gate_in_time)) ?></td>
                                    <td class="text-center pe-4">
                                        <button class="btn btn-primary btn-sm rounded-pill px-3" onclick='processLift(<?= json_encode($p) ?>)'>
                                            Proses <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent History -->
    <div class="col-xl-5">
        <div class="card-custom shadow-sm h-100">
            <div class="card-header border-bottom border-secondary p-4">
                <h6 class="mb-0 text-dark fw-bold"><i class="fas fa-history me-2 text-primary"></i>Log Gerakan Terakhir</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th class="ps-4">Time</th>
                                <th>Container</th>
                                <th>Equip</th>
                                <th class="pe-4">Posisi Yard</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recent_activities as $r): ?>
                            <tr>
                                <td class="ps-4 small text-muted"><?= date('H:i', strtotime($r->activity_time)) ?></td>
                                <td class="fw-bold small"><?= $r->container_no ?></td>
                                <td class="small"><?= $r->equipment_code ?></td>
                                <td class="pe-4"><span class="badge bg-light text-dark border"><?= $r->location_block ?>-<?= $r->location_slot ?>-<?= $r->location_row ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Process Lift -->
<div class="modal fade" id="modalLift" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">Konfirmasi Lift Operation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formLift">
                    <input type="hidden" name="gate_transaction_id" id="gate_id">
                    
                    <div class="bg-light rounded-3 p-3 mb-4 d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <i class="fas fa-box fa-2x text-primary opacity-25"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark" id="display_container">-</div>
                            <small class="text-muted" id="display_truck">-</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Alat yang Digunakan</label>
                            <select name="equipment_id" class="form-select border-light bg-light" required>
                                <?php foreach($equipments as $e): ?>
                                    <option value="<?= $e->id ?>"><?= $e->equipment_code ?> - <?= $e->equipment_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Block</label>
                            <input type="text" name="block" class="form-control border-light bg-light" placeholder="Contoh: B" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Slot</label>
                            <input type="text" name="slot" class="form-control border-light bg-light" placeholder="05" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Row</label>
                            <input type="text" name="row" class="form-control border-light bg-light" placeholder="01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Tier</label>
                            <input type="text" name="tier" class="form-control border-light bg-light" placeholder="1" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary fw-bold px-4" onclick="submitLift()">Simpan Operasi</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
function processLift(data) {
    $('#formLift')[0].reset();
    $('#gate_id').val(data.id);
    $('#display_container').text(data.container_no + ' (' + data.container_size + 'ft)');
    $('#display_truck').text(data.police_number + ' - ' + (data.transaction_type == 'IN' ? 'RECEIVING' : 'DELIVERY'));
    
    // Pre-fill planned coordinates
    if (data.planned_block) {
        $('input[name="block"]').val(data.planned_block);
        $('input[name="slot"]').val(data.planned_bay);
        $('input[name="row"]').val(data.planned_row);
        $('input[name="tier"]').val(data.planned_tier);
    }

    $('#modalLift').modal('show');
}

function submitLift() {
    $.ajax({
        url: "<?= site_url('operations/lift/ajax_save') ?>",
        type: "POST",
        data: $('#formLift').serialize(),
        dataType: "JSON",
        success: function(res) {
            if(res.status) {
                $('#modalLift').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
