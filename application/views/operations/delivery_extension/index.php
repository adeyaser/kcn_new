<div class="row">
    <div class="col-xl-5 col-lg-6 mx-auto">
        <div class="card-custom">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Delivery Extension Service</h6>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-4">Extend the storage period for containers currently in the yard.</p>
                
                <div class="mb-4">
                    <label class="form-label">Search Container No</label>
                    <div class="input-group">
                        <input type="text" id="cont_no" class="form-control" placeholder="MSKU1234567" style="text-transform: uppercase;">
                        <button class="btn btn-warning" type="button" onclick="searchContainer()"><i class="fas fa-search"></i></button>
                    </div>
                </div>

                <div id="extension_panel" style="display:none;">
                    <div class="alert alert-dark border-secondary p-3 mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Current Gate In:</span>
                            <span id="gate_in_time" class="text-white"></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Days in Yard:</span>
                            <span id="days_in_yard" class="text-white fw-bold"></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Chargeable:</span>
                            <span id="curr_charge" class="text-success fw-bold"></span>
                        </div>
                    </div>

                    <form id="formExtension">
                        <input type="hidden" name="gate_id" id="gate_id">
                        <div class="mb-4">
                            <label class="form-label">Extension Duration (Days)</label>
                            <div class="input-group">
                                <input type="number" name="extension_days" class="form-control text-center" value="1" min="1">
                                <span class="input-group-text">Days</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary-custom w-100" onclick="submitExtension()">
                            <i class="fas fa-check-circle me-2"></i>Approve & Update Extension
                        </button>
                    </form>
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

    $.ajax({
        url: '<?= site_url("operations/delivery_extension/ajax_search_container") ?>',
        type: 'GET',
        data: {container_no: cont},
        dataType: 'json',
        success: function(res) {
            if(res.status === 'success') {
                $('#gate_id').val(res.gate.id);
                $('#gate_in_time').text(res.gate.gate_in_time);
                $('#days_in_yard').text(res.calculation.total_days + ' Days');
                $('#curr_charge').text('Rp ' + new Intl.NumberFormat('id-ID').format(res.calculation.total_amount));
                $('#extension_panel').slideDown();
                Toast.fire({icon: 'success', title: 'Container Located'});
            } else {
                Toast.fire({icon: 'error', title: res.message});
                $('#extension_panel').hide();
            }
        }
    });
}

function submitExtension() {
    $.ajax({
        url: '<?= site_url("operations/delivery_extension/ajax_save_extension") ?>',
        type: 'POST',
        data: $('#formExtension').serialize(),
        dataType: 'json',
        success: function(res) {
            if(res.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Extension Approved',
                    text: res.message,
                    background: '#1e293b', color: '#e2e8f0'
                }).then(() => {
                    location.reload();
                });
            }
        }
    });
}
</script>
<?php $this->data['page_js'] = ob_get_clean(); ?>
