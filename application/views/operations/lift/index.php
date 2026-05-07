<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 d-flex align-items-center gap-3" style="border-left:3px solid #f59e0b;">
            <div style="width:44px;height:44px;background:rgba(245,158,11,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-clock" style="color:#f59e0b;font-size:18px;"></i>
            </div>
            <div>
                <div class="fw-bold fs-4" id="stat_pending"><?= $stats['pending'] ?></div>
                <div class="small text-muted">Menunggu Lift</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 d-flex align-items-center gap-3" style="border-left:3px solid #3b82f6;">
            <div style="width:44px;height:44px;background:rgba(59,130,246,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-arrow-down" style="color:#3b82f6;font-size:18px;"></i>
            </div>
            <div>
                <div class="fw-bold fs-4" id="stat_liftoff"><?= $stats['lift_off'] ?></div>
                <div class="small text-muted">Lift Off Hari Ini</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 d-flex align-items-center gap-3" style="border-left:3px solid #10b981;">
            <div style="width:44px;height:44px;background:rgba(16,185,129,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-arrow-up" style="color:#10b981;font-size:18px;"></i>
            </div>
            <div>
                <div class="fw-bold fs-4" id="stat_lifton"><?= $stats['lift_on'] ?></div>
                <div class="small text-muted">Lift On Hari Ini</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 d-flex align-items-center gap-3" style="border-left:3px solid #6366f1;">
            <div style="width:44px;height:44px;background:rgba(99,102,241,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-layer-group" style="color:#6366f1;font-size:18px;"></i>
            </div>
            <div>
                <div class="fw-bold fs-4" id="stat_total"><?= $stats['total_today'] ?></div>
                <div class="small text-muted">Total Gerakan</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pending Queue -->
    <div class="col-xl-7">
        <div class="card-custom h-100">
            <div class="card-header d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                <div>
                    <h6 class="mb-0 fw-bold"><i class="fas fa-truck-loading me-2 text-warning"></i>Antrian Gate-In — Menunggu Proses Lift</h6>
                    <small class="text-muted">Truk yang sudah masuk gerbang, kontainer belum dipindahkan ke Yard</small>
                </div>
                <button class="btn btn-sm btn-outline-secondary" onclick="location.reload()"><i class="fas fa-sync-alt"></i></button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead style="background:rgba(255,255,255,0.03);font-size:11px;letter-spacing:.5px;text-transform:uppercase;color:#94a3b8;">
                            <tr>
                                <th class="ps-4 py-3">Truk / Driver</th>
                                <th class="py-3">Kontainer</th>
                                <th class="py-3">Jenis</th>
                                <th class="py-3">Planning</th>
                                <th class="py-3">Gate-In</th>
                                <th class="py-3 pe-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(empty($pending_lifts)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5" style="color:#64748b;">
                                    <i class="fas fa-check-circle fa-2x mb-2 d-block" style="color:#10b981;opacity:.4;"></i>
                                    Tidak ada truk yang menunggu proses lift
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($pending_lifts as $p): ?>
                            <?php
                                $lift_label = ($p->activity_type == 'RECEIVING') ? 'LIFT OFF' : 'LIFT ON';
                                $lift_color = ($p->activity_type == 'RECEIVING') ? '#3b82f6' : '#10b981';
                            ?>
                            <tr style="border-bottom:1px solid rgba(255,255,255,0.04);">
                                <td class="ps-4 py-3">
                                    <div class="fw-semibold" style="color:#60a5fa;"><?= $p->police_number ?? '-' ?></div>
                                    <small class="text-muted"><?= $p->driver_name ?? '-' ?></small>
                                </td>
                                <td class="py-3">
                                    <div class="fw-bold" style="color:#e2e8f0;"><?= $p->container_no ?? 'N/A' ?></div>
                                    <small class="text-muted"><?= $p->container_size ?>ft <?= $p->container_type ?></small>
                                </td>
                                <td class="py-3">
                                    <span class="badge px-2 py-1" style="background:<?= $lift_color ?>20;border:1px solid <?= $lift_color ?>40;color:<?= $lift_color ?>;border-radius:6px;">
                                        <?= $lift_label ?>
                                    </span>
                                </td>
                                <td class="py-3">
                                    <div class="small fw-semibold"><?= $p->request_no ?? '-' ?></div>
                                    <small class="text-muted"><?= $p->vessel_name ?? '' ?></small>
                                </td>
                                <td class="py-3 small text-muted"><?= $p->gate_in_time ? date('H:i', strtotime($p->gate_in_time)) : '-' ?></td>
                                <td class="py-3 pe-4 text-center">
                                    <button class="btn btn-sm fw-semibold px-3"
                                        style="background:linear-gradient(135deg,<?= $lift_color ?>,<?= $lift_color ?>cc);border:none;color:#fff;border-radius:8px;"
                                        onclick='processLift(<?= json_encode($p) ?>)'>
                                        <?= $lift_label ?> <i class="fas fa-arrow-right ms-1"></i>
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

    <!-- Recent Activities -->
    <div class="col-xl-5">
        <div class="card-custom h-100">
            <div class="card-header px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                <h6 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-info"></i>Log Gerakan Terakhir</h6>
            </div>
            <div class="card-body p-0" style="max-height:500px;overflow-y:auto;">
                <?php if(empty($recent_activities)): ?>
                    <div class="text-center py-5 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>Belum ada aktivitas</div>
                <?php else: ?>
                <?php foreach($recent_activities as $r): ?>
                <?php $color = ($r->activity_type == 'LIFT OFF') ? '#3b82f6' : '#10b981'; ?>
                <div class="d-flex align-items-center gap-3 px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                    <div style="width:36px;height:36px;background:<?= $color ?>20;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas <?= ($r->activity_type == 'LIFT OFF') ? 'fa-arrow-down' : 'fa-arrow-up' ?>" style="color:<?= $color ?>;font-size:13px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold small" style="color:#e2e8f0;"><?= $r->container_no ?></div>
                        <div class="text-muted" style="font-size:11px;">
                            <?= $r->location_block ?>-<?= $r->location_slot ?>-<?= $r->location_row ?>-T<?= $r->location_tier ?>
                            &nbsp;·&nbsp; <?= $r->equipment_code ?? '-' ?>
                        </div>
                    </div>
                    <div class="text-muted" style="font-size:11px;flex-shrink:0;">
                        <?= $r->activity_time ? date('H:i', strtotime($r->activity_time)) : '-' ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Process Lift -->
<div class="modal fade" id="modalLift" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="background:#0f172a;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.5);">
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div id="modalIcon" style="width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:rgba(59,130,246,.2);">
                        <i class="fas fa-arrow-down" id="modalIconArrow" style="color:#3b82f6;font-size:16px;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" id="modalTitle">Proses Lift Off</h5>
                        <small class="text-white-50" id="modalSubtitle">Kontainer turun dari truk ke Yard</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white opacity-75" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <form id="formLift">
                    <input type="hidden" name="gate_transaction_id" id="gate_id">

                    <!-- Container Info Card -->
                    <div class="p-3 mb-4 rounded-3" style="background:#1e293b;">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-box-open fa-2x" style="color:#6366f1;opacity:.6;"></i>
                            <div>
                                <div class="fw-bold text-white" id="display_container">-</div>
                                <div class="small text-white-50" id="display_truck">-</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white-50 small fw-semibold text-uppercase" style="letter-spacing:.4px;">Alat Berat (Equipment)</label>
                        <select name="equipment_id" class="form-select border-0 text-white" style="background:#1e293b;border-radius:10px;" required>
                            <option value="">— Pilih Equipment —</option>
                            <?php foreach($equipments as $e): ?>
                                <option value="<?= $e->id ?>"><?= $e->equipment_code ?> – <?= $e->equipment_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-white-50 small fw-semibold text-uppercase" style="letter-spacing:.4px;">Posisi Penempatan di Yard</label>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label text-white-50 small">Block</label>
                            <input type="text" name="block" class="form-control border-0 text-white text-center fw-bold" style="background:#1e293b;border-radius:10px;" placeholder="A" maxlength="5" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-white-50 small">Bay / Slot</label>
                            <input type="text" name="slot" class="form-control border-0 text-white text-center fw-bold" style="background:#1e293b;border-radius:10px;" placeholder="01" maxlength="5" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-white-50 small">Row</label>
                            <input type="text" name="row" class="form-control border-0 text-white text-center fw-bold" style="background:#1e293b;border-radius:10px;" placeholder="01" maxlength="5" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-white-50 small">Tier</label>
                            <input type="number" name="tier" class="form-control border-0 text-white text-center fw-bold" style="background:#1e293b;border-radius:10px;" placeholder="1" min="1" max="6" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-2 gap-2">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn px-4 fw-semibold" id="btnSubmitLift" onclick="submitLift()"
                    style="background:linear-gradient(135deg,#3b82f6,#6366f1);border:none;color:#fff;">
                    <i class="fas fa-check me-2"></i>Konfirmasi & Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
var currentLiftType = 'LIFT OFF';

function processLift(data) {
    $('#formLift')[0].reset();
    $('#gate_id').val(data.id);

    var isLiftOff = (data.activity_type == 'RECEIVING');
    currentLiftType = isLiftOff ? 'LIFT OFF' : 'LIFT ON';

    var color = isLiftOff ? '#3b82f6' : '#10b981';
    var icon  = isLiftOff ? 'fa-arrow-down' : 'fa-arrow-up';

    $('#modalTitle').text(isLiftOff ? 'Proses Lift Off — Receiving' : 'Proses Lift On — Delivery');
    $('#modalSubtitle').text(isLiftOff ? 'Kontainer diturunkan dari truk ke Yard' : 'Kontainer diangkat dari Yard ke truk');
    $('#modalIcon').css('background', color + '20');
    $('#modalIconArrow').css('color', color).removeClass('fa-arrow-down fa-arrow-up').addClass(icon);
    $('#btnSubmitLift').css('background', 'linear-gradient(135deg,' + color + ',' + color + 'cc)');

    $('#display_container').text((data.container_no || 'N/A') + ' · ' + (data.container_size || '') + 'ft ' + (data.container_type || ''));
    $('#display_truck').text((data.police_number || '-') + ' — ' + (data.driver_name || '-'));

    $('#modalLift').modal('show');
}

function submitLift() {
    var btn = $('#btnSubmitLift');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');

    $.ajax({
        url: "<?= site_url('operations/lift/ajax_save') ?>",
        type: "POST",
        data: $('#formLift').serialize(),
        dataType: "json",
        success: function(res) {
            btn.prop('disabled', false).html('<i class="fas fa-check me-2"></i>Konfirmasi & Simpan');
            if (res.status) {
                $('#modalLift').modal('hide');
                Toast.fire({ icon: 'success', title: res.message });
                setTimeout(() => location.reload(), 1500);
            } else {
                Toast.fire({ icon: 'error', title: res.message });
            }
        },
        error: function() {
            btn.prop('disabled', false).html('<i class="fas fa-check me-2"></i>Konfirmasi & Simpan');
            Toast.fire({ icon: 'error', title: 'Server error, coba lagi.' });
        }
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
