<style>
    /* Custom refinements for Yard Planning (Matching Vessel style) */
    .card-yard {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: var(--transition);
        height: 100%;
    }
    .card-yard:hover {
        box-shadow: var(--shadow-lg);
    }
    .card-yard .card-body {
        padding: 24px;
    }

    .yard-header {
        background: linear-gradient(135deg, var(--accent), #1e40af);
        padding: 16px 20px;
        color: white;
    }
    .yard-header h6 {
        font-weight: 700;
        letter-spacing: 0.5px;
        margin: 0;
    }
    
    /* Smooth Navigation Tabs */
    .planning-tabs-container {
        background: #f1f5f9;
        padding: 4px;
        border-radius: var(--radius-sm);
        margin-bottom: 20px;
        border: 1px solid var(--border);
    }
    .planning-nav .nav-link {
        border-radius: calc(var(--radius-sm) - 2px);
        color: var(--text-muted);
        font-weight: 600;
        font-size: 12px;
        padding: 10px 15px;
        transition: var(--transition);
        border: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .planning-nav .nav-link:hover {
        color: var(--accent);
        background: rgba(0, 86, 179, 0.05);
    }
    .planning-nav .nav-link.active {
        background: linear-gradient(135deg, var(--accent), #6366f1) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(0, 86, 179, 0.25);
    }

    /* Container Info Glass Effect */
    .info-glass {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(14, 165, 233, 0.2);
        border-radius: var(--radius-sm);
        padding: 15px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }
    .info-glass-dark {
        background: rgba(15, 23, 42, 0.03);
        border: 1px dashed var(--info);
    }

    /* 2D Yard Plan Styles */
    #bayPlan2D {
        background: white;
        padding: 20px;
        height: 100%;
        overflow: auto;
    }
    .bay-plan-header {
        font-weight: 800;
        font-size: 16px;
        margin-bottom: 20px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
        color: #000;
    }
    .bay-grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 0;
        border-top: 1px solid #000;
        border-left: 1px solid #000;
    }
    .single-bay-wrapper {
        border-right: 1px solid #000;
        border-bottom: 1px solid #000;
        padding: 15px 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: white;
    }
    .bay-title {
        font-weight: bold;
        font-size: 12px;
        margin-bottom: 8px;
        color: #000;
    }
    .bay-row-labels {
        display: flex;
        justify-content: center;
        margin-bottom: 4px;
    }
    .bay-row-label {
        width: 16px;
        text-align: center;
        font-size: 8px;
        margin: 0 1px;
        color: #000;
    }
    .bay-tier-wrapper {
        display: flex;
        align-items: flex-start;
    }
    .bay-tier-labels {
        display: flex;
        flex-direction: column;
        margin-left: 6px;
    }
    .bay-tier-label {
        height: 16px;
        font-size: 8px;
        display: flex;
        align-items: center;
        margin-bottom: 2px;
        color: #000;
    }
    .bay-grid-inner {
        display: flex;
        flex-direction: column;
    }
    .bay-tier-row {
        display: flex;
        justify-content: center;
        margin-bottom: 2px;
    }
    .mini-bay-cell {
        width: 16px;
        height: 16px;
        border: 1px solid #94a3b8;
        margin: 0 1px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 7px;
        font-weight: bold;
        cursor: pointer;
        background: white;
    }
    .mini-bay-cell.occupied {
        color: white;
        border-color: #475569;
        box-shadow: inset 1px 1px 0 rgba(255,255,255,0.2);
    }
    
    @media print {
        @page { size: landscape; margin: 1cm; }
        body * { visibility: hidden; }
        #bayPlan2D, #bayPlan2D * { visibility: visible; }
        #bayPlan2D {
            position: absolute; left: 0; top: 0;
            width: 100% !important; height: auto !important;
            overflow: visible !important; padding: 0 !important;
            margin: 0 !important; background: white !important;
        }
        .bay-grid-container { display: flex; flex-wrap: wrap; border: none; }
        .single-bay-wrapper {
            border: 1px solid #000 !important;
            margin-bottom: 20px; margin-right: 15px;
            page-break-inside: avoid;
        }
        .mini-bay-cell {
            border: 1px solid #000 !important;
            -webkit-print-color-adjust: exact; color-adjust: exact;
        }
        .empty-slot { background-color: transparent !important; }
        #placeholder2D { display: none !important; }
    }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<div class="container-fluid py-4">
    <div class="row h-100 g-4">
        <div class="col-md-3">
            <div class="card-yard">
                <div class="yard-header">
                    <h6><i class="fas fa-cubes me-2"></i>Yard Stowage</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label text-info fw-bold small text-uppercase">Select Request Planning</label>
                        <select class="form-select shadow-sm" id="planningSelect" onchange="handleSelectionChange()">
                            <option value="">-- Choose Request Planning --</option>
                            <?php foreach($planning_requests as $p): ?>
                                <option value="<?= $p->id ?>" data-vessel="<?= $p->vessel_name ?>" data-voyage="<?= $p->voyage_in ?> / <?= $p->voyage_out ?>"><?= $p->request_no ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-info fw-bold small text-uppercase">Select Yard Block</label>
                        <select class="form-select shadow-sm" id="blockSelect" onchange="handleSelectionChange()">
                            <?php foreach($yard_blocks as $block): ?>
                                <option value="<?= $block->id ?>" 
                                    data-bays="<?= $block->max_bay ?>" 
                                    data-rows="<?= $block->max_row ?>" 
                                    data-tiers="<?= $block->max_tier ?>">
                                    <?= $block->block_name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="selectionInfo" class="mb-4" style="display:none;">
                        <h6 class="text-info border-bottom border-light pb-2 mb-3 small fw-bold text-uppercase">Planning Info</h6>
                        <div class="p-3 bg-light border rounded">
                            <div class="fw-bold text-dark" id="dispVesselName">-</div>
                            <div class="small text-muted" id="dispBlockName">-</div>
                        </div>
                    </div>

                        <div id="planningLayout" style="display:none;">
                            <div class="mb-4">
                                <h6 class="text-info border-bottom border-light pb-2 mb-3 small fw-bold text-uppercase">Container Info</h6>
                                <div id="containerInfo" class="info-glass info-glass-dark">
                                    <p class="text-info text-center mb-0 small" style="font-weight: 500;">
                                        <i class="fas fa-mouse-pointer me-2"></i>Select a container on the blueprint to view details.
                                    </p>
                                </div>
                            </div>

                            <div class="mb-4" id="unplannedSection" style="display:none;">
                                <h6 class="text-warning border-bottom border-light pb-2 mb-2 d-flex justify-content-between align-items-center small fw-bold text-uppercase">
                                    Unplanned (To Yard)
                                    <span class="badge bg-warning text-dark unplanned-count rounded-pill">0</span>
                                </h6>
                                <div class="input-group input-group-sm mb-2">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0" id="searchUnplanned" placeholder="Search..." onkeyup="filterUnplannedList()">
                                </div>
                                <div id="unplannedList" class="overflow-auto pe-1 custom-scrollbar" style="max-height: 180px;">
                                    <!-- List of containers to be planned to yard -->
                                </div>
                            </div>

                        <div class="mb-3">
                            <div class="planning-tabs-container">
                                <ul class="nav nav-pills nav-justified planning-nav" id="planningTabs" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" id="stowage-tab" data-bs-toggle="tab" data-bs-target="#stowageTab" type="button">
                                            <i class="fas fa-th me-1"></i> Stowage
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="equip-tab" data-bs-toggle="tab" data-bs-target="#equipTab" type="button">
                                            <i class="fas fa-tools me-1"></i> Machine Plan
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content" id="planningTabsContent">
                                <div class="tab-pane fade show active" id="stowageTab" role="tabpanel">
                                    <div id="controls2D">
                                        <h6 class="text-info border-bottom border-light pb-1 mb-2 small fw-bold text-uppercase">Blueprint View</h6>
                                        <p class="text-muted small mt-2 mb-3"><i class="fas fa-info-circle me-1"></i> Full 2D Yard Blueprint displayed.</p>
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100 fw-bold shadow-sm" onclick="printBlueprint()">
                                            <i class="fas fa-print me-1"></i> Print Blueprint & Detail
                                        </button>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="equipTab" role="tabpanel">
                                    <div id="equipPlanList" class="bg-light p-2 rounded border border-light" style="max-height: 300px; overflow-y: auto;">
                                        <div class="text-center py-4">
                                            <i class="fas fa-exclamation-circle text-warning mb-2 fa-lg"></i>
                                            <p class="text-muted mb-0 small fw-bold">No machines planned yet.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-info border-bottom border-light pb-2 mb-3 small fw-bold text-uppercase">Status Legend</h6>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <div class="d-flex align-items-center me-2 mb-1">
                                    <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background: #1e293b;"></span>
                                    <span class="small" style="font-size: 10px;">In Yard (Actual)</span>
                                </div>
                                <div class="d-flex align-items-center me-2 mb-1">
                                    <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background: #3b82f6;"></span>
                                    <span class="small" style="font-size: 10px;">Planned (Manifest)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card-custom h-100 position-relative" style="min-height: 700px;">
                <div id="loadingYard" class="position-absolute w-100 h-100 align-items-center justify-content-center d-none" style="background: rgba(255,255,255,0.8); z-index: 10;">
                    <div class="text-center">
                        <div class="spinner-border text-primary mb-2" role="status"></div>
                        <p class="text-primary fw-bold">Loading Yard Plan...</p>
                    </div>
                </div>
                
                <div id="bayPlan2D" class="w-100 h-100 position-absolute top-0 start-0">
                    <div class="text-center py-5 mt-5" id="placeholder2D">
                        <div class="bg-primary bg-opacity-10 p-4 rounded-circle mb-4 shadow-sm mx-auto" style="width: fit-content;">
                            <i class="fas fa-th fa-4x text-primary opacity-50"></i>
                        </div>
                        <h5 class="text-dark fw-bold mb-2">2D Blueprint Yard Plan</h5>
                        <p class="text-muted small mx-auto" style="max-width: 300px;">Select a planning request and yard block to view the stowage plan.</p>
                    </div>
                    <div id="bayGridContainer" class="text-center d-none">
                        <!-- Grid will be rendered here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
let cachedData = { containers: [], profile: null, equipments: [], unplanned: [] };
let selectedUnplannedCont = null;

function showLoading() { $('#loadingYard').removeClass('d-none').addClass('d-flex'); }
function hideLoading() { $('#loadingYard').addClass('d-none').removeClass('d-flex'); }

$(document).ready(function() {
    if ($('#planningSelect option').length > 1) {
        $('#planningSelect').prop('selectedIndex', 1).trigger('change');
    }
});

function handleSelectionChange() {
    const planningId = $('#planningSelect').val();
    const blockId = $('#blockSelect').val();
    
    if(planningId && blockId) {
        $('#selectionInfo').show();
        $('#planningLayout').show();
        
        const planText = $('#planningSelect option:selected').text();
        const blockText = $('#blockSelect option:selected').text();
        
        $('#dispVesselName').text(planText);
        $('#dispBlockName').text('Block: ' + blockText);
        
        loadYardData();
    } else {
        $('#selectionInfo').hide();
        $('#planningLayout').hide();
        $('#placeholder2D').show();
        $('#bayGridContainer').addClass('d-none');
    }
}

function loadYardData() {
    const planningId = $('#planningSelect').val();
    const blockId = $('#blockSelect').val();
    
    showLoading();
    
    $.ajax({
        url: '<?= site_url("planning/yard/get_block_data") ?>',
        type: 'GET',
        data: { planning_id: planningId, block_id: blockId },
        dataType: 'json',
        success: function(res) {
            if(res.status === 'success') {
                cachedData = { 
                    containers: res.data, 
                    profile: res.profile,
                    equipments: res.equipments,
                    unplanned: res.unplanned
                };
                render2DBay();
                renderEquipmentList(res.equipments);
                renderUnplannedList(res.unplanned);
                
                // Show unplanned list if any containers are available
                if(res.unplanned && res.unplanned.length > 0) {
                    $('#unplannedSection').show();
                } else {
                    $('#unplannedSection').hide();
                }
            }
            hideLoading();
        },
        error: function() {
            hideLoading();
            Toast.fire({icon: 'error', title: 'Failed to load yard data'});
        }
    });
}

function filterUnplannedList() {
    let input = document.getElementById('searchUnplanned');
    let filter = input.value.toUpperCase();
    let container = document.getElementById('unplannedList');
    let cards = container.getElementsByClassName('unplanned-item-card');

    for (let i = 0; i < cards.length; i++) {
        let span = cards[i].querySelector('.text-dark');
        if (span) {
            let txtValue = span.textContent || span.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                cards[i].style.setProperty("display", "block", "important");
            } else {
                cards[i].style.setProperty("display", "none", "important");
            }
        }       
    }
}

function renderUnplannedList(list) {
    const container = $('#unplannedList');
    container.empty();
    $('.unplanned-count').text(list ? list.length : 0);
    
    if (list && list.length > 0) {
        list.forEach(item => {
            const html = `
                <div class="card bg-white border mb-2 shadow-sm unplanned-item-card" 
                     style="cursor: pointer; transition: all 0.2s;"
                     onclick="selectForPlanning(${JSON.stringify(item).replace(/"/g, '&quot;')}, this)">
                    <div class="card-body p-2 d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-dark">${item.container_no}</span>
                        <span class="badge bg-light text-dark border small" style="font-size: 10px;">${item.size}ft ${item.type}</span>
                    </div>
                </div>
            `;
            container.append(html);
        });
    }
}

function selectForPlanning(item, element) {
    $('.unplanned-item-card').removeClass('border-primary bg-primary bg-opacity-10');
    $(element).addClass('border-primary bg-primary bg-opacity-10');
    selectedUnplannedCont = item;

    const html = `
        <div class="alert alert-warning border-warning bg-warning bg-opacity-10 mb-3">
            <h6 class="fw-bold mb-1"><i class="fas fa-thumbtack me-2"></i>YARD PLANNING MODE</h6>
            <p class="small mb-0">Select an empty slot on the 2D Blueprint to place this container in the yard.</p>
        </div>
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <strong class="text-dark fs-5">${item.container_no}</strong>
            <span class="badge bg-secondary">${item.size}ft ${item.type}</span>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="cancelSelection()">
            <i class="fas fa-times me-1"></i> Cancel Selection
        </button>
    `;
    $('#containerInfo').html(html);

    Toast.fire({
        icon: 'info', 
        title: 'Planning to Yard: ' + item.container_no,
        timer: 3000
    });
}

function cancelSelection() {
    $('.unplanned-item-card').removeClass('border-primary bg-primary bg-opacity-10');
    selectedUnplannedCont = null;
    $('#containerInfo').html('<p class="text-info text-center mb-0 small" style="font-weight: 500;"><i class="fas fa-mouse-pointer me-2"></i>Select a container on the blueprint to view details.</p>');
}

function render2DBay() {
    const profile = cachedData.profile;
    const containers = cachedData.containers;
    if (!profile) return;

    $('#placeholder2D').addClass('d-none');
    $('#bayGridContainer').removeClass('d-none').empty();

    const { bays, rows, tiers } = profile;
    const blockName = $('#blockSelect option:selected').text();
    
    let fullHtml = `
        <div class="bay-plan-header">
            <div class="row">
                <div class="col-6 text-start">YARD BLOCK: <span class="fw-bold">${blockName}</span></div>
                <div class="col-6 text-end">DATE: <span class="fw-bold">${new Date().toLocaleDateString('id-ID')}</span></div>
            </div>
        </div>
        <div class="bay-grid-container">
    `;

    for(let b=1; b<=bays; b++) {
        const bayContainers = containers.filter(c => c.bay == b);
        const bStr = b < 10 ? '0'+b : b;
        
        let bayHtml = `<div class="single-bay-wrapper">
            <div class="bay-title">BAY ${bStr}</div>
            <div class="bay-row-labels">`;
            
        for (let r = 1; r <= rows; r++) {
            bayHtml += `<div class="bay-row-label">${r < 10 ? '0'+r : r}</div>`;
        }
        bayHtml += `</div><div class="bay-tier-wrapper"><div class="bay-grid-inner">`;

        for (let t = tiers; t >= 1; t--) {
            bayHtml += `<div class="bay-tier-row">`;
            for (let r = 1; r <= rows; r++) {
                const cont = bayContainers.find(c => c.row == r && c.tier == t);
                const color = cont ? cont.color : 'transparent';
                const text = cont ? cont.type.charAt(0) : '';
                const textColor = (color === '#ffffff') ? '#000000' : '#ffffff';
                
                const clickHandler = cont ? 
                    `selectPlacedContainer(${JSON.stringify(cont).replace(/"/g, '&quot;')})` : 
                    `tryPlaceYard2D(${b}, ${r}, ${t})`;
                
                bayHtml += `<div class="mini-bay-cell ${cont ? 'occupied' : 'empty-slot'}" 
                                 style="background-color: ${color}; color: ${textColor};" 
                                 onclick="${clickHandler}">${text}</div>`;
            }
            bayHtml += `</div>`;
        }
        
        bayHtml += `</div><div class="bay-tier-labels">`;
        for (let t = tiers; t >= 1; t--) {
            bayHtml += `<div class="bay-tier-label">${t}</div>`;
        }
        bayHtml += `</div></div><div class="bay-row-labels mt-2">`;
        for (let r = 1; r <= rows; r++) {
            bayHtml += `<div class="bay-row-label">${r < 10 ? '0'+r : r}</div>`;
        }
        bayHtml += `</div></div>`;
        fullHtml += bayHtml;
    }

    fullHtml += `</div>`;
    $('#bayGridContainer').html(fullHtml);
}

function tryPlaceYard2D(bay, row, tier) {
    if (!selectedUnplannedCont) {
        Toast.fire({
            icon: 'warning',
            title: 'No container selected',
            text: 'Please select a container from the Unplanned List first.'
        });
        return;
    }

    const slot = { bay: bay, row: row, tier: tier };
    saveYardPlacement(selectedUnplannedCont.id, slot);
}

function saveYardPlacement(manifestId, slot) {
    showLoading();
    $.ajax({
        url: '<?= site_url("planning/yard/ajax_save_yard_stowage") ?>',
        type: 'POST',
        data: {
            id: manifestId,
            bay: slot.bay,
            row: slot.row,
            tier: slot.tier,
            block: $('#blockSelect').val()
        },
        dataType: 'json',
        success: function(res) {
            if(res.status === 'success') {
                Toast.fire({icon: 'success', title: 'Container planned to Yard!'});
                selectedUnplannedCont = null;
                loadYardData();
            } else {
                Toast.fire({icon: 'error', title: 'Failed to plan'});
                hideLoading();
            }
        },
        error: function() {
            Toast.fire({icon: 'error', title: 'Server error'});
            hideLoading();
        }
    });
}

function selectPlacedContainer(data) {
    if(!data) return;
    
    const html = `
        <div class="mb-2 d-flex justify-content-between align-items-center">
            <strong class="text-dark fs-5">${data.container_no}</strong>
            <span class="badge bg-primary">${data.size}ft ${data.type}</span>
        </div>
        <hr class="border-light my-2">
        <div class="row text-dark text-center mb-3">
            <div class="col-4">
                <small class="text-muted d-block">Bay</small>
                <strong class="fw-bold">${data.bay < 10 ? '0'+data.bay : data.bay}</strong>
            </div>
            <div class="col-4 border-start border-end border-light">
                <small class="text-muted d-block">Row</small>
                <strong class="fw-bold">${data.row < 10 ? '0'+data.row : data.row}</strong>
            </div>
            <div class="col-4">
                <small class="text-muted d-block">Tier</small>
                <strong class="fw-bold">${data.tier}</strong>
            </div>
        </div>
        <div class="mt-2">
            <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="cancelYardPlacement(${data.id})">
                <i class="fas fa-undo me-1"></i> Cancel Placement
            </button>
        </div>
    `;

    $('#containerInfo').html(html);

    Swal.fire({
        title: 'Container ' + data.container_no,
        html: `
            <div class="text-start" style="font-size: 14px;">
                <p class="mb-1"><strong>Type:</strong> ${data.size}ft ${data.type}</p>
                <p class="mb-1"><strong>Position:</strong> Bay ${data.bay < 10 ? '0'+data.bay : data.bay} | Row ${data.row < 10 ? '0'+data.row : data.row} | Tier ${data.tier}</p>
                <p class="mb-0"><strong>POD:</strong> ${data.pod}</p>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Close',
        cancelButtonText: 'Cancel Placement',
        cancelButtonColor: '#ef4444'
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
            cancelYardPlacement(data.id);
        }
    });
}

function cancelYardPlacement(manifestId) {
    Swal.fire({
        title: 'Cancel Yard Placement?',
        text: "This container will be moved back to the unplanned list.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, cancel it!'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            $.ajax({
                url: '<?= site_url("planning/yard/ajax_cancel_yard_stowage") ?>',
                type: 'POST',
                data: { id: manifestId },
                dataType: 'json',
                success: function(res) {
                    if(res.status === 'success') {
                        Toast.fire({icon: 'success', title: 'Placement cancelled'});
                        loadYardData();
                        $('#containerInfo').html('<p class="text-info text-center mb-0 small" style="font-weight: 500;"><i class="fas fa-mouse-pointer me-2"></i>Select a container on the blueprint to view details.</p>');
                    } else {
                        Toast.fire({icon: 'error', title: 'Failed to cancel'});
                        hideLoading();
                    }
                },
                error: function() {
                    Toast.fire({icon: 'error', title: 'Server error'});
                    hideLoading();
                }
            });
        }
    });
}

function renderEquipmentList(list) {
    const container = $('#equipPlanList');
    container.empty();
    if (list && list.length > 0) {
        list.forEach(item => {
            let icon = 'fa-tools';
            if(item.equipment_type === 'QCC') icon = 'fa-ship';
            if(item.equipment_type === 'RTG' || item.equipment_type === 'RS') icon = 'fa-truck-loading';
            if(item.equipment_type === 'FL') icon = 'fa-forklift';
            if(item.equipment_type === 'TRUCK') icon = 'fa-truck';

            const html = `
                <div class="card bg-white border-light mb-2 shadow-sm">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center mb-1">
                            <div class="bg-primary bg-opacity-10 p-2 rounded me-2 text-primary">
                                <i class="fas ${icon} fa-fw"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small fw-bold text-dark">${item.equipment_name}</span>
                                    <span class="badge bg-primary rounded-pill" style="font-size: 10px;">x${item.quantity}</span>
                                </div>
                                <div class="text-muted" style="font-size: 10px;">${item.equipment_type}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.append(html);
        });
    } else {
        container.html('<div class="text-center py-4"><p class="text-muted mb-0 small fw-bold">No machines planned.</p></div>');
    }
}

function printBlueprint() {
    if(!cachedData.profile || cachedData.containers.length === 0) {
        Toast.fire({icon: 'warning', title: 'No data to print.'});
        return;
    }

    showLoading();

    const element = document.getElementById('bayGridContainer');
    const parent = document.getElementById('bayPlan2D');
    
    const origParentHeight = parent.style.height;
    const origParentOverflow = parent.style.overflow;
    const origParentPosition = parent.style.position;
    const origElementWidth = element.style.width;
    const origElementBg = element.style.background;
    const origElementPad = element.style.padding;

    parent.style.height = 'auto';
    parent.style.overflow = 'visible';
    parent.style.position = 'static';
    element.style.width = '1450px'; 
    element.style.background = '#ffffff';
    element.style.padding = '20px';

    window.scrollTo(0,0);

    html2canvas(element, { 
        scale: 2, 
        useCORS: true, 
        scrollY: 0,
        windowWidth: 1500
    }).then(canvas => {
        parent.style.height = origParentHeight;
        parent.style.overflow = origParentOverflow;
        parent.style.position = origParentPosition;
        element.style.width = origElementWidth;
        element.style.background = origElementBg;
        element.style.padding = origElementPad;

        const blueprintImg = canvas.toDataURL('image/jpeg', 1.0);
        const blockName = $('#blockSelect option:selected').text();
        const planName = $('#planningSelect option:selected').text();
        
        let tableHtml = '';
        const sortedContainers = [...cachedData.containers].sort((a, b) => {
            if (a.bay !== b.bay) return a.bay - b.bay;
            if (a.row !== b.row) return a.row - b.row;
            return a.tier - b.tier;
        });

        sortedContainers.forEach((c, index) => {
            tableHtml += `
                <tr>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center;">${index + 1}</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center; font-weight: bold;">${c.container_no}</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center;">${c.size}ft / ${c.type}</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center;">${c.bay < 10 ? '0'+c.bay : c.bay}</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center;">${c.row < 10 ? '0'+c.row : c.row}</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center;">${c.tier}</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center; font-weight: bold;">${c.pod}</td>
                </tr>
            `;
        });

        const printDiv = document.createElement('div');
        printDiv.innerHTML = `
            <div style="padding: 20px; font-family: sans-serif; background: #fff;">
                <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 15px;">
                    <h2 style="margin: 0; color: #1e3a8a;">YARD STOWAGE PLAN REPORT</h2>
                    <h4 style="margin: 5px 0;">${blockName} | ${planName}</h4>
                    <p style="margin: 0; font-size: 11px; color: #666;">Generated on: ${new Date().toLocaleString('id-ID')}</p>
                </div>
                <div style="margin-bottom: 30px;">
                    <h5 style="background: #f1f5f9; padding: 8px; border-left: 5px solid #1e3a8a;">1. BLUEPRINT YARD VIEW</h5>
                    <img src="${blueprintImg}" style="width: 100%; border: 1px solid #ccc;">
                </div>
                <div style="page-break-before: always;">
                    <h5 style="background: #f1f5f9; padding: 8px; border-left: 5px solid #1e3a8a;">2. STOWAGE DETAIL LIST</h5>
                    <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
                        <thead>
                            <tr style="background: #1e3a8a; color: #fff;">
                                <th style="border: 1px solid #000; padding: 6px;">NO</th>
                                <th style="border: 1px solid #000; padding: 6px;">CONTAINER NO</th>
                                <th style="border: 1px solid #000; padding: 6px;">SIZE / TYPE</th>
                                <th style="border: 1px solid #000; padding: 6px;">BAY</th>
                                <th style="border: 1px solid #000; padding: 6px;">ROW</th>
                                <th style="border: 1px solid #000; padding: 6px;">TIER</th>
                                <th style="border: 1px solid #000; padding: 6px;">POD</th>
                            </tr>
                        </thead>
                        <tbody>${tableHtml}</tbody>
                    </table>
                </div>
            </div>
        `;

        const printWindow = window.open('', '_blank', 'width=1200,height=800');
        printWindow.document.write('<html><head><title>Print Yard Plan</title>');
        printWindow.document.write('<style>@page { size: landscape; margin: 1cm; } body { margin: 0; }</style></head><body>');
        printWindow.document.write(printDiv.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        setTimeout(() => {
            printWindow.print();
            hideLoading();
            Toast.fire({icon: 'success', title: 'Print Dialog Opened'});
        }, 1000);

    }).catch(err => {
        hideLoading();
        Toast.fire({icon: 'error', title: 'Failed to generate print view'});
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
