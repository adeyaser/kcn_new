<!DOCTYPE html>
<html>
<head>
    <title>Gate Pass - <?= $gate->gate_no ?></title>
    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            margin: 0; 
            padding: 0; 
            color: #000; 
            background: #fff;
            width: 80mm;
        }
        .pass-card { 
            width: 72mm;
            padding: 5mm 4mm; 
            margin: 0 auto; 
            position: relative; 
            box-sizing: border-box;
            background: #fff;
        }
        .header { text-align: center; border-bottom: 1px dashed #000; padding-bottom: 3mm; margin-bottom: 5mm; }
        .header h2 { margin: 0; font-size: 14pt; color: #000; }
        .header p { margin: 2pt 0 0; font-size: 9pt; font-weight: bold; }
        
        .qr-section { text-align: center; margin-bottom: 5mm; }
        .qr-code { width: 45mm; height: 45mm; border: none; display: inline-block; }
        .gate-no-box { font-weight: bold; margin-top: 2mm; font-size: 11pt; border: 1px solid #000; display: inline-block; padding: 1mm 2mm; }
        
        .info-section { font-size: 8pt; margin-bottom: 5mm; border-bottom: 1px dashed #000; padding-bottom: 5mm; }
        .info-row { display: flex; margin-bottom: 3pt; align-items: flex-start; }
        .info-label { width: 30mm; color: #000; font-weight: normal; flex-shrink: 0; }
        .info-value { color: #000; font-weight: bold; flex-grow: 1; text-align: right; word-wrap: break-word; }
        
        .container-box { text-align: center; font-size: 11pt; font-weight: 900; margin: 3mm 0; border: 1px solid #000; padding: 2mm; }
        
        .footer { padding-top: 3mm; text-align: center; font-size: 7.5pt; }
        .footer p { margin: 2pt 0; }
        
        .no-print { text-align: center; margin: 20px 0; display: flex; justify-content: center; gap: 10px; }
        .btn-print { padding: 10px 20px; cursor: pointer; background: #000; color: #fff; border: none; font-weight: bold; }
        .btn-pdf { padding: 10px 20px; cursor: pointer; background: #dc2626; color: #fff; border: none; font-weight: bold; }

        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .pass-card { width: 80mm; padding: 5mm; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">PRINT PASS</button>
        <button class="btn-pdf" onclick="downloadPDF()">DOWNLOAD PDF</button>
    </div>

    <div id="printArea">
        <div class="pass-card">
            <div class="header">
                <h2>KCN TERMINAL</h2>
                <p>JOB SLIP / GATE PASS</p>
            </div>

            <div class="qr-section">
                <img class="qr-code" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $gate->gate_no ?>" alt="QR Code">
                <br>
                <div class="gate-no-box"><?= $gate->gate_no ?></div>
            </div>

            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">Police No:</span>
                    <span class="info-value"><?= $gate->police_number ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Driver:</span>
                    <span class="info-value"><?= $gate->driver_name ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Activity:</span>
                    <span class="info-value"><?= $gate->activity_type ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Vessel:</span>
                    <span class="info-value"><?= $vessel_name ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Voyage:</span>
                    <span class="info-value"><?= $voyage ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Gate In:</span>
                    <span class="info-value"><?= date('d/m/Y H:i', strtotime($gate->gate_in_time)) ?></span>
                </div>
            </div>

            <div class="container-box">
                <?= $gate->container_no ? $gate->container_no : 'NO CONTAINER' ?><br>
                <span style="font-size: 8pt; font-weight: normal;"><?= $gate->container_size ?>' / <?= $gate->container_type ?></span>
            </div>

            <div class="footer">
                <p>Keep this slip for Gate Out</p>
                <p>Printed: <?= date('d/m/y H:i') ?></p>
                <p>Safety First - Drive Carefully</p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script type="text/javascript">
        function downloadPDF() {
            var element = document.getElementById('printArea');
            var opt = {
                margin:       [5, 5, 5, 5],
                filename:     'GatePass_<?= $gate->gate_no ?>.pdf',
                image:        { type: 'jpeg', quality: 1 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'mm', format: [80, 250], orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>
