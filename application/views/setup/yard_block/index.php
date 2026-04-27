<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-th me-2 text-primary"></i>Master Yard Blocks</h6>
                <?php if ($this->Acl_model->has_permission($current_user->role_id, 'setup/yard_block', 'can_create')): ?>
                <button class="btn btn-primary-custom" onclick="add_block()"><i class="fas fa-plus me-2"></i>Add Block</button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableBlock" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Block Name</th>
                                <th>Type</th>
                                <th>Max Bay</th>
                                <th>Max Row</th>
                                <th>Max Tier</th>
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

<!-- Modal Form -->
<div class="modal fade" id="modal_form" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yard Block Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="mb-3">
                        <label class="form-label">Block Name <span class="text-danger">*</span></label>
                        <input name="block_name" placeholder="e.g., BLOCK-A" class="form-control" type="text">
                        <span class="help-block text-danger small"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Block Type</label>
                        <select name="block_type" class="form-select">
                            <option value="EXPORT">Export</option>
                            <option value="IMPORT">Import</option>
                            <option value="REEFER">Reefer</option>
                            <option value="EMPTY">Empty</option>
                            <option value="DANGER">Danger / DG</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label">Max Bay</label>
                            <input name="max_bay" placeholder="10" class="form-control" type="number">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Max Row</label>
                            <input name="max_row" placeholder="6" class="form-control" type="number">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Max Tier</label>
                            <input name="max_tier" placeholder="5" class="form-control" type="number">
                        </div>
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
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary-custom">Save Block</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
var save_method;
var table;

$(document).ready(function() {
    table = $('#tableBlock').DataTable({ 
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('setup/yard_block/ajax_list')?>",
            "type": "POST"
        },
        "columnDefs": [
        { "targets": [ 0, -1 ], "orderable": false },
        ],
    });
});

function add_block() {
    save_method = 'add';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Add New Yard Block');
}

function edit_block(id) {
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();

    $.ajax({
        url : "<?php echo site_url('setup/yard_block/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="block_name"]').val(data.block_name);
            $('[name="block_type"]').val(data.block_type);
            $('[name="max_bay"]').val(data.max_bay);
            $('[name="max_row"]').val(data.max_row);
            $('[name="max_tier"]').val(data.max_tier);
            $('[name="is_active"]').val(data.is_active);
            $('#modal_form').modal('show');
            $('.modal-title').text('Edit Yard Block');
        }
    });
}

function save() {
    $('#btnSave').text('saving...');
    $('#btnSave').attr('disabled',true);
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('setup/yard_block/ajax_add')?>";
    } else {
        url = "<?php echo site_url('setup/yard_block/ajax_update')?>";
    }

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
                Toast.fire({icon: 'success', title: 'Yard block saved'});
            } else {
                for (var i = 0; i < data.inputerror.length; i++) {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
            }
            $('#btnSave').text('Save Block');
            $('#btnSave').attr('disabled',false);
        }
    });
}

function delete_block(id) {
    confirmDelete('javascript:void(0)', function() {
        $.ajax({
            url : "<?php echo site_url('setup/yard_block/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                table.ajax.reload(null,false);
                Toast.fire({icon: 'success', title: 'Yard block deleted'});
            }
        });
    });
}
</script>
<?php $this->data['page_js'] = ob_get_clean(); ?>
