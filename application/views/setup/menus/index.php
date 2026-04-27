<div class="row g-4">
    <div class="col-xl-10 mx-auto">
        <div class="card-custom shadow-sm">
            <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center p-4">
                <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-list me-2 text-primary"></i>Manajemen Menu Sistem</h5>
                <button class="btn btn-primary-custom btn-sm" onclick="addMenu()">
                    <i class="fas fa-plus me-1"></i>Tambah Menu
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nama Menu</th>
                                <th>URL / Path</th>
                                <th>Icon</th>
                                <th>Parent</th>
                                <th class="text-center">Urutan</th>
                                <th width="120" class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($all_menus as $m): ?>
                            <tr>
                                <td class="ps-4">
                                    <?php if($m->parent_id > 0): ?>
                                        <span class="text-muted ms-3">└</span> 
                                    <?php endif; ?>
                                    <span class="fw-medium"><?= $m->menu_name ?></span>
                                </td>
                                <td class="text-muted small"><code><?= $m->menu_url ?></code></td>
                                <td class="text-center"><i class="<?= $m->menu_icon ?> text-primary"></i></td>
                                <td>
                                    <?php 
                                        $p_name = '-';
                                        foreach($all_menus as $p) if($p->id == $m->parent_id) $p_name = $p->menu_name;
                                        echo $p_name;
                                    ?>
                                </td>
                                <td class="text-center"><span class="badge bg-light text-dark border"><?= $m->menu_order ?></span></td>
                                <td class="text-center pe-4">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" onclick='editMenu(<?= json_encode($m) ?>)'><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteMenu(<?= $m->id ?>)"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Menu -->
<div class="modal fade" id="modalMenu" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">Tambah Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formMenu">
                    <input type="hidden" name="id" id="menuId">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nama Menu</label>
                        <input type="text" name="menu_name" id="menuName" class="form-control border-light bg-light" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">URL / Controller Path</label>
                        <input type="text" name="menu_url" id="menuUrl" class="form-control border-light bg-light" placeholder="setup/users" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">FontAwesome Icon</label>
                            <input type="text" name="menu_icon" id="menuIcon" class="form-control border-light bg-light" placeholder="fas fa-user">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">Urutan</label>
                            <input type="number" name="menu_order" id="menuOrder" class="form-control border-light bg-light" value="1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Menu Induk (Parent)</label>
                        <select name="parent_id" id="menuParent" class="form-select border-light bg-light">
                            <option value="0">-- Tidak Ada (Menu Utama) --</option>
                            <?php foreach($all_menus as $p): if($p->parent_id == 0): ?>
                                <option value="<?= $p->id ?>"><?= $p->menu_name ?></option>
                            <?php endif; endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted">Status</label>
                        <select name="is_active" id="menuStatus" class="form-select border-light bg-light">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary fw-bold px-4" onclick="saveMenu()">Simpan Menu</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
function addMenu() {
    $('#formMenu')[0].reset();
    $('#menuId').val('');
    $('#modalTitle').text('Tambah Menu Baru');
    $('#modalMenu').modal('show');
}

function editMenu(data) {
    $('#menuId').val(data.id);
    $('#menuName').val(data.menu_name);
    $('#menuUrl').val(data.menu_url);
    $('#menuIcon').val(data.menu_icon);
    $('#menuOrder').val(data.menu_order);
    $('#menuParent').val(data.parent_id);
    $('#menuStatus').val(data.is_active);
    $('#modalTitle').text('Ubah Menu');
    $('#modalMenu').modal('show');
}

function saveMenu() {
    $.ajax({
        url: "<?= site_url('setup/menus/ajax_save') ?>",
        type: "POST",
        data: $('#formMenu').serialize(),
        dataType: "JSON",
        success: function(res) {
            if(res.status) {
                location.reload();
            }
        }
    });
}

function deleteMenu(id) {
    Swal.fire({
        title: 'Hapus Menu?',
        text: "Menu yang dihapus akan hilang dari navigasi!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= site_url('setup/menus/ajax_delete/') ?>" + id,
                type: "POST",
                dataType: "JSON",
                success: function(res) {
                    location.reload();
                }
            });
        }
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
