<style>
    .monitor-card {
        background: #ffffff;
        border: 1px solid rgba(14, 165, 233, 0.1);
        border-radius: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .kpi-box {
        padding: 20px;
        border-radius: 12px;
        background: rgba(14, 165, 233, 0.04);
        border: 1px solid rgba(14, 165, 233, 0.1);
        text-align: center;
        height: 100%;
    }
    .kpi-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--accent);
        display: block;
        line-height: 1.2;
    }
    .kpi-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
    }
    .table-modern thead th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 10px;
        padding: 12px 15px;
        border-top: none;
    }
    .table-modern tbody td {
        padding: 12px 15px;
        vertical-align: middle;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
    }
    .interruption-item {
        border-left: 3px solid #ef4444;
        background: rgba(239, 68, 68, 0.05);
        padding: 12px;
        border-radius: 0 8px 8px 0;
        margin-bottom: 10px;
    }
    .vessel-spec-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .vessel-spec-item:last-child { border-bottom: none; }
    
    .status-operating {
        background: rgba(34, 197, 94, 0.1);
        color: #16a34a;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }
</style>

<div class="row g-4 mb-4">
    <!-- Kolom Kiri: Performa & Pergerakan -->
    <div class="col-xl-8">
        <div class="monitor-card mb-4">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 fw-bold text-dark"><i class="fas fa-ship me-2 text-primary"></i>Monitoring Kapal: <?= $vessel_plan->vessel_name ?></h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="status-operating">BEROPERASI</span>
                        <small class="text-muted fw-medium">Voyage: <?= $vessel_plan->voyage_in ?> / <?= $vessel_plan->voyage_out ?></small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= site_url('reports/sof_report/print_sof/'.$vessel_plan->id) ?>" target="_blank" class="btn btn-sm btn-light border fw-bold px-3">
                        <i class="fas fa-file-pdf me-1 text-danger"></i> Cetak SOF
                    </a>
                </div>
            </div>
            
            <div class="p-4">
                <!-- KPI Row -->
                <div class="row g-3 mb-5">
                    <div class="col-md-3">
                        <div class="kpi-box">
                            <span class="kpi-label">Bongkar (Box)</span>
                            <span class="kpi-value text-info"><?= $stats['discharged'] ?></span>
                            <div class="progress mt-2" style="height:6px; background: #e2e8f0;">
                                <div class="progress-bar bg-info" style="width: <?= $stats['discharge_pct'] ?>%"></div>
                            </div>
                            <small class="text-muted mt-1 d-block" style="font-size: 9px;">Progres: <?= $stats['discharge_pct'] ?>%</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kpi-box">
                            <span class="kpi-label">Muat (Box)</span>
                            <span class="kpi-value text-success"><?= $stats['loaded'] ?></span>
                            <div class="progress mt-2" style="height:6px; background: #e2e8f0;">
                                <div class="progress-bar bg-success" style="width: <?= $stats['load_pct'] ?>%"></div>
                            </div>
                            <small class="text-muted mt-1 d-block" style="font-size: 9px;">Progres: <?= $stats['load_pct'] ?>%</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kpi-box">
                            <span class="kpi-label">GCR Rata-rata</span>
                            <span class="kpi-value text-dark"><?= $stats['gcr'] ?></span>
                            <small class="text-muted" style="font-size: 9px;">Box/Jam/Crane</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kpi-box">
                            <span class="kpi-label">Produktivitas Kapal</span>
                            <span class="kpi-value text-warning"><?= $stats['vessel_productivity'] ?></span>
                            <small class="text-muted" style="font-size: 9px;">Total Box/Jam</small>
                        </div>
                    </div>
                </div>

                <!-- Tabel Pergerakan -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-stream me-2 text-primary"></i>Pergerakan Terbaru</h6>
                    <span class="badge bg-light text-muted border px-2">Update: <?= date('H:i:s') ?></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>No. Kontainer</th>
                                <th>Aktivitas</th>
                                <th>Alat/Crane</th>
                                <th>Posisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($recent_movements)): ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted small">Belum ada pergerakan tercatat</td></tr>
                            <?php else: ?>
                                <?php foreach($recent_movements as $m): ?>
                                    <tr>
                                        <td class="fw-bold"><?= date('H:i:s', strtotime($m->activity_time)) ?></td>
                                        <td class="fw-bold text-primary"><?= $m->container_no ?></td>
                                        <td>
                                            <?php if($m->activity_type == 'LOAD'): ?>
                                                <span class="badge bg-success-glow text-success border border-success border-opacity-10 px-3">MUAT</span>
                                            <?php else: ?>
                                                <span class="badge bg-info-glow text-info border border-info border-opacity-10 px-3">BONGKAR</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-medium"><?= $m->equipment_code ?: '-' ?></td>
                                        <td class="text-muted"><?= $m->bay ?>-<?= $m->row ?>-<?= $m->tier ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <!-- Live CCTV (Dinonaktifkan)
        <div class="monitor-card mb-4 overflow-hidden">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-video me-2 text-danger"></i>CCTV Dermaga</h6>
                <span class="badge bg-danger pulse-animation px-2">LIVE</span>
            </div>
            ...
        </div>
        -->

        <!-- Laporan Timesheet (SOF) -->
        <div class="monitor-card mb-4">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-clock-rotate-left me-2 text-primary"></i>Laporan Timesheet (SOF)</h6>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 px-2">AKTIF</span>
            </div>
            <div class="p-0">
                <table class="table table-sm table-modern mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Kegiatan / Milestone</th>
                            <th class="text-end pe-3">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-3 fw-medium">Kedatangan (ETA)</td>
                            <td class="text-end pe-3 text-muted"><?= date('d/m H:i', strtotime($vessel_plan->eta)) ?></td>
                        </tr>
                        <tr>
                            <td class="ps-3 fw-medium">Sandar Dermaga (ATB)</td>
                            <td class="text-end pe-3 text-primary fw-bold"><?= date('d/m H:i', strtotime($vessel_plan->eta . ' - 1 hour')) ?></td>
                        </tr>
                        <tr>
                            <td class="ps-3 fw-medium">Mulai Kerja (Commence)</td>
                            <td class="text-end pe-3 text-success fw-bold"><?= $stats['commence_work'] ?></td>
                        </tr>
                        <tr>
                            <td class="ps-3 fw-medium text-muted">Selesai Kerja (Est.)</td>
                            <td class="text-end pe-3 text-warning fw-bold">Besok, 04:30</td>
                        </tr>
                        <tr>
                            <td class="ps-3 fw-medium text-muted">Lepas Sandar (ATD)</td>
                            <td class="text-end pe-3">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Gangguan Operasional -->
        <div class="monitor-card mb-4">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-exclamation-circle me-2 text-danger"></i>Gangguan Operasional</h6>
                <button class="btn btn-xs btn-outline-danger px-2" onclick="addInterruption()">
                    <i class="fas fa-plus me-1"></i>Lapor
                </button>
            </div>
            <div class="p-3">
                <?php if(empty($interruptions)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-success fa-2x mb-2 opacity-20"></i>
                        <p class="text-muted small mb-0">Operasi lancar, tidak ada gangguan.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($interruptions as $i): ?>
                        <div class="interruption-item">
                            <div class="d-flex justify-content-between mb-1">
                                <strong class="text-dark small"><?= $i->interruption_type ?></strong>
                                <span class="badge bg-white text-danger border border-danger border-opacity-20"><?= date('H:i', strtotime($i->start_time)) ?></span>
                            </div>
                            <p class="text-muted mb-0" style="font-size: 11px;"><?= $i->remarks ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Detail Kapal -->
        <div class="monitor-card">
            <div class="p-3 border-bottom">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-info-circle me-2 text-info"></i>Spesifikasi Kapal</h6>
            </div>
            <div class="p-4">
                <div class="vessel-spec-item">
                    <span class="text-muted fw-medium small">LOA</span>
                    <span class="fw-bold text-dark"><?= isset($vessel_plan->loa) ? $vessel_plan->loa : '-' ?> <small class="text-muted">m</small></span>
                </div>
                <div class="vessel-spec-item">
                    <span class="text-muted fw-medium small">Beam</span>
                    <span class="fw-bold text-dark"><?= isset($vessel_plan->beam) ? $vessel_plan->beam : '-' ?> <small class="text-muted">m</small></span>
                </div>
                <div class="vessel-spec-item">
                    <span class="text-muted fw-medium small">Draft Max</span>
                    <span class="fw-bold text-dark"><?= isset($vessel_plan->draft) ? $vessel_plan->draft : '-' ?> <small class="text-muted">m</small></span>
                </div>
                <div class="vessel-spec-item">
                    <span class="text-muted fw-medium small">ETA</span>
                    <span class="fw-bold text-primary"><?= date('d M, H:i', strtotime($vessel_plan->eta)) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Gangguan -->
<div class="modal fade" id="modalInterruption" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark">Laporkan Gangguan Operasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formInterruption">
                    <input type="hidden" name="vessel_id" value="<?= $vessel_plan->vessel_id ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Jenis Gangguan</label>
                        <select class="form-select border-light bg-light" name="type" required>
                            <option value="KERUSAKAN_ALAT">Kerusakan Alat</option>
                            <option value="CUACA_BURUK">Cuaca Buruk (Hujan/Angin)</option>
                            <option value="MATI_LAMPU">Gangguan Listrik</option>
                            <option value="LAINNYA">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Waktu Mulai</label>
                        <input type="datetime-local" class="form-control border-light bg-light" name="start_time" value="<?= date('Y-m-d\TH:i') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Keterangan</label>
                        <textarea class="form-control border-light bg-light" name="remarks" rows="3" placeholder="Jelaskan detail masalah..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger fw-bold px-4" onclick="saveInterruption()">Kirim Laporan</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
function addInterruption() {
    $('#modalInterruption').modal('show');
}

function saveInterruption() {
    const btn = event.target;
    const originalText = btn.innerText;
    btn.innerText = 'Mengirim...';
    btn.disabled = true;

    $.ajax({
        url: '<?= site_url("monitoring/vessel/ajax_save_interruption") ?>',
        type: 'POST',
        data: $('#formInterruption').serialize(),
        dataType: 'json',
        success: function(res) {
            if(res.status) {
                location.reload();
            } else {
                alert('Gagal mengirim laporan');
                btn.innerText = originalText;
                btn.disabled = false;
            }
        },
        error: function() {
            alert('Terjadi kesalahan koneksi');
            btn.innerText = originalText;
            btn.disabled = false;
        }
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
