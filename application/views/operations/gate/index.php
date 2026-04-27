<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h6 class="mb-0 text-white"><i class="fas fa-door-open me-2"></i>Gate Transactions (TCA System)</h6>
                    <?php if ($this->Acl_model->has_permission($current_user->role_id, 'operations/gate', 'can_create')): ?>
                    <a href="<?= site_url('operations/gate/gate_in') ?>" class="btn btn-light btn-sm text-primary fw-bold"><i class="fas fa-sign-in-alt me-2"></i>New Gate In</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Filters Section -->
                <div class="bg-light p-4 rounded-3 border mb-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-bold small text-secondary mb-2"><i class="fas fa-clipboard-list me-1"></i> Request Planning</label>
                            <select id="filter_planning" class="form-select select2-filter">
                                <option value="">-- All Requests --</option>
                                <?php foreach($plannings as $p): ?>
                                    <option value="<?= $p->id ?>"><?= $p->request_no ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label class="form-label fw-bold small text-secondary mb-2"><i class="fas fa-fingerprint me-1"></i> Gate No</label>
                            <input type="text" id="filter_gate_no" class="form-control" placeholder="GAT-..." style="height: 38px;">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label class="form-label fw-bold small text-secondary mb-2"><i class="fas fa-box me-1"></i> Container No</label>
                            <input type="text" id="filter_container" class="form-control" placeholder="MSKU..." style="height: 38px;">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label class="form-label fw-bold small text-secondary mb-2"><i class="fas fa-truck me-1"></i> Truck No</label>
                            <input type="text" id="filter_truck" class="form-control" placeholder="B 1234 ..." style="height: 38px;">
                        </div>
                        <div class="col-lg-3 col-md-12">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary w-100 fw-bold" onclick="apply_filter()" style="height: 38px;">
                                    <i class="fas fa-search me-2"></i>Apply Filter
                                </button>
                                <button class="btn btn-outline-secondary" onclick="reset_filter()" style="height: 38px;" title="Reset Filter">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="tableGate" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Gate & Planning No</th>
                                <th>Truck No</th>
                                <th>Container No</th>
                                <th>Activity</th>
                                <th>Gate In Time</th>
                                <th>Status</th>
                                <th width="10%">Action</th>
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

<!-- View Modal -->
<div class="modal fade" id="modalView" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Gate Transaction Detail</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="viewDetailContent">
                <!-- Data will be loaded here -->
                <div class="text-center p-5">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2 text-muted">Loading transaction details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" id="btnPrintInModal">
                    <i class="fas fa-print me-2"></i>Print Pass
                </button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
var table;

$(document).ready(function() {
    $('.select2-filter').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    table = $('#tableGate').DataTable({ 
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('operations/gate/ajax_list')?>",
            "type": "POST",
            "data": function(d) {
                d.filter_planning = $('#filter_planning').val();
                d.filter_gate_no = $('#filter_gate_no').val();
                d.filter_container = $('#filter_container').val();
                d.filter_truck = $('#filter_truck').val();
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

function apply_filter() {
    table.ajax.reload();
}

function reset_filter() {
    $('#filter_planning').val('').trigger('change');
    $('#filter_gate_no').val('');
    $('#filter_container').val('');
    $('#filter_truck').val('');
    table.ajax.reload();
}

function view_gate(id) {
    $('#modalView').modal('show');
    $('#viewDetailContent').html('<div class="text-center p-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Fetching data...</p></div>');
    
    $.ajax({
        url: "<?php echo site_url('operations/gate/ajax_view')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(res) {
            if (res.status === 'success') {
                const d = res.data;
                let html = `
                    <div class="p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="text-primary border-bottom pb-2 mb-3">Transaction Info</h6>
                                <table class="table table-sm table-borderless">
                                    <tr><td width="40%" class="text-muted small">Gate No</td><td class="fw-bold text-info">${d.gate_no}</td></tr>
                                    <tr><td class="text-muted small">Physical Gate</td><td>${d.gate_name || '-'}</td></tr>
                                    <tr><td class="text-muted small">Activity</td><td><span class="badge bg-secondary">${d.activity_type}</span></td></tr>
                                    <tr><td class="text-muted small">Status</td><td><span class="badge bg-primary">${d.status}</span></td></tr>
                                    <tr><td class="text-muted small">Gate In</td><td>${d.gate_in_time || '-'}</td></tr>
                                    <tr><td class="text-muted small">Gate Out</td><td>${d.gate_out_time || '-'}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary border-bottom pb-2 mb-3">Truck & Cargo</h6>
                                <table class="table table-sm table-borderless">
                                    <tr><td width="40%" class="text-muted small">Police No</td><td class="fw-bold">${d.police_number}</td></tr>
                                    <tr><td class="text-muted small">Driver Name</td><td>${d.driver_name}</td></tr>
                                    <tr><td class="text-muted small">Container No</td><td class="text-warning fw-bold">${d.container_no || 'N/A'}</td></tr>
                                    <tr><td class="text-muted small">Size / Type</td><td>${d.container_size || '-'}' / ${d.container_type || '-'}</td></tr>
                                    <tr><td class="text-muted small">Planning Req</td><td class="small">${d.request_no || 'Manual'}</td></tr>
                                    <tr><td class="text-muted small">Vessel</td><td>${d.vessel_name || '-'}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
                $('#viewDetailContent').html(html);
                $('#btnPrintInModal').attr('onclick', `window.open('<?php echo site_url("reports/gate_report/print_pass") ?>/${d.id}', '_blank')`);
            } else {
                $('#viewDetailContent').html('<div class="alert alert-danger m-3">' + res.message + '</div>');
            }
        },
        error: function() {
            $('#viewDetailContent').html('<div class="alert alert-danger m-3">Error fetching transaction details.</div>');
        }
    });
}

function gate_out(id) {
    Swal.fire({
        title: 'Confirm Gate Out?',
        text: "This truck will be marked as CHECKED OUT and leave the terminal.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Gate Out!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url : "<?php echo site_url('operations/gate/ajax_gate_out')?>/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    table.ajax.reload(null,false);
                    Toast.fire({icon: 'success', title: 'Gate Out completed'});
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    Toast.fire({icon: 'error', title: 'Error performing gate out'});
                }
            });
        }
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
