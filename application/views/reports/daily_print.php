<!DOCTYPE html>
<html>
<head>
    <title>Daily Report - <?= $report_date ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11px; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px; }
        .summary-card { border: 1px solid #ddd; padding: 15px; border-radius: 5px; text-align: center; }
        .summary-card h4 { margin: 0 0 5px 0; color: #666; font-size: 10px; text-transform: uppercase; }
        .summary-card h2 { margin: 0; color: #1e3a8a; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f8f9fa; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .section-title { background: #1e3a8a; color: white; padding: 8px 15px; margin-bottom: 10px; font-weight: bold; border-radius: 3px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; cursor: pointer;">Print Report</button>
    </div>

    <div class="header">
        <h1 style="margin:0;"><?= $terminal_name ?></h1>
        <h3 style="margin:5px 0; color: #666;">DAILY OPERATIONS REPORT</h3>
        <p>Report Date: <strong><?= date('d F Y', strtotime($report_date)) ?></strong></p>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <h4>Gate In</h4>
            <h2><?= count($gate_in) ?></h2>
        </div>
        <div class="summary-card">
            <h4>Gate Out</h4>
            <h2><?= count($gate_out) ?></h2>
        </div>
        <div class="summary-card">
            <h4>Container</h4>
            <h2><?= count($tally) ?></h2>
        </div>
        <div class="summary-card">
            <h4>Revenue (Est)</h4>
            <h2>-</h2>
        </div>
    </div>

    <div class="section-title">I. GATE TRANSACTIONS (IN/OUT)</div>
    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>Gate No</th>
                <th>Truck / Plate</th>
                <th>Container No</th>
                <th>Activity</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($gate_in as $g): ?>
            <tr>
                <td><?= date('H:i', strtotime($g->gate_in_time)) ?></td>
                <td><?= $g->gate_no ?></td>
                <td><?= $g->police_number ?></td>
                <td><?= $g->container_no ?></td>
                <td><?= $g->activity_type ?></td>
                <td><span style="color: green;">GATE IN</span></td>
            </tr>
            <?php endforeach; ?>
            <?php foreach($gate_out as $g): ?>
            <tr>
                <td><?= date('H:i', strtotime($g->gate_out_time)) ?></td>
                <td><?= $g->gate_no ?></td>
                <td><?= $g->police_number ?></td>
                <td><?= $g->container_no ?></td>
                <td><?= $g->activity_type ?></td>
                <td><span style="color: red;">GATE OUT</span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="section-title">II. CONTAINER</div>
    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>Container No</th>
                <th>Activity</th>
                <th>Vessel</th>
                <th>Equipment</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($tally as $t): ?>
            <tr>
                <td><?= date('H:i', strtotime($t->activity_time)) ?></td>
                <td><?= $t->container_no ?></td>
                <td><?= $t->activity_type ?></td>
                <td><?= $t->vessel_name ? $t->vessel_name : '-' ?></td>
                <td><?= $t->equipment_id ?></td>
                <td><?= $t->location_type ?> (<?= $t->bay ?>-<?= $t->row ?>-<?= $t->tier ?>)</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Jakarta, <?= date('d F Y') ?></p>
        <br><br><br>
        <p><strong>Terminal Operations Manager</strong></p>
    </div>
</body>
</html>
