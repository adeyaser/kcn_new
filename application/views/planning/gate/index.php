<div class="row">
    <div class="col-12">
        <div class="card-custom mb-4">
            <div class="card-body p-3">
                <div class="row align-items-center g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-1">Select Planning Request</label>
                        <select class="form-select select2-planning" id="planningSelect">
                            <option value="">-- Choose Request Planning --</option>
                            <?php foreach($plannings as $p): ?>
                                <option value="<?= $p->id ?>"><?= $p->request_no ?> - <?= $p->vessel_name ?> (<?= $p->operation_type ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-1">Default Gate</label>
                        <select class="form-select" id="defaultGate">
                            <?php foreach($gates as $g): ?>
                                <option value="<?= $g->id ?>"><?= $g->gate_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5 text-end pt-3">
                        <button class="btn btn-primary px-4" id="btnSaveBulk" disabled>
                            <i class="fas fa-save me-2"></i>Save All Assignments
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="gatePlanningSection" style="display:none;">
            <div class="card-custom">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-white"><i class="fas fa-calendar-check me-2"></i>Container Gate Schedule (Pre-Advice)</h6>
                    <div class="badge bg-info" id="countDisplay">0 Containers</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tableGatePlanning">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50" class="ps-3 text-center">#</th>
                                    <th>Container No</th>
                                    <th>Size/Type</th>
                                    <th>Current Status</th>
                                    <th width="150">Expected Arrival</th>
                                    <th width="120">Gate</th>
                                    <th width="180">Assigned Truck</th>
                                    <th>Assignment No</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.select2-planning').select2({ theme: 'bootstrap-5' });

    $('#planningSelect').on('change', function() {
        var id = $(this).val();
        if(id) {
            loadManifest(id);
        } else {
            $('#gatePlanningSection').hide();
            $('#btnSaveBulk').prop('disabled', true);
        }
    });

    function loadManifest(planningId) {
        Swal.fire({
            title: 'Loading Manifest...',
            background: '#1e293b', color: '#e2e8f0',
            didOpen: () => { Swal.showLoading() }
        });

        $.ajax({
            url: '<?= site_url("planning/gate/get_manifest_gate_data") ?>',
            type: 'GET',
            data: { planning_id: planningId },
            dataType: 'json',
            success: function(res) {
                Swal.close();
                if(res.status === 'success') {
                    renderTable(res.data);
                    $('#gatePlanningSection').fadeIn();
                    $('#btnSaveBulk').prop('disabled', false);
                    $('#countDisplay').text(res.data.length + ' Containers');
                }
            }
        });
    }

    function renderTable(data) {
        var tbody = $('#tableGatePlanning tbody');
        tbody.empty();
        
        var defaultGate = $('#defaultGate').val();

        data.forEach(function(m, i) {
            var arrival = m.estimated_arrival ? m.estimated_arrival.replace(' ', 'T') : '';
            var row = `
                <tr data-id="${m.id}">
                    <td class="ps-3 text-center text-muted">${i+1}</td>
                    <td class="fw-bold text-info">${m.container_no}</td>
                    <td>${m.size}' / ${m.type}</td>
                    <td><span class="badge bg-secondary">${m.status}</span></td>
                    <td>
                        <input type="datetime-local" class="form-control form-control-sm inp-arrival" value="${arrival}">
                    </td>
                    <td>
                        <select class="form-select form-select-sm inp-gate">
                            <?php foreach($gates as $g): ?>
                                <option value="<?= $g->id ?>" ${(m.gate_id == '<?= $g->id ?>' || (!m.gate_id && '<?= $g->id ?>' == defaultGate)) ? 'selected' : ''}><?= $g->gate_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select class="form-select form-select-sm inp-truck select2-truck-inline" data-placeholder="Assign Truck...">
                            ${m.police_number ? '<option value="'+m.truck_id+'" selected>'+m.police_number+'</option>' : '<option value=""></option>'}
                        </select>
                    </td>
                    <td>
                        ${m.assignment_no ? '<span class="badge bg-success">' + m.assignment_no + '</span>' : '<span class="text-muted small">Not Assigned</span>'}
                    </td>
                    <td class="text-center">
                        ${m.tca_status == 'CHECKED_IN' ? '<i class="fas fa-check-circle text-success"></i>' : ''}
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        // Re-init inline select2
        initInlineSelect2();
    }

    function initInlineSelect2() {
        $('.select2-truck-inline').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#gatePlanningSection'),
            ajax: {
                url: "<?= site_url('operations/tca_planning/ajax_get_truck_info') ?>",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { term: params.term };
                },
                processResults: function(data) {
                    return { results: data };
                },
                cache: true
            },
            minimumInputLength: 1
        });
    }

    $('#btnSaveBulk').on('click', function() {
        var planningId = $('#planningSelect').val();
        var assignments = [];
        
        $('#tableGatePlanning tbody tr').each(function() {
            var manifestId = $(this).data('id');
            var arrival = $(this).find('.inp-arrival').val();
            var gateId = $(this).find('.inp-gate').val();
            var truckId = $(this).find('.inp-truck').val();
            
            if(arrival || truckId) {
                assignments.push({
                    manifest_id: manifestId,
                    est_arrival: arrival,
                    gate_id: gateId,
                    truck_id: truckId
                });
            }
        });

        if(assignments.length === 0) {
            Toast.fire({icon: 'warning', title: 'No arrival times entered'});
            return;
        }

        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');

        $.ajax({
            url: '<?= site_url("planning/gate/ajax_save_bulk_assignment") ?>',
            type: 'POST',
            data: { 
                planning_id: planningId,
                assignments: assignments
            },
            dataType: 'json',
            success: function(res) {
                $('#btnSaveBulk').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Save All Assignments');
                if(res.status === 'success') {
                    Toast.fire({icon: 'success', title: res.message});
                    loadManifest(planningId); // Reload to show assignment numbers
                }
            }
        });
    });
});
</script>
