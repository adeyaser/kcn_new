<div class="row g-4">
    <!-- Left Sidebar: Search -->
    <div class="col-xl-3 col-lg-4">
        <div class="card-custom">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-search-location me-2"></i>Trace & Track</h6>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-4">Input nomor kontainer untuk melacak riwayat pergerakan di terminal.</p>
                <div class="form-group mb-3">
                    <label class="form-label small fw-bold text-uppercase">Nomor Kontainer</label>
                    <input type="text" id="trace_cont_no" class="form-control form-control-lg fw-bold" placeholder="MSKU1234567" style="text-transform: uppercase;">
                </div>
                <button class="btn btn-primary w-100 btn-lg" type="button" onclick="traceContainer()">
                    <i class="fas fa-search me-2"></i>Cari Riwayat
                </button>
            </div>
        </div>

        <!-- Quick Info / Help -->
        <div class="card-custom mt-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="flex-shrink-0 bg-light-primary rounded-3 p-3">
                        <i class="fas fa-info-circle text-primary"></i>
                    </div>
                    <div>
                        <div class="small fw-bold">Bantuan Pelacakan</div>
                        <div class="text-muted extra-small">Pastikan nomor kontainer sesuai standar ISO (4 huruf, 7 angka).</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Content: Timeline Results -->
    <div class="col-xl-9 col-lg-8">
        <div id="trace_results" class="card-custom" style="display:none;">
            <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-history me-2 text-info"></i>Riwayat Pergerakan: <span id="display_cont_no" class="text-primary fw-bold"></span></h6>
                <button class="btn btn-sm btn-outline-secondary" onclick="window.print()"><i class="fas fa-print me-1"></i>Cetak</button>
            </div>
            <div class="card-body p-4">
                <div class="main-timeline" id="timeline_content">
                    <!-- Timeline items injected here -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.main-timeline { position: relative; padding-left: 45px; border-left: 2px solid var(--border); margin-left: 10px; }
.timeline-item { position: relative; margin-bottom: 30px; }
.timeline-point { position: absolute; left: -56px; width: 20px; height: 20px; border-radius: 50%; background: #ffffff; border: 4px solid var(--accent); box-shadow: 0 0 10px rgba(0, 86, 179, 0.2); z-index: 2; top: 10px; }
.timeline-card { background: #f8fafc; border: 1px solid var(--border); border-radius: 12px; padding: 20px; transition: all 0.3s; position: relative; }
.timeline-card:hover { transform: translateX(5px); border-color: var(--accent); background: #ffffff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
.timeline-card::before { content: ''; position: absolute; left: -10px; top: 15px; width: 0; height: 0; border-top: 10px solid transparent; border-bottom: 10px solid transparent; border-right: 10px solid var(--border); }
.extra-small { font-size: 11px; }
</style>

<?php ob_start(); ?>
<script>
function traceContainer() {
    var cont = $('#trace_cont_no').val();
    if(!cont) {
        Toast.fire({icon: 'warning', title: 'Masukkan nomor kontainer!'});
        return;
    }

    $.ajax({
        url: '<?= site_url("monitoring/trace/ajax_trace") ?>',
        type: 'GET',
        data: {container_no: cont},
        dataType: 'json',
        success: function(res) {
            if(res.status === 'success') {
                $('#display_cont_no').text(cont);
                renderTimeline(res);
                $('#trace_empty').hide();
                $('#trace_results').fadeIn();
                Toast.fire({icon: 'success', title: 'Data riwayat ditemukan'});
            } else {
                Toast.fire({icon: 'error', title: res.message});
                $('#trace_results').hide();
                $('#trace_empty').fadeIn();
            }
        },
        error: function() {
            Toast.fire({icon: 'error', title: 'Terjadi kesalahan sistem'});
        }
    });
}

function renderTimeline(data) {
    let html = '';
    
    // Combine and sort by date
    let events = [];
    if(data.manifest) {
        data.manifest.forEach(m => {
            events.push({date: m.planned_at || '-', type: 'MANIFEST', data: m});
        });
    }
    if(data.gate) {
        data.gate.forEach(g => {
            if(g.gate_in_time) events.push({date: g.gate_in_time, type: 'GATE_IN', data: g});
            if(g.gate_out_time) events.push({date: g.gate_out_time, type: 'GATE_OUT', data: g});
        });
    }
    if(data.tally) {
        data.tally.forEach(t => {
            events.push({date: t.activity_time, type: 'TALLY', data: t});
        });
    }
    
    events.sort((a, b) => new Date(a.date) - new Date(b.date)); // Oldest first (Planned -> Gate In -> Tally -> Gate Out)

    events.forEach(ev => {
        let icon = 'fa-truck';
        let color = 'text-primary';
        let title = '';
        let desc = '';
        let pointColor = '#3b82f6';

        if(ev.type === 'MANIFEST') {
            icon = 'fa-file-signature';
            color = 'text-warning';
            pointColor = '#f59e0b';
            title = 'Perencanaan / Manifest Dibuat';
            desc = `<div class="row">
                        <div class="col-md-6"><b>No. Request:</b> ${ev.data.request_no}</div>
                        <div class="col-md-6"><b>Kapal:</b> ${ev.data.vessel_name}</div>
                        <div class="col-12 mt-1 small text-muted">Tipe: ${ev.data.request_type} | Voyage: ${ev.data.voyage_in}</div>
                    </div>`;
        } else if(ev.type === 'GATE_IN') {
            icon = 'fa-sign-in-alt';
            color = 'text-success';
            pointColor = '#10b981';
            title = 'Gate In (Masuk Terminal)';
            desc = `<div class="row">
                        <div class="col-md-6"><b>No. Polisi:</b> ${ev.data.police_number}</div>
                        <div class="col-md-6"><b>Supir:</b> ${ev.data.driver_name}</div>
                        <div class="col-12 mt-1 small text-muted">Aktivitas: ${ev.data.activity_type} | Gate: ${ev.data.gate_no}</div>
                    </div>`;
        } else if(ev.type === 'GATE_OUT') {
            icon = 'fa-sign-out-alt';
            color = 'text-danger';
            pointColor = '#ef4444';
            title = 'Gate Out (Keluar Terminal)';
            desc = `Kontainer telah keluar terminal melalui Gate: ${ev.data.gate_no}`;
        } else {
            icon = 'fa-box';
            color = 'text-info';
            pointColor = '#3b82f6';
            title = `Aktivitas Tally (${ev.data.activity_type})`;
            desc = `<div class="row">
                        <div class="col-md-6"><b>Alat:</b> ${ev.data.equipment_code || '<span class="text-muted italic">Tidak tercatat</span>'}</div>
                        <div class="col-md-6"><b>Lokasi:</b> ${ev.data.bay || '-'}-${ev.data.row || '-'}-${ev.data.tier || '-'}</div>
                        <div class="col-12 mt-1 small text-muted">Kapal: ${ev.data.vessel_name || 'Operasi Lapangan (Yard)'}</div>
                    </div>`;
        }

        html += `
            <div class="timeline-item">
                <div class="timeline-point" style="border-color: ${pointColor}"></div>
                <div class="timeline-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-white text-dark shadow-sm px-3 py-2 border border-secondary">
                            <i class="far fa-clock me-2 text-primary"></i>${ev.date}
                        </span>
                        <div class="bg-white rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                            <i class="fas ${icon} ${color} fa-lg"></i>
                        </div>
                    </div>
                    <h6 class="text-dark fw-bold mb-2">${title}</h6>
                    <div class="text-muted small">${desc}</div>
                </div>
            </div>
        `;
    });

    $('#timeline_content').html(html);
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
