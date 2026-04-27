<!DOCTYPE html>
<html>
<head>
    <title>Bulk TCA Entry Pass</title>
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
            margin: 0 0 10mm 0; 
            position: relative; 
            page-break-after: always;
            box-sizing: border-box;
            background: #fff;
            border-bottom: 2px dashed #000;
        }
        .header { text-align: center; border-bottom: 1px dashed #000; padding-bottom: 3mm; margin-bottom: 5mm; }
        .header h2 { margin: 0; font-size: 14pt; color: #000; }
        .header p { margin: 2pt 0 0; font-size: 9pt; font-weight: bold; }
        
        .qr-section { text-align: center; margin-bottom: 5mm; }
        .qr-code { width: 45mm; height: 45mm; border: none; display: inline-block; }
        .assignment-no { font-weight: bold; margin-top: 2mm; font-size: 11pt; border: 1px solid #000; display: inline-block; padding: 1mm 2mm; }
        
        .info-section { font-size: 8pt; margin-bottom: 5mm; border-bottom: 1px dashed #000; padding-bottom: 5mm; }
        .info-row { display: flex; margin-bottom: 3pt; align-items: flex-start; }
        .info-label { width: 30mm; color: #000; font-weight: normal; flex-shrink: 0; }
        .info-value { color: #000; font-weight: bold; flex-grow: 1; text-align: right; word-wrap: break-word; }
        
        .est-arrival { text-align: center; font-size: 10pt; font-weight: 900; margin: 3mm 0; border: 1px solid #000; padding: 2mm; }
        
        .footer { padding-top: 3mm; text-align: center; font-size: 7.5pt; margin-bottom: 5mm; }
        .footer p { margin: 2pt 0; }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .pass-card { width: 80mm; padding: 5mm; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin: 20px 0; display: flex; justify-content: center; gap: 10px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #000; color: #fff; border: none; font-weight: bold;">PRINT ALL (<?= count($assignments) ?>)</button>
        <button onclick="downloadBulkPDF()" style="padding: 10px 20px; cursor: pointer; background: #dc2626; color: #fff; border: none; font-weight: bold;">DOWNLOAD ALL AS PDF</button>
    </div>

    <div id="printArea">
        <?php foreach($assignments as $data): ?>
        <div class="pass-card">
            <div class="header">
                <h2>KCN TERMINAL</h2>
                <p>TRUCK APPOINTMENT PASS</p>
            </div>

            <div class="qr-section">
                <img class="qr-code" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $data->assignment_no ?>|<?= $data->qr_code_token ?>" alt="QR Code">
                <br>
                <div class="assignment-no"><?= $data->assignment_no ?></div>
            </div>

            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">Police No:</span>
                    <span class="info-value"><?= $data->police_number ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Company:</span>
                    <span class="info-value"><?= $data->truck_company ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Driver:</span>
                    <span class="info-value"><?= $data->driver_name ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">RFID:</span>
                    <span class="info-value"><?= $data->rfid_tag ?></span>
                </div>
                <div style="margin-top: 2mm;">
                    <div class="info-label">Planning:</div>
                    <div class="info-value" style="text-align: left;"><?= $data->request_no ?></div>
                </div>
                <div class="info-row">
                    <span class="info-label">Container:</span>
                    <span class="info-value"><?= $data->container_no ?> (<?= $data->container_size ?>')</span>
                </div>
            </div>

            <div class="est-arrival">
                <div style="font-size: 8pt; font-weight: normal; margin-bottom: 1mm;">ESTIMATED ARRIVAL:</div>
                <?= date('d/m/Y H:i', strtotime($data->estimated_arrival)) ?>
            </div>

            <div class="footer">
                <p>Show this to Gate Operator.</p>
                <p>Printed: <?= date('d/m/y H:i') ?></p>
                <p>*** VALID FOR ONE ENTRY ***</p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script type="text/javascript">
        function downloadBulkPDF() {
            var element = document.getElementById('printArea');
            var opt = {
                margin:       [5, 5, 5, 5],
                filename:     'Bulk_GatePasses_<?= date('Ymd_His') ?>.pdf',
                image:        { type: 'jpeg', quality: 1 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'mm', format: [80, 250], orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>
