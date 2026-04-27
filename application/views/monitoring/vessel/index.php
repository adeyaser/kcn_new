<style>
    .vessel-card {
        border: 1px solid rgba(14, 165, 233, 0.1);
        transition: all 0.3s ease;
        background: #ffffff;
    }
    .vessel-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
        border-color: var(--accent);
    }
    .stat-box-blue {
        background: rgba(14, 165, 233, 0.08);
        border: 1px solid rgba(14, 165, 233, 0.2);
        border-radius: 12px;
        transition: all 0.2s ease;
    }
    .stat-box-blue:hover {
        background: rgba(14, 165, 233, 0.12);
    }
    .progress-custom {
        background: #f1f5f9;
        height: 8px;
        border-radius: 10px;
        overflow: hidden;
    }
</style>

<div class="row g-4">
    <?php if(empty($operating_vessels)): ?>
        <div class="col-12 text-center py-5">
            <div class="card-custom py-5 border-dashed">
                <i class="fas fa-ship fa-4x text-muted mb-4 opacity-20"></i>
                <h5 class="text-muted fw-bold">Tidak Ada Kapal yang Sedang Beroperasi</h5>
                <p class="text-sm text-muted">Kapal akan muncul di sini setelah disetujui dan berstatus 'OPERATING'.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach($operating_vessels as $v): ?>
            <div class="col-xl-4 col-md-6">
                <div class="card-custom vessel-card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                                    <i class="fas fa-ship fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold text-dark"><?= $v->vessel_name ?></h5>
                                    <span class="badge bg-light text-primary border border-primary border-opacity-25 px-2"><?= $v->voyage_in ?> / <?= $v->voyage_out ?></span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 mb-1 pulse-animation">
                                    <i class="fas fa-circle extra-small me-1"></i> LIVE
                                </span>
                                <div class="text-muted small fw-medium">ATB: <?= date('d/m H:i', strtotime($v->eta)) ?></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between text-sm mb-2">
                                <span class="text-muted fw-medium">Progres Bongkar</span>
                                <span class="text-primary fw-bold">65%</span>
                            </div>
                            <div class="progress progress-custom">
                                <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width: 65%"></div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="stat-box-blue p-3 text-center">
                                    <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 10px; letter-spacing: 0.5px;">GCR</small>
                                    <strong class="text-dark fs-5">28.4</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-box-blue p-3 text-center">
                                    <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 10px; letter-spacing: 0.5px;">Est. Selesai</small>
                                    <strong class="text-dark fs-5">23:45</strong>
                                </div>
                            </div>
                        </div>

                        <a href="<?= site_url('monitoring/vessel/detail/'.$v->id) ?>" class="btn btn-outline-primary w-100 btn-sm fw-bold py-2 rounded-pill">
                            <i class="fas fa-chart-line me-2"></i>Lihat Monitoring Lengkap
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
