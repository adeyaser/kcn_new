<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-cogs me-2 text-info"></i>Master Data Equipment</h6>
                <?php if ($this->Acl_model->has_permission($current_user->role_id, 'master/equipment', 'can_create')): ?>
                <button class="btn btn-primary-custom" onclick="add_equipment()"><i class="fas fa-plus me-2"></i>Add Equipment</button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableEquipment" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Equipment Code</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Capacity (Ton)</th>
                                <th>Condition</th>
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
<div class="modal fade" id="modal_form" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Equipment Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Equipment Code</label>
                                <input name="equipment_code" placeholder="e.g., QCC-01" class="form-control" type="text" style="text-transform: uppercase;">
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Equipment Name</label>
                                <input name="equipment_name" placeholder="Name or Model" class="form-control" type="text">
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <select name="equipment_type" class="form-select">
                                    <option value="QCC">QCC (Quay Crane)</option>
                                    <option value="RTG">RTG (Rubber Tyred Gantry)</option>
                                    <option value="RS">RS (Reach Stacker)</option>
                                    <option value="FL">FL (Forklift)</option>
                                    <option value="TRUCK">Internal Truck</option>
                                </select>
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Capacity (Ton)</label>
                                <input name="capacity" placeholder="Capacity in Ton" class="form-control" type="number" step="0.1">
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Condition</label>
                                <select name="status" class="form-select">
                                    <option value="READY">READY</option>
                                    <option value="MAINTENANCE">MAINTENANCE</option>
                                    <option value="BROKEN">BROKEN</option>
                                </select>
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Active Status</label>
                                <select name="is_active" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary-custom">Save</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
var save_method;
var table;

$(document).ready(function() {
    table = $('#tableEquipment').DataTable({ 
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('master/equipment/ajax_list')?>",
            "type": "POST"
        },
        "columnDefs": [
        { 
            "targets": [ 0, -1 ],
            "orderable": false,
        },
        ],
    });

    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
});

function add_equipment() {
    save_method = 'add';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Add Equipment');
}

function edit_equipment(id) {
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();

    $.ajax({
        url : "<?php echo site_url('master/equipment/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="equipment_code"]').val(data.equipment_code);
            $('[name="equipment_name"]').val(data.equipment_name);
            $('[name="equipment_type"]').val(data.equipment_type);
            $('[name="capacity"]').val(data.capacity);
            $('[name="status"]').val(data.status);
            $('[name="is_active"]').val(data.is_active);
            $('#modal_form').modal('show');
            $('.modal-title').text('Edit Equipment');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Toast.fire({icon: 'error', title: 'Error get data from ajax'});
        }
    });
}

function reload_table() {
    table.ajax.reload(null,false);
}

function save() {
    $('#btnSave').text('saving...');
    $('#btnSave').attr('disabled',true);
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('master/equipment/ajax_add')?>";
    } else {
        url = "<?php echo site_url('master/equipment/ajax_update')?>";
    }

    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status)
            {
                $('#modal_form').modal('hide');
                reload_table();
                Toast.fire({icon: 'success', title: 'Data saved successfully'});
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
            }
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Toast.fire({icon: 'error', title: 'Error adding / update data'});
            $('#btnSave').text('Save');
            $('#btnSave').attr('disabled',false);
        }
    });
}

function delete_equipment(id) {
    confirmDelete('javascript:void(0)', function() {
        $.ajax({
            url : "<?php echo site_url('master/equipment/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                $('#modal_form').modal('hide');
                reload_table();
                Toast.fire({icon: 'success', title: 'Data deleted successfully'});
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Toast.fire({icon: 'error', title: 'Error deleting data'});
            }
        });
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
