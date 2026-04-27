<div class="row g-4 mb-4">
    <?php foreach($gate_queues as $gate => $count): ?>
    <div class="col-xl-4">
        <div class="card-custom h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted small mb-1"><?= $gate ?> TRAFFIC</h6>
                        <h2 class="text-white mb-0"><?= $count ?> <small class="fs-6 text-muted">Trucks</small></h2>
                    </div>
                    <div class="bg-<?= $count > 5 ? 'danger' : ($count > 2 ? 'warning' : 'success') ?> bg-opacity-10 p-3 rounded-circle text-<?= $count > 5 ? 'danger' : ($count > 2 ? 'warning' : 'success') ?>">
                        <i class="fas fa-truck-loading fa-2x"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="small text-muted">Status: </span>
                    <span class="badge bg-<?= $count > 5 ? 'danger' : ($count > 2 ? 'warning' : 'success') ?>-glow">
                        <?= $count > 5 ? 'CONGESTED' : ($count > 2 ? 'MODERATE' : 'SMOOTH') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row">
    <div class="col-xl-8">
        <div class="card-custom">
            <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-stream me-2 text-primary"></i>Active Trucks Inside Terminal</h6>
                <div class="badge bg-primary"><?= count($active_trucks) ?> Total</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableQueue" class="table table-dark-custom">
                        <thead>
                            <tr>
                                <th>Plate No</th>
                                <th>Driver</th>
                                <th>Gate In</th>
                                <th>Wait Time</th>
                                <th>Activity</th>
                                <th>Container</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($active_trucks as $t): 
                                $start = new DateTime($t->gate_in_time);
                                $now = new DateTime();
                                $diff = $start->diff($now);
                                $wait = ($diff->h * 60) + $diff->i;
                            ?>
                            <tr>
                                <td><strong class="text-white"><?= $t->police_number ?></strong></td>
                                <td><?= $t->driver_name ?></td>
                                <td><?= date('H:i', strtotime($t->gate_in_time)) ?></td>
                                <td>
                                    <span class="text-<?= $wait > 60 ? 'danger' : ($wait > 30 ? 'warning' : 'success') ?>">
                                        <?= $wait ?> Min
                                    </span>
                                </td>
                                <td><span class="badge bg-outline-info"><?= $t->activity_type ?></span></td>
                                <td><?= $t->container_no ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-chart-pie me-2 text-info"></i>Activity Distribution</h6>
            </div>
            <div class="card-body p-4">
                <canvas id="activityChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    $('#tableQueue').DataTable();
    
    // Activity Chart
    const ctx = document.getElementById('activityChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Receiving', 'Delivery'],
            datasets: [{
                data: [12, 8],
                backgroundColor: ['#3b82f6', '#10b981'],
                borderWidth: 0
            }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom', labels: { color: '#94a3b8' } }
            }
        }
    });
});
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
