<div class="row g-4">
    <div class="col-md-4">
        <div class="card-custom h-100 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom pb-3">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-ship me-2 text-primary"></i>Select Vessel</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table id="vesselListTable" class="table table-hover mb-0 w-100">
                        <thead class="d-none"><tr><th>Vessel</th></tr></thead>
                        <tbody>
                            <?php foreach($vessels as $v): ?>
                            <tr>
                                <td class="p-0 border-0">
                                    <a href="javascript:void(0)" class="list-group-item list-group-item-action border-0 border-bottom d-flex justify-content-between align-items-center py-3 px-2 vessel-item" onclick="load_profile(<?= $v->id ?>, '<?= htmlspecialchars($v->vessel_name) ?>', this)">
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 14px;"><?= $v->vessel_name ?></div>
                                            <div class="small text-muted"><i class="fas fa-hashtag me-1"></i><?= $v->vessel_code ?></div>
                                        </div>
                                        <div class="icon-container">
                                            <i class="fas fa-chevron-right text-primary opacity-50 transition-all"></i>
                                        </div>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div id="profile_container" style="display:none; animation: fadeIn 0.4s ease-out;">
            <div class="card-custom border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-id-card fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-black fw-bold" id="vessel_name_display"></h5>
                            <span class="text-black-50 small">Technical Bay Plan Configuration</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form id="formProfile">
                        <input type="hidden" name="vessel_id" id="vessel_id_input">
                        
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label fw-bold text-dark">Total Bays</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-grip-lines-vertical"></i></span>
                                        <input type="number" class="form-control border-start-0 ps-0 bg-light" name="bay_count" id="bay_count" placeholder="e.g. 24">
                                    </div>
                                    <small class="text-muted d-block mt-2">Jumlah sekat membujur kapal (01, 03, 05...)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label fw-bold text-dark">Max Rows</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-arrows-alt-h"></i></span>
                                        <input type="number" class="form-control border-start-0 ps-0 bg-light" name="row_count" id="row_count" placeholder="e.g. 12">
                                    </div>
                                    <small class="text-muted d-block mt-2">Lebar maksimal baris kontainer (port to stbd)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label fw-bold text-dark">Tiers (Under Deck)</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-layer-group"></i></span>
                                        <input type="number" class="form-control border-start-0 ps-0 bg-light" name="tier_count_under_deck" id="tier_under" placeholder="e.g. 6">
                                    </div>
                                    <small class="text-muted d-block mt-2">Jumlah susunan di dalam palka (02, 04, 06...)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label fw-bold text-dark">Tiers (On Deck)</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-layer-group mt-n1"></i></span>
                                        <input type="number" class="form-control border-start-0 ps-0 bg-light" name="tier_count_on_deck" id="tier_on" placeholder="e.g. 8">
                                    </div>
                                    <small class="text-muted d-block mt-2">Jumlah susunan di atas palka (82, 84, 86...)</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert bg-primary-subtle border-0 rounded-4 p-4 d-flex align-items-center shadow-sm">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-4">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-primary mb-1">Stowage Validation Info</h6>
                                <p class="small text-muted mb-0">Konfigurasi ini akan digunakan oleh sistem <b>Planning Bayplan</b> untuk memvalidasi posisi kontainer saat proses stowage planning agar tidak melebihi kapasitas fisik kapal.</p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white border-top-0 p-4 text-end">
                    <button class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm" onclick="save_profile()" id="btnSaveProfile">
                        <i class="fas fa-check-circle me-2"></i>Save Configuration
                    </button>
                </div>
            </div>
        </div>

        <div id="no_selection" class="card-custom border-0 shadow-sm h-100 d-flex flex-column align-items-center justify-content-center" style="min-height: 500px;">
            <div class="bg-light rounded-circle p-5 mb-4 shadow-sm">
                <i class="fas fa-ship fa-4x text-primary opacity-50"></i>
            </div>
            <h4 class="text-dark fw-bold">Select a Vessel</h4>
            <p class="text-muted">Choose a vessel from the list to manage its technical profile.</p>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable for vessel list
    $('#vesselListTable').DataTable({
        "pageLength": 6,
        "lengthChange": false,
        "info": true,
        "ordering": false,
        "language": {
            "search": "",
            "searchPlaceholder": "🔍 Search vessel name or code...",
            "paginate": {
                "previous": "<i class='fas fa-chevron-left small'></i>",
                "next": "<i class='fas fa-chevron-right small'></i>"
            },
            "info": "<span class='small text-muted'>Showing _START_ to _END_ of _TOTAL_</span>"
        },
        "dom": "<'row mb-3'<'col-sm-12'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row mt-3 align-items-center'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>"
    });
    
    // Style the search box
    $('.dataTables_filter input').addClass('form-control form-control-sm bg-light border-0 w-100').removeClass('form-control-sm').css('padding', '10px 15px');
});

function load_profile(id, name, element) {
    // UI selection state
    $('.vessel-item').removeClass('bg-primary-subtle border-primary border-start border-4');
    $('.vessel-item .icon-container').html('<i class="fas fa-chevron-right text-primary opacity-50 transition-all"></i>');
    
    if(element) {
        $(element).addClass('bg-primary-subtle border-primary border-start border-4');
        $(element).find('.icon-container').html('<i class="fas fa-circle text-primary small"></i>');
    }
    
    $('#vessel_id_input').val(id);
    $('#vessel_name_display').text(name);
    
    // Show loading state
    $('#no_selection').hide();
    $('#profile_container').hide();
    
    // Simulate slight loading for better UX feel
    setTimeout(function() {
        $.ajax({
            url: '<?= site_url("master/vessel_profile/get_profile") ?>/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#profile_container').fadeIn();
                
                if(data) {
                    $('#bay_count').val(data.bay_count);
                    $('#row_count').val(data.row_count);
                    $('#tier_under').val(data.tier_count_under_deck);
                    $('#tier_on').val(data.tier_count_on_deck);
                } else {
                    $('#formProfile')[0].reset();
                    $('#vessel_id_input').val(id);
                }
            }
        });
    }, 200);
}

function save_profile() {
    var btn = $('#btnSaveProfile');
    var originalText = btn.html();
    
    btn.html('<i class="fas fa-circle-notch fa-spin me-2"></i>Saving...').prop('disabled', true);
    
    var data = $('#formProfile').serialize();
    $.ajax({
        url: '<?= site_url("master/vessel_profile/ajax_save") ?>',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function(res) {
            btn.html(originalText).prop('disabled', false);
            if(res.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated!',
                    text: 'Vessel technical configuration saved successfully.',
                    background: '#ffffff',
                    confirmButtonColor: '#3b82f6',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        },
        error: function() {
            btn.html(originalText).prop('disabled', false);
            Toast.fire({icon: 'error', title: 'Server error occurred'});
        }
    });
}
</script>

<?php ob_start(); ?>
<script></script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
}
.bg-primary-subtle { 
    background-color: rgba(59, 130, 246, 0.08) !important; 
}
.form-control:focus {
    box-shadow: none;
    border-color: #3b82f6;
    background-color: #fff !important;
}
.transition-all {
    transition: all 0.3s ease;
}
.vessel-item:hover {
    background-color: #f8fafc;
    transform: translateX(5px);
}
.dataTables_filter {
    float: none !important;
    text-align: left !important;
}
.dataTables_filter label {
    width: 100%;
}
.page-item.active .page-link {
    background-color: #3b82f6;
    border-color: #3b82f6;
}
.page-link {
    color: #64748b;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    margin: 0 2px;
}
.page-link:hover {
    background-color: #f1f5f9;
    color: #3b82f6;
}
</style>
