<div class="row g-4 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="card-custom h-100 bg-primary bg-opacity-10">
            <div class="card-body p-4 text-center">
                <h6 class="text-muted small mb-3">CURRENT TERMINAL WEATHER</h6>
                <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
                    <i class="fas fa-cloud-showers-heavy fa-3x text-info"></i>
                    <h1 class="text-dark mb-0">28°C</h1>
                </div>
                <p class="text-info mb-0 fw-bold">Moderate Rain Expected</p>
                <small class="text-muted">Wind: 15km/h | Humidity: 82%</small>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-md-6">
        <div class="card-custom h-100">
            <div class="card-header border-bottom border-secondary">
                <h6><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Operational Interruption Log</h6>
                <button class="btn btn-sm btn-primary-custom" onclick="$('#modalDelay').modal('show')"><i class="fas fa-plus me-1"></i>Log Delay</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableWeather" class="table table-sm table-dark-custom">
                        <thead>
                            <tr>
                                <th>Start Time</th>
                                <th>Vessel</th>
                                <th>Reason</th>
                                <th>Remarks</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($interruptions as $i): ?>
                            <tr>
                                <td class="small"><?= $i->start_time ?></td>
                                <td><?= $i->vessel_name ? $i->vessel_name : 'ALL TERMINAL' ?></td>
                                <td><span class="badge bg-warning text-dark"><?= $i->interruption_type ?></span></td>
                                <td class="small"><?= $i->remarks ?></td>
                                <td><?= $i->end_time ? '<span class="badge bg-success">RESOLVED</span>' : '<span class="badge bg-danger">ONGOING</span>' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delay -->
<div class="modal fade" id="modalDelay" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Record Operational Delay</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formDelay">
                    <div class="mb-3">
                        <label class="form-label">Scope</label>
                        <select name="vessel_id" class="form-select">
                            <option value="">All Terminal Operations</option>
                            <!-- Vessel options would go here -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Interruption Reason</label>
                        <select name="reason" class="form-select">
                            <option value="WEATHER">Weather (Rain/Heavy Wind)</option>
                            <option value="EQUIPMENT">Equipment Breakdown</option>
                            <option value="POWER">Power Outage</option>
                            <option value="STRIKE">Labor Strike</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="e.g., Heavy rain stop crane operations"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary-custom" onclick="submitDelay()">Save Interruption</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
$(document).ready(function() {
    $('#tableWeather').DataTable({
        "order": [[0, "desc"]]
    });
});

function submitDelay() {
    $.ajax({
        url: '<?= site_url("monitoring/weather/ajax_log_weather_delay") ?>',
        type: 'POST',
        data: $('#formDelay').serialize(),
        dataType: 'json',
        success: function(res) {
            if(res.status) {
                Toast.fire({icon: 'success', title: 'Interruption recorded'});
                setTimeout(() => location.reload(), 1000);
            }
        }
    });
}
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
