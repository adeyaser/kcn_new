<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-calendar-alt me-2 text-primary"></i>Vessel Arrival/Departure Schedule</h6>
                <button class="btn btn-primary-custom" onclick="add_schedule()"><i class="fas fa-plus me-2"></i>Add Schedule</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableSchedule" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Vessel Name</th>
                                <th>Voyage (In/Out)</th>
                                <th>Berth</th>
                                <th>ETA</th>
                                <th>ETD</th>
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
                <h5 class="modal-title">Vessel Schedule Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="mb-3">
                        <label class="form-label">Vessel <span class="text-danger">*</span></label>
                        <select name="vessel_id" class="form-select" required>
                            <option value="">-- Select Vessel --</option>
                            <?php foreach($vessels as $v): ?>
                                <option value="<?= $v->id ?>"><?= $v->vessel_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Voyage In</label>
                            <input name="voyage_in" class="form-control" type="text" placeholder="V.001I">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Voyage Out</label>
                            <input name="voyage_out" class="form-control" type="text" placeholder="V.001O">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Berth Assignment</label>
                        <select name="berth_id" class="form-select">
                            <option value="">-- No Assignment --</option>
                            <?php foreach($berths as $b): ?>
                                <option value="<?= $b->id ?>"><?= $b->berth_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">ETA</label>
                            <input name="eta" class="form-control" type="datetime-local">
                        </div>
                        <div class="col-6">
                            <label class="form-label">ETD</label>
                            <input name="etd" class="form-control" type="datetime-local">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">POD</label>
                            <select name="pod" class="form-select">
                                <option value="">-- Select POD --</option>
                                <?php foreach($ports as $p): ?>
                                    <option value="<?= $p->port_code ?>"><?= $p->port_code ?> - <?= $p->port_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Final POD (FPOD)</label>
                            <select name="fpod" class="form-select">
                                <option value="">-- Select FPOD --</option>
                                <?php foreach($ports as $p): ?>
                                    <option value="<?= $p->port_code ?>"><?= $p->port_code ?> - <?= $p->port_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="PLANNED">Planned</option>
                            <option value="ARRIVED">Arrived</option>
                            <option value="BERTHED">Berthed</option>
                            <option value="DEPARTED">Departed</option>
                            <option value="CANCELLED">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary-custom">Save Schedule</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
var save_method;
var table;

$(document).ready(function() {
    table = $('#tableSchedule').DataTable({ 
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('master/schedule/ajax_list')?>",
            "type": "POST"
        },
    });
});

function add_schedule() {
    save_method = 'add';
    $('#form')[0].reset();
    $('#modal_form').modal('show');
    $('.modal-title').text('Add Vessel Schedule');
}

function edit_schedule(id) {
    save_method = 'update';
    $('#form')[0].reset();
    $.ajax({
        url : "<?php echo site_url('master/schedule/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="vessel_id"]').val(data.vessel_id);
            $('[name="voyage_in"]').val(data.voyage_in);
            $('[name="voyage_out"]').val(data.voyage_out);
            $('[name="berth_id"]').val(data.berth_id);
            if(data.eta) $('[name="eta"]').val(data.eta.replace(' ', 'T').substring(0, 16));
            if(data.etd) $('[name="etd"]').val(data.etd.replace(' ', 'T').substring(0, 16));
            $('[name="status"]').val(data.status);
            $('[name="pod"]').val(data.pod);
            $('[name="fpod"]').val(data.fpod);
            $('[name="remarks"]').val(data.remarks);
            $('#modal_form').modal('show');
            $('.modal-title').text('Edit Vessel Schedule');
        }
    });
}

function save() {
    var url = save_method == 'add' ? "<?php echo site_url('master/schedule/ajax_add')?>" : "<?php echo site_url('master/schedule/ajax_update')?>";
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
                Toast.fire({icon: 'success', title: 'Schedule saved'});
            }
        }
    });
}

function delete_schedule(id) {
    confirmDelete('javascript:void(0)', function() {
        $.ajax({
            url : "<?php echo site_url('master/schedule/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                table.ajax.reload(null,false);
                Toast.fire({icon: 'success', title: 'Schedule deleted'});
            }
        });
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
