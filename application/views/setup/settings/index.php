<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <form action="<?= site_url('setup/settings/save') ?>" method="POST" enctype="multipart/form-data">
            <div class="card-custom">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-white"><i class="fas fa-sliders-h me-2 text-primary"></i>System Configurations</h6>
                    <button type="submit" class="btn btn-primary-custom btn-sm"><i class="fas fa-save me-2"></i>Save All Changes</button>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Sidebar Tabs -->
                        <div class="col-md-4 border-end border-secondary bg-dark-subtle">
                            <div class="nav flex-column nav-pills p-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <?php $i=0; foreach($grouped_settings as $group => $items): ?>
                                    <button class="nav-link mb-2 text-start <?= $i==0?'active':'' ?>" id="tab-<?= $group ?>" data-bs-toggle="pill" data-bs-target="#content-<?= $group ?>" type="button" role="tab">
                                        <i class="fas fa-folder me-2"></i><?= $group ?>
                                    </button>
                                <?php $i++; endforeach; ?>
                            </div>
                        </div>
                        <!-- Tab Content -->
                        <div class="col-md-8 p-4">
                            <div class="tab-content" id="v-pills-tabContent">
                                <?php $i=0; foreach($grouped_settings as $group => $items): ?>
                                    <div class="tab-pane fade <?= $i==0?'show active':'' ?>" id="content-<?= $group ?>" role="tabpanel">
                                        <h6 class="text-info border-bottom border-secondary pb-2 mb-4"><?= $group ?> SETTINGS</h6>
                                        <?php foreach($items as $s): ?>
                                            <div class="mb-4">
                                                <label class="form-label text-muted small mb-1"><?= strtoupper(str_replace('_', ' ', $s->setting_key)) ?></label>
                                                <?php if(strpos($s->setting_key, 'address') !== false || strpos($s->setting_key, 'note') !== false): ?>
                                                    <textarea name="<?= $s->setting_key ?>" class="form-control" rows="3"><?= $s->setting_value ?></textarea>
                                                <?php else: ?>
                                                    <input type="text" name="<?= $s->setting_key ?>" class="form-control" value="<?= $s->setting_value ?>">
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php $i++; endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.nav-pills .nav-link {
    color: var(--text-muted);
    font-weight: 500;
    transition: all 0.2s;
    border-radius: 8px;
}
.nav-pills .nav-link:hover {
    background: rgba(255,255,255,0.05);
    color: var(--primary-color);
}
.nav-pills .nav-link.active {
    background: var(--primary-color);
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}
.bg-dark-subtle {
    background: rgba(15, 23, 42, 0.5);
}
</style>
