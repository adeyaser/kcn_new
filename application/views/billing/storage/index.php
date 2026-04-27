<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-file-invoice-dollar me-2 text-success"></i>Storage & Dwelling Time Billing</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableBilling" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Container No</th>
                                <th>Size/Type</th>
                                <th>Gate In</th>
                                <th>Gate Out</th>
                                <th>Duration</th>
                                <th>Billable Days</th>
                                <th>Amount (Est.)</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($billables as $b): 
                                $calc = $this->Billing_model->calculate_storage($b->id);
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong class="text-white"><?= $b->container_no ?></strong></td>
                                <td><?= $b->container_size ?>' <?= $b->container_type ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($b->gate_in_time)) ?></td>
                                <td><?= $b->gate_out_time ? date('d/m/Y H:i', strtotime($b->gate_out_time)) : '<span class="badge bg-warning text-dark">IN YARD</span>' ?></td>
                                <td><?= $calc['total_days'] ?> Days</td>
                                <td><?= $calc['chargeable_days'] ?> Days</td>
                                <td><span class="text-success fw-bold">Rp <?= number_format($calc['total_amount'], 0, ',', '.') ?></span></td>
                                <td>
                                    <button class="btn btn-sm btn-sm-action" onclick="viewCalculation(<?= $b->id ?>)" title="View Breakdown"><i class="fas fa-calculator"></i></button>
                                    <button class="btn btn-sm btn-sm-action btn-success" title="Create Invoice"><i class="fas fa-file-invoice"></i></button>
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

<!-- Modal Calculation -->
<div class="modal fade" id="modalCalc" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Storage Calculation Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="calcContent">
                <!-- Content loaded via Ajax -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary-custom">Confirm & Generate Invoice</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
$(document).ready(function() {
    $('#tableBilling').DataTable();
});

function viewCalculation(id) {
    $('#modalCalc').modal('show');
    $('#calcContent').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');

    $.ajax({
        url: '<?= site_url("billing/storage/ajax_calculate") ?>',
        type: 'GET',
        data: {id: id},
        dataType: 'json',
        success: function(res) {
            if(res.status === 'success') {
                const d = res.data;
                let html = `
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-secondary">
                            Total Days in Yard <span>${d.total_days} Days</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-success border-secondary">
                            Free Storage Period <span>- ${d.free_days} Days</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-warning border-secondary fw-bold">
                            Chargeable Days <span>${d.chargeable_days} Days</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-secondary">
                            Daily Rate <span>Rp ${new Intl.NumberFormat('id-ID').format(d.rate_per_day)}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0 pt-4">
                            <h5 class="text-white mb-0">Total Storage Fee</h5>
                            <h5 class="text-success mb-0">Rp ${new Intl.NumberFormat('id-ID').format(d.total_amount)}</h5>
                        </div>
                    </div>
                `;
                $('#calcContent').html(html);
            }
        }
    });
}
</script>
<?php $this->data['page_js'] = ob_get_clean(); ?>
