<div class="row g-4 mb-4">
    <!-- Big KPI Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card-custom bg-gradient-primary">
            <div class="card-body p-4 text-center">
                <h6 class="text-white-50 small mb-1">AVERAGE GCR</h6>
                <h2 class="text-white mb-0"><?= $kpis['gcr'] ?></h2>
                <small class="text-white-50">Moves / Crane / Hour</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card-custom bg-gradient-success">
            <div class="card-body p-4 text-center">
                <h6 class="text-white-50 small mb-1">SHIP PRODUCTIVITY</h6>
                <h2 class="text-white mb-0"><?= $kpis['vessel_productivity'] ?></h2>
                <small class="text-white-50">Moves / Vessel / Hour</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card-custom bg-gradient-warning">
            <div class="card-body p-4 text-center text-dark">
                <h6 class="text-dark-50 small mb-1">AVERAGE TRT</h6>
                <h2 class="mb-0"><?= $kpis['trt'] ?>m</h2>
                <small class="text-dark-50">Truck Round Turn Time</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card-custom bg-gradient-info">
            <div class="card-body p-4 text-center">
                <h6 class="text-white-50 small mb-1">GATE EFFICIENCY</h6>
                <h2 class="text-white mb-0"><?= $kpis['gate_efficiency'] ?>%</h2>
                <small class="text-white-50">Service Level Agreement</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card-custom">
            <div class="card-header border-bottom border-secondary">
                <h6><i class="fas fa-chart-line me-2 text-primary"></i>Productivity Trend (Last 30 Days)</h6>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="350"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card-custom h-100">
            <div class="card-header border-bottom border-secondary">
                <h6><i class="fas fa-tachometer-alt me-2 text-info"></i>Utility Metrics</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Yard Occupancy</span>
                        <span class="text-dark small fw-bold"><?= $kpis['yard_utilization'] ?>%</span>
                    </div>
                    <div class="progress" style="height: 10px; background: rgba(0,0,0,0.05);">
                        <div class="progress-bar bg-primary-glow" style="width: <?= $kpis['yard_utilization'] ?>%"></div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Berth Utilization</span>
                        <span class="text-dark small fw-bold">72.4%</span>
                    </div>
                    <div class="progress" style="height: 10px; background: rgba(0,0,0,0.05);">
                        <div class="progress-bar bg-success-glow" style="width: 72.4%"></div>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Equipment Readiness</span>
                        <span class="text-dark small fw-bold">88.2%</span>
                    </div>
                    <div class="progress" style="height: 10px; background: rgba(0,0,0,0.05);">
                        <div class="progress-bar bg-warning-glow" style="width: 88.2%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary { background: linear-gradient(135deg, #0056b3 0%, #004494 100%); }
.bg-gradient-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
.bg-gradient-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
.bg-gradient-info { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
.text-dark-50 { color: rgba(0,0,0,0.4); }
.bg-primary-glow { background: var(--accent); box-shadow: 0 0 10px rgba(0,86,179,0.2); }
.bg-success-glow { background: var(--success); box-shadow: 0 0 10px rgba(16,185,129,0.2); }
.bg-warning-glow { background: var(--warning); box-shadow: 0 0 10px rgba(245,158,11,0.2); }
</style>

<?php ob_start(); ?>
$(document).ready(function() {
    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: Array.from({length: 30}, (_, i) => i + 1),
            datasets: [{
                label: 'GCR (Moves/Hour)',
                data: Array.from({length: 30}, () => Math.random() * 10 + 25),
                borderColor: '#0056b3',
                borderWidth: 2,
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: '#64748b' } } },
            scales: {
                y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: '#64748b' } },
                x: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: '#64748b' } }
            }
        }
    });
});
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
