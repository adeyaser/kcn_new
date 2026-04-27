<!DOCTYPE html>
<html>
<head>
    <title>Tally Report - <?= $planning->vessel_name ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; padding: 30px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .summary-grid { display: flex; gap: 20px; margin-bottom: 30px; }
        .summary-box { flex: 1; border: 1px solid #ddd; padding: 15px; border-radius: 5px; text-align: center; }
        .summary-box h4 { margin: 0 0 5px 0; font-size: 10px; color: #666; }
        .summary-box h2 { margin: 0; color: #1e3a8a; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f8f9fa; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .badge { padding: 2px 5px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .bg-discharge { background: #dcfce7; color: #166534; }
        .bg-load { background: #dbeafe; color: #1e40af; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; cursor: pointer;">Print Report</button>
    </div>

    <div class="header">
        <h2 style="margin:0;"><?= $terminal_name ?></h2>
        <h3 style="margin:5px 0;">VESSEL TALLY & PRODUCTIVITY REPORT</h3>
        <p>Vessel: <strong><?= $planning->vessel_name ?> (<?= $planning->voyage_in ?>)</strong> | Date: <?= date('d/m/Y') ?></p>
    </div>

    <div class="summary-grid">
        <div class="summary-box">
            <h4>Total Container Moves</h4>
            <h2><?= count($logs) ?></h2>
        </div>
        <?php foreach($productivity as $p): ?>
        <div class="summary-box">
            <h4><?= $p->equipment_code ?></h4>
            <h2><?= $p->total_moves ?> <small style="font-size: 10px; color: #666;">Moves</small></h2>
        </div>
        <?php endforeach; ?>
    </div>

    <h4 style="margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">DETAILED ACTIVITY LOG</h4>
    <table>
        <thead>
            <tr>
                <th width="50">No</th>
                <th width="120">Time</th>
                <th>Container No</th>
                <th>Activity</th>
                <th>Equipment</th>
                <th>Position (B-R-T)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($logs as $l): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= date('H:i:s', strtotime($l->activity_time)) ?></td>
                <td><strong><?= $l->container_no ?></strong></td>
                <td>
                    <span class="badge <?= $l->activity_type == 'DISCHARGE' ? 'bg-discharge' : 'bg-load' ?>">
                        <?= $l->activity_type ?>
                    </span>
                </td>
                <td><?= $l->equipment_code ?></td>
                <td><?= $l->bay ?>-<?= $l->row ?>-<?= $l->tier ?> (<?= $l->location_type ?>)</td>
                <td>COMPLETED</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Jakarta, <?= date('d F Y') ?></p>
        <br><br><br>
        <p><strong>Chief Tally</strong></p>
    </div>
</body>
</html>
