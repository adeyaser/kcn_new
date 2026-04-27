<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-truck me-2 text-warning"></i>Master Data Truck (TCA)</h6>
                <?php if ($this->Acl_model->has_permission($current_user->role_id, 'master/truck', 'can_create')): ?>
                <button class="btn btn-primary-custom" onclick="add_truck()"><i class="fas fa-plus me-2"></i>Add Truck</button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableTruck" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Police Number</th>
                                <th>Company</th>
                                <th>Driver Name</th>
                                <th>Driver Phone</th>
                                <th>RFID Tag</th>
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
                <h5 class="modal-title" id="modalLabel">Truck Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Police Number</label>
                                <input name="police_number" placeholder="e.g., B 1234 CD" class="form-control" type="text" style="text-transform: uppercase;">
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Truck Company</label>
                                <input name="truck_company" placeholder="Company Name" class="form-control" type="text">
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Driver Name</label>
                                <input name="driver_name" placeholder="Driver Name" class="form-control" type="text">
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Driver Phone</label>
                                <input name="driver_phone" placeholder="Phone Number" class="form-control" type="text">
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">RFID Tag (TCA)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark text-muted border-secondary"><i class="fas fa-wifi"></i></span>
                                    <input name="rfid_tag" placeholder="Scan or Enter RFID" class="form-control" type="text">
                                </div>
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
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
    table = $('#tableTruck').DataTable({ 
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('master/truck/ajax_list')?>",
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

function add_truck() {
    save_method = 'add';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#modal_form').modal('show');
    $('.modal-title').text('Add Truck (TCA Registration)');
}

function edit_truck(id) {
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();

    $.ajax({
        url : "<?php echo site_url('master/truck/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="police_number"]').val(data.police_number);
            $('[name="truck_company"]').val(data.truck_company);
            $('[name="driver_name"]').val(data.driver_name);
            $('[name="driver_phone"]').val(data.driver_phone);
            $('[name="rfid_tag"]').val(data.rfid_tag);
            $('[name="is_active"]').val(data.is_active);
            $('#modal_form').modal('show');
            $('.modal-title').text('Edit Truck');
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
        url = "<?php echo site_url('master/truck/ajax_add')?>";
    } else {
        url = "<?php echo site_url('master/truck/ajax_update')?>";
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

// Utility for confirmation
function confirmDelete(callback) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data truk ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

function delete_truck(id) {
    confirmDelete(function() {
        $.ajax({
            url : "<?php echo site_url('master/truck/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                reload_table();
                Toast.fire({icon: 'success', title: 'Data truk berhasil dihapus'});
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Toast.fire({icon: 'error', title: 'Gagal menghapus data'});
            }
        });
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
