<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 text-white"><i class="fas fa-door-open me-2 text-info"></i>Gate Master Data</h5>
        <button class="btn btn-primary" onclick="add_gate()"><i class="fas fa-plus me-2"></i>Add New Gate</button>
    </div>

    <div class="card-custom">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tableGate" class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Gate Name</th>
                            <th>Gate Type</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalGate" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="modalTitle">Add New Gate</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formGate">
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Gate Name</label>
                        <input type="text" name="gate_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gate Type</label>
                        <select name="gate_type" class="form-select">
                            <option value="IN">IN ONLY</option>
                            <option value="OUT">OUT ONLY</option>
                            <option value="BOTH">IN & OUT (BOTH)</option>
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                        <label class="form-check-label" for="isActive">Active Status</label>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
var table;
var save_method;

$(document).ready(function() {
    table = $('#tableGate').DataTable({
        ajax: "<?= site_url('master/gate/ajax_list') ?>",
        order: [],
        columnDefs: [{ targets: [-1, 0], orderable: false }]
    });

    $('#formGate').submit(function(e) {
        e.preventDefault();
        var url = save_method === 'add' ? "<?= site_url('master/gate/ajax_add') ?>" : "<?= site_url('master/gate/ajax_update') ?>";
        $.ajax({
            url: url,
            type: "POST",
            data: $(this).serialize(),
            dataType: "JSON",
            success: function(res) {
                if(res.status) {
                    $('#modalGate').modal('hide');
                    table.ajax.reload();
                    Toast.fire({ icon: 'success', title: 'Data saved successfully' });
                }
            }
        });
    });
});

function add_gate() {
    save_method = 'add';
    $('#formGate')[0].reset();
    $('#modalTitle').text('Add New Gate');
    $('#modalGate').modal('show');
}

function edit_gate(id) {
    save_method = 'update';
    $('#formGate')[0].reset();
    $.ajax({
        url: "<?= site_url('master/gate/ajax_edit/') ?>" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="id"]').val(data.id);
            $('[name="gate_name"]').val(data.gate_name);
            $('[name="gate_type"]').val(data.gate_type);
            $('#isActive').prop('checked', data.is_active == 1);
            $('#modalTitle').text('Edit Gate');
            $('#modalGate').modal('show');
        }
    });
}

function delete_gate(id) {
    confirmDelete(null, function() {
        $.ajax({
            url: "<?= site_url('master/gate/ajax_delete/') ?>" + id,
            type: "POST",
            dataType: "JSON",
            success: function(res) {
                table.ajax.reload();
                Toast.fire({ icon: 'success', title: 'Data deleted successfully' });
            }
        });
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
