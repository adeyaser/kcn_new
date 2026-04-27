<div class="row g-4">
    <div class="col-xl-8 mx-auto">
        <div class="card-custom shadow-sm">
            <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center p-4">
                <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-user-tag me-2 text-primary"></i>Manajemen Hak Akses (Roles)</h5>
                <button class="btn btn-primary-custom btn-sm" onclick="addRole()">
                    <i class="fas fa-plus me-1"></i>Tambah Role
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Kode Role</th>
                                <th>Nama Role</th>
                                <th>Status</th>
                                <th width="120" class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($roles as $r): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-primary"><?= $r->role_code ?></td>
                                <td class="fw-medium"><?= $r->role_name ?></td>
                                <td>
                                    <?= $r->is_active ? '<span class="badge bg-success-glow text-success border border-success border-opacity-10 px-3">AKTIF</span>' : '<span class="badge bg-danger-glow text-danger border border-danger border-opacity-10 px-3">NONAKTIF</span>' ?>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" onclick='editRole(<?= json_encode($r) ?>)'><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteRole(<?= $r->id ?>)"><i class="fas fa-trash"></i></button>
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

<!-- Modal Role -->
<div class="modal fade" id="modalRole" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">Tambah Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formRole">
                    <input type="hidden" name="id" id="roleId">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Kode Role (Singkatan)</label>
                        <input type="text" name="role_code" id="roleCode" class="form-control border-light bg-light" placeholder="Contoh: ADM" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Nama Role</label>
                        <input type="text" name="role_name" id="roleName" class="form-control border-light bg-light" placeholder="Contoh: Administrator" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-muted">Status</label>
                        <select name="is_active" id="roleStatus" class="form-select border-light bg-light">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary fw-bold px-4" onclick="saveRole()">Simpan Role</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
function addRole() {
    $('#formRole')[0].reset();
    $('#roleId').val('');
    $('#modalTitle').text('Tambah Role Baru');
    $('#modalRole').modal('show');
}

function editRole(data) {
    $('#roleId').val(data.id);
    $('#roleCode').val(data.role_code);
    $('#roleName').val(data.role_name);
    $('#roleStatus').val(data.is_active);
    $('#modalTitle').text('Ubah Role');
    $('#modalRole').modal('show');
}

function saveRole() {
    $.ajax({
        url: "<?= site_url('setup/roles/ajax_save') ?>",
        type: "POST",
        data: $('#formRole').serialize(),
        dataType: "JSON",
        success: function(res) {
            if(res.status) {
                location.reload();
            }
        }
    });
}

function deleteRole(id) {
    Swal.fire({
        title: 'Hapus Role?',
        text: "Role yang dihapus mungkin memengaruhi akses pengguna!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= site_url('setup/roles/ajax_delete/') ?>" + id,
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
