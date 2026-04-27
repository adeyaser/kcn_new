<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <div class="brand-icon-sm">
                <?php if(isset($app_settings['terminal_logo']) && !empty($app_settings['terminal_logo'])): ?>
                    <img src="<?= base_url($app_settings['terminal_logo']) ?>" width="32" height="32" style="object-fit: contain;">
                <?php else: ?>
                    <i class="fas fa-anchor"></i>
                <?php endif; ?>
            </div>
            <div class="brand-info">
                <h6 class="brand-name"><?= isset($app_settings['app_short_name']) ? $app_settings['app_short_name'] : 'KCN TOS' ?></h6>
                <span class="brand-version">v1.0</span>
            </div>
        </div>
        <button class="sidebar-toggle d-lg-none" id="sidebarClose"><i class="fas fa-times"></i></button>
    </div>

    <nav class="sidebar-nav">
        <?php foreach($sidebar_menus as $menu): ?>
            <?php if(empty($menu->children)): ?>
                <a href="<?= site_url($menu->menu_url) ?>" class="nav-link <?= (uri_string() == $menu->menu_url) ? 'active' : '' ?>">
                    <i class="<?= $menu->menu_icon ?> nav-icon"></i>
                    <span class="nav-label"><?= $menu->menu_name ?></span>
                </a>
            <?php else: ?>
                <div class="nav-group <?= $this->uri->segment(1) == strtolower(explode(' ',$menu->menu_name)[0]) ? 'open' : '' ?>">
                    <a href="#" class="nav-link nav-group-toggle">
                        <i class="<?= $menu->menu_icon ?> nav-icon"></i>
                        <span class="nav-label"><?= $menu->menu_name ?></span>
                        <i class="fas fa-chevron-right nav-arrow"></i>
                    </a>
                    <div class="nav-group-items">
                        <?php foreach($menu->children as $child): ?>
                            <a href="<?= site_url($child->menu_url) ?>" class="nav-link <?= (uri_string() == $child->menu_url) ? 'active' : '' ?>">
                                <i class="<?= $child->menu_icon ?> nav-icon"></i>
                                <span class="nav-label"><?= $child->menu_name ?></span>
                            </a>
                        <?php endforeach; ?>
                        
                        <?php if($menu->menu_name == 'Planning'): ?>
                            <a href="<?= site_url('operations/tca_planning') ?>" class="nav-link <?= (uri_string() == 'operations/tca_planning') ? 'active' : '' ?>">
                                <i class="fas fa-truck-loading nav-icon"></i>
                                <span class="nav-label">TCA Planning</span>
                            </a>
                        <?php endif; ?>

                        <?php if($menu->menu_name == 'Master Data' || $menu->menu_name == 'Master'): ?>
                            <a href="<?= site_url('master/gate') ?>" class="nav-link <?= (uri_string() == 'master/gate') ? 'active' : '' ?>">
                                <i class="fas fa-door-open nav-icon"></i>
                                <span class="nav-label">Master Gate</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <span><?= strtoupper(substr($current_user->full_name, 0, 2)) ?></span>
            </div>
            <div class="user-detail">
                <span class="user-name"><?= $current_user->full_name ?></span>
                <span class="user-role"><?= $current_user->role_name ?></span>
            </div>
        </div>
    </div>
</aside>

<!-- Top Navbar -->
<div class="main-content" id="mainContent">
    <nav class="top-navbar">
        <div class="d-flex align-items-center">
            <button class="sidebar-toggle me-3" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <div class="page-breadcrumb">
                <h5 class="page-title mb-0"><?= $page_title ?></h5>
            </div>
        </div>
        <div class="navbar-actions">
            <div class="nav-item-action" title="Notifications">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </div>
            <div class="dropdown">
                <button class="nav-user-btn dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="user-avatar-sm"><?= strtoupper(substr($current_user->full_name, 0, 1)) ?></div>
                    <span class="d-none d-md-inline"><?= $current_user->full_name ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                    <li><a class="dropdown-item" href="<?= site_url('setup/profile') ?>"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= site_url('auth/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
