<div class="row">
    <div class="col-md-3">
        <!-- Navigation Menu -->
        <div class="card-custom mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0 text-white"><i class="fas fa-book me-2"></i>Daftar Isi</h6>
            </div>
            <div class="card-body p-3">
                <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link text-start active" id="v-pills-end-to-end-tab" data-bs-toggle="pill" data-bs-target="#v-pills-end-to-end" type="button" role="tab" aria-controls="v-pills-end-to-end" aria-selected="true">
                        <i class="fas fa-sitemap me-2"></i>1. Panduan End-to-End
                    </button>
                    <button class="nav-link text-start" id="v-pills-sop-resmi-tab" data-bs-toggle="pill" data-bs-target="#v-pills-sop-resmi" type="button" role="tab" aria-controls="v-pills-sop-resmi" aria-selected="false">
                        <i class="fas fa-file-contract me-2"></i>2. SOP Resmi Perusahaan
                    </button>
                    <button class="nav-link text-start" id="v-pills-aplikasi-tab" data-bs-toggle="pill" data-bs-target="#v-pills-aplikasi" type="button" role="tab" aria-controls="v-pills-aplikasi" aria-selected="false">
                        <i class="fas fa-laptop-code me-2"></i>3. SOP Detail Menu Aplikasi
                    </button>
                </div>
            </div>
        </div>

        <div class="alert alert-info border-0 shadow-sm">
            <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>Informasi</h6>
            <p class="small mb-0">Dokumen ini merupakan panduan resmi standar operasional untuk aplikasi Terminal Operating System KCN. Harap mematuhi alur yang telah ditetapkan.</p>
        </div>
    </div>

    <div class="col-md-9">
        <!-- Content Area -->
        <div class="tab-content" id="v-pills-tabContent">
            
            <!-- 1. END-TO-END MANUAL -->
            <div class="tab-pane fade show active" id="v-pills-end-to-end" role="tabpanel" aria-labelledby="v-pills-end-to-end-tab">
                <div class="doc-card">
                    <div class="text-center mb-5 border-bottom pb-4">
                        <h2 class="fw-bold text-primary mb-2">KCN Terminal Operating System (TOS)</h2>
                        <h4 class="text-secondary mt-0">End-to-End User Manual</h4>
                    </div>

                    <div class="doc-highlight">
                        <strong>Deskripsi:</strong> Dokumen ini adalah panduan komprehensif (*Master Manual*) untuk menggunakan seluruh modul di dalam sistem KCN TOS dari hulu (penyiapan data) hingga hilir (pelaporan).
                    </div>

                    <h4 class="doc-section-title">1. Modul Setup & Master Data</h4>
                    <p>Sebelum sistem dapat berjalan, data dasar pelabuhan harus dimasukkan.</p>
                    <ul>
                        <li><strong>Setup (Hak Akses):</strong> Buka menu <code>Setup > Roles</code> untuk membuat peran pengguna. Buka <code>Setup > Permissions</code> untuk mengatur akses View/Create/Edit/Delete.</li>
                        <li><strong>Master Vessel (Kapal):</strong> Buka <code>Master Data > Vessels</code>. Input nama kapal, bendera, dan spesifikasi <em>Vessel Profile</em> (jumlah Bay, Row, Tier).</li>
                        <li><strong>Vessel Schedule (Jadwal):</strong> Buka <code>Master Data > Schedules</code>. Pilih kapal, masukkan ETA, ETD, dan POD.</li>
                        <li><strong>Master Yard (Lapangan):</strong> Buka <code>Master Data > Yard Blocks</code>. Daftarkan kapasitas maksimal blok penumpukan.</li>
                    </ul>

                    <h4 class="doc-section-title">2. Modul Planning (Perencanaan)</h4>
                    <p>Tahap krusial di mana Planner menentukan posisi kontainer sebelum operasi dimulai.</p>
                    <ul>
                        <li><strong>Request Planning:</strong> Buka <code>Planning > Request</code>. Tentukan Operation Type (DIS/LOD/VSL), lalu unggah file Manifest atau Load List.</li>
                        <li><strong>Approval Planning:</strong> Buka <code>Planning > Approval</code>. Manajer menyetujui request agar bisa dilanjutkan ke pemetaan.</li>
                        <li><strong>Vessel Planning (Stowage Plan):</strong> Buka <code>Planning > Vessel Planning</code>. Drag & Drop kontainer ke slot kapal untuk menyeimbangkan stabilitas (Stowage).</li>
                        <li><strong>Yard Planning (Alokasi Lapangan):</strong> Buka <code>Planning > Yard Planning</code>. Alokasikan kontainer ke blok-blok lapangan agar alat angkat mengetahui tujuannya.</li>
                        <li><strong>TCA Planning:</strong> Buka <code>Planning > TCA</code>. Atur jumlah truk internal yang didedikasikan untuk operasi kapal.</li>
                    </ul>

                    <h4 class="doc-section-title">3. Modul Operations (Eksekusi Lapangan)</h4>
                    <p>Aktivitas fisik yang dilakukan secara real-time oleh petugas menggunakan perangkat <em>mobile/tablet</em>.</p>
                    <ul>
                        <li><strong>Gate In / Gate Out:</strong> Gerbang masuk/keluar. Scan dokumen untuk memvalidasi dan memberikan tiket berisi arahan blok tujuan (*Routing Slip*).</li>
                        <li><strong>Lift On / Lift Off:</strong> Digunakan oleh Operator Reach Stacker di lapangan untuk mengkonfirmasi bongkar muat kontainer dari/ke atas truk. Menentukan koordinat aktual di Yard.</li>
                        <li><strong>Tally (Stevedoring):</strong> Digunakan oleh Tallyman di sisi kapal. Klik <strong>[Discharge]</strong> saat kontainer turun dari kapal, klik <strong>[Load]</strong> saat kontainer diangkat ke atas kapal.</li>
                    </ul>

                    <h4 class="doc-section-title">4. Modul Monitoring & Report</h4>
                    <ul>
                        <li><strong>Trace & Track:</strong> Lacak riwayat spesifik nomor kontainer (Waktu Gate In -> Waktu Lift -> Waktu Load).</li>
                        <li><strong>Vessel Monitoring:</strong> Dashboard real-time menampilkan persentase progres produktivitas bongkar/muat kapal.</li>
                        <li><strong>Daily Print:</strong> Buka <code>Reports > Daily Print</code> untuk mencetak laporan utilitas alat, kepadatan yard, dan produktivitas crane (BSH).</li>
                    </ul>
                </div>
            </div>

            <!-- 2. SOP RESMI -->
            <div class="tab-pane fade" id="v-pills-sop-resmi" role="tabpanel" aria-labelledby="v-pills-sop-resmi-tab">
                <div class="doc-card">
                    <div class="text-center mb-5 border-bottom pb-4">
                        <h2 class="fw-bold text-primary mb-2">STANDAR OPERASIONAL PROSEDUR (SOP)</h2>
                        <h4 class="text-secondary mt-0">Terminal Operating System (TOS) KCN</h4>
                        <div class="d-flex justify-content-center gap-4 mt-3 text-muted small">
                            <span><strong>No. Dokumen:</strong> SOP-KCN-OP-01</span>
                            <span><strong>Revisi:</strong> 00</span>
                            <span><strong>Departemen:</strong> Operasional & IT</span>
                        </div>
                    </div>

                    <h4 class="doc-section-title">1. TUJUAN & RUANG LINGKUP</h4>
                    <p>Memberikan panduan standar, terstruktur, dan terintegrasi dalam menggunakan Terminal Operating System (TOS) KCN untuk memastikan efisiensi, akurasi, dan keterlacakan (traceability). Ruang lingkup mencakup Pra-Kedatangan (Master Data), Perencanaan (Planning), Eksekusi Fisik Lapangan (Operations), hingga Pengawasan (Monitoring & Reports).</p>

                    <h4 class="doc-section-title">2. SIKLUS FISIK OPERASIONAL BERDASARKAN TIPE LAYANAN</h4>
                    
                    <div class="doc-highlight">
                        <strong>A. Siklus Bongkar (Discharge Only - DIS)</strong>
                    </div>
                    <ol>
                        <li><strong>Dermaga:</strong> Crane kapal mengangkat kontainer dari palka/dek.</li>
                        <li><strong>Validasi:</strong> Tallyman memverifikasi fisik dan mencatat bongkaran di sistem TOS (Status: <em>Discharged</em>).</li>
                        <li><strong>Transportasi:</strong> Kontainer diletakkan di atas sasis truk internal dermaga (Head Truck).</li>
                        <li><strong>Perjalanan:</strong> Truk melaju dari sisi kapal menuju blok lapangan penumpukan (Yard) khusus impor.</li>
                        <li><strong>Penyimpanan:</strong> Operator Alat (RTG/RS) mengangkat kontainer dari truk dan menumpuknya di slot yang telah dialokasikan (Status: <em>Lifted Off</em>).</li>
                        <li><strong>Rotasi:</strong> Truk kembali ke sisi kapal dalam keadaan kosong untuk mengambil bongkaran berikutnya.</li>
                    </ol>

                    <div class="doc-highlight mt-4">
                        <strong>B. Siklus Muat (Load Only - LOD)</strong>
                    </div>
                    <ol>
                        <li><strong>Persiapan:</strong> Kontainer muat dipastikan telah ditumpuk di Yard (Pre-stacked).</li>
                        <li><strong>Transportasi:</strong> Truk dermaga kosong tiba di blok penumpukan ekspor.</li>
                        <li><strong>Pengambilan:</strong> Operator Alat mengangkat kontainer dari tumpukan dan menaruhnya ke atas truk (Status: <em>Lifted On</em>).</li>
                        <li><strong>Perjalanan:</strong> Truk melaju meninggalkan Yard menuju sisi kapal (Dermaga).</li>
                        <li><strong>Dermaga:</strong> Crane kapal mengangkat kontainer dari truk ke atas kapal.</li>
                        <li><strong>Validasi:</strong> Tallyman memverifikasi dan mencatat muatan di sistem TOS (Status: <em>Loaded</em>).</li>
                    </ol>

                    <div class="doc-highlight mt-4">
                        <strong>C. Siklus Bongkar Muat Sekaligus (Combined - VSL) / Double Banking</strong>
                        <p class="mb-0 mt-2 text-muted small"><i class="fas fa-exclamation-triangle me-1"></i>Kombinasi efisiensi tertinggi (meminimalkan perjalanan truk kosong).</p>
                    </div>
                    <ol>
                        <li><strong>Bongkar:</strong> Crane kapal membongkar kontainer ke truk pertama. Tallyman mencatat <em>Discharge</em>.</li>
                        <li><strong>Drop-Off (Yard):</strong> Truk pertama melaju ke blok Impor. Operator menaruh kontainer (<em>Lift Off</em>).</li>
                        <li><strong>Pick-Up (Yard):</strong> Alih-alih kembali kosong, truk pertama langsung melaju ke blok Ekspor terdekat.</li>
                        <li><strong>Muat (Yard):</strong> Operator menaikkan kontainer ekspor ke truk tersebut (<em>Lift On</em>).</li>
                        <li><strong>Muat (Kapal):</strong> Truk kembali ke sisi kapal membawa muatan. Crane kapal mengangkatnya ke atas dek, dan Tallyman mencatat <em>Load</em>.</li>
                        <li><strong>Rotasi:</strong> Truk kini kembali kosong di sisi kapal dan siap menerima bongkaran berikutnya.</li>
                    </ol>
                </div>
            </div>

            <!-- 3. SOP APLIKASI -->
            <div class="tab-pane fade" id="v-pills-aplikasi" role="tabpanel" aria-labelledby="v-pills-aplikasi-tab">
                <div class="doc-card">
                    <div class="text-center mb-5 border-bottom pb-4">
                        <h2 class="fw-bold text-primary mb-2">SOP DETAIL NAVIGASI APLIKASI</h2>
                        <h4 class="text-secondary mt-0">Integrasi Alur Fisik dan Navigasi Sistem KCN</h4>
                    </div>

                    <div class="doc-highlight">
                        <strong>Panduan Kronologis:</strong> Dokumen ini merangkaikan alur operasional pelabuhan secara kronologis dengan langkah-langkah spesifik (klik-demi-klik) di dalam aplikasi KCN TOS.
                    </div>

                    <h4 class="doc-section-title">SKENARIO 1: BONGKAR (DISCHARGE ONLY - DIS)</h4>
                    <ul>
                        <li><strong>Tahap Planning (Menu: Planning > Request):</strong> Pilih Operation Type <code>DIS (Bongkar)</code>. Unggah BAPLIE bongkaran. Submit.</li>
                        <li><strong>Tahap Approval (Menu: Planning > Approval):</strong> Manajer klik Approve.</li>
                        <li><strong>Tahap Pemetaan (Menu: Planning > Yard):</strong> Alokasikan daftar kontainer <em>Unplanned</em> ke blok lapangan impor. Save.</li>
                        <li><strong>Tahap Alokasi Truk (Menu: Operations > TCA Planning):</strong> <em>(Langkah Wajib)</em> Tugaskan armada truk internal (Head Truck) yang akan bekerja mengangkut barang dari kapal ke lapangan.</li>
                        <li><strong>Eksekusi Dermaga (Menu: Operations > Tally):</strong> Saat crane menurunkan barang, Tallyman klik <strong>[Discharge]</strong>.</li>
                        <li><strong>Eksekusi Yard (Menu: Operations > Lift):</strong> Saat truk internal tiba di yard, Operator Lift klik <strong>[Lift Off]</strong> untuk menumpuk kontainer ke tanah.</li>
                        <li><strong>Pengambilan oleh Eksternal (Menu: Operations > Gate & Lift):</strong> Truk luar pelanggan masuk <strong>[Gate In]</strong>, menuju yard untuk mengambil barang <strong>[Lift On]</strong>, dan keluar pelabuhan membawa muatan <strong>[Gate Out]</strong>.</li>
                    </ul>

                    <h4 class="doc-section-title">SKENARIO 2: MUAT (LOAD ONLY - LOD)</h4>
                    <ul>
                        <li><strong>Penerimaan Eksternal (Menu: Operations > Gate & Lift):</strong> Truk luar masuk membawa barang ekspor <strong>[Gate In]</strong>, menuju yard menurunkan muatan <strong>[Lift Off]</strong>, dan keluar pelabuhan kosong <strong>[Gate Out]</strong>.</li>
                        <li><strong>Tahap Planning (Menu: Planning > Request):</strong> Pilih Operation Type <code>LOD (Muat)</code>. Unggah Load List. Submit & Approve.</li>
                        <li><strong>Tahap Stowage (Menu: Planning > Vessel):</strong> Planner drag-and-drop kontainer ekspor ke dalam slot kapal (pastikan barang berat di bawah). Save.</li>
                        <li><strong>Tahap Alokasi Truk (Menu: Operations > TCA Planning):</strong> <em>(Langkah Wajib)</em> Tugaskan armada truk internal yang akan membawa barang dari lapangan menuju ke sisi kapal.</li>
                        <li><strong>Pengambilan Yard (Menu: Operations > Lift):</strong> Truk internal tiba, Operator Lift klik <strong>[Lift On]</strong> untuk menaikkan kontainer ke truk internal.</li>
                        <li><strong>Eksekusi Dermaga (Menu: Operations > Tally):</strong> Truk di pinggir kapal, crane mengangkat barang, Tallyman klik <strong>[Load]</strong>.</li>
                    </ul>

                    <h4 class="doc-section-title">SKENARIO 3: BONGKAR MUAT (COMBINED - VSL)</h4>
                    <ul>
                        <li><strong>Tahap Planning (Menu: Planning > Request):</strong> Pilih <code>VSL (Bongkar Muat)</code>. Unggah Manifest Bongkar DAN Load List. Submit & Approve.</li>
                        <li><strong>Pemetaan Sinkron:</strong> Prioritaskan merencanakan/mengosongkan slot bongkaran di <code>Planning > Vessel</code> terlebih dahulu, lalu isi kekosongannya dengan rencana pemuatan. Di <code>Planning > Yard</code>, pisahkan jalur blok Impor dan Ekspor secara tegas.</li>
                        <li><strong>Tahap Alokasi Truk (Menu: Operations > TCA Planning):</strong> <em>(Langkah Wajib)</em> Tugaskan armada truk internal untuk menjalankan siklus <em>Double Banking</em> (memastikan armada tidak berjalan kosong).</li>
                        <li><strong>Siklus Berkelanjutan (Double Banking):</strong>
                            <ol>
                                <li>Menu <code>Tally</code>: Klik <strong>[Discharge]</strong>. Truk jalan ke Blok Impor.</li>
                                <li>Menu <code>Lift</code>: Di Blok Impor, klik <strong>[Lift Off]</strong>. Truk jalan ke Blok Ekspor terdekat.</li>
                                <li>Menu <code>Lift</code>: Di Blok Ekspor, klik <strong>[Lift On]</strong>. Truk kembali ke Kapal.</li>
                                <li>Menu <code>Tally</code>: Di Kapal, klik <strong>[Load]</strong>. Siklus berulang ke langkah 1.</li>
                            </ol>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
