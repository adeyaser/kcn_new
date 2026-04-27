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
            <a href="<?= site_url('operations/tca_planning/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Assignment
            </a>
        </div>
    </div>

    <div class="card-custom mb-4">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Filter by Request Planning</label>
                    <select class="form-select select2-filter" id="filterRequest">
                        <option value="">-- All Requests --</option>
                        <?php 
                        $this->db->select('id, request_no');
                        $plannings = $this->db->get('opr_planning_requests')->result();
                        foreach($plannings as $p): ?>
                            <option value="<?= $p->request_no ?>"><?= $p->request_no ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-8 text-end pt-4">
                    <button class="btn btn-secondary btn-sm" onclick="reset_filter()"><i class="fas fa-undo me-2"></i>Reset Filter</button>
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
            type: "POST"
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

    $('.select2-filter').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('.card-custom')
    });

    // Filter handling
    $('#filterRequest').change(function() {
        table.search($(this).val()).draw();
    });
    // Handle Check All
    $('#checkAll').change(function() {
        $('.row-check').prop('checked', $(this).prop('checked'));
        updateSelectedCount();
    });

    // Handle individual row check
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
});

function reset_filter() {
    $('#filterRequest').val('').trigger('change');
}

function bulk_print() {
    var ids = [];
    $('.row-check:checked').each(function() {
        ids.push($(this).val());
    });
    
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
            $('#viewContent').html('<div class="alert alert-danger m-3">Failed to load assignment details.</div>');
        }
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
