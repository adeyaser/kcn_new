<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-ship me-2 text-primary"></i>Master Data Vessel</h6>
                <?php if ($this->Acl_model->has_permission($current_user->role_id, 'master/vessel', 'can_create')): ?>
                <button class="btn btn-primary-custom" onclick="add_vessel()"><i class="fas fa-plus me-2"></i>Add Vessel</button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableVessel" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Vessel Code</th>
                                <th>Vessel Name</th>
                                <th>Call Sign</th>
                                <th>Flag</th>
                                <th>LOA</th>
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
                <h5 class="modal-title" id="modalLabel">Vessel Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Vessel Code</label>
                                <input name="vessel_code" placeholder="e.g., VSL001" class="form-control" type="text">
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Vessel Name</label>
                                <input name="vessel_name" placeholder="e.g., MV. OCEAN NAVIGATOR" class="form-control" type="text">
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Call Sign</label>
                                <input name="call_sign" placeholder="Call Sign" class="form-control" type="text">
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Flag</label>
                                <input name="flag" placeholder="Flag/Country" class="form-control" type="text">
                                <span class="help-block text-danger small"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">LOA (Length Overall)</label>
                                <div class="input-group">
                                    <input name="loa" placeholder="Length" class="form-control" type="number" step="0.01">
                                    <span class="input-group-text bg-dark text-muted border-secondary">meters</span>
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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php ob_start(); ?>
<script>
var save_method; //for save method string
var table;

$(document).ready(function() {
    //datatables
    table = $('#tableVessel').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('master/vessel/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0, -1 ], //first and last column
            "orderable": false, //set not orderable
        },
        ],
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
});

function add_vessel() {
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Vessel'); // Set Title to Bootstrap modal title
}

function edit_vessel(id) {
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('master/vessel/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="vessel_code"]').val(data.vessel_code);
            $('[name="vessel_name"]').val(data.vessel_name);
            $('[name="call_sign"]').val(data.call_sign);
            $('[name="flag"]').val(data.flag);
            $('[name="loa"]').val(data.loa);
            $('[name="is_active"]').val(data.is_active);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Vessel'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Toast.fire({
                icon: 'error',
                title: 'Error get data from ajax'
            });
        }
    });
}

function reload_table() {
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save() {
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('master/vessel/ajax_add')?>";
    } else {
        url = "<?php echo site_url('master/vessel/ajax_update')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
                Toast.fire({
                    icon: 'success',
                    title: 'Data saved successfully'
                });
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Toast.fire({
                icon: 'error',
                title: 'Error adding / update data'
            });
            $('#btnSave').text('Save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        }
    });
}

function delete_vessel(id) {
    confirmDelete('javascript:void(0)', function() {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('master/vessel/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                reload_table();
                Toast.fire({
                    icon: 'success',
                    title: 'Data deleted successfully'
                });
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Toast.fire({
                    icon: 'error',
                    title: 'Error deleting data'
                });
            }
        });
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
