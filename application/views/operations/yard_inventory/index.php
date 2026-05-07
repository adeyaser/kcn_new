<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-desktop me-2"></i>Yard Monitoring List</h6>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-light" onclick="window.print()"><i class="fas fa-print me-1"></i>Print</button>
                    <button class="btn btn-sm btn-success"><i class="fas fa-file-excel me-1"></i>Export</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="tableInventory">
                        <thead>
                            <tr>
                                <th>Block</th>
                                <th>Position (B/R/T)</th>
                                <th>Container No</th>
                                <th>Size</th>
                                <th>Type</th>
                                <th>Consignee</th>
                                <th>Last Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($inventory as $item): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-primary"><?= $item->block_name ?></span>
                                    <small class="d-block text-muted"><?= $item->block_type ?></small>
                                </td>
                                <td class="fw-bold">
                                    <?= str_pad($item->bay, 2, '0', STR_PAD_LEFT) ?> / 
                                    <?= str_pad($item->row, 2, '0', STR_PAD_LEFT) ?> / 
                                    <?= str_pad($item->tier, 2, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="fw-bold text-info"><?= $item->container_no ?></td>
                                <td><?= $item->size ?>ft</td>
                                <td><?= $item->type ?></td>
                                <td><?= $item->consignee ?></td>
                                <td class="small"><?= date('d/m/Y H:i', strtotime($item->last_update)) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tableInventory').DataTable({
        pageLength: 25,
        order: [[0, 'asc'], [1, 'asc']]
    });
});
</script>
