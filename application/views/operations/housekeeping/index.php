<div class="row">
    <div class="col-md-5">
        <div class="card-custom mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>New Transfer / Shifting</h6>
            </div>
            <div class="card-body p-4">
                <form id="formTransfer" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Container Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="container_no" id="cont_no" required placeholder="ABCD1234567" style="text-transform: uppercase;">
                            <button class="btn btn-primary" type="button" onclick="searchContainer()"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                    <div id="transfer_details" style="display:none;">
                        <div class="alert alert-info py-2 mb-3">
                            <small>Current Location: <strong id="curr_loc">B-05-02-03</strong></small>
                            <input type="hidden" name="old_location" id="old_loc">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Transfer Equipment <span class="text-danger">*</span></label>
                            <select class="form-select" name="equipment_id" required>
                                <option value="">-- Select RS / RTG --</option>
                                <?php foreach($equipments as $e): ?>
                                    <option value="<?= $e->id ?>"><?= $e->equipment_code ?> - <?= $e->equipment_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <h6 class="text-info mb-3 border-bottom border-secondary pb-2">New Position (Target)</h6>
                        <div class="row g-2 mb-4">
                            <div class="col-4">
                                <label class="form-label">Bay</label>
                                <input type="number" class="form-control" name="new_bay" required placeholder="01">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Row</label>
                                <input type="number" class="form-control" name="new_row" required placeholder="01">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Tier</label>
                                <input type="number" class="form-control" name="new_tier" required placeholder="01">
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary-custom w-100" onclick="submitTransfer()">
                            <i class="fas fa-check-circle me-2"></i>Execute Transfer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-history me-2 text-primary"></i>Transfer History (Today)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Container No</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Equipment</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody id="transfer_logs">
                            <tr>
                                <td colspan="5" class="text-center text-muted">No transfers logged today</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
function searchContainer() {
    var cont = $('#cont_no').val();
    if(!cont) return;

    // Simulation of container search
    Toast.fire({icon: 'info', title: 'Searching container...'});
    setTimeout(() => {
        $('#curr_loc').text('BLOCK-A, Bay 03, Row 02, Tier 01');
        $('#old_loc').val('A-03-02-01');
        $('#transfer_details').slideDown();
    }, 500);
}

function submitTransfer() {
    var form = document.getElementById('formTransfer');
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    $.ajax({
        url: '<?= site_url("operations/housekeeping/ajax_save_transfer") ?>',
        type: 'POST',
        data: $('#formTransfer').serialize(),
        dataType: 'json',
        success: function(res) {
            if(res.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Transfer Successful',
                    text: 'Container location has been updated.',
                    background: '#1e293b', color: '#e2e8f0',
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
            }
        }
    });
}
</script>
<?php $this->data['page_js'] = ob_get_clean(); ?>
