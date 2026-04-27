<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-book-reader me-2 text-primary"></i>Vessel Movement Log (Port Book)</h6>
                <div class="badge bg-dark border border-secondary text-muted">Chronological Milestones</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableVesselLog" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Vessel Name</th>
                                <th>Voyage</th>
                                <th>Berth</th>
                                <th>Arrival (ATA)</th>
                                <th>Berthing (ATB)</th>
                                <th>Departure (ATD)</th>
                                <th>Status</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($logs as $l): ?>
                            <tr>
                                <td><strong><?= $l->vessel_name ?></strong></td>
                                <td><?= $l->voyage_in ?> / <?= $l->voyage_out ?></td>
                                <td><?= $l->berth_name ? $l->berth_name : '-' ?></td>
                                <td><?= $l->eta ? date('d/m H:i', strtotime($l->eta)) : '-' ?></td>
                                <td><?= $l->etb ? date('d/m H:i', strtotime($l->etb)) : '-' ?></td>
                                <td><?= $l->etd ? date('d/m H:i', strtotime($l->etd)) : '-' ?></td>
                                <td>
                                    <?php 
                                        $class = 'bg-secondary';
                                        if($l->status == 'ARRIVED') $class = 'bg-info text-dark';
                                        if($l->status == 'BERTHED') $class = 'bg-success';
                                        if($l->status == 'DEPARTED') $class = 'bg-dark';
                                    ?>
                                    <span class="badge <?= $class ?>"><?= $l->status ?></span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <?php if($l->status == 'PLANNED'): ?>
                                            <button class="btn btn-info" onclick="updateVesselStatus(<?= $l->id ?>, 'ARRIVED')">ARRIVED</button>
                                        <?php elseif($l->status == 'ARRIVED'): ?>
                                            <button class="btn btn-success" onclick="updateVesselStatus(<?= $l->id ?>, 'BERTHED')">BERTHED</button>
                                        <?php elseif($l->status == 'BERTHED'): ?>
                                            <button class="btn btn-dark" onclick="updateVesselStatus(<?= $l->id ?>, 'DEPARTED')">DEPARTED</button>
                                        <?php endif; ?>
                                    </div>
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
<script>
$(document).ready(function() {
    $('#tableVesselLog').DataTable({
        "order": [[3, "desc"]]
    });
});

function updateVesselStatus(id, status) {
    confirmAction(`Confirm vessel status update to ${status}?`, function() {
        $.ajax({
            url: '<?= site_url("monitoring/vessel_log/update_status") ?>',
            type: 'POST',
            data: {id: id, status: status},
            dataType: 'json',
            success: function(res) {
                if(res.status) {
                    Toast.fire({icon: 'success', title: `Vessel marked as ${status}`});
                    setTimeout(() => location.reload(), 1000);
                }
            }
        });
    });
}
</script>
<?php $this->data['page_js'] = ob_get_clean(); ?>
