<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-anchor me-2 text-primary"></i>Master Port List (POD/FPOD)</h6>
                <button class="btn btn-primary-custom" onclick="add_port()"><i class="fas fa-plus me-2"></i>Add Port</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tablePort" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Port Code</th>
                                <th>Port Name</th>
                                <th width="15%">Status</th>
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

<!-- Modal Form -->
<div class="modal fade" id="modal_form" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Port Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="mb-3">
                        <label class="form-label">Port Code (UNLOCODE) <span class="text-danger">*</span></label>
                        <input name="port_code" class="form-control" type="text" placeholder="e.g. IDJKT" maxlength="10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Port Name <span class="text-danger">*</span></label>
                        <input name="port_name" class="form-control" type="text" placeholder="e.g. JAKARTA / TANJUNG PRIOK" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary-custom">Save Port</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
var save_method;
var table;

$(document).ready(function() {
    table = $('#tablePort').DataTable({ 
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('master/port/ajax_list')?>",
            "type": "POST"
        },
        "columnDefs": [
            { "targets": [ 0, -1 ], "orderable": false },
        ],
    });
});

function add_port() {
    save_method = 'add';
    $('#form')[0].reset();
    $('#modal_form').modal('show');
    $('.modal-title').text('Add Master Port');
}

function edit_port(id) {
    save_method = 'update';
    $('#form')[0].reset();
    $.ajax({
        url : "<?php echo site_url('master/port/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="port_code"]').val(data.port_code);
            $('[name="port_name"]').val(data.port_name);
            $('[name="is_active"]').val(data.is_active);
            $('#modal_form').modal('show');
            $('.modal-title').text('Edit Master Port');
        }
    });
}

function save() {
    var url = save_method == 'add' ? "<?php echo site_url('master/port/ajax_add')?>" : "<?php echo site_url('master/port/ajax_update')?>";
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) {
                $('#modal_form').modal('hide');
                table.ajax.reload(null,false);
                Toast.fire({icon: 'success', title: 'Port saved successfully'});
            }
        }
    });
}

function delete_port(id) {
    confirmDelete('javascript:void(0)', function() {
        $.ajax({
            url : "<?php echo site_url('master/port/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                table.ajax.reload(null,false);
                Toast.fire({icon: 'success', title: 'Port deleted successfully'});
            }
        });
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
