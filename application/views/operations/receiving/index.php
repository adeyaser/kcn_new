<div class="row g-4 mb-4">
    <div class="col-xl-6">
        <div class="card-custom h-100">
            <div class="card-header bg-primary-subtle d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-sign-in-alt me-2 text-primary"></i>Recent Receiving (Masuk Yard)</h6>
                <span class="badge bg-primary">Today: 42 Units</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Gate Time</th>
                                <th>Truck No</th>
                                <th>Container</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="small">12:30</td>
                                <td class="fw-bold">B 9122 KCN</td>
                                <td>MSKU1221445</td>
                                <td><span class="badge bg-success">Stored</span></td>
                            </tr>
                            <tr>
                                <td class="small">12:35</td>
                                <td class="fw-bold">B 8821 XYZ</td>
                                <td>TCNU5562110</td>
                                <td><span class="badge bg-info">In-Gate</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card-custom h-100">
            <div class="card-header bg-warning-subtle d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-sign-out-alt me-2 text-warning"></i>Recent Delivery (Keluar Yard)</h6>
                <span class="badge bg-warning text-dark">Today: 38 Units</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Gate Time</th>
                                <th>Truck No</th>
                                <th>Container</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="small">12:15</td>
                                <td class="fw-bold">B 1120 ABC</td>
                                <td>MSKU8872112</td>
                                <td><span class="badge bg-secondary">Out-Gate</span></td>
                            </tr>
                            <tr>
                                <td class="small">12:40</td>
                                <td class="fw-bold">B 7761 KCN</td>
                                <td>TCNU1123445</td>
                                <td><span class="badge bg-warning text-dark">Loading</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-custom">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6><i class="fas fa-tasks me-2 text-primary"></i>Operational Controls</h6>
        <div>
            <button class="btn btn-sm btn-primary-custom" onclick="new_job()"><i class="fas fa-plus me-1"></i>New Job Order</button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tableJobs">
                <thead>
                    <tr>
                        <th>Job No</th>
                        <th>Type</th>
                        <th>Container</th>
                        <th>Truck No</th>
                        <th>Booking/DO</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($jobs)): foreach($jobs as $job): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= $job->job_no ?></td>
                        <td><span class="badge bg-light text-dark border"><?= $job->job_type ?></span></td>
                        <td><?= $job->container_no ?> (<?= $job->size ?>')</td>
                        <td class="fw-bold"><?= $job->truck_no ?></td>
                        <td class="small text-muted"><?= $job->doc_no ?></td>
                        <td><span class="badge bg-light text-warning border border-warning px-3"><?= $job->status ?></span></td>
                        <td class="text-end">
                            <button class="btn btn-xs btn-outline-secondary" title="Print Ticket"><i class="fas fa-print"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 d-block opacity-25"></i>
                            Belum ada Job Order aktif hari ini. Klik "New Job Order" untuk memulai.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Job Order -->
<div class="modal fade" id="modal_job" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-invoice me-2 text-primary"></i>Create New Job Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formJob">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Job Type</label>
                            <select class="form-select" name="job_type">
                                <option value="Receiving">Receiving (Import/Full)</option>
                                <option value="Delivery">Delivery (Export/Full)</option>
                                <option value="Empty Receive">Empty Receive</option>
                                <option value="Empty Delivery">Empty Delivery</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Booking / DO Number</label>
                            <input type="text" class="form-control" name="doc_no" placeholder="BK-123456 or DO-9988">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Truck Number</label>
                            <input type="text" class="form-control" name="truck_no" placeholder="B 1234 ABC">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Container Number</label>
                            <input type="text" class="form-control" name="container_no" placeholder="MSKU1234567">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Size</label>
                            <select class="form-select" name="size">
                                <option value="20">20 Feet</option>
                                <option value="40">40 Feet</option>
                                <option value="45">45 Feet</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Type</label>
                            <select class="form-select" name="type">
                                <option value="GP">Dry (GP)</option>
                                <option value="HC">High Cube (HC)</option>
                                <option value="RF">Reefer (RF)</option>
                                <option value="OT">Open Top (OT)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Weight (KG)</label>
                            <input type="number" class="form-control" name="weight" placeholder="24000">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary-custom" onclick="save_job()">Generate Job Order</button>
            </div>
        </div>
    </div>
</div>

<script>
function new_job() {
    $('#formJob')[0].reset();
    $('#modal_job').modal('show');
}

function save_job() {
    var formData = $('#formJob').serialize();
    
    $.ajax({
        url: '<?= site_url("operations/receiving/ajax_save") ?>',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(res) {
            if(res.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Job Order Generated!',
                    text: 'Job Order has been created and saved to database.',
                    background: '#1e293b', color: '#e2e8f0'
                }).then(() => {
                    location.reload(); // Refresh to show new data
                });
            }
        }
    });
}
</script>

<?php ob_start(); ?>
<script>
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>

<style>
.bg-primary-subtle { background-color: rgba(14, 165, 233, 0.05); }
.bg-warning-subtle { background-color: rgba(245, 158, 11, 0.05); }
</style>
