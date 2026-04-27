<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-id-badge me-2 text-primary"></i>Master TID (Terminal ID)</h6>
                <button class="btn btn-primary-custom" onclick="add_tid()"><i class="fas fa-plus me-2"></i>Add TID</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableTid" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>TID Number</th>
                                <th>Company Name</th>
                                <th>Email</th>
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

<!-- Simple Modal for TID -->
<div class="modal fade" id="modal_tid" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TID Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formTid">
                    <input type="hidden" name="id" value="">
                    <div class="mb-3">
                        <label class="form-label">TID Number</label>
                        <input name="tid_number" class="form-control" type="text" placeholder="TID001">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input name="company_name" class="form-control" type="text">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" class="form-control" type="email">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" onclick="save()" class="btn btn-primary-custom">Save TID</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
var save_method;
var table;

$(document).ready(function() {
    table = $('#tableTid').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo site_url('master/tid/ajax_list')?>",
            "type": "POST"
        }
    });
});

function add_tid() {
    save_method = 'add';
    $('#formTid')[0].reset();
    $('[name="id"]').val('');
    $('#modal_tid').modal('show');
    $('.modal-title').text('Add New TID');
}

function edit_tid(id) {
    save_method = 'update';
    $('#formTid')[0].reset();
    $.ajax({
        url : "<?php echo site_url('master/tid/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="tid_number"]').val(data.tid_number);
            $('[name="company_name"]').val(data.company_name);
            $('[name="email"]').val(data.email);
            $('#modal_tid').modal('show');
            $('.modal-title').text('Edit TID');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Toast.fire({icon: 'error', title: 'Error fetching data'});
        }
    });
}

function save() {
    var url = save_method == 'add' ? "<?php echo site_url('master/tid/ajax_add')?>" : "<?php echo site_url('master/tid/ajax_update')?>";
    $.ajax({
        url : url,
        type: "POST",
        data: $('#formTid').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) {
                $('#modal_tid').modal('hide');
                table.ajax.reload(null,false);
                Toast.fire({icon: 'success', title: 'Data TID berhasil disimpan'});
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Toast.fire({icon: 'error', title: 'Gagal menyimpan data'});
        }
    });
}

function confirmDelete(callback) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data TID ini akan dihapus!",
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

function delete_tid(id) {
    confirmDelete(function() {
        $.ajax({
            url : "<?php echo site_url('master/tid/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                table.ajax.reload(null,false);
                Toast.fire({icon: 'success', title: 'Data TID berhasil dihapus'});
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
