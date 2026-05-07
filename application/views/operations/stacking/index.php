<div class="row">
    <div class="col-lg-8">
        <div class="card-custom mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-th me-2 text-primary"></i>Yard Occupancy Overview</h6>
                <div class="badge bg-success">Overall: 64% Full</div>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <?php 
                    foreach($blocks as $b): 
                        $percentage = rand(40, 90);
                        $color = ($percentage > 80) ? 'bg-danger' : (($percentage > 60) ? 'bg-warning' : 'bg-success');
                    ?>
                    <div class="col-md-3">
                        <div class="p-3 border rounded-3 text-center mb-2">
                            <div class="fw-bold text-dark"><?= $b->block_name ?></div>
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
                    <table class="table table-hover align-middle" id="tableMovements">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Container</th>
                                <th>From Loc</th>
                                <th>To Loc</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="small">Just now</td>
                                <td class="fw-bold text-info">SEARCH_DEMO</td>
                                <td class="text-muted small">GATE-IN</td>
                                <td class="text-primary fw-bold">YARD</td>
                                <td><span class="badge bg-primary">RECEIVING</span></td>
                                <td>-</td>
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
                <span class="text-muted small">Ready to Stack (Gate-In)</span>
                <span class="fw-bold text-warning" id="readyCount">Loading...</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <span class="text-muted small">Stacked Today</span>
                <span class="fw-bold text-dark">42</span>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted small">Total Slots Available</span>
                <span class="fw-bold text-success">842</span>
            </div>
        </div>

        <div class="alert alert-info border-0 shadow-sm">
            <h6 class="fw-bold small"><i class="fas fa-info-circle me-2"></i>Integrated Workflow</h6>
            <p class="extra-small mb-0">Halaman ini kini terintegrasi dengan data <strong>Gate-In</strong>. Pilih kontainer yang sudah masuk gerbang untuk dicatat posisi tumpukannya di Yard.</p>
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
                        <label class="form-label fw-bold">Search Container (Gated-In)</label>
                        <select class="form-select select2-container" name="container_no" id="container_no" required>
                            <option value="">-- Search No. Container --</option>
                        </select>
                    </div>
                    
                    <div id="containerInfo" class="bg-light p-3 rounded mb-3" style="display:none;">
                        <div class="row small">
                            <div class="col-6"><strong>Size/Type:</strong> <span id="infoSize">-</span></div>
                            <div class="col-6"><strong>Planning:</strong> <span id="infoPlanning">-</span></div>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Yard Block</label>
                            <select class="form-select" name="block_id" id="block_id" required>
                                <option value="">-- Select Block --</option>
                                <?php foreach($blocks as $b): ?>
                                    <option value="<?= $b->id ?>"><?= $b->block_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Bay</label>
                            <input type="number" class="form-control" name="bay" required min="1">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Row</label>
                            <input type="number" class="form-control" name="row" required min="1">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Tier</label>
                            <input type="number" class="form-control" name="tier" required min="1">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Movement Reason</label>
                        <select class="form-select" name="reason">
                            <option value="Receiving">Receiving (Gate-In to Yard)</option>
                            <option value="Shifting">Shifting (Internal)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary-custom" id="btnSave" onclick="save_stacking()">Confirm Stacking</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.select2-container').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#modal_stacking'),
        ajax: {
            url: '<?= site_url("operations/stacking/ajax_search_container") ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { term: params.term };
            },
            processResults: function(data) {
                return { results: data };
            }
        },
        minimumInputLength: 3
    });

    $('.select2-container').on('select2:select', function(e) {
        var data = e.params.data;
        $('#infoSize').text(data.size + ' / ' + data.type);
        $('#infoPlanning').text(data.request_no);
        $('#containerInfo').slideDown();
    });
});

function new_stacking() {
    $('#formStacking')[0].reset();
    $('#containerInfo').hide();
    $('.select2-container').val(null).trigger('change');
    $('#modal_stacking').modal('show');
}

function save_stacking() {
    var form = $('#formStacking');
    if (!$('#container_no').val() || !$('#block_id').val()) {
        Toast.fire({icon: 'warning', title: 'Mohon lengkapi data kontainer dan lokasi'});
        return;
    }

    $('#btnSave').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');

    $.ajax({
        url: '<?= site_url("operations/stacking/ajax_save") ?>',
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function(res) {
            $('#btnSave').prop('disabled', false).html('Confirm Stacking');
            if(res.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Stacked!',
                    text: res.message,
                    background: '#1e293b', color: '#e2e8f0'
                });
                $('#modal_stacking').modal('hide');
                // Optional: refresh page or table
                location.reload();
            } else {
                Toast.fire({icon: 'error', title: res.message});
            }
        },
        error: function() {
            $('#btnSave').prop('disabled', false).html('Confirm Stacking');
            Toast.fire({icon: 'error', title: 'Server error'});
        }
    });
}
</script>

<style>
.extra-small { font-size: 11px; }
</style>
