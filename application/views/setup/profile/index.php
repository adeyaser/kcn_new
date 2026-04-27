<div class="row">
    <div class="col-xl-4">
        <!-- Terminal Branding Card -->
        <div class="card-custom p-4 mb-4 text-center">
            <div class="mb-4">
                <?php if(isset($settings['terminal_logo']) && !empty($settings['terminal_logo'])): ?>
                    <img src="<?= base_url($settings['terminal_logo']) ?>" class="img-fluid rounded-3 shadow-sm mb-3" style="max-height: 120px;" id="logo_preview">
                <?php else: ?>
                    <div class="bg-light-primary rounded-4 p-4 mb-3 d-inline-block shadow-sm">
                        <i class="fas fa-landmark fa-4x text-primary"></i>
                    </div>
                <?php endif; ?>
            </div>
            
            <h5 class="text-dark mb-1"><?= isset($settings['app_name']) ? $settings['app_name'] : 'KCN Terminal Marunda' ?></h5>
            <p class="text-muted small mb-0">Terminal Management System</p>
            <hr class="my-4">
            
            <div class="text-start">
                <div class="mb-3">
                    <label class="text-muted extra-small text-uppercase fw-bold">Map Center Config</label>
                    <div class="bg-light p-3 rounded-3 d-flex align-items-center gap-3">
                        <i class="fas fa-crosshairs text-danger"></i>
                        <div>
                            <div class="small fw-bold">Lat: <?= isset($settings['map_lat']) ? $settings['map_lat'] : '-6.0920' ?></div>
                            <div class="small fw-bold">Lng: <?= isset($settings['map_lng']) ? $settings['map_lng'] : '106.9530' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documentation/Help -->
        <div class="alert alert-info border-0 shadow-sm">
            <h6 class="alert-heading small fw-bold text-uppercase"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
            <p class="mb-0 extra-small">Gunakan koordinat dari Google Maps untuk akurasi posisi sandar kapal yang tepat.</p>
        </div>
    </div>
    
    <div class="col-xl-8">
        <div class="card-custom">
            <div class="card-header border-bottom border-secondary">
                <h6><i class="fas fa-cogs me-2 text-primary"></i>Advanced Terminal Settings</h6>
            </div>
            <div class="card-body p-4">
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success border-0 shadow-sm mb-4">
                        <i class="fas fa-check-circle me-2"></i><?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger border-0 shadow-sm mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i><?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('setup/profile/update') ?>" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        <!-- Identity & Logo Group -->
                        <div class="col-12">
                            <h6 class="text-dark border-start border-primary border-4 ps-2 mb-3">Identity & Branding</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Terminal Name</label>
                            <input type="text" name="app_name" class="form-control" value="<?= isset($settings['app_name']) ? $settings['app_name'] : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Short Name</label>
                            <input type="text" name="app_short_name" class="form-control" value="<?= isset($settings['app_short_name']) ? $settings['app_short_name'] : '' ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Terminal Logo</label>
                            <div class="input-group">
                                <input type="file" name="logo_file" class="form-control" accept="image/*">
                            </div>
                            <div class="extra-small text-muted mt-1">Format: JPG, PNG, WEBP. Ukuran rekomendasi 200x200px.</div>
                        </div>
                        
                        <!-- Map Configuration -->
                        <div class="col-12 pt-2">
                            <h6 class="text-dark border-start border-danger border-4 ps-2 mb-3">Map Configuration (Dashboard)</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-danger">Latitude Center</label>
                            <input type="text" name="map_lat" class="form-control font-monospace" value="<?= isset($settings['map_lat']) ? $settings['map_lat'] : '' ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-danger">Longitude Center</label>
                            <input type="text" name="map_lng" class="form-control font-monospace" value="<?= isset($settings['map_lng']) ? $settings['map_lng'] : '' ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-danger">Default Zoom</label>
                            <select name="map_zoom" class="form-select">
                                <?php for($i=10; $i<=20; $i++): ?>
                                    <option value="<?= $i ?>" <?= (isset($settings['map_zoom']) && $settings['map_zoom'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <!-- Contact Group -->
                        <div class="col-12 pt-2">
                            <h6 class="text-dark border-start border-secondary border-4 ps-2 mb-3">Contact Details</h6>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Address</label>
                            <textarea name="terminal_address" class="form-control" rows="2"><?= isset($settings['terminal_address']) ? $settings['terminal_address'] : '' ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Support</label>
                            <input type="email" name="contact_email" class="form-control" value="<?= isset($settings['contact_email']) ? $settings['contact_email'] : '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone</label>
                            <input type="text" name="contact_phone" class="form-control" value="<?= isset($settings['contact_phone']) ? $settings['contact_phone'] : '' ?>">
                        </div>

                        <!-- Submit -->
                        <div class="col-12 mt-4 pt-3 border-top border-light text-end">
                            <button type="submit" class="btn btn-primary-custom px-5 btn-lg shadow">
                                <i class="fas fa-save me-2"></i>Update Terminal Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.extra-small { font-size: 11px; }
.bg-light-primary { background-color: rgba(14, 165, 233, 0.1); }
.font-monospace { font-family: 'Courier New', Courier, monospace; }
</style>
