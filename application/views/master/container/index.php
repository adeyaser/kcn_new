<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-box me-2 text-primary"></i>Master Container Database</h6>
                <button class="btn btn-primary-custom" onclick="add_container()"><i class="fas fa-plus me-2"></i>Add Container</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableContainer" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Container No</th>
                                <th>Size/Type</th>
                                <th>ISO Code</th>
                                <th>Status</th>
                                <th>Last Position</th>
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

<!-- Simple Modal for Container -->
<div class="modal fade" id="modal_container" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Container Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formContainer">
                    <div class="mb-3">
                        <label class="form-label">Container Number</label>
                        <input name="container_no" class="form-control" type="text" placeholder="MSKU1234567" style="text-transform: uppercase;">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Size</label>
                            <select name="size" class="form-select">
                                <option value="20">20 ft</option>
                                <option value="40">40 ft</option>
                                <option value="45">45 ft</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="GP">GP</option>
                                <option value="HC">HC</option>
                                <option value="RF">RF</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ISO Code</label>
                        <input name="iso_code" class="form-control" type="text">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary-custom">Save Container</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
$(document).ready(function() {
    $('#tableContainer').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo site_url('master/container/ajax_list')?>",
            "type": "POST"
        }
    });
});
function add_container() {
    $('#modal_container').modal('show');
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
