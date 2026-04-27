<div class="row g-4">
    <div class="col-xl-4 col-lg-5">
        <div class="card-custom mb-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="fas fa-id-card me-2"></i>TCA RFID / Container Scanner</h6>
            </div>
            <div class="card-body p-4">
                <ul class="nav nav-pills nav-justified mb-4" id="tcaTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active btn-sm" id="rfid-tab" data-bs-toggle="tab" data-bs-target="#rfidSearch" type="button"><i class="fas fa-wifi me-1"></i> RFID</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn-sm" id="qr-tab" data-bs-toggle="tab" data-bs-target="#qrSearch" type="button"><i class="fas fa-qrcode me-1"></i> QR Pass</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn-sm" id="cont-tab" data-bs-toggle="tab" data-bs-target="#contSearch" type="button"><i class="fas fa-box me-1"></i> Container</button>
                    </li>
                </ul>

                <div class="tab-content" id="tcaTabsContent">
                    <!-- RFID Tab -->
                    <div class="tab-pane fade show active text-center" id="rfidSearch" role="tabpanel">
                        <div class="rfid-icon-wrapper mb-3">
                            <i class="fas fa-wifi fa-2x text-warning"></i>
                        </div>
                        <p class="text-muted small mb-3">Scan truck RFID tag to fetch driver and vehicle info.</p>
                        <div class="input-group mb-3">
                            <input type="text" id="rfid_input" class="form-control text-center" placeholder="Scan RFID Tag" autofocus>
                            <button class="btn btn-warning" type="button" onclick="checkRfid()"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <!-- QR Tab -->
                    <div class="tab-pane fade text-center" id="qrSearch" role="tabpanel">
                        <div id="qr-reader" style="width: 100%; max-width: 300px; margin: 0 auto; border-radius: 8px; overflow: hidden; border: 2px solid #334155;"></div>
                        <div id="qr-reader-results" class="mt-2 small text-info"></div>
                        <p class="text-muted small mt-3">Point camera at the QR Code on the Truck Appointment Pass.</p>
                        <button class="btn btn-primary btn-sm w-100" onclick="startScanner()" id="btnStartScan">
                            <i class="fas fa-camera me-2"></i>Start Camera
                        </button>
                    </div>
                    <!-- Container Tab -->
                    <div class="tab-pane fade text-center" id="contSearch" role="tabpanel">
                        <div class="rfid-icon-wrapper mb-3" style="border-color: var(--info); background: rgba(14, 165, 233, 0.1);">
                            <i class="fas fa-box fa-2x text-info"></i>
                        </div>
                        <p class="text-muted small mb-3">Search container number to fetch planning/manifest details.</p>
                        <div class="input-group mb-3">
                            <input type="text" id="cont_search_input" class="form-control text-center" placeholder="Container No (e.g. MSKU1234567)" style="text-transform: uppercase;">
                            <button class="btn btn-info text-white" type="button" onclick="checkContainer()"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>

                <div id="truck_preview" class="text-start mt-4" style="display:none;">
                    <div class="p-3 rounded bg-primary border border-secondary">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="bg-dark rounded p-2"><i class="fas fa-truck text-warning"></i></div>
                            <div>
                                <h6 class="mb-0 text-white" id="prev_police_no"></h6>
                                <small class="text-muted" id="prev_driver"></small>
                            </div>
                        </div>
                        <div id="cont_preview_info" style="display:none;" class="mt-2 pt-2 border-top border-secondary">
                            <div class="small text-white">Container: <span class="fw-bold text-info" id="prev_cont_no"></span></div>
                            <div class="small text-muted">Planning: <span id="prev_planning"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-lg-7">
        <form id="formGateIn" class="needs-validation" novalidate>
            <div class="card-custom">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h6 class="mb-0 text-white"><i class="fas fa-sign-in-alt me-2"></i>Gate In Registration</h6>
                        <span class="badge bg-light text-primary"><?= $gate_no ?></span>
                        <input type="hidden" name="gate_no" value="<?= $gate_no ?>">
                    </div>
                </div>
                <div class="card-body p-4">
                    <h6 class="text-info mb-3 border-bottom border-secondary pb-2">1. Activity & Planning</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Gate Name <span class="text-danger">*</span></label>
                            <select class="form-select" name="gate_id" id="gate_id" required>
                                <option value="">-- Choose Gate --</option>
                                <?php foreach($gates as $g): ?>
                                    <option value="<?= $g->id ?>"><?= $g->gate_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Activity Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="activity_type" id="activity_type" required>
                                <option value="RECEIVING">Receiving (Full In)</option>
                                <option value="DELIVERY">Delivery (Full Out)</option>
                                <option value="EMPTY_IN">Empty In</option>
                                <option value="EMPTY_OUT">Empty Out</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Link to Planning</label>
                            <select class="form-select select2-planning" name="planning_id" id="planning_id">
                                <option value="">-- No Planning --</option>
                                <?php foreach($plannings as $p): ?>
                                    <option value="<?= $p->id ?>"><?= $p->request_no ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <h6 class="text-info mb-3 border-bottom border-secondary pb-2">2. Truck & Driver Information</h6>
                    <input type="hidden" name="truck_id" id="truck_id">
                    <input type="hidden" name="rfid_tag" id="rfid_tag">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Police Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="police_number" id="police_number" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Driver Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="driver_name" id="driver_name" required readonly>
                        </div>
                    </div>

                    <h6 class="text-info mb-3 border-bottom border-secondary pb-2">3. Container Details</h6>
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label">Container Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="container_no" id="container_no" placeholder="e.g. MSKU1234567" required style="text-transform: uppercase;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Size</label>
                            <select class="form-select" name="container_size" id="container_size">
                                <option value="20">20 ft</option>
                                <option value="40">40 ft</option>
                                <option value="45">45 ft</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select class="form-select" name="container_type" id="container_type">
                                <option value="GP">GP</option>
                                <option value="HC">HC</option>
                                <option value="RF">RF</option>
                                <option value="OT">OT</option>
                                <option value="FR">FR</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end bg-transparent border-top border-secondary p-3">
                    <a href="<?= site_url('operations/gate') ?>" class="btn btn-secondary me-2">Cancel</a>
                    <button type="button" class="btn btn-primary-custom" id="btnSubmit" onclick="submitGateIn()" disabled>
                        <i class="fas fa-check-circle me-2"></i>Complete Gate In
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.rfid-icon-wrapper {
    width: 100px;
    height: 100px;
    background: rgba(245, 158, 11, 0.1);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 2px dashed var(--warning);
}
</style>

<?php ob_start(); ?>
<script>
var html5QrCode;
$(document).ready(function() {
    $('.select2-planning').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Handle Enter on RFID input
    $('#rfid_input').on('keypress', function(e) {
        if(e.which == 13) {
            checkRfid();
        }
    });

    // Handle Enter on Container input
    $('#cont_search_input').on('keypress', function(e) {
        if(e.which == 13) {
            checkContainer();
        }
    });

    // Tab change event to stop scanner if switching tabs
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        if (e.target.id !== 'qr-tab' && html5QrCode && html5QrCode.isScanning) {
            stopScanner();
        }
    });
});

function startScanner() {
    $('#qr-reader').show();
    $('#btnStartScan').hide();
    
    html5QrCode = new Html5Qrcode("qr-reader");
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

    html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
        .catch(err => {
            Toast.fire({icon: 'error', title: 'Camera access denied'});
            $('#btnStartScan').show();
        });
}

function stopScanner() {
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            $('#btnStartScan').show();
        }).catch(err => console.error(err));
    }
}

function onScanSuccess(decodedText, decodedResult) {
    // console.log(`Code scanned = ${decodedText}`, decodedResult);
    Toast.fire({icon: 'info', title: 'QR Detected: ' + decodedText});
    stopScanner();

    $.ajax({
        url: '<?= site_url("operations/gate/ajax_check_qr") ?>',
        type: 'GET',
        data: {code: decodedText},
        dataType: 'json',
        success: function(res) {
            if(res.status === 'success') {
                populateForm(res.data);
                Toast.fire({icon: 'success', title: 'Data Loaded Successfully'});
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Pass',
                    text: res.message,
                    background: '#1e293b', color: '#e2e8f0',
                    confirmButtonColor: '#ef4444'
                });
            }
        }
    });
}

function populateForm(data) {
    // 1. Activity & Planning
    if(data.gate_id) $('#gate_id').val(data.gate_id);
    if(data.activity_type) $('#activity_type').val(data.activity_type);
    if(data.planning_id) $('#planning_id').val(data.planning_id).trigger('change');

    // 2. Truck & Driver
    $('#truck_id').val(data.truck_id);
    $('#rfid_tag').val(data.rfid_tag);
    $('#police_number').val(data.police_number);
    $('#driver_name').val(data.driver_name);

    // 3. Container
    $('#container_no').val(data.container_no);
    $('#container_size').val(data.container_size);
    $('#container_type').val(data.container_type);

    // Update Preview
    $('#prev_police_no').text(data.police_number);
    $('#prev_driver').text(data.driver_name);
    $('#prev_cont_no').text(data.container_no);
    $('#prev_planning').text(data.request_no || 'Manual');
    $('#cont_preview_info').show();
    $('#truck_preview').slideDown();

    checkEnableSubmit();
}

function checkRfid() {
    var rfid = $('#rfid_input').val();
    if(!rfid) return;

    $.ajax({
        url: '<?= site_url("operations/gate/ajax_check_rfid") ?>',
        type: 'GET',
        data: {rfid: rfid},
        dataType: 'json',
        success: function(res) {
            if(res.status === 'success') {
                if (res.type === 'TCA_FULL') {
                    populateForm(res.data);
                    Toast.fire({icon: 'success', title: 'TCA Plan Found via RFID'});
                } else {
                    $('#truck_id').val(res.data.id);
                    $('#rfid_tag').val(res.data.rfid_tag);
                    $('#police_number').val(res.data.police_number);
                    $('#driver_name').val(res.data.driver_name);
                    
                    $('#prev_police_no').text(res.data.police_number);
                    $('#prev_driver').text(res.data.driver_name);
                    $('#truck_preview').slideDown();
                    
                    checkEnableSubmit();
                    Toast.fire({icon: 'success', title: 'RFID Authorized'});
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Access Denied',
                    text: res.message,
                    background: '#1e293b', color: '#e2e8f0',
                    confirmButtonColor: '#ef4444'
                });
                checkEnableSubmit();
                $('#truck_preview').hide();
                $('#police_number, #driver_name').val('');
            }
            $('#rfid_input').val('');
        }
    });
}

function checkContainer() {
    var container_no = $('#cont_search_input').val();
    if(!container_no) return;

    $.ajax({
        url: '<?= site_url("operations/gate/ajax_check_container") ?>',
        type: 'GET',
        data: {container_no: container_no},
        dataType: 'json',
        success: function(res) {
            if(res.status === 'success') {
                if (res.type === 'TCA_FULL') {
                    populateForm(res.data);
                    Toast.fire({icon: 'success', title: 'TCA Plan Found via Container'});
                } else {
                    $('#container_no').val(res.data.container_no);
                    $('#container_size').val(res.data.size);
                    $('#container_type').val(res.data.type);
                    if(res.data.planning_id) {
                        $('#planning_id').val(res.data.planning_id).trigger('change');
                    }
                    
                    $('#prev_cont_no').text(res.data.container_no);
                    $('#prev_planning').text(res.data.request_no || 'Manual');
                    $('#cont_preview_info').show();
                    $('#truck_preview').slideDown();
                    
                    checkEnableSubmit();
                    Toast.fire({icon: 'success', title: 'Container Data Found'});
                }
            } else {
                Toast.fire({icon: 'warning', title: res.message});
                $('#container_no').val(container_no);
            }
            $('#cont_search_input').val('');
        }
    });
}

function checkEnableSubmit() {
    const hasTruck = $('#truck_id').val() !== '';
    const hasContainer = $('#container_no').val() !== '';
    $('#btnSubmit').prop('disabled', !(hasTruck && hasContainer));
}

function submitGateIn() {
    var form = document.getElementById('formGateIn');
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    var btn = $('#btnSubmit');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

    $.ajax({
        url: '<?= site_url("operations/gate/ajax_save_gate_in") ?>',
        type: 'POST',
        data: $('#formGateIn').serialize(),
        dataType: 'json',
        success: function(res) {
            if(res.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Gate In Successful!',
                    text: 'Truck has been registered into the terminal.',
                    background: '#1e293b', color: '#e2e8f0',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = res.redirect;
                });
            } else {
                Toast.fire({icon: 'error', title: 'Error saving transaction'});
                btn.prop('disabled', false).html('<i class="fas fa-check-circle me-2"></i>Complete Gate In');
            }
        },
        error: function() {
            Toast.fire({icon: 'error', title: 'Server Error'});
            btn.prop('disabled', false).html('<i class="fas fa-check-circle me-2"></i>Complete Gate In');
        }
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
