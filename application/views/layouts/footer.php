    </div><!-- /.content-wrapper -->
</div><!-- /.main-content -->

<script>
const BASE_URL = '<?= base_url() ?>';
const SITE_URL = '<?= site_url() ?>';

// Page loader
window.addEventListener('load', () => {
    document.getElementById('pageLoader').classList.add('hide');
});

// Sidebar toggle
document.getElementById('sidebarToggle').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('mainContent').classList.toggle('expanded');
});
document.getElementById('sidebarClose')?.addEventListener('click', () => {
    document.getElementById('sidebar').classList.remove('mobile-open');
});

// Sidebar dropdown
document.querySelectorAll('.nav-group-toggle').forEach(el => {
    el.addEventListener('click', (e) => {
        e.preventDefault();
        el.closest('.nav-group').classList.toggle('open');
    });
});

// SweetAlert helpers
const Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
    background: '#1e293b', color: '#e2e8f0',
    didOpen: (toast) => { toast.onmouseenter = Swal.stopTimer; toast.onmouseleave = Swal.resumeTimer; }
});

function confirmDelete(url, callback) {
    Swal.fire({
        title: 'Konfirmasi Hapus', text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
        background: '#1e293b', color: '#e2e8f0'
    }).then((result) => {
        if (result.isConfirmed && callback) callback();
    });
}
</script>
<?php if(isset($extra_js)): foreach($extra_js as $js): ?>
<script src="<?= $js ?>"></script>
<?php endforeach; endif; ?>
<?php if(isset($page_js)): ?>
<?= $page_js ?>
<?php endif; ?>
</body>
</html>
