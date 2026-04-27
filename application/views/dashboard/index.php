<div class="row g-4 mb-4">
    <!-- Stat Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="fas fa-ship"></i></div>
            <div class="stat-value"><?= $stats['active_vessels'] ?> / <?= $stats['total_vessels'] ?></div>
            <div class="stat-label">Active / Total Vessels</div>
            <div class="stat-trend text-success"><i class="fas fa-arrow-up"></i> 2 Berthing Today</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-green">
            <div class="stat-icon"><i class="fas fa-box"></i></div>
            <div class="stat-value"><?= number_format($stats['container_in'] + $stats['container_out']) ?></div>
            <div class="stat-label">Total Container Movement</div>
            <div class="stat-trend text-success"><i class="fas fa-arrow-up"></i> +12% from yesterday</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-orange">
            <div class="stat-icon"><i class="fas fa-truck"></i></div>
            <div class="stat-value"><?= $stats['trucks_active'] ?></div>
            <div class="stat-label">Active Trucks in Terminal</div>
            <div class="stat-trend text-warning"><i class="fas fa-minus"></i> Normal Traffic</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-cyan">
            <div class="stat-icon"><i class="fas fa-th"></i></div>
            <div class="stat-value"><?= $stats['yard_occupancy'] ?>%</div>
            <div class="stat-label">Yard Occupancy</div>
            <div class="stat-trend text-success"><i class="fas fa-arrow-down"></i> Optimal</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Map Monitoring -->
    <div class="col-xl-8 col-lg-7">
        <div class="card-custom h-100">
            <div class="card-header">
                <h6><i class="fas fa-map-marked-alt me-2 text-primary"></i>Live Terminal Map</h6>
                <button class="btn btn-sm btn-sm-action" onclick="map.setView([-6.103, 106.940], 16)"><i class="fas fa-crosshairs"></i></button>
            </div>
            <div class="card-body p-0">
                <div id="terminalMap" style="height: 400px; width: 100%; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; z-index: 1;"></div>
            </div>
        </div>
    </div>
    
    <!-- Active Vessels Info -->
    <div class="col-xl-4 col-lg-5">
        <div class="card-custom h-100">
            <div class="card-header">
                <h6><i class="fas fa-anchor me-2 text-info"></i>Active Vessels</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                    <?php foreach($active_vessels as $v): ?>
                    <div class="list-group-item border-bottom border-secondary bg-transparent p-3">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <!-- Use placeholder if image not exists -->
                                <div class="rounded-3 d-flex align-items-center justify-content-center bg-light text-primary" style="width:60px; height:60px; border: 2px solid var(--border)">
                                    <i class="fas fa-ship fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <h6 class="mb-1 text-dark text-truncate"><?= $v['name'] ?></h6>
                                <p class="mb-1 text-muted small">Voyage: <?= isset($v['voyage_in']) ? $v['voyage_in'] : '-' ?> | <?= isset($v['berth_name']) ? $v['berth_name'] : 'At Sea' ?></p>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary text-white" style="font-size: 10px;"><?= $v['status'] ?></span>
                                    <small class="text-info"><?= isset($v['progress']) ? $v['progress'] : '0' ?>% Complete</small>
                                </div>
                                <div class="progress" style="height: 4px; background: rgba(0,0,0,0.05);">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= isset($v['progress']) ? $v['progress'] : '0' ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- TRT Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card-custom h-100">
            <div class="card-header">
                <h6><i class="fas fa-chart-line me-2 text-warning"></i>Truck Round Trip (TRT) Monitoring</h6>
                <select class="form-select form-select-sm w-auto" id="trtVesselSelect" onchange="loadTrtData(this.value)">
                    <?php foreach($active_vessels as $v): ?>
                        <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="trtChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-xl-4 col-lg-5">
         <div class="card-custom h-100">
            <div class="card-header">
                <h6><i class="fas fa-history me-2 text-success"></i>Recent Activity</h6>
            </div>
            <div class="card-body p-3">
                <div class="timeline" style="border-left: 2px solid var(--border); margin-left: 10px; padding-left: 20px;">
                    <?php foreach($recent_activities as $act): ?>
                    <div class="timeline-item mb-4 position-relative">
                        <span class="timeline-icon position-absolute" style="left: -29px; top: 0; width: 16px; height: 16px; border-radius: 50%; background: #ffffff; border: 3px solid var(--accent);"></span>
                        <div class="text-dark small fw-bold mb-1"><?= $act['time'] ?></div>
                        <div class="text-muted small"><?= $act['desc'] ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
    // 1. Initialize Leaflet Map with dynamic settings
    var mapLat = <?= isset($app_settings['map_lat']) ? $app_settings['map_lat'] : '-6.0920' ?>;
    var mapLng = <?= isset($app_settings['map_lng']) ? $app_settings['map_lng'] : '106.9530' ?>;
    var mapZoom = <?= isset($app_settings['map_zoom']) ? $app_settings['map_zoom'] : '16' ?>;

    var map = L.map('terminalMap', {
        zoomControl: false,
        attributionControl: false
    }).setView([mapLat, mapLng], mapZoom);

    // Fix for map not showing correctly in some containers
    setTimeout(function() {
        map.invalidateSize();
    }, 500);

    // Using Google Satellite Hybrid style for realistic view
    L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3'],
        attribution: '© Google Maps'
    }).addTo(map);

    // Data from Database
    var activeVessels = <?= json_encode($active_vessels) ?>;

    // Function to add a scaling SVG ship to the map
    function addScalingShip(lat, lng, lengthMeters, widthMeters, heading, name, status) {
        var latDiff = (lengthMeters / 111320) / 2;
        var lngDiff = (widthMeters / (111320 * Math.cos(lat * Math.PI / 180))) / 2;

        var bounds = [
            [lat - latDiff, lng - lngDiff],
            [lat + latDiff, lng + lngDiff]
        ];

        var svgElement = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svgElement.setAttribute('xmlns', "http://www.w3.org/2000/svg");
        svgElement.setAttribute('viewBox', "0 0 100 30");
        svgElement.innerHTML = `
            <g transform="rotate(${heading - 90} 50 15)">
                <path d="M0,15 L10,5 L85,5 L100,15 L85,25 L10,25 Z" fill="#0284c7" stroke="#fff" stroke-width="2" />
                <rect x="25" y="8" width="40" height="14" fill="rgba(255,255,255,0.4)" />
                <rect x="70" y="10" width="12" height="10" fill="#fff" />
            </g>
        `;

        var svgOverlay = L.svgOverlay(svgElement, bounds, {
            interactive: true,
            opacity: 1
        }).addTo(map);

        svgOverlay.bindPopup("<b>" + name + "</b><br>Status: " + status);
        return svgOverlay;
    }

    // Render Vessels Dynamically
    activeVessels.forEach(function(v) {
        // Simple logic for position and heading based on Berth
        var lat = -6.0925;
        var lng = 106.9545;
        var heading = 0;
        var width = 85; // Diperbesar lagi ke 85
        var loa = (v.loa && v.loa > 0) ? v.loa * 1.5 : 350; // LOA diperbesar 1.5x atau default 350
        
        if (v.berth_id == 1) {
            lat = -6.0898; lng = 106.9510; heading = 90;
        } else if (v.berth_id == 2) {
            lat = -6.0935; lng = 106.9554; heading = 0;
        }

        addScalingShip(lat, lng, loa, width, heading, v.name, v.status + " (" + (v.remarks || '') + ")");
    });


    // 2. Initialize TRT Chart
    var ctx = document.getElementById('trtChart').getContext('2d');
    var trtChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($trt_data['labels']) ?>,
            datasets: [{
                label: 'TRT (Minutes)',
                data: <?= json_encode($trt_data['values']) ?>,
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { color: '#64748b' },
                    title: { display: true, text: 'Minutes', color: '#64748b' }
                },
                x: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { color: '#64748b' }
                }
            }
        }
    });

    // 3. Load TRT Data via AJAX (Simulated update)
    function loadTrtData(vesselId) {
        // In real app, this would fetch new data. For now, we simulate a small change
        const randomData = trtChart.data.datasets[0].data.map(v => v + Math.floor(Math.random() * 10) - 5);
        trtChart.data.datasets[0].data = randomData;
        trtChart.update();
    }

</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
