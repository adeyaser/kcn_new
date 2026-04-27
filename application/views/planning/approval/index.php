<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-check-circle me-2 text-primary"></i>Planning Request Approval</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableApproval" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Request No</th>
                                <th>Vessel</th>
                                <th>Voyage (I/O)</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Created At</th>
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

<!-- Review Modal -->
<div class="modal fade" id="modalReview" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-contract me-2"></i>Review Planning Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="requestDetail">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Request No</label>
                            <span id="view_request_no" class="fw-bold fs-5"></span>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <label class="text-muted small d-block">Status</label>
                            <span id="view_status"></span>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4 p-3 bg-light rounded border">
                        <div class="col-md-4">
                            <label class="text-muted small d-block">Vessel Name</label>
                            <span id="view_vessel" class="fw-bold"></span>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small d-block">Voyage In/Out</label>
                            <span id="view_voyage" class="fw-bold"></span>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small d-block">Service Type</label>
                            <span id="view_service" class="fw-bold"></span>
                        </div>
                    </div>

                    <div class="row g-3 mb-4 border-bottom pb-3">
                        <div class="col-md-3">
                            <label class="text-muted small d-block">ETA</label>
                            <span id="view_eta"></span>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small d-block">ETD</label>
                            <span id="view_etd"></span>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small d-block">POD</label>
                            <span id="view_pod"></span>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small d-block">FPOD</label>
                            <span id="view_fpod"></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold mb-0">Container List (Manifest)</h6>
                            <span class="badge bg-secondary" id="container_count">0 Containers</span>
                        </div>
                        <div class="table-responsive border rounded" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm table-bordered table-striped mb-0" id="tableReviewManifest">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th>No</th>
                                        <th>Container No</th>
                                        <th>Size</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Weight</th>
                                    </tr>
                                </thead>
                                <tbody id="view_manifest_list">
                                    <!-- Dynamic -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="mb-3">
                        <label class="form-label">Approval Note / Reason</label>
                        <textarea id="approval_note" class="form-control" rows="3" placeholder="Add some notes here..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="current_request_id">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <div id="actionButtons">
                    <button type="button" onclick="processAction('reject')" class="btn btn-danger"><i class="fas fa-times me-2"></i>Reject</button>
                    <button type="button" onclick="processAction('approve')" class="btn btn-success"><i class="fas fa-check me-2"></i>Approve Request</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
var table;

$(document).ready(function() {
    table = $('#tableApproval').DataTable({ 
        "processing": true,
        "ajax": {
            "url": "<?php echo site_url('planning/approval/ajax_list')?>",
            "type": "POST"
        },
    });
});

function view_request(id) {
    $('#current_request_id').val(id);
    $('#approval_note').val('');
    
    $.ajax({
        url: '<?= site_url("planning/approval/get_detail") ?>/' + id,
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if(res.status) {
                var d = res.data;
                $('#view_request_no').text(d.request_no);
                $('#view_vessel').text(d.vessel_name);
                $('#view_voyage').text(d.voyage_in + ' / ' + d.voyage_out);
                $('#view_service').text(d.service_type + ' (' + d.request_type + ')');
                $('#view_eta').text(d.eta);
                $('#view_etd').text(d.etd);
                $('#view_pod').text(d.pod);
                $('#view_fpod').text(d.fpod);
                
                // Manifest list
                var manifestHtml = '';
                if(res.manifest && res.manifest.length > 0) {
                    $('#container_count').text(res.manifest.length + ' Containers');
                    res.manifest.forEach(function(m, index) {
                        manifestHtml += `<tr>
                            <td>${index + 1}</td>
                            <td>${m.container_no}</td>
                            <td>${m.size}</td>
                            <td>${m.type}</td>
                            <td>${m.status}</td>
                            <td>${m.weight}</td>
                        </tr>`;
                    });
                } else {
                    manifestHtml = '<tr><td colspan="6" class="text-center text-muted">No container data available</td></tr>';
                }
                $('#view_manifest_list').html(manifestHtml);
                
                var statusHtml = '';
                if(d.status === 'REQUESTED') {
                    statusHtml = '<span class="badge bg-warning text-dark">Pending Approval</span>';
                    $('#actionButtons').show();
                } else if(d.status === 'APPROVED') {
                    statusHtml = '<span class="badge bg-success">Approved</span>';
                    $('#actionButtons').hide();
                } else {
                    statusHtml = '<span class="badge bg-danger">Rejected</span>';
                    $('#actionButtons').hide();
                }
                $('#view_status').html(statusHtml);
                $('#approval_note').val(d.approval_note);
                
                $('#modalReview').modal('show');
            }
        }
    });
}

function processAction(type) {
    var id = $('#current_request_id').val();
    var note = $('#approval_note').val();
    var url = type === 'approve' ? '<?= site_url("planning/approval/ajax_approve") ?>' : '<?= site_url("planning/approval/ajax_reject") ?>';
    var confirmText = type === 'approve' ? 'Approve this request?' : 'Reject this request?';
    
    Swal.fire({
        title: confirmText,
        text: "This action cannot be undone.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: type === 'approve' ? '#22c55e' : '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, ' + type + ' it!',
        background: '#1e293b', color: '#e2e8f0'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {id: id, note: note},
                dataType: 'json',
                success: function(res) {
                    if(res.status) {
                        $('#modalReview').modal('hide');
                        table.ajax.reload(null, false);
                        Toast.fire({icon: 'success', title: res.message});
                    }
                }
            });
        }
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
