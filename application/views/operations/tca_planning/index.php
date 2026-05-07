<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">TCA - Truck Assignment Planning</h5>
            <p class="text-muted small">Manage truck assignments and container pre-planning for Gate operations.</p>
        </div>
        <div class="d-flex gap-2">
            <button id="btnBulkPrint" class="btn btn-outline-info" style="display: none;" onclick="bulk_print()">
                <i class="fas fa-print me-2"></i>Bulk Print Selected (<span id="selectedCount">0</span>)
            </button>
            <button class="btn btn-outline-primary" id="btnShowBulk">
                <i class="fas fa-list me-2"></i>Bulk From Manifest
            </button>
            <a href="<?= site_url('operations/tca_planning/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Assignment
            </a>
        </div>
    </div>

    <div class="card-custom mb-4">
        <div class="card-body p-3">
            <div class="row align-items-center g-3">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Filter by Request Planning</label>
                    <select class="form-select" id="filterRequest">
                        <option value="">-- All Requests --</option>
                        <?php 
                        $this->db->select('id, request_no');
                        $plannings = $this->db->get('opr_planning_requests')->result();
                        foreach($plannings as $p): ?>
                            <option value="<?= $p->request_no ?>"><?= $p->request_no ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 pt-4">
                    <button class="btn btn-secondary btn-sm" onclick="reset_filter()"><i class="fas fa-undo me-2"></i>Reset</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card-custom">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table id="tableTca" class="table table-hover w-100">
                    <thead>
                        <tr>
                            <th width="30"><input type="checkbox" id="checkAll" class="form-check-input"></th>
                            <th>Assignment No</th>
                            <th>Request Planning</th>
                            <th>Container No</th>
                            <th>Truck No</th>
                            <th>Estimated Arrival</th>
                            <th>Status</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="modalView" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="fas fa-eye me-2 text-info"></i>Assignment Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="viewContent">
                <div class="text-center p-5">
                    <div class="spinner-border text-info" role="status"></div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" id="btnPrintModal"><i class="fas fa-print me-2"></i>Print Pass</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Modal -->
<div class="modal fade" id="modalBulk" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content border-0" style="background: #0f172a;">

            <!-- Modern Header with Gradient -->
            <div class="modal-header border-0 px-4 py-3" style="background: linear-gradient(135deg, #1e3a5f 0%, #0f2744 50%, #1a1a3e 100%); box-shadow: 0 2px 20px rgba(0,0,0,0.4);">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:42px;height:42px;background:linear-gradient(135deg,#3b82f6,#6366f1);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-layer-group text-white" style="font-size:16px;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-white fw-bold mb-0">Bulk Assignment From Manifest</h5>
                        <p class="text-white-50 mb-0" style="font-size:12px;">Assign trucks to multiple containers at once</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 ms-auto">
                    <div id="bulkCountBadge" class="d-none">
                        <span class="badge px-3 py-2" style="background:rgba(59,130,246,0.2);border:1px solid rgba(59,130,246,0.4);color:#93c5fd;font-size:12px;border-radius:20px;">
                            <i class="fas fa-box me-1"></i><span id="bulkCountNum">0</span> Containers
                        </span>
                    </div>
                    <button type="button" class="btn-close btn-close-white opacity-75" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="px-4 py-3 border-bottom" style="background:#111827; border-color:rgba(255,255,255,0.06) !important;">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label text-white-50 small fw-semibold text-uppercase mb-1" style="letter-spacing:.5px;">Planning Request</label>
                        <select class="form-select border-0 text-white fw-semibold" id="bulkPlanningSelect" style="background:#1e293b;border-radius:10px;padding:10px 14px;">
                            <option value="">— Choose a Planning Request —</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-white-50 small fw-semibold text-uppercase mb-1" style="letter-spacing:.5px;">Default Gate</label>
                        <select class="form-select border-0 text-white" id="bulkDefaultGate" style="background:#1e293b;border-radius:10px;padding:10px 14px;">
                            <option value="">— Loading gates... —</option>
                        </select>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn px-4 py-2 fw-semibold" id="btnSaveBulk" disabled
                            style="background:linear-gradient(135deg,#10b981,#059669);border:none;border-radius:10px;color:#fff;box-shadow:0 4px 15px rgba(16,185,129,0.3);">
                            <i class="fas fa-save me-2"></i>Save All Assignments
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="modal-body p-0" style="overflow-y:auto; background:#0f172a;">
                <table class="table align-middle mb-0" id="tableBulk" style="color:#e2e8f0;">
                    <thead style="background:#1e293b; position:sticky; top:0; z-index:10;">
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.08);">
                            <th class="ps-4 py-3 text-white-50 small fw-semibold" style="letter-spacing:.5px;" width="50">#</th>
                            <th class="py-3 text-white-50 small fw-semibold" style="letter-spacing:.5px;">Container No</th>
                            <th class="py-3 text-white-50 small fw-semibold" style="letter-spacing:.5px;">Size / Type</th>
                            <th class="py-3 text-white-50 small fw-semibold" style="letter-spacing:.5px;" width="200">Arrival Schedule</th>
                            <th class="py-3 text-white-50 small fw-semibold" style="letter-spacing:.5px;" width="170">Gate</th>
                            <th class="py-3 text-white-50 small fw-semibold" style="letter-spacing:.5px;" width="230">Assigned Truck</th>
                            <th class="py-3 text-white-50 small fw-semibold" style="letter-spacing:.5px;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="bulkTableBody">
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div style="opacity:.4;">
                                    <i class="fas fa-layer-group fa-3x mb-3 d-block" style="color:#3b82f6;"></i>
                                    <p class="text-white-50 mb-0">Select a planning request above to load containers</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
var table;
$(document).ready(function() {
    table = $('#tableTca').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: {
            url: "<?= site_url('operations/tca_planning/ajax_list') ?>",
            type: "POST",
            data: function(d) {
                d.filter_planning = $('#filterRequest').val();
            }
        },
        columnDefs: [{
            targets: [-1, 0],
            orderable: false
        }],
        language: {
            searchPlaceholder: "Search assignment...",
            paginate: {
                previous: '<i class="fas fa-chevron-left"></i>',
                next: '<i class="fas fa-chevron-right"></i>'
            }
        }
    });

    // Filter by request - reload table with filter param
    $('#filterRequest').on('change', function() {
        table.ajax.reload();
    });

    // Handle Check All
    $('#checkAll').change(function() {
        $('.row-check').prop('checked', $(this).prop('checked'));
        updateSelectedCount();
    });

    $(document).on('change', '.row-check', function() {
        if(!$(this).prop('checked')) $('#checkAll').prop('checked', false);
        updateSelectedCount();
    });

    function updateSelectedCount() {
        var count = $('.row-check:checked').length;
        $('#selectedCount').text(count);
        if(count > 0) {
            $('#btnBulkPrint').fadeIn();
        } else {
            $('#btnBulkPrint').fadeOut();
        }
    }

    // Bulk Modal trigger
    $('#btnShowBulk').on('click', function() {
        $('#modalBulk').modal('show');
        loadBulkData();
    });

    // Load planning list & gates when modal first opened
    var bulkLoaded = false;
    var bulkGates = [];

    function loadBulkData() {
        if (bulkLoaded) return;
        $.getJSON("<?= site_url('operations/tca_planning/bulk_create') ?>", function(res) {
            if (!res) return;
            bulkLoaded = true;
            bulkGates = res.gates || [];

            var planHtml = '<option value="">-- Select Planning Request --</option>';
            if (res.plannings) {
                $.each(res.plannings, function(i, p) {
                    planHtml += '<option value="' + p.id + '">' + p.request_no + ' - ' + p.vessel_name + '</option>';
                });
            }
            $('#bulkPlanningSelect').html(planHtml);

            var gateHtml = '';
            $.each(bulkGates, function(i, g) {
                gateHtml += '<option value="' + g.id + '">' + g.gate_name + '</option>';
            });
            $('#bulkDefaultGate').html(gateHtml);
        });
    }

    $('#bulkPlanningSelect').on('change', function() {
        var id = $(this).val();
        if (!id) { $('#btnSaveBulk').prop('disabled', true); return; }
        
        $('#bulkTableBody').html('<tr><td colspan="7" class="text-center p-5"><div class="spinner-border text-info"></div></td></tr>');
        
        $.getJSON("<?= site_url('operations/tca_planning/ajax_get_manifest_bulk') ?>", { planning_id: id }, function(res) {
            if (res && res.status === 'success') {
                renderBulkTable(res.data);
                $('#btnSaveBulk').prop('disabled', false);
            } else {
                $('#bulkTableBody').html('<tr><td colspan="7" class="text-center p-5 text-muted">No containers found</td></tr>');
            }
        });
    });

    function renderBulkTable(data) {
        var defaultGate = $('#bulkDefaultGate').val();
        var tbody = $('#bulkTableBody');
        tbody.empty();

        if (!data || data.length === 0) {
            tbody.html('<tr><td colspan="7" class="text-center py-5" style="color:#64748b;"><i class="fas fa-inbox fa-3x mb-3 d-block"></i>No containers found for this planning</td></tr>');
            return;
        }

        // Update count badge
        $('#bulkCountNum').text(data.length);
        $('#bulkCountBadge').removeClass('d-none');

        $.each(data, function(i, m) {
            var arrival = m.estimated_arrival ? m.estimated_arrival.replace(' ', 'T') : '';

            var gateOptions = '';
            $.each(bulkGates, function(j, g) {
                var sel = (m.gate_id == g.id || (!m.gate_id && g.id == defaultGate)) ? 'selected' : '';
                gateOptions += '<option value="' + g.id + '" ' + sel + '>' + g.gate_name + '</option>';
            });

            var truckOption = m.police_number
                ? '<option value="' + m.truck_id + '" selected>' + m.police_number + '</option>'
                : '<option value=""></option>';

            var assignBadge = m.assignment_no
                ? '<span class="badge bg-success">' + m.assignment_no + '</span>'
                : '<span class="text-muted small">-</span>';

            var row = '<tr data-id="' + m.id + '" style="border-bottom:1px solid rgba(255,255,255,0.05); transition:background .15s;">' +
                '<td class="ps-4 py-3 text-white-50 small">' + (i+1) + '</td>' +
                '<td class="py-3"><span class="fw-bold" style="color:#60a5fa;letter-spacing:.3px;">' + m.container_no + '</span></td>' +
                '<td class="py-3"><span class="badge" style="background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.3);color:#a5b4fc;border-radius:6px;padding:4px 10px;">' + m.size + '\' ' + m.type + '</span></td>' +
                '<td class="py-3"><input type="datetime-local" class="form-control form-control-sm border-0 inp-arrival" style="background:#1e293b;color:#e2e8f0;border-radius:8px;font-size:12px;" value="' + arrival + '"></td>' +
                '<td class="py-3"><select class="form-select form-select-sm border-0 inp-gate" style="background:#1e293b;color:#e2e8f0;border-radius:8px;font-size:12px;">' + gateOptions + '</select></td>' +
                '<td class="py-3"><select class="form-select form-select-sm border-0 inp-truck-bulk" style="background:#1e293b;color:#e2e8f0;border-radius:8px;font-size:12px;" data-placeholder="Type to search...">' + truckOption + '</select></td>' +
                '<td class="py-3">' + assignBadge + '</td>' +
            '</tr>';
            tbody.append(row);
        });

        // Init Select2 for each truck dropdown
        tbody.find('.inp-truck-bulk').each(function() {
            $(this).select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modalBulk'),
                ajax: {
                    url: "<?= site_url('operations/tca_planning/ajax_get_truck_info') ?>",
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return { term: params.term };
                    },
                    processResults: function(data) {
                        return { results: data };
                    },
                    cache: true
                },
                minimumInputLength: 1,
                placeholder: 'Type to search truck...'
            });
        });
    }

    $('#btnSaveBulk').on('click', function() {
        var assignments = [];
        $('#bulkTableBody tr').each(function() {
            var manifestId = $(this).data('id');
            var arrival = $(this).find('.inp-arrival').val();
            var gateId = $(this).find('.inp-gate').val();
            var truckId = $(this).find('.inp-truck-bulk').val();
            if (arrival || truckId) {
                assignments.push({ manifest_id: manifestId, est_arrival: arrival, gate_id: gateId, truck_id: truckId });
            }
        });

        if (assignments.length === 0) {
            Toast.fire({ icon: 'warning', title: 'Isi minimal satu jadwal atau truk terlebih dahulu' });
            return;
        }

        $('#btnSaveBulk').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');

        $.post("<?= site_url('operations/tca_planning/ajax_save_bulk') ?>", {
            planning_id: $('#bulkPlanningSelect').val(),
            assignments: assignments
        }, function(res) {
            $('#btnSaveBulk').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Save All Assignments');
            if (res && res.status === 'success') {
                Toast.fire({ icon: 'success', title: res.message });
                $('#bulkPlanningSelect').trigger('change');
                table.ajax.reload();
            }
        }, 'json');
    });
});

function reset_filter() {
    $('#filterRequest').val('');
    table.ajax.reload();
}

function bulk_print() {
    var ids = [];
    $('.row-check:checked').each(function() { ids.push($(this).val()); });
    if(ids.length > 0) {
        window.open("<?= site_url('operations/tca_planning/bulk_print?ids=') ?>" + ids.join(','), "_blank");
    }
}

function print_pass(id) {
    window.open("<?= site_url('operations/tca_planning/print_pass/') ?>" + id, "_blank");
}

function view_assignment(id) {
    $('#modalView').modal('show');
    $('#viewContent').html('<div class="text-center p-5"><div class="spinner-border text-info" role="status"></div></div>');
    $.ajax({
        url: "<?= site_url('operations/tca_planning/ajax_view/') ?>" + id,
        type: "GET",
        dataType: "HTML",
        success: function(html) {
            $('#viewContent').html(html);
            $('#btnPrintModal').attr('onclick', 'print_pass(' + id + ')');
        },
        error: function() {
            $('#viewContent').html('<div class="alert alert-danger m-3">Failed to load details.</div>');
        }
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
