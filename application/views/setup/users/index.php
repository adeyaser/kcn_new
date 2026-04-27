<div class="row g-4">
    <div class="col-12 text-end">
        <button class="btn btn-primary-custom" onclick="addUser()">
            <i class="fas fa-user-plus me-2"></i>Tambah Pengguna Baru
        </button>
    </div>
    
    <div class="col-12">
        <div class="card-custom shadow-sm">
            <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center p-4">
                <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-users me-2 text-primary"></i>Manajemen Pengguna</h5>
                <span class="badge bg-light text-muted border px-2">Total Akun: <?= count($roles) ?> Roles</span>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tableUsers" style="width:100%">
                        <thead class="bg-light">
                            <tr>
                                <th width="50">ID</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal User -->
<div class="modal fade" id="modalUser" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formUser">
                    <input type="hidden" name="id" id="userId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Username</label>
                            <input type="text" name="username" id="username" class="form-control border-light bg-light" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                            <input type="text" name="full_name" id="full_name" class="form-control border-light bg-light" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Email</label>
                            <input type="email" name="email" id="email" class="form-control border-light bg-light" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Role / Jabatan</label>
                            <select name="role_id" id="role_id" class="form-select border-light bg-light" required>
                                <?php foreach($roles as $r): ?>
                                    <option value="<?= $r->id ?>"><?= $r->role_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Password</label>
                            <input type="password" name="password" id="password" class="form-control border-light bg-light">
                            <small class="text-muted" id="passHint">Kosongkan jika tidak ingin mengubah password</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Status Akun</label>
                            <select name="is_active" id="is_active" class="form-select border-light bg-light">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary fw-bold px-4" onclick="saveUser()">Simpan Pengguna</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script>
let table;
$(document).ready(function() {
    table = $('#tableUsers').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= site_url('setup/users/ajax_list') ?>",
            type: "POST"
        },
        columnDefs: [
            { targets: [6], orderable: false }
        ]
    });
});

function addUser() {
    $('#formUser')[0].reset();
    $('#userId').val('');
    $('#modalTitle').text('Tambah Pengguna Baru');
    $('#passHint').hide();
    $('#modalUser').modal('show');
}

function editUser(id) {
    $('#formUser')[0].reset();
    $.ajax({
        url: "<?= site_url('setup/users/ajax_edit/') ?>" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('#userId').val(data.id);
            $('#username').val(data.username);
            $('#full_name').val(data.full_name);
            $('#email').val(data.email);
            $('#role_id').val(data.role_id);
            $('#is_active').val(data.is_active);
            
            $('#modalTitle').text('Ubah Pengguna');
            $('#passHint').show();
            $('#modalUser').modal('show');
        }
    });
}

function saveUser() {
    $.ajax({
        url: "<?= site_url('setup/users/ajax_save') ?>",
        type: "POST",
        data: $('#formUser').serialize(),
        dataType: "JSON",
        success: function(data) {
            if(data.status) {
                $('#modalUser').modal('hide');
                table.ajax.reload();
                Swal.fire('Berhasil', data.message, 'success');
            }
        }
    });
}

function deleteUser(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Akun ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= site_url('setup/users/ajax_delete/') ?>" + id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    table.ajax.reload();
                    Swal.fire('Terhapus', data.message, 'success');
                }
            });
        }
    });
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
