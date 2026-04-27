<div class="row g-4 mb-4">
    <?php foreach($type_stats as $type => $s): ?>
    <div class="col-xl-3 col-md-6">
        <div class="card-custom">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted small mb-1"><?= $type ?> AVAILABILITY</h6>
                        <h3 class="text-dark mb-0"><?= $s['ready'] ?> / <?= $s['total'] ?></h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="fas <?= $type=='QCC'?'fa-crane':($type=='RTG'?'fa-th-large':($type=='RS'?'fa-truck-pickup':'fa-truck')) ?> fa-2x"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 4px; background: rgba(0,0,0,0.05);">
                    <div class="progress-bar bg-success" style="width: <?= $s['total']>0?($s['ready']/$s['total']*100):0 ?>%"></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-tools me-2 text-primary"></i>Equipment Live Status</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableEquipmentMonitor" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Current Status</th>
                                <th>Capacity</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($equipments as $e): ?>
                            <tr>
                                <td><strong><?= $e->equipment_code ?></strong></td>
                                <td><?= $e->equipment_name ?></td>
                                <td><span class="badge bg-secondary"><?= $e->equipment_type ?></span></td>
                                <td>
                                    <?php 
                                        $class = 'bg-success';
                                        if($e->status == 'MAINTENANCE') $class = 'bg-warning text-dark';
                                        if($e->status == 'BROKEN') $class = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $class ?>"><?= $e->status ?></span>
                                </td>
                                <td><?= $e->capacity ?> Ton</td>
                                <td>
                                    <select class="form-select form-select-sm" onchange="updateStatus(<?= $e->id ?>, this.value)">
                                        <option value="READY" <?= $e->status=='READY'?'selected':'' ?>>READY</option>
                                        <option value="MAINTENANCE" <?= $e->status=='MAINTENANCE'?'selected':'' ?>>MAINTENANCE</option>
                                        <option value="BROKEN" <?= $e->status=='BROKEN'?'selected':'' ?>>BROKEN</option>
                                    </select>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
$(document).ready(function() {
    $('#tableEquipmentMonitor').DataTable();
});

function updateStatus(id, status) {
    $.ajax({
        url: '<?= site_url("monitoring/equipment/ajax_update_status") ?>',
        type: 'POST',
        data: {id: id, status: status},
        dataType: 'json',
        success: function(res) {
            if(res.status) {
                Toast.fire({icon: 'success', title: 'Equipment status updated'});
                setTimeout(() => location.reload(), 1000);
            }
        }
    });
}
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
