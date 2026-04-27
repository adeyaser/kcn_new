<style>
    .form-section-title {
        color: var(--accent);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border);
        padding-bottom: 8px;
        margin-bottom: 20px;
    }
    .table-modern thead th {
        background: #f8fafc;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border);
    }
    .table-modern tbody td {
        padding: 12px 8px;
        border-bottom: 1px solid var(--border-light);
    }
    .equipment-row .form-control-sm, 
    .equipment-row .form-select-sm {
        border: 1px solid var(--border);
        background: white !important;
        font-size: 12px;
        border-radius: var(--radius-sm);
        transition: var(--transition);
    }
    .equipment-row .form-control-sm:focus, 
    .equipment-row .form-select-sm:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-glow);
    }
</style>

<div class="row">

    <div class="col-12">
        <form id="formRequest" class="needs-validation" novalidate>
            <div class="card-custom mb-4">
                <div class="card-header bg-primary text-white" style="border-radius: var(--radius) var(--radius) 0 0; padding: 12px 20px;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 text-white"><i class="fas fa-file-signature me-2"></i><?= isset($request) ? 'Edit Planning Request' : 'New Planning Request' ?></h6>
                            <span class="badge bg-light text-primary fs-6 ms-3" id="displayRequestNo"><?= isset($request) ? $request->request_no : $request_no ?></span>
                        </div>
                        <input type="hidden" name="request_no" value="<?= isset($request) ? $request->request_no : $request_no ?>">
                        <?php if(isset($request)): ?>
                            <input type="hidden" name="id" value="<?= $request->id ?>">
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <!-- Vessel Selection -->
                    <h6 class="text-info mb-3 border-bottom border-secondary pb-2">1. Vessel Information</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Select Vessel Schedule <span class="text-danger">*</span></label>
                            <select class="form-select select2-vessel" name="schedule_id" id="schedule_id" required>
                                <option value="">-- Choose Schedule --</option>
                                <?php foreach($schedules as $s): ?>
                                    <option value="<?= $s->id ?>" <?= isset($request) && $request->schedule_id == $s->id ? 'selected' : '' ?>><?= $s->vessel_name ?> (<?= $s->voyage_in ?> / <?= $s->voyage_out ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="vessel_id" id="vessel_id" value="<?= isset($request) ? $request->vessel_id : '' ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Voyage In <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="voyage_in" id="voyage_in" value="<?= isset($request) ? $request->voyage_in : '' ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Voyage Out <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="voyage_out" id="voyage_out" value="<?= isset($request) ? $request->voyage_out : '' ?>" required>
                        </div>
                    </div>

                    <!-- Terminal / Time Config -->
                    <h6 class="form-section-title">2. Terminal & Schedule Configuration</h6>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-2">
                            <label class="form-label text-primary-custom fw-bold">Operation Type <span class="text-danger">*</span></label>
                            <select class="form-select border-primary" name="operation_type" id="operation_type" onchange="updateRequestNo()" required>
                                <option value="DIS" <?= isset($request) && $request->operation_type == 'DIS' ? 'selected' : '' ?>>DIS (Bongkar)</option>
                                <option value="LOD" <?= isset($request) && $request->operation_type == 'LOD' ? 'selected' : '' ?>>LOD (Muat)</option>
                                <option value="VSL" <?= isset($request) && $request->operation_type == 'VSL' ? 'selected' : '' ?>>VSL (Bongkar Muat)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Service Type</label>
                            <select class="form-select" name="service_type" id="service_type" onchange="toggleInternational()">
                                <option value="Domestic" <?= isset($request) && $request->service_type == 'Domestic' ? 'selected' : '' ?>>Domestic</option>
                                <option value="International" <?= isset($request) && $request->service_type == 'International' ? 'selected' : '' ?>>International</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Request Type</label>
                            <select class="form-select bg-light" name="request_type" id="request_type" readonly style="pointer-events: none;">
                                <option value="INBOUND" <?= isset($request) && $request->request_type == 'INBOUND' ? 'selected' : '' ?>>INBOUND</option>
                                <option value="OUTBOUND" <?= isset($request) && $request->request_type == 'OUTBOUND' ? 'selected' : '' ?>>OUTBOUND</option>
                                <option value="BOTH" <?= isset($request) && $request->request_type == 'BOTH' ? 'selected' : '' ?>>BOTH</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Booking Limit</label>
                            <input type="number" class="form-control" name="booking_limit" id="booking_limit" value="<?= isset($request) ? $request->booking_limit : '500' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">POD</label>
                            <select class="form-select" name="pod" id="pod">
                                <option value="">-- Select POD --</option>
                                <?php foreach($ports as $p): ?>
                                    <option value="<?= $p->port_code ?>" <?= isset($request) && $request->pod == $p->port_code ? 'selected' : '' ?>><?= $p->port_code ?> - <?= $p->port_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Final POD (FPOD)</label>
                            <select class="form-select" name="fpod" id="fpod">
                                <option value="">-- Select FPOD --</option>
                                <?php foreach($ports as $p): ?>
                                    <option value="<?= $p->port_code ?>" <?= isset($request) && $request->fpod == $p->port_code ? 'selected' : '' ?>><?= $p->port_code ?> - <?= $p->port_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Estimate Time Arrival (ETA) <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" name="eta" id="eta" value="<?= isset($request) ? date('Y-m-d\TH:i', strtotime($request->eta)) : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estimate Time Departure (ETD) <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" name="etd" id="etd" value="<?= isset($request) ? date('Y-m-d\TH:i', strtotime($request->etd)) : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Open Stack <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" name="open_stack" id="open_stack" value="<?= isset($request) ? date('Y-m-d\TH:i', strtotime($request->open_stack)) : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Closing Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" name="closing_time" id="closing_time" value="<?= isset($request) ? date('Y-m-d\TH:i', strtotime($request->closing_time)) : '' ?>" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-5">
                        <div class="col-md-4">
                            <label class="form-label">Closing Time Document</label>
                            <input type="datetime-local" class="form-control" name="closing_time_doc" id="closing_time_doc" value="<?= isset($request) && $request->closing_time_doc ? date('Y-m-d\TH:i', strtotime($request->closing_time_doc)) : '' ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Start Shift Reefer</label>
                            <input type="datetime-local" class="form-control" name="start_shift_reefer" id="start_shift_reefer" value="<?= isset($request) && $request->start_shift_reefer ? date('Y-m-d\TH:i', strtotime($request->start_shift_reefer)) : '' ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Shift Reefer</label>
                            <input type="datetime-local" class="form-control" name="end_shift_reefer" id="end_shift_reefer" value="<?= isset($request) && $request->end_shift_reefer ? date('Y-m-d\TH:i', strtotime($request->end_shift_reefer)) : '' ?>">
                        </div>
                    </div>



                    <!-- International Documents -->
                    <div id="internationalSection" style="display:none;">
                        <h6 class="text-warning mb-3 border-bottom border-warning pb-2">3. Customs Documents (International Only)</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Customs Document Type</label>
                                <select class="form-select" name="customs_document_type" id="customs_document_type">
                                    <option value="">-- Select Type --</option>
                                    <option value="EKSPOR">Ekspor</option>
                                    <option value="IMPOR">Impor</option>
                                    <option value="TRANSHIPMENT">Transhipment</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Dokumen BC 1.2</label>
                                <input type="text" class="form-control" name="doc_bc_1_2" placeholder="Nomor Dokumen">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">NPE (Nota Pelayanan Ekspor)</label>
                                <input type="text" class="form-control" name="doc_npe" placeholder="Nomor NPE">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">PKBE</label>
                                <input type="text" class="form-control" name="doc_pkbe" placeholder="Nomor PKBE">
                            </div>
                            <!-- More fields can be added based on needs -->
                        </div>
                    </div>

                    <!-- Equipment Planning Section -->
                    <div class="mb-5">
                        <h6 class="form-section-title d-flex justify-content-between align-items-center">
                            <span>3. Equipment Planning</span>
                            <button type="button" class="btn btn-xs btn-primary-custom px-3" onclick="addEquipmentRow()">
                                <i class="fas fa-plus me-1"></i>Add Machine
                            </button>
                        </h6>
                        
                        <div class="table-responsive rounded border border-light shadow-sm">
                            <table class="table table-sm mb-0 align-middle table-modern" id="tableEquipments">
                                <thead>
                                    <tr>
                                        <th width="30%" class="ps-3 py-2">Machine Type / Name</th>
                                        <th width="10%" class="py-2">Qty</th>
                                        <th width="20%" class="py-2">Start Date</th>
                                        <th width="20%" class="py-2">End Date</th>
                                        <th width="15%" class="py-2">Notes</th>
                                        <th width="5%" class="text-center py-2"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php if(isset($planned_equipments) && count($planned_equipments) > 0): ?>
                                        <?php foreach($planned_equipments as $pe): ?>
                                        <tr class="equipment-row">
                                            <td class="ps-3">
                                                <select name="equip_id[]" class="form-select form-select-sm">
                                                    <?php foreach($all_equipments as $eq): ?>
                                                    <option value="<?= $eq->id ?>" <?= ($pe->equipment_id == $eq->id) ? 'selected' : '' ?>>
                                                        <?= $eq->equipment_name ?> (<?= $eq->equipment_type ?>)
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td><input type="number" name="equip_qty[]" class="form-control form-control-sm" value="<?= $pe->quantity ?>" min="1"></td>
                                            <td><input type="datetime-local" name="equip_start[]" class="form-control form-control-sm" value="<?= $pe->start_date ? date('Y-m-d\TH:i', strtotime($pe->start_date)) : '' ?>"></td>
                                            <td><input type="datetime-local" name="equip_end[]" class="form-control form-control-sm" value="<?= $pe->end_date ? date('Y-m-d\TH:i', strtotime($pe->end_date)) : '' ?>"></td>
                                            <td><input type="text" name="equip_notes[]" class="form-control form-control-sm" value="<?= $pe->notes ?>" placeholder="..."></td>
                                            <td class="text-center"><button type="button" class="btn btn-sm text-danger p-0" onclick="$(this).closest('tr').remove()"><i class="fas fa-times-circle"></i></button></td>
                                        </tr>

                                        <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr class="equipment-row">
                                        <td class="ps-3">
                                            <select name="equip_id[]" class="form-select form-select-sm">
                                                <option value="">Select Machine...</option>
                                                <?php foreach($all_equipments as $eq): ?>
                                                <option value="<?= $eq->id ?>"><?= $eq->equipment_name ?> (<?= $eq->equipment_type ?>)</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><input type="number" name="equip_qty[]" class="form-control form-control-sm" value="1" min="1"></td>
                                        <td><input type="datetime-local" name="equip_start[]" class="form-control form-control-sm"></td>
                                        <td><input type="datetime-local" name="equip_end[]" class="form-control form-control-sm"></td>
                                        <td><input type="text" name="equip_notes[]" class="form-control form-control-sm" placeholder="..."></td>
                                        <td class="text-center"><button type="button" class="btn btn-sm text-danger p-0" onclick="$(this).closest('tr').remove()"><i class="fas fa-times-circle"></i></button></td>
                                    </tr>

                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Uploads -->
                    <h6 class="form-section-title">4. Upload Documents</h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label mb-0">Upload Baplie/Manifest (Excel/CSV/EDI)</label>
                                <a href="<?= base_url('assets/templates/manifest_template.csv') ?>" class="btn btn-xs btn-outline-primary py-0 px-2" style="font-size: 11px;">
                                    <i class="fas fa-download me-1"></i>Download Template
                                </a>
                            </div>
                            <input class="form-control" type="file" name="manifest_file" id="manifest_file" accept=".csv, .xls, .xlsx" onchange="previewManifest()">
                            <small class="text-muted">Format: .xls, .xlsx, .csv, .edi</small>
                        </div>
                        <div class="col-md-6" id="bcUploadSection" style="display:none;">
                            <label class="form-label">Upload Additional Doc (BC Doc)</label>
                            <input class="form-control" type="file" name="bc_file" id="bc_file">
                            <small class="text-muted">Format: .pdf, .zip</small>
                        </div>
                    </div>

                    <!-- Container Preview Table -->
                    <div id="manifestPreviewSection" style="<?= (isset($manifest) && count($manifest) > 0) ? '' : 'display:none;' ?>" class="mt-4">
                        <h6 class="form-section-title d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-list me-2"></i><?= isset($manifest) ? 'Saved Manifest' : 'Manifest Preview' ?></span>
                            <span class="badge bg-info text-dark" id="previewCount"><?= isset($manifest) ? count($manifest) : '0' ?> Containers</span>
                        </h6>

                        <div class="table-responsive border rounded" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm table-striped table-hover mb-0" id="tablePreview">
                                <thead class="sticky-top" style="z-index: 10;">
                                    <tr style="background-color: #e3f2fd !important;">
                                        <th class="ps-3 py-2 text-dark" style="background-color: #e3f2fd !important;">No</th>
                                        <th class="py-2 text-dark" style="background-color: #e3f2fd !important;">Container No</th>
                                        <th class="py-2 text-dark" style="background-color: #e3f2fd !important;">Size</th>
                                        <th class="py-2 text-dark" style="background-color: #e3f2fd !important;">Type</th>
                                        <th class="py-2 text-dark" style="background-color: #e3f2fd !important;">Status</th>
                                        <th class="py-2 text-dark" style="background-color: #e3f2fd !important;">Weight (Kg)</th>
                                        <th class="py-2 text-dark" style="background-color: #e3f2fd !important;">POD</th>
                                        <th class="text-center pe-3 py-2 text-dark" style="background-color: #e3f2fd !important;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($manifest)): foreach($manifest as $idx => $m): ?>
                                    <tr id="cont-row-<?= $m->id ?>">
                                        <td><?= $idx + 1 ?></td>
                                        <td class="fw-bold text-info"><?= $m->container_no ?></td>
                                        <td><?= $m->size ?></td>
                                        <td><?= $m->type ?></td>
                                        <td><?= $m->status ?></td>
                                        <td><?= $m->weight ?></td>
                                        <td><?= $m->pod ?></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-xs btn-danger" onclick="deleteContainer(<?= $m->id ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2 text-muted small">
                            <i class="fas fa-info-circle me-1"></i> Please verify the container list before submitting.
                        </div>
                    </div>
                    
                    <!-- Operational Tips Section (Dynamic) -->
                    <div id="operationalGuide" class="alert alert-info border-0 shadow-sm p-4 mt-5" style="border-left: 5px solid var(--info) !important;">
                        <h6 class="fw-bold mb-3"><i class="fas fa-lightbulb me-2"></i>TOS OPERATIONAL TIPS: <span id="guideTitle">VSL (Bongkar Muat)</span></h6>
                        <div id="guideContent" class="small">
                            <!-- Content loaded via JS -->
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end bg-transparent border-top border-secondary p-3">
                    <a href="<?= site_url('planning/request') ?>" class="btn btn-secondary me-2">Cancel</a>
                    <button type="button" class="btn btn-primary-custom" id="btnSubmit" onclick="submitRequest()">
                        <i class="fas fa-save me-2"></i><?= isset($request) ? 'Update Planning' : 'Submit Request' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
$(document).ready(function() {
    // Init Select2 for better UX with large vessel lists
    $('.select2-vessel').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Auto-fill trigger on Schedule Change
    $('#schedule_id').on('change', function() {
        var scheduleId = $(this).val();
        // If edit mode and same schedule, don't overwrite manual changes
        <?php if(isset($request)): ?>
        if (scheduleId == '<?= $request->schedule_id ?>') return;
        <?php endif; ?>

        if(scheduleId) {
            Swal.fire({
                title: 'Loading Schedule...',
                text: 'Mengambil data voyage dan estimasi waktu.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading() },
                background: '#1e293b', color: '#e2e8f0'
            });

            $.ajax({
                url: '<?= site_url("planning/request/get_vessel_info") ?>',
                type: 'GET',
                data: {schedule_id: scheduleId},
                dataType: 'json',
                success: function(res) {
                    Swal.close();
                    if(res.status === 'success') {
                        $('#vessel_id').val(res.vessel_id);
                        $('#voyage_in').val(res.voyage_in);
                        $('#voyage_out').val(res.voyage_out);
                        $('#eta').val(res.eta);
                        $('#etd').val(res.etd);
                        $('#open_stack').val(res.open_stack);
                        $('#closing_time').val(res.closing_time);
                        $('#closing_time_doc').val(res.closing_time_doc);
                        $('#start_shift_reefer').val(res.start_shift_reefer);
                        $('#end_shift_reefer').val(res.end_shift_reefer);
                        $('#pod').val(res.pod);
                        $('#fpod').val(res.fpod);
                        
                        Toast.fire({icon: 'success', title: 'Data otomatis terisi dari Jadwal Kapal'});
                    } else {
                        Toast.fire({icon: 'error', title: 'Gagal mengambil data jadwal'});
                    }
                },
                error: function() {
                    Swal.close();
                    Toast.fire({icon: 'error', title: 'Server error'});
                }
            });
        } else {
            $('#formRequest')[0].reset();
            $('.select2-vessel').val('').trigger('change');
        }
    });
});

function toggleInternational() {
    var type = $('#service_type').val();
    if(type === 'International') {
        $('#internationalSection').slideDown();
        $('#bcUploadSection').fadeIn();
    } else {
        $('#internationalSection').slideUp();
        $('#bcUploadSection').fadeOut();
    }
}

function updateRequestNo() {
    var type = $('#operation_type').val();
    var currentNo = $('#displayRequestNo').text();
    // Assuming format XXX-YYYYMMDD-0001
    var parts = currentNo.split('-');
    if (parts.length === 3) {
        var newNo = type + '-' + parts[1] + '-' + parts[2];
        $('#displayRequestNo').text(newNo);
        $('input[name="request_no"]').val(newNo);
    }

    // Sync Request Type
    if (type === 'DIS') {
        $('#request_type').val('INBOUND');
    } else if (type === 'LOD') {
        $('#request_type').val('OUTBOUND');
    } else if (type === 'VSL') {
        $('#request_type').val('BOTH');
    }

    updateGuide();
}

function updateGuide() {
    var type = $('#operation_type').val();
    var title = "VSL (Bongkar Muat)";
    var content = "";

    if (type === 'DIS') {
        title = "DIS (Inbound Discharge Only)";
        content = `
            <ul class="mb-0">
                <li><strong>Langkah 1:</strong> Siapkan manifest bongkar dari file BAPLIE atau CSV.</li>
                <li><strong>Langkah 2:</strong> Pastikan POD (Point of Discharge) sesuai dengan Terminal KCN.</li>
                <li><strong>Langkah 3:</strong> Setelah request disetujui, buka modul <strong>Vessel Planning</strong> untuk memplot posisi discharge.</li>
                <li><strong>Langkah 4:</strong> Buka <strong>Yard Planning</strong> untuk alokasi blok penumpukan kontainer impor.</li>
            </ul>
        `;
    } else if (type === 'LOD') {
        title = "LOD (Outbound Loading Only)";
        content = `
            <ul class="mb-0">
                <li><strong>Langkah 1:</strong> Pastikan kontainer sudah terkumpul di Yard (Pre-stacking).</li>
                <li><strong>Langkah 2:</strong> Upload Load List untuk validasi data kontainer yang akan naik kapal.</li>
                <li><strong>Langkah 3:</strong> Gunakan <strong>Vessel Planning</strong> untuk menentukan Bay Plan (stabilitas kapal).</li>
                <li><strong>Langkah 4:</strong> Periksa Closing Time agar tidak ada kontainer yang terlambat masuk gate.</li>
            </ul>
        `;
    } else {
        title = "VSL (Combined Discharge & Load)";
        content = `
            <ul class="mb-0">
                <li><strong>Tips:</strong> Gunakan mode ini untuk efisiensi "Double Banking" (sekali sandar bongkar muat).</li>
                <li><strong>Langkah 1:</strong> Upload Manifest gabungan atau lakukan upload terpisah.</li>
                <li><strong>Langkah 2:</strong> Prioritaskan <strong>Discharge Planning</strong> sebelum Load Planning agar slot di kapal kosong.</li>
                <li><strong>Langkah 3:</strong> Koordinasikan dengan <strong>Yard Planning</strong> untuk menghindari tabrakan arus alat.</li>
            </ul>
        `;
    }

    $('#guideTitle').text(title);
    $('#guideContent').html(content);
}

// Initial toggle & guide
$(document).ready(function() {
    toggleInternational();
    updateGuide();
});

function submitRequest() {
    // Basic validation
    var form = document.getElementById('formRequest');
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        Toast.fire({icon: 'warning', title: 'Please fill all required fields'});
        return;
    }

    var btn = $('#btnSubmit');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');

    // Use FormData for file uploads
    var formData = new FormData(form);

    $.ajax({
        url: '<?= site_url("planning/request/ajax_save") ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
            if(res.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Request Submitted!',
                    text: 'Planning request has been submitted for approval.',
                    background: '#1e293b', color: '#e2e8f0',
                    confirmButtonColor: '#3b82f6'
                }).then(() => {
                    window.location.href = res.redirect;
                });
            } else {
                Toast.fire({icon: 'error', title: 'Error saving data'});
                btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i><?= isset($request) ? 'Update Planning' : 'Submit Request' ?>');
            }
        },
        error: function() {
            Toast.fire({icon: 'error', title: 'Server Error'});
            btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i><?= isset($request) ? 'Update Planning' : 'Submit Request' ?>');
        }
    });
}

function previewManifest() {
    var fileInput = document.getElementById('manifest_file');
    var file = fileInput.files[0];
    if(!file) return;

    var formData = new FormData();
    formData.append('manifest_file', file);

    Swal.fire({
        title: 'Parsing Manifest...',
        text: 'Mohon tunggu sejenak.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading() },
        background: '#1e293b', color: '#e2e8f0'
    });

    $.ajax({
        url: '<?= site_url("planning/request/ajax_preview_manifest") ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
            Swal.close();
            if(res.status === 'success') {
                var tbody = $('#tablePreview tbody');
                tbody.empty();
                
                res.data.forEach(function(item, index) {
                    var row = `<tr>
                        <td class="ps-3">${index + 1}</td>
                        <td class="fw-bold text-primary">${item.container_no}</td>
                        <td>${item.size}</td>
                        <td>${item.type}</td>
                        <td>${item.status}</td>
                        <td>${item.weight}</td>
                        <td>${item.pod}</td>
                        <td class="text-center text-muted pe-3"><i class="fas fa-clock"></i></td>
                    </tr>`;
                    tbody.append(row);
                });

                $('#previewCount').text(res.data.length + ' Containers (Pending)');
                $('#manifestPreviewSection').slideDown();
                Toast.fire({icon: 'success', title: 'Manifest parsed successfully'});
            } else {
                Toast.fire({icon: 'error', title: res.message || 'Gagal memproses file'});
                $('#manifestPreviewSection').hide();
            }
        },
        error: function() {
            Swal.close();
            Toast.fire({icon: 'error', title: 'Server error saat memproses file'});
            $('#manifestPreviewSection').hide();
        }
    });
}

function deleteContainer(id) {
    Swal.fire({
        title: 'Hapus Kontainer?',
        text: 'Kontainer ini akan dihapus dari manifest perencanaan ini.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        background: '#1e293b', color: '#e2e8f0'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= site_url("planning/request/ajax_delete_container") ?>/' + id,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    if(res.status === 'success') {
                        $('#cont-row-' + id).fadeOut(300, function() { 
                            $(this).remove(); 
                            // Update count
                            var currentCount = parseInt($('#previewCount').text());
                            $('#previewCount').text((currentCount - 1) + ' Containers');
                        });
                        Toast.fire({icon: 'success', title: res.message});
                    } else {
                        Toast.fire({icon: 'error', title: res.message});
                    }
                },
                error: function() {
                    Toast.fire({icon: 'error', title: 'Server error'});
                }
            });
        }
    });
}
function addEquipmentRow() {
    var row = `<tr class="equipment-row">
        <td class="ps-3">
            <select name="equip_id[]" class="form-select form-select-sm">
                <option value="">Select Machine...</option>
                <?php foreach($all_equipments as $eq): ?>
                <option value="<?= $eq->id ?>"><?= $eq->equipment_name ?> (<?= $eq->equipment_type ?>)</option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><input type="number" name="equip_qty[]" class="form-control form-control-sm" value="1" min="1"></td>
        <td><input type="datetime-local" name="equip_start[]" class="form-control form-control-sm"></td>
        <td><input type="datetime-local" name="equip_end[]" class="form-control form-control-sm"></td>
        <td><input type="text" name="equip_notes[]" class="form-control form-control-sm" placeholder="..."></td>
        <td class="text-center"><button type="button" class="btn btn-sm text-danger p-0" onclick="$(this).closest('tr').remove()"><i class="fas fa-times-circle"></i></button></td>
    </tr>`;
    $('#tableEquipments tbody').append(row);
}

</script>
