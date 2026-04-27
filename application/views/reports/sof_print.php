<!DOCTYPE html>
<html>
<head>
    <title>SOF - <?= $sof['planning']->vessel_name ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; padding: 30px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .vessel-info { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .vessel-info td { padding: 5px; border: 1px solid #ddd; }
        .label { background: #f4f4f4; font-weight: bold; width: 150px; }
        .timeline { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .timeline th { background: #1e3a8a; color: white; padding: 10px; text-align: left; }
        .timeline td { padding: 8px; border-bottom: 1px solid #eee; }
        .interruption { color: #dc2626; font-style: italic; }
        .footer { margin-top: 50px; display: flex; justify-content: space-between; text-align: center; }
        .sign-box { height: 80px; margin-bottom: 5px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #3b82f6; color: white; border: none; cursor: pointer;">Print SOF</button>
        <button onclick="window.close()" style="padding: 8px 16px; background: #64748b; color: white; border: none; cursor: pointer;">Close</button>
    </div>

    <div class="header">
        <h2 style="margin:0;"><?= $terminal['name'] ?></h2>
        <p style="margin:5px 0;"><?= $terminal['address'] ?></p>
        <h3 style="margin:10px 0; border-top: 1px solid #000; padding-top: 10px;">STATEMENT OF FACTS (SOF)</h3>
    </div>

    <table class="vessel-info">
        <tr>
            <td class="label">Vessel Name</td>
            <td><?= $sof['planning']->vessel_name ?></td>
            <td class="label">Call Sign</td>
            <td><?= $sof['planning']->call_sign ?></td>
        </tr>
        <tr>
            <td class="label">Voyage In/Out</td>
            <td><?= $sof['planning']->voyage_in ?> / <?= $sof['planning']->voyage_out ?></td>
            <td class="label">Flag</td>
            <td><?= $sof['planning']->flag ?></td>
        </tr>
        <tr>
            <td class="label">ETA / ETD</td>
            <td><?= date('d/m/Y H:i', strtotime($sof['planning']->eta)) ?> / <?= date('d/m/Y H:i', strtotime($sof['planning']->etd)) ?></td>
            <td class="label">LOA</td>
            <td><?= $sof['planning']->loa ?> m</td>
        </tr>
    </table>

    <table class="timeline">
        <thead>
            <tr>
                <th width="150">Date & Time</th>
                <th>Activity Description</th>
                <th width="100">Remarks</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($sof['planning']->eta)) ?></td>
                <td>Vessel Arrived at Port (ETA)</td>
                <td>-</td>
            </tr>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($sof['planning']->eta . ' + 1 hour')) ?></td>
                <td>Vessel Berthed (ATB)</td>
                <td>Berth 02</td>
            </tr>
            <?php if($sof['commence']): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($sof['commence'])) ?></td>
                <td><strong>Cargo Operation Commenced</strong></td>
                <td>-</td>
            </tr>
            <?php endif; ?>

            <?php foreach($sof['interruptions'] as $i): ?>
                <tr class="interruption">
                    <td><?= date('d/m/Y H:i', strtotime($i->start_time)) ?></td>
                    <td>INTERRUPTION: <?= $i->interruption_type ?></td>
                    <td><?= $i->remarks ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if($sof['complete']): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($sof['complete'])) ?></td>
                <td><strong>Cargo Operation Completed</strong></td>
                <td>-</td>
            </tr>
            <?php endif; ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($sof['planning']->etd)) ?></td>
                <td>Vessel Departed (ATD)</td>
                <td>-</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div>
            <p>Master of Vessel</p>
            <div class="sign-box"></div>
            <p>( ............................ )</p>
        </div>
        <div>
            <p>Shipping Agent</p>
            <div class="sign-box"></div>
            <p>( ............................ )</p>
        </div>
        <div>
            <p>Terminal Operations</p>
            <div class="sign-box"></div>
            <p>( ............................ )</p>
        </div>
    </div>
</body>
</html>
