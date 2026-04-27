<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card-custom">
                <div class="card-header bg-primary text-white p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-white"><i class="fas fa-truck-loading me-2"></i>New Truck-Container Assignment</h6>
                        <span class="badge bg-light text-primary"><?= $assignment_no ?></span>
                    </div>
                </div>
                <form id="formTca" class="needs-validation" novalidate>
                    <input type="hidden" name="assignment_no" value="<?= $assignment_no ?>">
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- 1. Planning Request Section -->
                            <div class="col-12">
                                <h6 class="text-info border-bottom pb-2 mb-3">1. Planning Request & Container</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Select Request Planning <span class="text-danger">*</span></label>
                                        <select class="form-select" name="planning_id" id="planning_id" required>
                                            <option value="">-- Choose Planning --</option>
                                            <?php foreach($plannings as $p): ?>
                                                <option value="<?= $p->id ?>"><?= $p->request_no ?> - <?= $p->vessel_name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Select Container <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select select2-container" name="manifest_id" id="manifest_id" required>
                                                <option value="">-- Choose Planning First --</option>
                                            </select>
                                            <!-- <button class="btn btn-outline-info" type="button" id="btnRefreshCont" onclick="loadContainers()" title="Reload Containers"><i class="fas fa-sync"></i></button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Truck Information Section -->
                            <div class="col-12">
                                <h6 class="text-info border-bottom pb-2 mb-3">2. Truck & Driver Information (TCA)</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Search Police Number <span class="text-danger">*</span></label>
                                        <select class="form-select select2-truck" name="truck_id" id="truck_id" required>
                                            <option value="">-- Type Police Number --</option>
                                        </select>
                                        <div class="form-text">Autocomplete from Master Truck</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Truck Company</label>
                                        <input type="text" class="form-control bg-light" id="truck_company" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Driver Name</label>
                                        <input type="text" class="form-control bg-light" id="driver_name" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Driver Phone</label>
                                        <input type="text" class="form-control bg-light" id="driver_phone" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">RFID Tag</label>
                                        <input type="text" class="form-control bg-light" id="rfid_tag" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Arrival Section -->
                            <div class="col-12">
                                <h6 class="text-info border-bottom pb-2 mb-3">3. Arrival Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Gate Post <span class="text-danger">*</span></label>
                                        <select class="form-select" name="gate_id" id="gate_id" required>
                                            <option value="">-- Choose Gate --</option>
                                            <?php foreach($gates as $g): ?>
                                                <option value="<?= $g->id ?>"><?= $g->gate_name ?> (<?= $g->gate_type ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Estimated Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="est_date" required value="<?= date('Y-m-d') ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Estimated Time <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" name="est_time" required value="<?= date('H:i') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-3 text-end border-top">
                        <a href="<?= site_url('operations/tca_planning') ?>" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4" id="btnSave">
                            <i class="fas fa-save me-2"></i>Save Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
// Manual load function - Global scope
function loadContainers() {
    var id = $('#planning_id').val();
    var url = "<?= site_url('operations/tca_planning/ajax_get_planning_containers') ?>";
    var btn = $('#btnRefreshCont');
    
    console.log("loadContainers triggered! ID:", id);
    
    if(!id) {
        Toast.fire({icon: 'warning', title: 'Please select a Planning Request first'});
        return;
    }

    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
    $('#manifest_id').html('<option value="">Loading...</option>').trigger('change');

    $.ajax({
        url: url,
        type: "GET",
        data: { planning_id: id },
        dataType: 'json',
        cache: false,
        success: function(res) {
            console.log("Success Response:", res);
            var html = '<option value="">-- Choose Container --</option>';
            if(res.status === 'success' && res.data && res.data.length > 0) {
                res.data.forEach(function(item) {
                    html += '<option value="'+item.id+'">'+item.container_no+' ('+item.size+'ft '+item.type+')</option>';
                });
                $('#manifest_id').html(html).trigger('change');
            } else {
                html = '<option value="">No available containers</option>';
                $('#manifest_id').html(html).trigger('change');
                var msg = 'No containers found for ID ' + (res.debug ? res.debug.planning_id : id);
                Toast.fire({icon: 'info', title: msg});
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            $('#manifest_id').html('<option value="">Error loading</option>').trigger('change');
        },
        complete: function() {
            btn.prop('disabled', false).html('<i class="fas fa-sync"></i>');
        }
    });
};

$(document).ready(function() {
    // Clear and initialize Planning Select2
    $('#planning_id').select2({ 
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Choose Planning --',
        allowClear: true
    }).on('change', function() {
        loadContainers();
    });

    // Initialize other Select2
    $('#manifest_id').select2({ 
        theme: 'bootstrap-5',
        width: '100%'
    });

    $('.select2-truck').select2({
        theme: 'bootstrap-5',
        ajax: {
            url: "<?= site_url('operations/tca_planning/ajax_get_truck_info') ?>",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { term: params.term };
            },
            processResults: function(data) {
                return { results: data };
            },
            cache: true
        },
        minimumInputLength: 1,
        placeholder: '-- Type Police Number --'
    }).on('select2:select', function(e) {
        var data = e.params.data;
        $('#truck_company').val(data.truck_company);
        $('#driver_name').val(data.driver_name);
        $('#driver_phone').val(data.driver_phone);
        $('#rfid_tag').val(data.rfid_tag);
    });

    $('#formTca').submit(function(e) {
        e.preventDefault();
        if(!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }

        var btn = $('#btnSave');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');

        $.ajax({
            url: "<?= site_url('operations/tca_planning/ajax_save') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(res) {
                if(res.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Truck assignment has been saved.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = res.redirect;
                    });
                } else {
                    Toast.fire({icon: 'error', title: 'Error saving data'});
                    btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Save Assignment');
                }
            }
        });
    });
});
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
