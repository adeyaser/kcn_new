<div class="row">
    <div class="col-xl-6 col-md-8 mx-auto">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-ship me-2 text-primary"></i>Vessel Tally & Productivity Report</h6>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-4">Select an active or completed vessel visit to generate the detailed tally productivity report.</p>
                <div class="table-responsive">
                    <table class="table table-dark-custom table-sm" id="tableVesselTally">
                        <thead>
                            <tr>
                                <th>Vessel Name</th>
                                <th>Voyage</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($vessels as $v): ?>
                            <tr>
                                <td><?= $v->vessel_name ?></td>
                                <td><?= $v->voyage_in ?></td>
                                <td class="text-end">
                                    <a href="<?= site_url('reports/tally/print_productivity/'.$v->id) ?>" target="_blank" class="btn btn-primary-custom btn-sm">
                                        <i class="fas fa-file-pdf me-2"></i>Generate Report
                                    </a>
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
    $('#tableVesselTally').DataTable();
});
</script>
<?php $this->data['page_js'] = ob_get_clean(); ?>
