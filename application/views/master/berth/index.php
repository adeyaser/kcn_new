<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header">
                <h6><i class="fas fa-anchor me-2 text-primary"></i>Master Berth / Dermaga</h6>
                <?php if ($this->Acl_model->has_permission($current_user->role_id, 'master/berth', 'can_create')): ?>
                <button class="btn btn-primary-custom" onclick="add_berth()"><i class="fas fa-plus me-2"></i>Add Berth</button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableBerth" class="table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Berth Code</th>
                                <th>Berth Name</th>
                                <th>Length (m)</th>
                                <th>Max Draft (m)</th>
                                <th>Status</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modal_form" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Berth Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Berth Code <span class="text-danger">*</span></label>
                                <input name="berth_code" placeholder="e.g., B1" class="form-control" type="text">
                                <span class="help-block text-danger small"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Berth Name <span class="text-danger">*</span></label>
                                <input name="berth_name" placeholder="e.g., Berth 1 KCN" class="form-control" type="text">
                                <span class="help-block text-danger small"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Length (Meters)</label>
                                <input name="length" placeholder="250" class="form-control" type="number" step="0.01">
                                <span class="help-block text-danger small"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Max Draft (Meters)</label>
                                <input name="draft_max" placeholder="12" class="form-control" type="number" step="0.1">
                                <span class="help-block text-danger small"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="is_active" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Coordinates (GeoJSON/Points)</label>
                                <textarea name="coordinate_polygon" id="coordinate_polygon" class="form-control" rows="3" readonly placeholder="Click on map to set coordinates"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <label class="form-label d-flex justify-content-between">
                                Berth Location Map 
                                <small class="text-muted">Click on map to mark location</small>
                            </label>
                            <div id="berthMap" style="height: 450px; border-radius: var(--radius); border: 1px solid var(--border-color);"></div>
                            <div class="mt-2 text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearMap()"><i class="fas fa-trash me-1"></i>Clear Points</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary-custom">Save Berth</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
var save_method;
var table;
var map, marker;
var points = [];

$(document).ready(function() {
    table = $('#tableBerth').DataTable({ 
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('master/berth/ajax_list')?>",
            "type": "POST"
        },
        "columnDefs": [
        { 
            "targets": [ 0, -1 ],
            "orderable": false,
        },
        ],
    });

    // Initialize Map only when modal is shown to avoid size issues
    $('#modal_form').on('shown.bs.modal', function() {
        if (!map) {
            map = L.map('berthMap').setView([<?= isset($app_settings['map_lat']) ? $app_settings['map_lat'] : '-6.0920' ?>, <?= isset($app_settings['map_lng']) ? $app_settings['map_lng'] : '106.9530' ?>], 16);
            
            // Using Google Satellite Hybrid for better precision
            L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains:['mt0','mt1','mt2','mt3'],
                attribution: '© Google Maps'
            }).addTo(map);

            map.on('click', function(e) {
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker(e.latlng).addTo(map);
                $('#coordinate_polygon').val(JSON.stringify(e.latlng));
            });
        } else {
            map.invalidateSize();
        }
    });
});

function add_berth() {
    save_method = 'add';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    if (marker && map) map.removeLayer(marker);
    $('#modal_form').modal('show');
    $('.modal-title').text('Add New Berth');
}

function edit_berth(id) {
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();

    $.ajax({
        url : "<?php echo site_url('master/berth/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="berth_code"]').val(data.berth_code);
            $('[name="berth_name"]').val(data.berth_name);
            $('[name="length"]').val(data.length);
            $('[name="draft_max"]').val(data.draft_max);
            $('[name="coordinate_polygon"]').val(data.coordinate_polygon);
            $('[name="is_active"]').val(data.is_active);
            
            if (data.coordinate_polygon && map) {
                var coords = JSON.parse(data.coordinate_polygon);
                if (marker) map.removeLayer(marker);
                marker = L.marker([coords.lat, coords.lng]).addTo(map);
                map.setView([coords.lat, coords.lng], 16);
            }

            $('#modal_form').modal('show');
            $('.modal-title').text('Edit Berth');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Toast.fire({icon: 'error', title: 'Error fetching data'});
        }
    });
}

function clearMap() {
    if (marker && map) map.removeLayer(marker);
    $('#coordinate_polygon').val('');
}

function reload_table() {
    table.ajax.reload(null,false);
}

function save() {
    $('#btnSave').text('saving...');
    $('#btnSave').attr('disabled',true);
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('master/berth/ajax_add')?>";
    } else {
        url = "<?php echo site_url('master/berth/ajax_update')?>";
    }

    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status)
            {
                $('#modal_form').modal('hide');
                reload_table();
                Toast.fire({icon: 'success', title: 'Berth saved successfully'});
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
            }
            $('#btnSave').text('Save Berth');
            $('#btnSave').attr('disabled',false);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            Toast.fire({icon: 'error', title: 'Error adding / update data'});
            $('#btnSave').text('Save Berth');
            $('#btnSave').attr('disabled',false);
        }
    });
}

// Utility for confirmation
function confirmDelete(callback) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data dermaga ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

function delete_berth(id) {
    confirmDelete(function() {
        $.ajax({
            url : "<?php echo site_url('master/berth/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                reload_table();
                Toast.fire({icon: 'success', title: 'Dermaga berhasil dihapus'});
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                Toast.fire({icon: 'error', title: 'Gagal menghapus data'});
            }
        });
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
