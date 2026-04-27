<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6><i class="fas fa-stream me-2 text-primary"></i>Visual Berth Window (7 Days)</h6>
                <div class="d-flex gap-3 text-muted small">
                    <span><i class="fas fa-square text-primary me-1"></i> Planned</span>
                    <span><i class="fas fa-square text-success me-1"></i> Berthed</span>
                    <span><i class="fas fa-square text-secondary me-1"></i> Departed</span>
                </div>
            </div>
            <div class="card-body p-0" style="overflow-x: auto;">
                <div class="gantt-container" style="min-width: 1200px;">
                    <!-- Timeline Header (Days) -->
                    <div class="gantt-header d-flex">
                        <div class="berth-label-head" style="width: 150px;"></div>
                        <?php for($i=0; $i<7; $i++): 
                            $date = date('d M', strtotime("+$i days"));
                        ?>
                            <div class="day-head flex-fill border-start border-secondary text-center py-2 small">
                                <?= $date ?>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <!-- Berth Rows -->
                    <?php foreach($berths as $b): ?>
                    <div class="berth-row d-flex border-top border-secondary position-relative" style="height: 80px;">
                        <div class="berth-label bg-dark-subtle d-flex align-items-center justify-content-center border-end border-secondary text-white fw-bold" style="width: 150px; z-index: 10;">
                            <?= $b->berth_name ?>
                        </div>
                        
                        <!-- Grid Background -->
                        <div class="grid-bg d-flex flex-fill">
                            <?php for($i=0; $i<7; $i++): ?>
                                <div class="flex-fill border-start border-secondary opacity-25"></div>
                            <?php endfor; ?>
                        </div>

                        <!-- Vessels Overlay -->
                        <?php foreach($schedules as $s): 
                            if($s->berth_id != $b->id) continue;
                            
                            $start = new DateTime($s->eta);
                            $end = new DateTime($s->etd);
                            $now = new DateTime(date('Y-m-d'));
                            
                            $diff_start = $now->diff($start);
                            $start_hours = ($diff_start->days * 24) + $diff_start->h;
                            if($start < $now) $start_hours = 0; // Started before today

                            $duration_hours = $start->diff($end)->days * 24 + $start->diff($end)->h;
                            
                            $total_window_hours = 7 * 24;
                            $left_pct = ($start_hours / $total_window_hours) * 100;
                            $width_pct = ($duration_hours / $total_window_hours) * 100;

                            $color_class = 'bg-primary';
                            if($s->status == 'BERTHED') $color_class = 'bg-success';
                            if($s->status == 'DEPARTED') $color_class = 'bg-secondary';
                        ?>
                        <div class="vessel-bar position-absolute <?= $color_class ?> rounded-3 p-2 shadow-lg" 
                             style="left: calc(150px + <?= $left_pct ?>%); width: <?= $width_pct ?>%; top: 15px; height: 50px; cursor: pointer; z-index: 5;"
                             onclick="viewVesselDetail(<?= $s->id ?>)">
                            <div class="text-white fw-bold text-truncate small"><?= $s->vessel_name ?></div>
                            <div class="text-white-50 small" style="font-size: 9px;"><?= date('H:i', strtotime($s->eta)) ?> - <?= date('H:i', strtotime($s->etd)) ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.gantt-container { background: #ffffff; position: relative; border: 1px solid var(--border); border-radius: var(--radius); }
.bg-dark-subtle { background: #f8fafc; }
.vessel-bar { transition: transform 0.2s; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
.vessel-bar:hover { transform: translateY(-2px); z-index: 20 !important; filter: brightness(1.1); }
.day-head { color: var(--text-muted); font-weight: 600; background: #f1f5f9; }
.berth-label { color: var(--text) !important; background: #f8fafc !important; }
.grid-bg div { border-color: #e2e8f0 !important; }
</style>

<?php ob_start(); ?>
<script>
function viewVesselDetail(id) {
    Toast.fire({icon: 'info', title: 'Loading vessel plan...'});
    // Redirect or show modal
}
</script>
<?php $this->load->vars(['page_js' => ob_get_clean()]); ?>
