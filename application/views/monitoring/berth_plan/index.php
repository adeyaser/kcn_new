<div class="row g-4">
    <div class="col-xl-9">
        <div class="card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-map-marked-alt me-2 text-primary"></i>Live Berthing Map</h6>
                <div class="badge bg-success-glow">LIVE UPDATES</div>
            </div>
            <div class="card-body p-0">
                <div id="berthingMap" style="height: 600px; border-radius: 0 0 var(--radius) var(--radius);"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3">
        <div class="card-custom mb-4">
            <div class="card-header">
                <h6><i class="fas fa-list me-2 text-primary"></i>Vessels at Berth</h6>
            </div>
            <div class="card-body p-3">
                <div id="vesselList">
                    <?php if(empty($active_vessels)): ?>
                        <p class="text-muted text-center py-4">No vessels currently at berth.</p>
                    <?php else: ?>
                        <?php foreach($active_vessels as $v): ?>
                            <div class="d-flex gap-3 mb-3 p-3 rounded bg-dark border border-secondary vessel-item" style="cursor:pointer;" onclick="focusVessel(<?= $v->id ?>)">
                                <div class="text-primary"><i class="fas fa-ship fa-2x"></i></div>
                                <div>
                                    <h6 class="mb-0 text-white"><?= $v->vessel_name ?></h6>
                                    <small class="text-muted"><?= $v->voyage_in ?> | LOA: <?= $v->loa ?>m</small>
                                    <div class="mt-1">
                                        <span class="badge bg-success small">BERTHED</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card-custom">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Berth Occupancy</h6>
            </div>
            <div class="card-body p-4 text-center">
                <h2 class="text-white mb-0">65%</h2>
                <small class="text-muted">Current utilization of all quays</small>
            </div>
        </div>
    </div>
</div>

<style>
.vessel-item:hover {
    background: rgba(59, 130, 246, 0.1) !important;
    border-color: var(--primary-color) !important;
}
.ship-marker-label {
    background: var(--primary-color);
    border: 2px solid white;
    border-radius: 4px;
    padding: 2px 6px;
    color: white;
    font-weight: bold;
    font-size: 10px;
    white-space: nowrap;
}
</style>

<?php ob_start(); ?>
<script>
var map;
var markers = {};

$(document).ready(function() {
    initMap();
});

function initMap() {
    // Default to a central port location (simulated)
    map = L.map('berthingMap').setView([-6.1042, 106.9150], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Add Berths to Map
    <?php foreach($berths as $b): ?>
        <?php if($b->coordinate_polygon): ?>
            var coords_<?= $b->id ?> = <?= $b->coordinate_polygon ?>;
            L.circle([coords_<?= $b->id ?>.lat, coords_<?= $b->id ?>.lng], {
                color: '#3b82f6',
                fillColor: '#3b82f6',
                fillOpacity: 0.1,
                radius: 100
            }).addTo(map).bindPopup("<strong><?= $b->berth_name ?></strong><br>Length: <?= $b->length ?>m");
        <?php endif; ?>
    <?php endforeach; ?>

    // Add Vessels to Map
    <?php foreach($active_vessels as $v): ?>
        <?php if($v->coordinate_polygon): ?>
            var v_coords_<?= $v->id ?> = <?= $v->coordinate_polygon ?>;
            var shipIcon = L.divIcon({
                className: 'ship-marker-wrapper',
                html: '<div class="ship-marker-label"><?= $v->vessel_name ?></div><i class="fas fa-ship fa-2x text-warning"></i>',
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });
            
            markers[<?= $v->id ?>] = L.marker([v_coords_<?= $v->id ?>.lat, v_coords_<?= $v->id ?>.lng], {icon: shipIcon})
                .addTo(map)
                .bindPopup("<strong><?= $v->vessel_name ?></strong><br>Voyage: <?= $v->voyage_in ?><br>LOA: <?= $v->loa ?>m");
        <?php endif; ?>
    <?php endforeach; ?>
}

function focusVessel(id) {
    if(markers[id]) {
        map.setView(markers[id].getLatLng(), 17);
        markers[id].openPopup();
    }
}
</script>
<?php $this->data['page_js'] = ob_get_clean(); ?>
