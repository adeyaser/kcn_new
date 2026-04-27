<!DOCTYPE html>
<html>
<head>
    <title>SP2 - <?= $sp2_no ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo-section h1 {
            margin: 0;
            font-size: 24px;
            color: #1e3a8a;
        }
        .doc-title {
            text-align: right;
        }
        .doc-title h2 {
            margin: 0;
            font-size: 18px;
        }
        .section-title {
            background: #f3f4f6;
            padding: 5px 10px;
            font-weight: bold;
            margin-bottom: 15px;
            border-left: 4px solid #1e3a8a;
        }
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 5px 0;
        }
        .label {
            color: #6b7280;
            width: 140px;
        }
        .value {
            font-weight: bold;
            border-bottom: 1px solid #e5e7eb;
        }
        .main-box {
            border: 2px solid #000;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .main-box h3 {
            margin: 0;
            font-size: 28px;
            letter-spacing: 2px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 50px;
            margin-top: 50px;
            text-align: center;
        }
        .sign-box {
            height: 100px;
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 5px; cursor: pointer;">Print Document</button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #64748b; color: white; border: none; border-radius: 5px; cursor: pointer;">Close</button>
    </div>

    <div class="header">
        <div class="logo-section">
            <h1>KCN</h1>
            <p>Karya Citra Nusantara Terminal</p>
        </div>
        <div class="doc-title">
            <h2>SURAT PENYERAHAN PENUMPUKAN (SP2)</h2>
            <p>No: <?= $sp2_no ?></p>
        </div>
    </div>

    <div class="section-title">INFORMASI KENDARAAN & DRIVER</div>
    <div class="grid-container">
        <table class="data-table">
            <tr>
                <td class="label">Nama Trucking</td>
                <td class="value">PT. TRANSPORTASI MAJU JAYA</td>
            </tr>
            <tr>
                <td class="label">No Polisi</td>
                <td class="value"><?= $gate->police_number ?></td>
            </tr>
            <tr>
                <td class="label">Nama Driver</td>
                <td class="value"><?= $gate->driver_name ?></td>
            </tr>
        </table>
        <table class="data-table">
            <tr>
                <td class="label">No Gate In</td>
                <td class="value"><?= $gate->gate_no ?></td>
            </tr>
            <tr>
                <td class="label">Waktu Masuk</td>
                <td class="value"><?= date('d F Y H:i', strtotime($gate->gate_in_time)) ?></td>
            </tr>
            <tr>
                <td class="label">Operator Gate</td>
                <td class="value">SYSTEM_ADMIN</td>
            </tr>
        </table>
    </div>

    <div class="section-title">DETAIL KONTAINER</div>
    <div class="grid-container">
        <table class="data-table">
            <tr>
                <td class="label">Nomor Kontainer</td>
                <td class="value"><?= $gate->container_no ?></td>
            </tr>
            <tr>
                <td class="label">Ukuran / Tipe</td>
                <td class="value"><?= $gate->container_size ?> / <?= $gate->container_type ?></td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td class="value">FCL</td>
            </tr>
        </table>
        <table class="data-table">
            <tr>
                <td class="label">Nama Kapal</td>
                <td class="value"><?= $vessel_name ?></td>
            </tr>
            <tr>
                <td class="label">Voyage</td>
                <td class="value"><?= $voyage ?></td>
            </tr>
            <tr>
                <td class="label">Lokasi Yard</td>
                <td class="value">BLOCK A-05-02-01</td>
            </tr>
        </table>
    </div>

    <div class="main-box">
        <p style="margin-bottom: 10px; font-weight: bold;">NOMOR KONTAINER</p>
        <h3><?= $gate->container_no ?></h3>
        <p style="margin-top: 10px;">Harap serahkan dokumen ini kepada petugas Lift Off / Lapangan</p>
    </div>

    <div style="margin-top: 30px; font-size: 10px; color: #666;">
        <p>* Dokumen ini sah dicetak melalui sistem KCN TOS.</p>
        <p>* Berlaku selama 24 jam dari waktu cetak.</p>
    </div>

    <div class="footer-grid">
        <div>
            <p>Petugas Gate</p>
            <div class="sign-box"></div>
            <p>( ............................ )</p>
        </div>
        <div>
            <p>Driver / Pembawa</p>
            <div class="sign-box"></div>
            <p>( <?= $gate->driver_name ?> )</p>
        </div>
        <div>
            <p>Petugas Lapangan</p>
            <div class="sign-box"></div>
            <p>( ............................ )</p>
        </div>
    </div>
</body>
</html>
