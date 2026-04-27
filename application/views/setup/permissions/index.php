<div class="row g-4">
    <div class="col-xl-10 mx-auto">
        <div class="card-custom shadow-sm">
            <div class="card-header border-bottom border-secondary p-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-user-lock me-2 text-primary"></i>Pengaturan Izin Akses</h5>
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <select id="roleSelector" class="form-select border-primary border-opacity-25 bg-primary bg-opacity-5" onchange="loadPermissions()">
                            <option value="">-- Pilih Role / Jabatan --</option>
                            <?php foreach($roles as $r): ?>
                                <option value="<?= $r->id ?>"><?= $r->role_name ?> (<?= $r->role_code ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0" id="permissionsPanel" style="display:none;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Menu & Fitur</th>
                                <th class="text-center">Lihat (View)</th>
                                <th class="text-center">Tambah (Create)</th>
                                <th class="text-center">Ubah (Edit)</th>
                                <th class="text-center">Hapus (Delete)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($menus as $m): ?>
                                <!-- Parent Menu -->
                                <tr class="bg-light bg-opacity-50">
                                    <td class="ps-4 fw-bold text-dark"><i class="<?= $m->menu_icon ?> me-2 opacity-50"></i><?= $m->menu_name ?></td>
                                    <td class="text-center"><?= renderCheckbox($m->id, 'can_view') ?></td>
                                    <td class="text-center"><?= renderCheckbox($m->id, 'can_create') ?></td>
                                    <td class="text-center"><?= renderCheckbox($m->id, 'can_edit') ?></td>
                                    <td class="text-center"><?= renderCheckbox($m->id, 'can_delete') ?></td>
                                </tr>
                                <!-- Child Menus -->
                                <?php foreach($m->children as $c): ?>
                                    <tr>
                                        <td class="ps-5 text-muted small"><span class="me-2">└</span><?= $c->menu_name ?></td>
                                        <td class="text-center"><?= renderCheckbox($c->id, 'can_view') ?></td>
                                        <td class="text-center"><?= renderCheckbox($c->id, 'can_create') ?></td>
                                        <td class="text-center"><?= renderCheckbox($c->id, 'can_edit') ?></td>
                                        <td class="text-center"><?= renderCheckbox($c->id, 'can_delete') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Empty State -->
            <div id="emptyPanel" class="text-center py-5">
                <i class="fas fa-shield-alt fa-4x text-muted mb-3 opacity-20"></i>
                <p class="text-muted">Silakan pilih Role terlebih dahulu untuk mengatur izin akses.</p>
            </div>
        </div>
    </div>
</div>

<?php 
function renderCheckbox($menu_id, $action) {
    return '<div class="form-check form-switch d-inline-block">
                <input class="form-check-input perm-check" type="checkbox" 
                    data-menu="'.$menu_id.'" data-action="'.$action.'" 
                    onchange="togglePerm(this)">
            </div>';
}
?>

<?php ob_start(); ?>
<script>
function loadPermissions() {
    const roleId = $('#roleSelector').val();
    if(!roleId) {
        $('#permissionsPanel').hide();
        $('#emptyPanel').show();
        return;
    }

    $('.perm-check').prop('checked', false);
    
    $.ajax({
        url: "<?= site_url('setup/permissions/get_role_permissions/') ?>" + roleId,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            data.forEach(p => {
                if(p.can_view == 1) $(`.perm-check[data-menu="${p.menu_id}"][data-action="can_view"]`).prop('checked', true);
                if(p.can_create == 1) $(`.perm-check[data-menu="${p.menu_id}"][data-action="can_create"]`).prop('checked', true);
                if(p.can_edit == 1) $(`.perm-check[data-menu="${p.menu_id}"][data-action="can_edit"]`).prop('checked', true);
                if(p.can_delete == 1) $(`.perm-check[data-menu="${p.menu_id}"][data-action="can_delete"]`).prop('checked', true);
            });
            $('#emptyPanel').hide();
            $('#permissionsPanel').fadeIn();
        }
    });
}

function togglePerm(el) {
    const roleId = $('#roleSelector').val();
    const menuId = $(el).data('menu');
    const action = $(el).data('action');
    const value = $(el).is(':checked') ? 1 : 0;

    $.ajax({
        url: "<?= site_url('setup/permissions/update_permission') ?>",
        type: "POST",
        data: {
            role_id: roleId,
            menu_id: menuId,
            action: action,
            value: value
        },
        dataType: "JSON",
        success: function(res) {
            // Toast notification could be added here
        }
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
