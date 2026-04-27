<div class="row">
    <div class="col-xl-4">
        <div class="card-custom mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>New Tally Entry</h6>
            </div>
            <div class="card-body p-4">
                <form id="formTally" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-clipboard-list me-1 text-primary"></i> Request Planning <span class="text-danger">*</span></label>
                        <select class="form-select select2-planning" name="planning_id" id="planning_id" required onchange="loadContainers(this.value)">
                            <option value="">-- Search Request Planning --</option>
                            <?php foreach($plannings as $p): ?>
                                <option value="<?= $p->id ?>"><?= $p->request_no ?> (<?= $p->vessel_name ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Activity Type</label>
                            <input type="text" class="form-control form-control-sm bg-light" id="display_activity" readonly placeholder="-">
                            <input type="hidden" name="activity_type" id="activity_type">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Voyage / Vessel</label>
                            <input type="text" class="form-control form-control-sm bg-light" id="display_voyage" readonly placeholder="-">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-box me-1 text-primary"></i> Container Number <span class="text-danger">*</span></label>
                        <select class="form-select select2-container" name="container_no" id="container_no" required onchange="fillContainerDetail(this.value)">
                            <option value="">-- Select Container --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold"><i class="fas fa-crane me-1 text-primary"></i> Equipment / Crane <span class="text-danger">*</span></label>
                        <select class="form-select select2-basic" name="equipment_id" required>
                            <option value="">-- Select Equipment --</option>
                            <?php foreach($equipments as $e): ?>
                                <option value="<?= $e->id ?>"><?= $e->equipment_code ?> - <?= $e->equipment_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label small fw-bold">Bay</label>
                            <input type="number" class="form-control" name="bay" id="bay" placeholder="01">
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold">Row</label>
                            <input type="number" class="form-control" name="row" id="row" placeholder="01">
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold">Tier</label>
                            <input type="number" class="form-control" name="tier" id="tier" placeholder="01">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Location Type</label>
                        <select class="form-select" name="location_type" id="location_type">
                            <option value="VESSEL">On Vessel</option>
                            <option value="YARD">In Yard</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Remarks</label>
                        <textarea class="form-control" name="remarks" rows="2" placeholder="Notes..."></textarea>
                    </div>

                    <button type="button" class="btn btn-primary w-100 fw-bold py-2" onclick="submitTally()">
                        <i class="fas fa-save me-2"></i>SAVE TALLY ACTIVITY
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card-custom">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h6 class="mb-0 text-white"><i class="fas fa-list me-2"></i>Recent Tally Logs</h6>
                    <button class="btn btn-light btn-sm" onclick="reload_table()"><i class="fas fa-sync-alt"></i></button>
                </div>
            </div>
            <div class="card-body p-3">
                <!-- Filters Section -->
                <div class="bg-light p-3 rounded border mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted mb-1">Request Planning</label>
                            <select id="filter_planning" class="form-select select2-filter">
                                <option value="">-- All Requests --</option>
                                <?php foreach($plannings as $p): ?>
                                    <option value="<?= $p->id ?>"><?= $p->request_no ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-muted mb-1">Gate No</label>
                            <input type="text" id="filter_gate_no" class="form-control form-control-sm" placeholder="GAT-...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted mb-1">Container No</label>
                            <input type="text" id="filter_container" class="form-control form-control-sm" placeholder="MSKU...">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm flex-grow-1" onclick="apply_filter()"><i class="fas fa-search me-1"></i>Search</button>
                                <button class="btn btn-secondary btn-sm" onclick="reset_filter()"><i class="fas fa-undo"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="tableTally" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Container & Gate No</th>
                                <th>Activity</th>
                                <th>Vessel & Planning</th>
                                <th>Equipment</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
var table;
var containerData = [];

$(document).ready(function() {
    $('.select2-planning, .select2-container, .select2-filter, .select2-basic').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    table = $('#tableTally').DataTable({ 
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('operations/tally/ajax_list')?>",
            "type": "POST",
            "data": function(d) {
                d.filter_planning = $('#filter_planning').val();
                d.filter_gate_no = $('#filter_gate_no').val();
                d.filter_container = $('#filter_container').val();
            }
        },
        "columnDefs": [
        { 
            "targets": [ 0, -1 ],
            "orderable": false,
        },
        ],
    });
});

function loadContainers(planning_id) {
    if (!planning_id) return;
    
    $.ajax({
        url: "<?php echo site_url('operations/tally/ajax_get_containers')?>/" + planning_id,
        type: "GET",
        dataType: "JSON",
        success: function(res) {
            if (res.status === 'success') {
                containerData = res.data;
                let html = '<option value="">-- Select Container --</option>';
                containerData.forEach(item => {
                    html += `<option value="${item.container_no}">${item.container_no}</option>`;
                });
                $('#container_no').html(html).trigger('change');
                
                // Set metadata
                if (containerData.length > 0) {
                    $('#display_activity').val(containerData[0].planning_activity);
                    $('#activity_type').val(containerData[0].planning_activity);
                    $('#display_voyage').val(containerData[0].voyage_in + ' (' + containerData[0].vessel_name + ')');
                }
                
                // Set Equipment automatically
                if (res.equipment_id) {
                    $('.select2-basic[name="equipment_id"]').val(res.equipment_id).trigger('change');
                }
            }
        }
    });
}

function fillContainerDetail(container_no) {
    if (!container_no) {
        $('#bay, #row, #tier').val('');
        return;
    }
    
    const detail = containerData.find(item => item.container_no === container_no);
    if (detail) {
        $('#bay').val(detail.bay);
        $('#row').val(detail.row);
        $('#tier').val(detail.tier);
        
        // Auto select location type based on activity
        const activity = $('#activity_type').val();
        if (activity === 'DISCHARGE' || activity === 'LOAD') {
            $('#location_type').val('VESSEL');
        } else {
            $('#location_type').val('YARD');
        }
    }
}

function apply_filter() {
    table.ajax.reload();
}

function reset_filter() {
    $('#filter_planning').val('').trigger('change');
    $('#filter_gate_no').val('');
    $('#filter_container').val('');
    table.ajax.reload();
}

function reload_table() {
    table.ajax.reload(null,false);
}

function submitTally() {
    var form = document.getElementById('formTally');
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    $.ajax({
        url: '<?= site_url("operations/tally/ajax_save") ?>',
        type: 'POST',
        data: $('#formTally').serialize(),
        dataType: 'json',
        success: function(res) {
            if(res.status) {
                Toast.fire({icon: 'success', title: 'Tally activity saved'});
                form.reset();
                $('.select2-planning, .select2-container').val('').trigger('change');
                $('#display_activity, #display_voyage').val('');
                form.classList.remove('was-validated');
                reload_table();
            } else {
                Toast.fire({icon: 'error', title: 'Error saving data'});
            }
        }
    });
}

function view_tally(id) {
    Toast.fire({icon: 'info', title: 'View Detail Tally ID: ' + id});
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
