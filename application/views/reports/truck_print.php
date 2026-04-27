<!DOCTYPE html>
<html>
<head>
    <title>Truck Activity Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; padding: 30px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f8f9fa; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        .summary-stats { display: flex; gap: 20px; margin-bottom: 20px; }
        .stat-item { flex: 1; border: 1px solid #ddd; padding: 10px; text-align: center; border-radius: 5px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; cursor: pointer;">Print Report</button>
    </div>

    <div class="header">
        <h2 style="margin:0;"><?= $terminal_name ?></h2>
        <h3 style="margin:5px 0;">TRUCK CONTAINER ACTIVITY REPORT</h3>
        <p>Period: <strong><?= $start_date ?></strong> to <strong><?= $end_date ?></strong></p>
    </div>

    <div class="summary-stats">
        <div class="stat-item">
            <small>Total Transactions</small>
            <h3 style="margin:5px 0;"><?= count($activities) ?></h3>
        </div>
        <div class="stat-item">
            <small>Avg. Cycle Time</small>
            <h3 style="margin:5px 0;">45 Min</h3>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Gate No</th>
                <th>Police Number</th>
                <th>Driver</th>
                <th>Container No</th>
                <th>Activity</th>
                <th>Gate In</th>
                <th>Gate Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($activities as $a): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $a->gate_no ?></td>
                <td><strong><?= $a->police_number ?></strong></td>
                <td><?= $a->driver_name ?></td>
                <td><?= $a->container_no ?></td>
                <td><?= $a->activity_type ?></td>
                <td><?= date('d/m H:i', strtotime($a->gate_in_time)) ?></td>
                <td><?= $a->gate_out_time ? date('d/m H:i', strtotime($a->gate_out_time)) : '-' ?></td>
                <td><?= $a->status ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Jakarta, <?= date('d F Y') ?></p>
        <br><br><br>
        <p><strong>Gate Superintendent</strong></p>
    </div>
</body>
</html>
