<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-file-alt me-2 text-primary"></i>Planning Request List</h6>
                <?php if ($this->Acl_model->has_permission($current_user->role_id, 'planning/request', 'can_create')): ?>
                <a href="<?= site_url('planning/request/create') ?>" class="btn btn-primary-custom"><i class="fas fa-plus me-2"></i>New Request</a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableRequest" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Request No</th>
                                <th>Vessel Name</th>
                                <th>Voyage (In/Out)</th>
                                <th>Service Type</th>
                                <th>Request Type</th>
                                <th>Loosing Type</th>
                                <th>ETA</th>
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


<script>
var table;

$(document).ready(function() {
    table = $('#tableRequest').DataTable({ 
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('planning/request/ajax_list')?>",
            "type": "POST"
        },
        "columnDefs": [
        { 
            "targets": [ 0, -1 ],
            "orderable": false,
        },
        ],
    });
});

function delete_request(id) {
    confirmDelete('javascript:void(0)', function() {
        $.ajax({
            url : "<?php echo site_url('planning/request/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
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

function reload_table() {
    table.ajax.reload(null,false);
}
</script>
