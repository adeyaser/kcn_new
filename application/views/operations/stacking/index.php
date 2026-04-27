<div class="row">
    <div class="col-lg-8">
        <div class="card-custom mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-th me-2 text-primary"></i>Yard Occupancy Overview</h6>
                <div class="badge bg-success">Overall: 64% Full</div>
            </div>
            <div class="card-body">
                <!-- Visual Yard Representation (Simplified) -->
                <div class="row g-2">
                    <?php 
                    $blocks = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                    foreach($blocks as $block): 
                        $percentage = rand(40, 90);
                        $color = ($percentage > 80) ? 'bg-danger' : (($percentage > 60) ? 'bg-warning' : 'bg-success');
                    ?>
                    <div class="col-md-3">
                        <div class="p-3 border rounded-3 text-center mb-2">
                            <div class="fw-bold text-dark">BLOCK <?= $block ?></div>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar <?= $color ?>" style="width: <?= $percentage ?>%"></div>
                            </div>
                            <div class="extra-small text-muted mt-1"><?= $percentage ?>% Capacity</div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-history me-2 text-primary"></i>Recent Stacking Movements</h6>
                <button class="btn btn-sm btn-primary-custom" onclick="new_stacking()"><i class="fas fa-plus me-1"></i>New Movement</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Container</th>
                                <th>From Loc</th>
                                <th>To Loc</th>
                                <th>Reason</th>
                                <th>Equipment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="small">11:20</td>
                                <td class="fw-bold">MSKU9082123</td>
                                <td class="text-muted small">Truck B 9912</td>
                                <td class="text-primary fw-bold">A-01-02-01</td>
                                <td>Receiving</td>
                                <td>RS-01</td>
                            </tr>
                            <tr>
                                <td class="small">11:15</td>
                                <td class="fw-bold">TCNU4451221</td>
                                <td class="text-muted small">C-02-05-03</td>
                                <td class="text-primary fw-bold">C-02-05-04</td>
                                <td>Shifting</td>
                                <td>RS-04</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-custom p-4 mb-4">
            <h6 class="text-dark fw-bold mb-3">Stacking Statistics</h6>
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <span class="text-muted small">Total Containers in Yard</span>
                <span class="fw-bold text-dark">1,452</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <span class="text-muted small">Total Slots Available</span>
                <span class="fw-bold text-success">842</span>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted small">Reefer Points Used</span>
                <span class="fw-bold text-info">42 / 60</span>
            </div>
        </div>

        <div class="alert alert-warning border-0 shadow-sm">
            <h6 class="fw-bold small"><i class="fas fa-exclamation-triangle me-2"></i>Yard Congestion Alert</h6>
            <p class="extra-small mb-0">Block C is currently at 92% capacity. Recommend redirecting new arrivals to Block G or H.</p>
        </div>
    </div>
</div>

<!-- Modal Entry -->
<div class="modal fade" id="modal_stacking" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Stacking Movement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formStacking">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Container Number</label>
                        <input type="text" class="form-control" name="container_no" placeholder="MSKU1234567">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Target Location (Block-Row-Tier)</label>
                        <input type="text" class="form-control" name="location" placeholder="A-01-02-01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Movement Reason</label>
                        <select class="form-select" name="reason">
                            <option value="Receiving">Receiving</option>
                            <option value="Shifting">Shifting (Internal)</option>
                            <option value="Delivery">Pre-Delivery</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary-custom" onclick="save_stacking()">Confirm Movement</button>
            </div>
        </div>
    </div>
</div>

<script>
function new_stacking() {
    $('#formStacking')[0].reset();
    $('#modal_stacking').modal('show');
}

function save_stacking() {
    Toast.fire({
        icon: 'success',
        title: 'Stacking movement recorded'
    });
    $('#modal_stacking').modal('hide');
}
</script>

<?php ob_start(); ?>
<script>
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>

<style>
.extra-small { font-size: 11px; }
</style>
