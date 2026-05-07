<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 d-flex align-items-center gap-3" style="border-left: 3px solid #3b82f6;">
            <div style="width:44px;height:44px;background:rgba(59,130,246,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-truck-loading" style="color:#3b82f6;font-size:18px;"></i>
            </div>
            <div>
                <div class="fw-bold fs-4" id="stat_receiving"><?= $stats['receiving_in'] ?></div>
                <div class="small text-muted">Receiving Today</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 d-flex align-items-center gap-3" style="border-left: 3px solid #8b5cf6;">
            <div style="width:44px;height:44px;background:rgba(139,92,246,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-truck" style="color:#8b5cf6;font-size:18px;"></i>
            </div>
            <div>
                <div class="fw-bold fs-4" id="stat_delivery"><?= $stats['delivery_out'] ?></div>
                <div class="small text-muted">Delivery Today</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 d-flex align-items-center gap-3" style="border-left: 3px solid #f59e0b;">
            <div style="width:44px;height:44px;background:rgba(245,158,11,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-warehouse" style="color:#f59e0b;font-size:18px;"></i>
            </div>
            <div>
                <div class="fw-bold fs-4" id="stat_inyard"><?= $stats['in_yard'] ?></div>
                <div class="small text-muted">Currently In Yard</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 d-flex align-items-center gap-3" style="border-left: 3px solid #10b981;">
            <div style="width:44px;height:44px;background:rgba(16,185,129,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-calendar-check" style="color:#10b981;font-size:18px;"></i>
            </div>
            <div>
                <div class="fw-bold fs-4" id="stat_pending"><?= $stats['pending_tca'] ?></div>
                <div class="small text-muted">Pending TCA</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="card-custom mb-4">
    <div class="card-body p-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-muted fw-semibold text-uppercase" style="letter-spacing:.4px;">Planning Request</label>
                <select class="form-select" id="filterPlanning">
                    <option value="">— All Plannings —</option>
                    <?php foreach($plannings as $p): ?>
                        <option value="<?= $p->id ?>"><?= $p->request_no ?> – <?= $p->vessel_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted fw-semibold text-uppercase" style="letter-spacing:.4px;">Activity Type</label>
                <select class="form-select" id="filterType">
                    <option value="">— All Types —</option>
                    <option value="RECEIVING">Receiving</option>
                    <option value="DELIVERY">Delivery</option>
                    <option value="EMPTY_IN">Empty In</option>
                    <option value="EMPTY_OUT">Empty Out</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted fw-semibold text-uppercase" style="letter-spacing:.4px;">Status</label>
                <select class="form-select" id="filterStatus">
                    <option value="">— All Status —</option>
                    <option value="CHECKED_IN">Gate In</option>
                    <option value="IN_YARD">In Yard</option>
                    <option value="CHECKED_OUT">Gate Out</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted fw-semibold text-uppercase" style="letter-spacing:.4px;">Date</label>
                <input type="date" class="form-control" id="filterDate" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill" onclick="loadData()">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
                <button class="btn btn-secondary" onclick="resetFilter()">
                    <i class="fas fa-undo"></i>
                </button>
                <button class="btn btn-outline-success" onclick="refreshStats()" title="Refresh Stats">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card-custom">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-exchange-alt me-2 text-info"></i>Gate Transaction Records</h6>
        <span class="badge bg-secondary" id="totalBadge">0 records</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tableReceiving">
                <thead style="background: rgba(255,255,255,0.04); font-size:11px; letter-spacing:.4px; text-transform:uppercase; color:#94a3b8;">
                    <tr>
                        <th class="ps-3 py-3" width="40">#</th>
                        <th class="py-3">Gate No</th>
                        <th class="py-3">Container</th>
                        <th class="py-3">Size/Type</th>
                        <th class="py-3">Truck / Driver</th>
                        <th class="py-3">Activity</th>
                        <th class="py-3">Planning</th>
                        <th class="py-3">Gate In</th>
                        <th class="py-3">Gate Out</th>
                        <th class="py-3">Duration</th>
                        <th class="py-3">Status</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr><td colspan="11" class="text-center py-5 text-muted"><i class="fas fa-circle-notch fa-spin me-2"></i>Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
function loadData() {
    var tbody = $('#tableBody');
    tbody.html('<tr><td colspan="11" class="text-center py-5 text-muted"><i class="fas fa-circle-notch fa-spin me-2"></i>Loading...</td></tr>');

    $.post('<?= site_url("monitoring/gate_monitor/ajax_list") ?>', {
        planning_id:   $('#filterPlanning').val(),
        activity_type: $('#filterType').val(),
        status:        $('#filterStatus').val(),
        date:          $('#filterDate').val()
    }, function(res) {
        if (res.status !== 'success') return;

        $('#totalBadge').text(res.total + ' records');
        tbody.empty();

        if (res.data.length === 0) {
            tbody.html('<tr><td colspan="11" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>No records found</td></tr>');
            return;
        }

        $.each(res.data, function(i, r) {
            var row = '<tr style="border-bottom:1px solid rgba(255,255,255,0.04);">' +
                '<td class="ps-3 text-muted small">' + r.no + '</td>' +
                '<td><span class="fw-semibold text-info">' + r.gate_no + '</span></td>' +
                '<td><span class="fw-bold" style="color:#e2e8f0;">' + r.container + '</span></td>' +
                '<td><span class="badge" style="background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.25);color:#a5b4fc;border-radius:5px;padding:3px 8px;">' + r.size_type + '</span></td>' +
                '<td><div class="fw-semibold small">' + (r.truck || '-') + '</div><div class="text-muted" style="font-size:11px;">' + (r.driver || '') + '</div></td>' +
                '<td>' + r.activity + '</td>' +
                '<td class="small">' + r.planning + '</td>' +
                '<td class="small">' + r.gate_in + '</td>' +
                '<td class="small">' + r.gate_out + '</td>' +
                '<td class="small text-muted">' + r.duration + '</td>' +
                '<td>' + r.status + '</td>' +
            '</tr>';
            tbody.append(row);
        });
    }, 'json');
}

function resetFilter() {
    $('#filterPlanning').val('');
    $('#filterType').val('');
    $('#filterStatus').val('');
    $('#filterDate').val('<?= date("Y-m-d") ?>');
    loadData();
}

function refreshStats() {
    $.getJSON('<?= site_url("monitoring/gate_monitor/ajax_stats") ?>', function(res) {
        $('#stat_receiving').text(res.receiving_today);
        $('#stat_delivery').text(res.delivery_today);
        $('#stat_inyard').text(res.in_yard);
        $('#stat_pending').text(res.pending_tca);
        Toast.fire({ icon: 'success', title: 'Stats refreshed' });
    });
}

// Auto-refresh stats every 60 seconds
setInterval(refreshStats, 60000);

$(document).ready(function() {
    loadData();
});
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
