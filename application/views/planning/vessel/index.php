<style>
    /* Custom refinements for Vessel Planning */
    .card-vessel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: var(--transition);
        height: 100%;
    }
    .card-vessel:hover {
        box-shadow: var(--shadow-lg);
    }
    .card-vessel .card-body {
        padding: 24px;
    }

    .vessel-header {
        background: linear-gradient(135deg, var(--accent), #1e40af);
        padding: 16px 20px;
        color: white;
    }
    .vessel-header h6 {
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

    /* Unplanned Item Modernization */
    .unplanned-item-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        margin-bottom: 8px;
        transition: var(--transition);
        cursor: pointer;
    }
    .unplanned-item-card:hover {
        transform: translateX(4px);
        border-color: var(--accent);
        box-shadow: var(--shadow-sm);
    }
    .unplanned-item-card.active {
        border-left: 4px solid var(--accent);
        background: var(--accent-glow);
    }

    /* View Mode Radio Style */
    .view-mode-group .btn-check + .btn {
        background: white;
        border: 1px solid var(--border);
        color: var(--text-muted);
        font-weight: 600;
        font-size: 11px;
        padding: 6px 12px;
        transition: var(--transition);
    }
    .view-mode-group .btn-check:checked + .btn {
        background: var(--info);
        border-color: var(--info);
        color: white;
        box-shadow: 0 2px 8px rgba(14, 165, 233, 0.3);
    }

    /* 2D Bay Plan Styles */
    #bayPlan2D {
        background: #f8fafc;
        padding: 20px;
        height: 100%;
        overflow: auto;
    }
    /* Full Vessel Bay Plan Styles */
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
    .mini-bay-cell.empty-slot:hover {
        background: #dcfce7 !important;
        border-color: #22c55e;
        transform: scale(1.2);
        z-index: 10;
        position: relative;
    }
    .deck-separator {
        height: 15px;
    }
    .taper-invisible {
        border-color: transparent !important;
        background: transparent !important;
        pointer-events: none;
    }

    /* Print Styles for Blueprint */
    @media print {
        @page { size: landscape; margin: 1cm; }
        body * {
            visibility: hidden;
        }
        #bayPlan2D, #bayPlan2D * {
            visibility: visible;
        }
        #bayPlan2D {
            position: absolute;
            left: 0;
            top: 0;
            width: 100% !important;
            height: auto !important;
            overflow: visible !important;
            padding: 0 !important;
            margin: 0 !important;
            background: white !important;
        }
        .bay-grid-container {
            display: flex;
            flex-wrap: wrap;
            border: none;
        }
        .single-bay-wrapper {
            border: 1px solid #000 !important;
            margin-bottom: 20px;
            margin-right: 15px;
            page-break-inside: avoid;
        }
        .mini-bay-cell {
            border: 1px solid #000 !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        .empty-slot {
            background-color: transparent !important;
        }
        .bay-plan-header {
            width: 100%;
            margin-bottom: 30px;
        }
        #placeholder2D {
            display: none !important;
        }
    }

    /* Custom Scrollbar for small lists */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<div class="container-fluid py-4">
    <div class="row h-100 g-4">


    <div class="col-md-3">
        <div class="card-vessel">
            <div class="vessel-header d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-ship me-2"></i>Vessel Stowage</h6>
                <div id="operationBadge" class="badge bg-white text-primary fw-bold" style="display:none;">-</div>
            </div>
            <div class="card-body">

                <div class="mb-5">
                    <label class="form-label text-info fw-bold small text-uppercase">Select Request Planning</label>
                    <select class="form-select shadow-sm" id="planningSelect" onchange="handlePlanningSelect()">
                        <option value="">-- Choose Request Planning --</option>
                        <?php foreach($planning_requests as $p): ?>
                            <option value="<?= $p->id ?>" data-vessel="<?= $p->vessel_name ?>" data-voyage="<?= $p->voyage_in ?> / <?= $p->voyage_out ?>" data-status="<?= $p->status ?>"><?= $p->request_no ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="shipCallInfo" class="mb-4" style="display:none;">
                    <h6 class="text-info border-bottom border-light pb-2 mb-3 small fw-bold text-uppercase">Ship Call Info</h6>
                    <div class="p-3 bg-light border rounded mb-3">
                        <div class="fw-bold text-dark" id="dispVesselName">-</div>
                        <div class="small text-muted" id="dispVoyage">-</div>
                        <div id="dispStatusBadge" class="mt-2"></div>
                    </div>
                    
                    <button id="btnStartOp" class="btn btn-success w-100 shadow-sm fw-bold" onclick="startOperation()" style="display:none;">
                        <i class="fas fa-play-circle me-2"></i> Mulai Operasi
                    </button>
                </div>

                <div id="planningLayout" style="display:none;">
                    <div class="mb-5">
                        <h6 class="text-info border-bottom border-light pb-2 mb-3 small fw-bold text-uppercase">Container Info / Selection</h6>
                        <div id="containerInfo" class="info-glass info-glass-dark">
                            <p class="text-info text-center mb-0 small" style="font-weight: 500;">
                                <i class="fas fa-mouse-pointer me-2"></i>Select a container to view stowage details or from the list below to start planning.
                            </p>
                        </div>
                    </div>



                <div class="mb-4" id="unplannedSection" style="display:none;">
                    <h6 class="text-warning border-bottom border-light pb-2 mb-2 d-flex justify-content-between align-items-center small fw-bold text-uppercase">
                        Unplanned List
                        <span class="badge bg-warning text-dark unplanned-count rounded-pill">0</span>
                    </h6>
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" id="searchUnplanned" placeholder="Search container..." onkeyup="filterUnplannedList()">
                    </div>

                    <div id="unplannedList" class="overflow-auto pe-1 custom-scrollbar" style="max-height: 200px;">
                        <!-- List of containers to be planned -->
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
                        <!-- Stowage Tab -->
                        <div class="tab-pane fade show active" id="stowageTab" role="tabpanel">
                            <div id="controls2D">
                                <h6 class="text-info border-bottom border-light pb-1 mb-2 small fw-bold text-uppercase">Blueprint View</h6>
                                <p class="text-muted small mt-2 mb-3"><i class="fas fa-info-circle me-1"></i> Full 2D Blueprint Bay Plan displayed.</p>
                                <!-- <button type="button" class="btn btn-outline-danger btn-sm w-100 fw-bold shadow-sm mb-2" onclick="downloadPDF()">
                                    <i class="fas fa-file-pdf me-1"></i> Download PDF (Blueprint Only)
                                </button> -->
                                <!-- <button type="button" class="btn btn-outline-success btn-sm w-100 fw-bold shadow-sm mb-2" onclick="downloadImage()">
                                    <i class="fas fa-image me-1"></i> Download Blueprint (Image)
                                </button> -->
                                <button type="button" class="btn btn-outline-primary btn-sm w-100 fw-bold shadow-sm" onclick="printBlueprint()">
                                    <i class="fas fa-print me-1"></i> Print Blueprint & Detail
                                </button>
                            </div>
                        </div>

                        
                        <!-- Machine Plan Tab -->
                        <div class="tab-pane fade" id="equipTab" role="tabpanel">
                            <div id="equipPlanList" class="bg-light p-2 rounded border border-light" style="max-height: 300px; overflow-y: auto;">
                                <div class="text-center py-4">
                                    <i class="fas fa-exclamation-circle text-warning mb-2 fa-lg"></i>
                                    <p class="text-muted mb-0 small fw-bold">No machines planned yet.</p>
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="button" class="btn btn-xs btn-outline-primary w-100" onclick="window.location.href='<?= site_url('planning/request/edit/') ?>' + $('#planningSelect').val()">
                                    <i class="fas fa-edit me-1"></i> Edit Machine Plan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h6 class="text-info border-bottom border-light pb-2 mb-3 small fw-bold text-uppercase">Planning Summary</h6>
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="p-2 rounded bg-light border">
                                <div class="small text-muted">Total</div>
                                <div class="fw-bold" id="stat_total">0</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-success bg-opacity-10 border border-success border-opacity-20">
                                <div class="small text-success">Planned</div>
                                <div class="fw-bold text-success" id="stat_planned">0</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-warning bg-opacity-10 border border-warning border-opacity-20">
                                <div class="small text-warning">Left</div>
                                <div class="fw-bold text-warning" id="stat_unplanned">0</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-info border-bottom border-light pb-2 mb-3 small fw-bold text-uppercase">Color Legend</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="d-flex align-items-center me-2 mb-1">
                            <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background: #eab308;"></span>
                            <span class="small" style="font-size: 10px;">Planned (Standard)</span>
                        </div>
                        <div class="d-flex align-items-center me-2 mb-1">
                            <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background: #ffffff; border: 1px solid #cbd5e1;"></span>
                            <span class="small" style="font-size: 10px;">Reefer</span>
                        </div>
                        <div class="d-flex align-items-center me-2 mb-1">
                            <span class="d-inline-block rounded-circle me-1" style="width: 10px; height: 10px; background: #dc2626;"></span>
                            <span class="small" style="font-size: 10px;">Hazardous</span>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info py-2 small mt-4" style="background: rgba(6, 182, 212, 0.05); border-color: rgba(6, 182, 212, 0.1); border-left: 4px solid var(--info);">
                    <h6 class="small fw-bold mb-1 text-info"><i class="fas fa-lightbulb me-2"></i>Quick Tips:</h6>
                    <ul class="ps-3 mb-0" style="font-size: 10px;">
                        <li><b>Step 1:</b> Select a Request Planning above.</li>
                        <li><b>Step 2:</b> Pick a container from "Unplanned List".</li>
                        <li><b>Step 3:</b> Click an empty slot (white box) in the 2D Blueprint to place it.</li>
                        <li class="mt-1">Scroll to view all bays from front to back.</li>
                    </ul>
                </div>
                </div> <!-- End planningLayout -->
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card-custom h-100 position-relative" style="min-height: 700px;">
            <div id="loading3D" class="position-absolute w-100 h-100 align-items-center justify-content-center d-none" style="background: rgba(255,255,255,0.8); z-index: 10;">
                <div class="text-center">
                    <div class="spinner-border text-primary mb-2" role="status"></div>
                    <p class="text-primary fw-bold">Rendering Vessel & Containers...</p>
                </div>
            </div>
            
            <div id="vesselCanvas" class="w-100 h-100 position-relative d-none" style="border-radius: var(--radius); overflow: hidden; background: #ffffff;">
            </div>

            <div id="bayPlan2D" class="w-100 h-100 position-absolute top-0 start-0">
                <div class="text-center py-5 mt-5" id="placeholder2D">
                    <div class="bg-primary bg-opacity-10 p-4 rounded-circle mb-4 shadow-sm mx-auto" style="width: fit-content;">
                        <i class="fas fa-th fa-4x text-primary opacity-50"></i>
                    </div>
                    <h5 class="text-dark fw-bold mb-2">2D Blueprint Stowage Plan</h5>
                    <p class="text-muted small mx-auto" style="max-width: 300px;">Select a ship from the dropdown to start planning stowage in a full-vessel 2D view.</p>
                </div>
                <div id="bayGridContainer" class="text-center d-none">
                    <!-- Grid will be rendered here -->
                </div>
            </div>

        </div>
    </div>
</div>


<?php ob_start(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
let currentViewType = '2D';
let cachedData = { containers: [], profile: null, unplanned: [] };

let scene, camera, renderer, controls;
let containerMeshes = [];
let slotMeshes = []; // Clickable empty slots
let shipHullMesh = null;
let deckPlanes = [];
let raycaster, mouse;
let hoveredMesh = null;
let selectedMesh = null;
let selectedUnplannedCont = null;
let loadingTimeout = null;
let currentProfile = null;

function switchViewType(type) {
    currentViewType = type;
    if (type === '3D') {
        $('#vesselCanvas').removeClass('d-none');
        $('#bayPlan2D').addClass('d-none');
        $('#controls3D').show();
        $('#controls2D').hide();
    } else {
        $('#vesselCanvas').addClass('d-none');
        $('#bayPlan2D').removeClass('d-none');
        $('#controls3D').hide();
        $('#controls2D').show();
        render2DBay();
    }
}

const CONT_LENGTH = 12;
const CONT_WIDTH = 4.8;
const CONT_HEIGHT = 5.2;
const SPACING = 0.2;

let currentViewMode = 'ALL';

function showLoading() {
    $('#loading3D').removeClass('d-none').addClass('d-flex');
}
function hideLoading() {
    $('#loading3D').addClass('d-none').removeClass('d-flex');
}

$(document).ready(function() {
    if (typeof THREE === 'undefined') {
        hideLoading();
        $('#placeholder3D').html('<div class="text-center py-5"><i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i><h5 class="text-muted">3D Engine unavailable</h5><p class="text-muted small">Please check your internet connection and refresh.</p></div>');
        return;
    }
    raycaster = new THREE.Raycaster();
    mouse = new THREE.Vector2();

    try {
        init3D();
        animate();
    } catch(e) {
        console.error('3D init failed:', e);
        hideLoading();
        $('#placeholder3D').html('<div class="text-center py-5"><i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i><h5 class="text-muted">3D Engine failed to initialize</h5><p class="text-muted small">' + e.message + '</p></div>');
        return;
    }

    if ($('#planningSelect option').length > 1) {
        $('#planningSelect').prop('selectedIndex', 1).trigger('change');
    }
});

function init3D() {
    const container = document.getElementById('vesselCanvas');
    const width = container.clientWidth;
    const height = container.clientHeight || 700;

    scene = new THREE.Scene();
    // Light background
    scene.background = new THREE.Color('#ffffff');
    scene.fog = new THREE.FogExp2('#ffffff', 0.002);

    camera = new THREE.PerspectiveCamera(45, width / height, 1, 1000);
    camera.position.set(120, 80, -100);

    renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(width, height);
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    container.appendChild(renderer.domElement);

    controls = new THREE.OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;
    controls.maxPolarAngle = Math.PI / 2 + 0.05; // Prevent camera from going deep underwater
    
    // Natural outdoor lighting
    const hemiLight = new THREE.HemisphereLight(0xffffff, 0x444444, 0.7);
    hemiLight.position.set(0, 200, 0);
    scene.add(hemiLight);

    const dirLight = new THREE.DirectionalLight(0xffffff, 0.8);
    dirLight.position.set(150, 300, 150);
    dirLight.castShadow = true;
    dirLight.shadow.camera.top = 250;
    dirLight.shadow.camera.bottom = -250;
    dirLight.shadow.camera.left = -250;
    dirLight.shadow.camera.right = 250;
    dirLight.shadow.mapSize.width = 2048;
    dirLight.shadow.mapSize.height = 2048;
    scene.add(dirLight);

    // Ocean Water Plane
    const waterGeo = new THREE.PlaneGeometry(4000, 4000);
    const waterMat = new THREE.MeshStandardMaterial({ 
        color: '#0369a1', 
        transparent: true, 
        opacity: 0.8,
        roughness: 0.1,
        metalness: 0.2
    });
    const water = new THREE.Mesh(waterGeo, waterMat);
    water.rotation.x = -Math.PI / 2;
    water.position.y = -15; // Set water level slightly above the red bottom
    water.receiveShadow = true;
    scene.add(water);

    // Initial camera position
    camera.position.set(200, 150, 250);
    controls.update();

    window.addEventListener('resize', onWindowResize, false);
    renderer.domElement.addEventListener('mousemove', onMouseMove, false);
    renderer.domElement.addEventListener('click', onMouseClick, false);
}

function drawShipHull(bays, rows, tiers_under) {
    if(shipHullMesh) scene.remove(shipHullMesh);
    deckPlanes.forEach(p => scene.remove(p));
    deckPlanes = [];

    const length = (bays/2) * (CONT_LENGTH + SPACING);
    const width = rows * (CONT_WIDTH + SPACING) + 8;
    const depth = tiers_under * CONT_HEIGHT + 4;
    const sternLength = 50;
    const bowLength = 70;

    shipHullMesh = new THREE.Group();

    // Create the 2D boat profile shape from a top-down perspective
    const shape = new THREE.Shape();
    shape.moveTo(-length/2 - sternLength, -width/2);
    shape.lineTo(length/2, -width/2);
    shape.quadraticCurveTo(length/2 + bowLength * 0.6, -width/2, length/2 + bowLength, 0);
    shape.quadraticCurveTo(length/2 + bowLength * 0.6, width/2, length/2, width/2);
    shape.lineTo(-length/2 - sternLength, width/2);
    shape.lineTo(-length/2 - sternLength, -width/2);

    const extrudeSettings = {
        depth: depth,
        bevelEnabled: true,
        bevelThickness: 1,
        bevelSize: 1,
        bevelSegments: 2
    };
    const hullGeo = new THREE.ExtrudeGeometry(shape, extrudeSettings);
    const hullMat = new THREE.MeshStandardMaterial({ color: '#38bdf8', roughness: 0.4, metalness: 0.1 }); 
    const mainHull = new THREE.Mesh(hullGeo, hullMat);
    mainHull.rotation.x = Math.PI / 2;
    mainHull.position.y = 0;
    mainHull.castShadow = true;
    mainHull.receiveShadow = true;
    shipHullMesh.add(mainHull);

    const bottomExtrudeSettings = {
        depth: depth * 0.4,
        bevelEnabled: true,
        bevelThickness: 3,
        bevelSize: 4,
        bevelSegments: 3
    };
    const bottomGeo = new THREE.ExtrudeGeometry(shape, bottomExtrudeSettings);
    const redMat = new THREE.MeshStandardMaterial({ color: '#991b1b', roughness: 0.7 });
    const bottomHull = new THREE.Mesh(bottomGeo, redMat);
    bottomHull.rotation.x = Math.PI / 2;
    bottomHull.position.y = -depth;
    bottomHull.receiveShadow = true;
    shipHullMesh.add(bottomHull);

    // --- DECK PLANE (Gray) ---
    const deckGeo = new THREE.ShapeGeometry(shape);
    const deckMat = new THREE.MeshStandardMaterial({ color: '#475569', roughness: 0.9 });
    const deck = new THREE.Mesh(deckGeo, deckMat);
    deck.rotation.x = -Math.PI / 2;
    deck.position.y = 0.2;
    deck.receiveShadow = true;
    shipHullMesh.add(deck);
    deckPlanes.push(deck);

    // --- SUPERSTRUCTURE / BRIDGE (White) ---
    const bridgeGroup = new THREE.Group();
    const bridgeWidth = width - 4;
    const bridgeLength = 25;
    const bridgeHeight = 35;
    const bridgeGeo = new THREE.BoxGeometry(bridgeLength, bridgeHeight, bridgeWidth);
    const bridgeMat = new THREE.MeshStandardMaterial({ color: '#f8fafc', roughness: 0.5 });
    const bridge = new THREE.Mesh(bridgeGeo, bridgeMat);
    bridge.position.set(0, bridgeHeight/2, 0);
    bridge.castShadow = true;
    bridge.receiveShadow = true;
    bridgeGroup.add(bridge);

    const wingsGeo = new THREE.BoxGeometry(10, 8, bridgeWidth + 24);
    const wings = new THREE.Mesh(wingsGeo, bridgeMat);
    wings.position.set(0, bridgeHeight - 4, 0);
    bridgeGroup.add(wings);

    const windowGeo = new THREE.BoxGeometry(bridgeLength + 1, 6, bridgeWidth + 25);
    const windowMat = new THREE.MeshStandardMaterial({ color: '#0f172a', roughness: 0.1, metalness: 0.8 });
    const windows = new THREE.Mesh(windowGeo, windowMat);
    windows.position.set(0, bridgeHeight - 4, 0);
    bridgeGroup.add(windows);

    const funnelGeo = new THREE.CylinderGeometry(3, 5, 20, 16);
    const funnelMat = new THREE.MeshStandardMaterial({ color: '#eab308' });
    const funnel = new THREE.Mesh(funnelGeo, funnelMat);
    funnel.position.set(-8, bridgeHeight + 10, 0);
    bridgeGroup.add(funnel);

    bridgeGroup.position.set(-length/2 - sternLength/2 + 10, 0, 0);
    shipHullMesh.add(bridgeGroup);

    scene.add(shipHullMesh);
}

function handlePlanningSelect() {
    const select = document.getElementById('planningSelect');
    const option = select.options[select.selectedIndex];
    
    if(select.value) {
        document.getElementById('shipCallInfo').style.display = 'block';
        document.getElementById('planningLayout').style.display = 'block';
        
        document.getElementById('dispVesselName').innerText = option.getAttribute('data-vessel');
        document.getElementById('dispVoyage').innerText = 'Voyage: ' + option.getAttribute('data-voyage');
        
        // Handle Status and Start Button
        const status = option.getAttribute('data-status');
        const badge = document.getElementById('dispStatusBadge');
        const btn = document.getElementById('btnStartOp');
        
        if (status === 'OPERATING') {
            badge.innerHTML = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> OPERATING</span>';
            btn.style.display = 'none';
        } else {
            badge.innerHTML = '<span class="badge bg-primary text-uppercase">' + status + '</span>';
            btn.style.display = 'block';
        }

        loadVesselData();
    } else {
        document.getElementById('shipCallInfo').style.display = 'none';
        document.getElementById('planningLayout').style.display = 'none';
        
        $('#bayPlan2D').removeClass('d-none');
        $('#placeholder2D').show();
        $('#bayGridContainer').addClass('d-none');
        
        $('#stat_total').text('0');
        $('#stat_planned').text('0');
        $('#stat_unplanned').text('0');
        $('#unplannedSection').hide();
    }
}

function loadVesselData() {
    const planningId = $('#planningSelect').val();
    if(!planningId) return;

    // $('#placeholder3D').hide(); // Removed since we use placeholder2D
    $('#placeholder2D').hide();
    showLoading();

    if (loadingTimeout) clearTimeout(loadingTimeout);
    loadingTimeout = setTimeout(function() {
        if (!$('#loading3D').hasClass('d-none')) hideLoading();
    }, 15000);
    
    $.ajax({
        url: '<?= site_url("planning/vessel/get_vessel_stowage_data") ?>',
        type: 'GET',
        data: { planning_id: planningId },
        dataType: 'json',
        success: function(res) {
            try {
                if(res.status === 'success') {
                    currentProfile = res.profile;
                    cachedData.containers = res.data;
                    cachedData.profile = res.profile;
                    cachedData.unplanned = res.unplanned;
                    cachedData.operation_type = res.operation_type;

                    // Update operation badge
                    const opBadge = $('#operationBadge');
                    opBadge.show();
                    if(res.operation_type === 'DIS') {
                        opBadge.html('<i class="fas fa-arrow-down me-1"></i> BONGKAR').removeClass('text-primary text-success').addClass('text-danger');
                    } else if(res.operation_type === 'LOD') {
                        opBadge.html('<i class="fas fa-arrow-up me-1"></i> MUAT').removeClass('text-primary text-danger').addClass('text-success');
                    } else {
                        opBadge.html('<i class="fas fa-exchange-alt me-1"></i> BONGKAR MUAT').addClass('text-primary');
                    }

                    $('#stat_total').text(res.data.length + res.unplanned.length);
                    $('#stat_planned').text(res.data.length);
                    $('#stat_unplanned').text(res.unplanned.length);

                    drawShipHull(res.profile.bays, res.profile.rows, res.profile.tiers_under);
                    renderContainers(res.data, res.profile);
                    renderUnplannedList(res.unplanned);
                    renderEquipmentList(res.equipments);
                    
                    if(currentViewType === '2D') render2DBay();

                    selectedUnplannedCont = null;
                } else {
                    Toast.fire({icon: 'error', title: res.message || 'Invalid data'});
                }
            } catch(e) {
                console.error('3D error:', e);
            } finally {
                if (loadingTimeout) clearTimeout(loadingTimeout);
                hideLoading();
            }
        },
        error: function() {
            hideLoading();
            Toast.fire({icon: 'error', title: 'Failed to load data'});
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
    $('.unplanned-count').text(list.length);
    $('#searchUnplanned').val('');
    
    if (list.length > 0) {
        $('#unplannedSection').show();
        list.forEach(item => {
            let yardInfo = '';
            if (item.block_id) {
                yardInfo = `<div class="extra-small text-muted"><i class="fas fa-map-marker-alt me-1"></i>${item.block_id}-${item.yard_bay}-${item.yard_row}-${item.yard_tier}</div>`;
            }

            const html = `
                <div class="unplanned-item-card unplanned-item" 
                     onclick="selectForPlanning(${JSON.stringify(item).replace(/"/g, '&quot;')}, this)">
                    <div class="card-body p-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-bold text-dark">${item.container_no}</span>
                            <span class="badge bg-light text-dark border" style="font-size: 9px;">${item.size}ft ${item.type}</span>
                        </div>
                        ${yardInfo}
                    </div>
                </div>
            `;
            container.append(html);
        });
    } else {
        $('#unplannedSection').hide();
    }
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
                        ${item.notes ? `<div class="mt-1 p-1 bg-light rounded text-muted" style="font-size: 10px;"><i class="fas fa-sticky-note me-1"></i>${item.notes}</div>` : ''}
                    </div>
                </div>
            `;
            container.append(html);
        });
    } else {
        container.html(`
            <div class="text-center py-4">
                <i class="fas fa-exclamation-circle text-warning mb-2 fa-lg"></i>
                <p class="text-muted mb-0 small fw-bold">No machines planned for this vessel.</p>
            </div>
        `);
    }
}

function selectForPlanning(item, element) {
    $('.unplanned-item').removeClass('active');
    $(element).addClass('active');
    selectedUnplannedCont = item;

    const html = `
        <div class="alert alert-warning border-warning bg-warning bg-opacity-10 mb-3">
            <h6 class="fw-bold mb-1"><i class="fas fa-thumbtack me-2"></i>PLANNING MODE</h6>
            <p class="small mb-0">Select an empty slot (white box) on the 2D Bay Plan to place this container.</p>
        </div>
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <strong class="text-dark fs-5">${item.container_no}</strong>
            <span class="badge bg-secondary">${item.size}ft ${item.type}</span>
        </div>
        <div class="row text-dark small mb-3">
            <div class="col-6">Weight: <strong>${item.weight} Kg</strong></div>
            <div class="col-6">POD: <strong>${item.pod}</strong></div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="cancelSelection()">
            <i class="fas fa-times me-1"></i> Cancel Selection
        </button>
    `;
    $('#containerInfo').html(html);

    Toast.fire({
        icon: 'info', 
        title: 'Planning: ' + item.container_no,
        timer: 3000
    });

    drawAvailableSlots();
}

function cancelSelection() {
    $('.unplanned-item').removeClass('active');
    selectedUnplannedCont = null;
    slotMeshes.forEach(s => scene.remove(s));
    slotMeshes = [];
    $('#containerInfo').html('<p class="text-info text-center mb-0 small" style="font-weight: 500;"><i class="fas fa-mouse-pointer me-2"></i>Select a container to view stowage details or from the list below to start planning.</p>');
}

function drawAvailableSlots() {
    slotMeshes.forEach(s => scene.remove(s));
    slotMeshes = [];

    if (!currentProfile) return;

    const geometry = new THREE.BoxGeometry(CONT_LENGTH, CONT_HEIGHT, CONT_WIDTH);
    
    const offsetX = -((currentProfile.bays/2) * (CONT_LENGTH + SPACING)) / 2 + (CONT_LENGTH/2);
    const offsetZ = -((currentProfile.rows) * (CONT_WIDTH + SPACING)) / 2 + (CONT_WIDTH/2);

    for (let b = 1; b <= currentProfile.bays; b+=2) {
        for (let r = 1; r <= currentProfile.rows; r++) {
            const filled = containerMeshes.filter(m => m.userData.bay == b && m.userData.row == r);
            const highestTierOn = Math.max(80, ...filled.filter(m => m.userData.deck == 'ON').map(m => m.userData.tier));
            
            if (highestTierOn < 80 + (currentProfile.tiers_on * 2)) {
                const nextTier = (highestTierOn < 82) ? 82 : highestTierOn + 2;
                
                const material = new THREE.MeshBasicMaterial({ 
                    color: '#22c55e', 
                    transparent: true, 
                    opacity: 0.05 
                });
                const mesh = new THREE.Mesh(geometry, material);
                
                const xPos = offsetX + ((b - 1) / 2) * (CONT_LENGTH + SPACING);
                const zPos = offsetZ + ((r - 1) * (CONT_WIDTH + SPACING));
                const actualTier = (nextTier - 80) / 2;
                const yPos = (CONT_HEIGHT / 2) + ((actualTier - 1) * CONT_HEIGHT);
                
                mesh.position.set(xPos, yPos, zPos);
                
                // Neon green wireframe makes it easy to target
                const edges = new THREE.EdgesGeometry(geometry);
                const line = new THREE.LineSegments(edges, new THREE.LineBasicMaterial({ color: '#22c55e', linewidth: 2 }));
                mesh.add(line);

                mesh.userData = { isSlot: true, bay: b, row: r, tier: nextTier, deck: 'ON' };
                scene.add(mesh);
                slotMeshes.push(mesh);
            }
        }
    }
}

function renderContainers(data, profile) {
    containerMeshes.forEach(mesh => {
        scene.remove(mesh);
        if(mesh.material.map) mesh.material.map.dispose();
        mesh.material.dispose();
        mesh.geometry.dispose();
    });
    containerMeshes = [];

    const geometry = new THREE.BoxGeometry(CONT_LENGTH, CONT_HEIGHT, CONT_WIDTH);
    const offsetX = -((profile.bays/2) * (CONT_LENGTH + SPACING)) / 2 + (CONT_LENGTH/2);
    const offsetZ = -((profile.rows) * (CONT_WIDTH + SPACING)) / 2 + (CONT_WIDTH/2);

    data.forEach(item => {
        const material = new THREE.MeshStandardMaterial({ 
            color: item.color || '#22c55e',
            roughness: 0.6,
            metalness: 0.1
        });
        const mesh = new THREE.Mesh(geometry, material);
        mesh.castShadow = true;
        mesh.receiveShadow = true;

        const xPos = offsetX + ((item.bay - 1) / 2) * (CONT_LENGTH + SPACING);
        const zPos = offsetZ + ((item.row - 1) * (CONT_WIDTH + SPACING));
        
        let yPos = 0;
        if(item.deck === 'ON') {
            const actualTier = (item.tier - 80) / 2; // e.g. 82 -> 1, 84 -> 2
            yPos = (CONT_HEIGHT / 2) + ((actualTier - 1) * CONT_HEIGHT);
        } else {
            const actualTier = item.tier / 2; // e.g. 02 -> 1, 04 -> 2
            // For under deck, we go downwards from y=0
            yPos = -(CONT_HEIGHT / 2) - ((profile.tiers_under - actualTier) * CONT_HEIGHT);
        }

        mesh.position.set(xPos, yPos, zPos);
        
        mesh.userData = {
            id: item.id,
            bay: item.bay, row: item.row, tier: item.tier, deck: item.deck,
            container_no: item.container_no, type: item.type, size: item.size,
            pol: item.pol, pod: item.pod
        };

        // Apply visibility filter based on current mode
        if (currentViewMode === 'ON' && item.deck === 'UNDER') mesh.visible = false;
        if (currentViewMode === 'UNDER' && item.deck === 'ON') mesh.visible = false;

        scene.add(mesh);
        containerMeshes.push(mesh);
    });
}

function toggleViewMode(mode) {
    currentViewMode = mode;
    containerMeshes.forEach(mesh => {
        if(mode === 'ALL') {
            mesh.visible = true;
        } else if (mode === 'ON') {
            mesh.visible = (mesh.userData.deck === 'ON');
        } else if (mode === 'UNDER') {
            mesh.visible = (mesh.userData.deck === 'UNDER');
        }
    });

    if(shipHullMesh) {
        const opacity = (mode === 'UNDER') ? 0.2 : 1.0;
        shipHullMesh.traverse((child) => {
            if (child.isMesh && child.material) {
                if (opacity < 1) {
                    child.material.transparent = true;
                    child.material.opacity = opacity;
                } else {
                    // Optional: reset transparent if it wasn't supposed to be, but 1.0 is fine.
                    child.material.transparent = false;
                    child.material.opacity = 1;
                }
            }
        });
    }
}

function onMouseMove(event) {
    event.preventDefault();
    const rect = renderer.domElement.getBoundingClientRect();
    mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
    mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;

    // Only raycast visible meshes
    const visibleMeshes = containerMeshes.filter(m => m.visible);
    raycaster.setFromCamera(mouse, camera);
    const intersects = raycaster.intersectObjects(visibleMeshes);

    if (intersects.length > 0) {
        if (hoveredMesh != intersects[0].object && hoveredMesh != selectedMesh) {
            if (hoveredMesh) hoveredMesh.material.emissive.setHex(0x000000);
            hoveredMesh = intersects[0].object;
            hoveredMesh.material.emissive.setHex(0x333333);
            document.body.style.cursor = 'pointer';
        }
    } else {
        if (hoveredMesh && hoveredMesh != selectedMesh) {
            hoveredMesh.material.emissive.setHex(0x000000);
        }
        hoveredMesh = null;
        document.body.style.cursor = 'default';
    }
}

function onMouseClick(event) {
    const visibleMeshes = containerMeshes.filter(m => m.visible);
    raycaster.setFromCamera(mouse, camera);
    
    // Check if we are clicking a slot for planning
    if (selectedUnplannedCont) {
        const slotIntersects = raycaster.intersectObjects(slotMeshes);
        if (slotIntersects.length > 0) {
            const slot = slotIntersects[0].object.userData;
            savePlacement(selectedUnplannedCont.id, slot);
            return;
        }
    }

    const intersects = raycaster.intersectObjects(visibleMeshes);

    if (selectedMesh) {
        selectedMesh.material.emissive.setHex(0x000000);
    }

    if (intersects.length > 0) {
        selectedMesh = intersects[0].object;
        selectedMesh.material.emissive.setHex(0x555555); 
        
        const data = selectedMesh.userData;
        let badgeClass = 'bg-primary';
        if(data.type == 'RF') badgeClass = 'bg-light text-dark';
        if(data.type == 'HZ') badgeClass = 'bg-danger';

        const tierStr = data.tier < 10 ? '0'+data.tier : data.tier;
        const deckStr = data.deck === 'ON' ? '<span class="text-warning">ON DECK</span>' : '<span class="text-info">UNDER DECK</span>';

        const html = `
            <div class="mb-2 d-flex justify-content-between align-items-center">
                <strong class="text-dark fs-5">${data.container_no}</strong>
                <span class="badge ${badgeClass}">${data.size}ft ${data.type}</span>
            </div>
            <div class="mb-2 text-end fw-bold">${deckStr}</div>
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
                    <strong class="fw-bold">${tierStr}</strong>
                </div>
            </div>
            <div class="row text-dark small mb-3">
                <div class="col-6">POL: <strong class="text-info">${data.pol}</strong></div>
                <div class="col-6">POD: <strong class="text-success">${data.pod}</strong></div>
            </div>
            <div class="mt-2">
                <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="cancelStowage(${data.id})">
                    <i class="fas fa-undo me-1"></i> Cancel Placement
                </button>
            </div>
        `;

        $('#containerInfo').html(html);
    } else {
        selectedMesh = null;
        $('#containerInfo').html('<p class="text-muted text-center mb-0"><i class="fas fa-mouse-pointer me-2"></i>Select a container to view stowage details or from the list below to start planning.</p>');
    }
}

function cancelStowage(manifestId) {
    Swal.fire({
        title: 'Cancel Placement?',
        text: "This container will be moved back to the unplanned list.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, cancel it!',
        background: '#ffffff', color: '#000000'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();
            $.ajax({
                url: '<?= site_url("planning/vessel/ajax_cancel_stowage") ?>',
                type: 'POST',
                data: { id: manifestId },
                dataType: 'json',
                success: function(res) {
                    if(res.status === 'success') {
                        Toast.fire({icon: 'success', title: 'Placement cancelled'});
                        loadVesselData();
                        $('#containerInfo').html('<p class="text-info text-center mb-0 small" style="font-weight: 500;"><i class="fas fa-mouse-pointer me-2"></i>Select a container to view stowage details or from the list below to start planning.</p>');
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

function savePlacement(manifestId, slot) {
    showLoading();
    $.ajax({
        url: '<?= site_url("planning/vessel/ajax_save_stowage") ?>',
        type: 'POST',
        data: {
            id: manifestId,
            bay: slot.bay,
            row: slot.row,
            tier: slot.tier,
            deck: slot.deck
        },
        dataType: 'json',
        success: function(res) {
            if(res.status === 'success') {
                Toast.fire({icon: 'success', title: 'Container planned!'});
                // Remove slot highlights
                slotMeshes.forEach(s => scene.remove(s));
                slotMeshes = [];
                // Refresh data
                loadVesselData();
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

function onWindowResize() {
    const container = document.getElementById('vesselCanvas');
    const width = container.clientWidth;
    const height = container.clientHeight || 700;
    camera.aspect = width / height;
    camera.updateProjectionMatrix();
    renderer.setSize(width, height);
}

function tryPlace2D(bay, row, tier, deck) {
    if (!selectedUnplannedCont) {
        Toast.fire({
            icon: 'warning',
            title: 'No container selected',
            text: 'Please select a container from the Unplanned List first.'
        });
        return;
    }

    const slot = { bay: bay, row: row, tier: tier, deck: deck };
    savePlacement(selectedUnplannedCont.id, slot);
}

function render2DBay() {
    const profile = cachedData.profile;
    const containers = cachedData.containers;
    
    if (!profile) return;

    $('#placeholder2D').addClass('d-none');
    $('#bayGridContainer').removeClass('d-none').empty();

    const rows = profile.rows;
    const tiersOn = profile.tiers_on;
    const tiersUnder = profile.tiers_under;

    const vesselName = $('#planningSelect option:selected').text().split('(')[0] || 'VESSEL';
    const pol = 'IDJKT'; // Hardcoded for now based on previous code
    
    let fullHtml = `
        <div class="bay-plan-header">
            <div class="row">
                <div class="col-4 text-start">M.V. <span class="fw-bold">${vesselName}</span></div>
                <div class="col-4 text-center">POL : <span class="fw-bold">${pol}</span></div>
                <div class="col-4 text-end">DATE : <span class="fw-bold">${new Date().toLocaleDateString('id-ID')}</span></div>
            </div>
        </div>
        <div class="bay-grid-container">
    `;

    // Loop through all bays
    for(let b=1; b<=profile.bays; b+=2) {
        const bayContainers = containers.filter(c => c.bay == b);
        const bStr = b < 10 ? '0'+b : b;
        
        let bayHtml = `<div class="single-bay-wrapper">
            <div class="bay-title">BAY ${bStr}</div>
            
            <div class="bay-row-labels">`;
            
        // Top Row labels
        for (let r = 1; r <= rows; r++) {
            const rStr = r < 10 ? '0'+r : r;
            bayHtml += `<div class="bay-row-label">${rStr}</div>`;
        }
        bayHtml += `</div>`; 

        // ON DECK
        bayHtml += `<div class="bay-tier-wrapper"><div class="bay-grid-inner">`;
        for (let t = tiersOn; t >= 1; t--) {
            const tierValue = 80 + (t * 2);
            bayHtml += `<div class="bay-tier-row">`;
            for (let r = 1; r <= rows; r++) {
                const cont = bayContainers.find(c => c.row == r && c.tier == tierValue && c.deck == 'ON');
                const color = cont ? cont.color : 'transparent';
                const text = cont ? cont.type : ''; 
                const textColor = (color === '#ffffff' || color === '#eab308') ? '#000000' : '#ffffff';
                const clickHandler = cont ? `selectPlacedContainer(${JSON.stringify(cont).replace(/"/g, '&quot;')})` : `tryPlace2D(${b}, ${r}, ${tierValue}, 'ON')`;
                bayHtml += `<div class="mini-bay-cell ${cont ? 'occupied' : 'empty-slot'}" style="background-color: ${color}; color: ${cont ? textColor : 'transparent'};" onclick="${clickHandler}">${text}</div>`;
            }
            bayHtml += `</div>`;
        }
        bayHtml += `</div><div class="bay-tier-labels">`;
        for (let t = tiersOn; t >= 1; t--) {
            const tierValue = 80 + (t * 2);
            bayHtml += `<div class="bay-tier-label">${tierValue}</div>`;
        }
        bayHtml += `</div></div>`; // End On Deck

        // SEPARATOR
        bayHtml += `<div class="deck-separator"></div>`;

        // UNDER DECK
        bayHtml += `<div class="bay-tier-wrapper"><div class="bay-grid-inner">`;
        for (let t = tiersUnder; t >= 1; t--) {
            const tierValue = t * 2;
            bayHtml += `<div class="bay-tier-row">`;
            
            // Calculate Taper logic (simulating hull shape)
            let missingEachSide = 0;
            if (t <= Math.floor(tiersUnder / 2)) {
                 missingEachSide = Math.floor(tiersUnder / 2) - t + 1;
            }
            if (missingEachSide * 2 >= rows) missingEachSide = 0;

            for (let r = 1; r <= rows; r++) {
                let isTapered = (r <= missingEachSide || r > rows - missingEachSide);

                if (isTapered) {
                    bayHtml += `<div class="mini-bay-cell taper-invisible"></div>`;
                } else {
                    const cont = bayContainers.find(c => c.row == r && c.tier == tierValue && c.deck == 'UNDER');
                    const color = cont ? cont.color : 'transparent';
                    const text = cont ? cont.type : '';
                    const textColor = (color === '#ffffff' || color === '#eab308') ? '#000000' : '#ffffff';
                    const clickHandler = cont ? `selectPlacedContainer(${JSON.stringify(cont).replace(/"/g, '&quot;')})` : `tryPlace2D(${b}, ${r}, ${tierValue}, 'UNDER')`;
                    bayHtml += `<div class="mini-bay-cell ${cont ? 'occupied' : 'empty-slot'}" style="background-color: ${color}; color: ${cont ? textColor : 'transparent'};" onclick="${clickHandler}">${text}</div>`;
                }
            }
            bayHtml += `</div>`;
        }
        bayHtml += `</div><div class="bay-tier-labels">`;
        for (let t = tiersUnder; t >= 1; t--) {
            const tierValue = t * 2;
            const tierStr = tierValue < 10 ? '0'+tierValue : tierValue;
            bayHtml += `<div class="bay-tier-label">${tierStr}</div>`;
        }
        bayHtml += `</div></div>`; // End Under Deck

        // Bottom Row Labels
        bayHtml += `<div class="bay-row-labels mt-2">`;
        for (let r = 1; r <= rows; r++) {
            const rStr = r < 10 ? '0'+r : r;
            bayHtml += `<div class="bay-row-label">${rStr}</div>`;
        }
        bayHtml += `</div></div>`; // End single bay wrapper
        
        fullHtml += bayHtml;
    }

    fullHtml += `</div>`;
    $('#bayGridContainer').html(fullHtml);
}

function selectPlacedContainer(data) {
    if(!data) return;
    
    // Trigger the same info panel logic as 3D click
    const tierStr = data.tier < 10 ? '0'+data.tier : data.tier;
    const deckStr = data.deck === 'ON' ? '<span class="text-warning">ON DECK</span>' : '<span class="text-info">UNDER DECK</span>';

    const html = `
        <div class="mb-2 d-flex justify-content-between align-items-center">
            <strong class="text-dark fs-5">${data.container_no}</strong>
            <span class="badge bg-primary">${data.size}ft ${data.type}</span>
        </div>
        <div class="mb-2 text-end fw-bold">${deckStr}</div>
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
                <strong class="fw-bold">${tierStr}</strong>
            </div>
        </div>
        <div class="row text-dark small mb-3">
            <div class="col-6">POL: <strong class="text-info">${data.pol}</strong></div>
            <div class="col-6">POD: <strong class="text-success">${data.pod}</strong></div>
        </div>
        <div class="mt-2">
            <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="cancelStowage(${data.id})">
                <i class="fas fa-undo me-1"></i> Cancel Placement
            </button>
        </div>
    `;

    $('#containerInfo').html(html);

    const requestNo = $('#planningSelect option:selected').text();
    Swal.fire({
        title: 'Container ' + data.container_no,
        html: `
            <div class="text-start" style="font-size: 14px;">
                <p class="mb-1"><strong>Request:</strong> ${requestNo}</p>
                <p class="mb-1"><strong>Type:</strong> ${data.size}ft ${data.type}</p>
                <p class="mb-1"><strong>Position:</strong> Bay ${data.bay < 10 ? '0'+data.bay : data.bay} | Row ${data.row < 10 ? '0'+data.row : data.row} | Tier ${tierStr} (${data.deck})</p>
                <p class="mb-0"><strong>POL:</strong> ${data.pol} &nbsp;|&nbsp; <strong>POD:</strong> ${data.pod}</p>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Tutup',
        cancelButtonText: 'Cancel Placement',
        cancelButtonColor: '#ef4444'
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
            cancelStowage(data.id);
        }
    });
}

function downloadPDF() {
    if(!cachedData.profile) {
        Toast.fire({icon: 'warning', title: 'No vessel data to print.'});
        return;
    }

    const element = document.getElementById('bayGridContainer');
    const parent = document.getElementById('bayPlan2D');
    const vesselName = $('#planningSelect option:selected').text().split('(')[0] || 'VESSEL';
    
    // Temporarily adjust styles to prevent clipping and force A3 aspect layout
    const origParentHeight = parent.style.height || '';
    const origParentOverflow = parent.style.overflow || '';
    const origParentPosition = parent.style.position || '';
    
    const origElementWidth = element.style.width || '';
    const origElementBg = element.style.background || '';
    const origElementPad = element.style.padding || '';

    // Remove clipping so html2canvas can capture the full height of the bay plan
    parent.style.height = 'auto';
    parent.style.overflow = 'visible';
    parent.style.position = 'static';
    
    // Force a specific width so the grid forms a predictable layout instead of relying on the user's screen width.
    // 1450px fits exactly 5 bays across (280px each + borders).
    element.style.width = '1450px'; 
    element.style.background = '#ffffff';
    element.style.padding = '20px';
    
    // Scroll to top to prevent html2canvas blank space bug
    window.scrollTo(0,0);

    const opt = {
        margin:       10,
        filename:     `BayPlan_${vesselName.trim()}.pdf`,
        image:        { type: 'jpeg', quality: 1.0 },
        html2canvas:  { 
            scale: 2, 
            useCORS: true, 
            scrollY: 0,
            windowWidth: 1500 // provide breathing room for the 1450px element
        },
        jsPDF:        { unit: 'mm', format: 'a3', orientation: 'landscape' }
    };

    showLoading();

    html2pdf().set(opt).from(element).save().then(() => {
        // Revert styles after generation
        parent.style.height = origParentHeight;
        parent.style.overflow = origParentOverflow;
        parent.style.position = origParentPosition;
        
        element.style.width = origElementWidth;
        element.style.background = origElementBg;
        element.style.padding = origElementPad;
        
        hideLoading();
        Toast.fire({icon: 'success', title: 'PDF Downloaded Successfully!'});
    });
}

function printBlueprint() {
    if(!cachedData.profile || cachedData.containers.length === 0) {
        Toast.fire({icon: 'warning', title: 'No planned containers to print.'});
        return;
    }

    showLoading();

    // 1. Prepare the blueprint element for capture
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

    // 2. Capture the blueprint as an image
    html2canvas(element, { 
        scale: 2, 
        useCORS: true, 
        scrollY: 0,
        windowWidth: 1500
    }).then(canvas => {
        // Revert styles
        parent.style.height = origParentHeight;
        parent.style.overflow = origParentOverflow;
        parent.style.position = origParentPosition;
        element.style.width = origElementWidth;
        element.style.background = origElementBg;
        element.style.padding = origElementPad;

        const blueprintImg = canvas.toDataURL('image/jpeg', 1.0);
        
        // 3. Build the table and full print content
        const vesselName = $('#planningSelect option:selected').text().split('(')[0] || 'VESSEL';
        const voyage = $('#dispVoyage').text();
        
        let tableHtml = '';
        const sortedContainers = [...cachedData.containers].sort((a, b) => {
            if (a.bay !== b.bay) return a.bay - b.bay;
            if (a.row !== b.row) return a.row - b.row;
            return a.tier - b.tier;
        });

        sortedContainers.forEach((c, index) => {
            const tierStr = c.tier < 10 ? '0'+c.tier : c.tier;
            const bayStr = c.bay < 10 ? '0'+c.bay : c.bay;
            const rowStr = c.row < 10 ? '0'+c.row : c.row;
            
            tableHtml += `
                <tr>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center;">${index + 1}</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center; font-weight: bold;">${c.container_no}</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center;">${c.size}ft / ${c.type}</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center;">${bayStr}</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center;">${rowStr}</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center;">${tierStr}</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center;">${c.deck}</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center; font-weight: bold;">${c.pod}</td>
                </tr>
            `;
        });

        // 4. Create a printable wrapper
        const printDiv = document.createElement('div');
        printDiv.id = 'printWrapper';
        printDiv.style.cssText = 'padding: 20px; font-family: "Segoe UI", Roboto, sans-serif; color: #000; background: #fff;';
        
        printDiv.innerHTML = `
            <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 15px;">
                <h2 style="margin: 0; color: #1e3a8a;">VESSEL STOWAGE PLAN REPORT</h2>
                <h4 style="margin: 5px 0;">M.V. ${vesselName} | ${voyage}</h4>
                <p style="margin: 0; font-size: 11px; color: #666;">Generated on: ${new Date().toLocaleString('id-ID')}</p>
            </div>

            <div style="margin-bottom: 30px;">
                <h5 style="background: #f1f5f9; padding: 8px; border-left: 5px solid #1e3a8a; margin-bottom: 15px;">1. BLUEPRINT BAY PLAN VIEW</h5>
                <div style="text-align: center; border: 1px solid #ccc; padding: 10px; background: #fff;">
                    <img src="${blueprintImg}" style="width: 100%; height: auto; max-height: 800px; object-fit: contain;">
                </div>
            </div>

            <div style="page-break-before: always;">
                <h5 style="background: #f1f5f9; padding: 8px; border-left: 5px solid #1e3a8a; margin-bottom: 15px;">2. STOWAGE DETAIL LIST</h5>
                <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
                    <thead>
                        <tr style="background: #1e3a8a; color: #fff;">
                            <th style="border: 1px solid #000; padding: 6px;">NO</th>
                            <th style="border: 1px solid #000; padding: 6px;">CONTAINER NO</th>
                            <th style="border: 1px solid #000; padding: 6px;">SIZE / TYPE</th>
                            <th style="border: 1px solid #000; padding: 6px;">BAY</th>
                            <th style="border: 1px solid #000; padding: 6px;">ROW</th>
                            <th style="border: 1px solid #000; padding: 6px;">TIER</th>
                            <th style="border: 1px solid #000; padding: 6px;">DECK</th>
                            <th style="border: 1px solid #000; padding: 6px;">POD</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${tableHtml}
                    </tbody>
                </table>
                <div style="margin-top: 20px; font-size: 10px; text-align: right;">
                    <p>Total Containers: <strong>${sortedContainers.length}</strong></p>
                </div>
            </div>
        `;

        // 5. Open print window and print
        const printWindow = window.open('', '_blank', 'width=1200,height=800');
        printWindow.document.write('<html><head><title>Print Stowage Plan - ' + vesselName + '</title>');
        printWindow.document.write('<style>@page { size: landscape; margin: 1cm; } body { margin: 0; background: #fff; } @media print { .no-print { display: none; } }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(printDiv.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        // Wait for image to load in the new window
        setTimeout(() => {
            printWindow.print();
            // Optional: close the window after printing
            // printWindow.close();
            hideLoading();
            Toast.fire({icon: 'success', title: 'Print Dialog Opened'});
        }, 1000);

    }).catch(err => {
        console.error('Print error:', err);
        hideLoading();
        Toast.fire({icon: 'error', title: 'Failed to generate print view'});
    });
}

function downloadImage() {
    if(!cachedData.profile) {
        Toast.fire({icon: 'warning', title: 'No vessel data to export.'});
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
        // Revert styles
        parent.style.height = origParentHeight;
        parent.style.overflow = origParentOverflow;
        parent.style.position = origParentPosition;
        element.style.width = origElementWidth;
        element.style.background = origElementBg;
        element.style.padding = origElementPad;

        const vesselName = $('#planningSelect option:selected').text().split('(')[0] || 'VESSEL';
        const link = document.createElement('a');
        link.download = `Blueprint_${vesselName.trim()}_${new Date().getTime()}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
        
        hideLoading();
        Toast.fire({icon: 'success', title: 'Image Downloaded!'});
    });
}

function startOperation() {
    const planningId = $('#planningSelect').val();
    const vesselName = $('#planningSelect option:selected').attr('data-vessel');

    Swal.fire({
        title: 'Mulai Operasi Kapal?',
        text: "Kapal " + vesselName + " akan dinyatakan mulai beroperasi dan akan muncul di dashboard monitoring.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Mulai!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= site_url("planning/vessel/ajax_start_operation") ?>',
                type: 'POST',
                data: { planning_id: planningId },
                dataType: 'json',
                success: function(res) {
                    if(res.status === 'success') {
                        // Update the option attribute in the dropdown
                        $('#planningSelect option:selected').attr('data-status', 'OPERATING');
                        // Update UI
                        document.getElementById('dispStatusBadge').innerHTML = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> OPERATING</span>';
                        document.getElementById('btnStartOp').style.display = 'none';
                        
                        Toast.fire({icon: 'success', title: 'Kapal resmi beroperasi'});
                    } else {
                        Toast.fire({icon: 'error', title: res.message});
                    }
                },
                error: function() {
                    Toast.fire({icon: 'error', title: 'Terjadi kesalahan sistem'});
                }
            });
        }
    });
}

function animate() {
    requestAnimationFrame(animate);
    controls.update();
    renderer.render(scene, camera);
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
