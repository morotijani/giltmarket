    <!-- Sidenav (lg) -->
    <aside class="aside">
        <nav class="navbar navbar-expand-xl navbar-vertical">
            <div class="container-fluid">
                    <!-- Brand -->
                    <a class="navbar-brand fs-5 fw-bold px-xl-3 mb-xl-4" href="<?= PROOT; ?>">
                        <!-- <i class="fs-4 text-secondary me-1" data-duoicon="box-2"></i> --> <img src="<?= PROOT; ?>assets/media/logo-no-bg.png" width="20" height="25" /> JSpence
                    </a>
                
                    <!-- User -->
                    <div class="ms-auto d-xl-none">
                        <div class="dropdown my-n2">
                            <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="avatar avatar-sm avatar-status avatar-status-success me-3">
                                <img class="avatar-img" src="<?= PROOT . (($admin_data["admin_profile"] != '') ? $admin_data["admin_profile"] : 'assets/media/avatar.png'); ?>" alt="..." />
                                </span>
                                <?= ucwords($admin_data['admin_fullname']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= PROOT; ?>acc/profile">Account</a></li>
                                <li><a class="dropdown-item" href="<?= PROOT; ?>acc/change-password">Change password</a></li>
                                <li>
                                <hr class="dropdown-divider" />
                                </li>
                                <li><a class="dropdown-item" href="<?= PROOT; ?>auth/logout">Sign out</a></li>
                            </ul>
                        </div>
                    </div>
                
                    <!-- Toggler -->
                    <button
                        class="navbar-toggler ms-3"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#sidenavLargeCollapse"
                        aria-controls="sidenavLargeCollapse"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                
                    <!-- Collapse -->
                    <div class="collapse navbar-collapse" id="sidenavLargeCollapse">
                        <!-- Search -->
                        <div class="input-group d-xl-none my-4 my-xl-0">
                        <input
                            class="form-control"
                            id="topnavSearchInputMobile"
                            type="search"
                            placeholder="Trade"
                            aria-label="Search"
                            aria-describedby="navbarSearchMobile" 
                            readonly style="cursor: pointer" data-bs-target="<?= ((!admin_has_permission()) ? '#buyModal' : ''); ?>" data-bs-toggle="modal"
                        />
                        <span class="input-group-text" id="navbarSearchMobile">
                            <span class="material-symbols-outlined"> send_money </span>
                        </span>
                    </div>
                
                    <!-- Nav -->
                    <nav class="navbar-nav nav-pills mb-7">
                        <div class="nav-item">
                            <a
                            class="nav-link nav-dashboard"
                            href="javascript:;"
                            data-bs-toggle="collapse"
                            data-bs-target="#dashboard"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="dashboard"
                            >
                            <span class="material-symbols-outlined me-3">space_dashboard</span> Dashboards
                            </a>
                            <div class="collapse" id="dashboard">
                                <nav class="nav nav-pills">
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>index"><?= ucwords(_admin_position($admin_data['admin_permissions'])); ?></a>
                                    <?php if (admin_has_permission()): ?>
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>account/analytics">Analytics</a>
                                    <?php else: ?>
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>account/summary">Summary board</a>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a
                            class="nav-link nav-market"
                            href="javascript:;"
                            data-bs-toggle="collapse"
                            data-bs-target="#market"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="market"
                            >
                            <span class="material-symbols-outlined me-3">storefront</span> Market
                            </a>
                            <div class="collapse " id="market">
                            <nav class="nav nav-pills">
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>account/trades">Trades</a>
                                    <?php if (admin_has_permission('salesperson')): ?>
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>account/expenditure">Expenditures</a>
                                    <?php endif; ?>
                                    <?php if (!admin_has_permission()): ?>
                                    <a class="nav-link nav-child" href="javascript:;" data-bs-target="#buyModal" data-bs-toggle="modal">New trade</a>
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>account/end-trade">End trade</a>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                        <?php if (admin_has_permission('salesperson')): ?>
                        <div class="nav-item">
                            <a
                            class="nav-link nav-expenditure"
                            href="javascript:;"
                            data-bs-toggle="collapse"
                            data-bs-target="#expenditure"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="expenditure"
                            >
                                <span class="material-symbols-outlined me-3">payments</span> Expenditure
                            </a>
                            <div class="collapse " id="expenditure">
                                <nav class="nav nav-pills">
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>account/expenditure">Expenditures</a>
                                    <?php if ($admin_data['admin_permissions'] == 'salesperson'): ?>
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>account/expenditure?add=1">New expenditure</a>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="nav-item">
                            <a
                            class="nav-link nav-pushes"
                            href="javascript:;"
                            data-bs-toggle="collapse"
                            data-bs-target="#pushes"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="pushes"
                            >
                                <span class="material-symbols-outlined me-3">compare_arrows</span> Pushes
                            </a>
                            <div class="collapse" id="pushes">
                                <nav class="nav nav-pills">
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>account/pushes">Pushes</a>
                                    <?php if (admin_has_permission('supervisor')): ?>
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>account/pushes/salesperson">Sales persons</a>
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>account/pushes/gold-receive">Received</a>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                        <?php if (admin_has_permission()): ?>
                        <div class="nav-item">
                            <a
                            class="nav-link nav-admins"
                            href="javascript:;"
                            data-bs-toggle="collapse"
                            data-bs-target="#admins"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="admins"
                            >
                            <span class="material-symbols-outlined me-3">group</span> Admins
                            </a>
                            <div class="collapse" id="admins">
                            <nav class="nav nav-pills">
                                <a class="nav-link nav-child" href="<?= PROOT; ?>account/admins">Admins</a>
                                <a class="nav-link nav-child" href="<?= PROOT; ?>account/admins?add=1">New admin</a>
                            </nav>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="nav-item">
                            <a
                            class="nav-link nav-account"
                            href="javascript:;"
                            data-bs-toggle="collapse"
                            data-bs-target="#account"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="account"
                            >
                                <span class="material-symbols-outlined me-3">person</span> Account
                            </a>
                            <div class="collapse " id="account">
                                <nav class="nav nav-pills">
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>account/profile">Account overview</a>
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>account/settings">Account settings</a>
                                </nav>
                            </div>
                        </div>
                        <?php if (admin_has_permission()): ?>
                        <div class="nav-item">
                            <a class="nav-link nav-child" href="<?= PROOT; ?>account/report">
                                <span class="material-symbols-outlined me-3">lab_profile</span> Generate report
                            </a>
                        </div>
                        <?php endif; ?>
                        <div class="nav-item">
                            <a
                            class="nav-link nav-logs"
                            href="javascript:;"
                            data-bs-toggle="collapse"
                            data-bs-target="#logs"
                            rol="button"
                            aria-expanded="false"
                            aria-controls="logs"
                            >
                            <span class="material-symbols-outlined me-3">list_alt</span> Logs
                            </a>
                            <div class="collapse" id="logs">
                                <nav class="nav nav-pills">
                                    <a class="nav-link nav-child" href="<?= PROOT; ?>account/logs">Logs</a>
                                </nav>
                            </div>
                        </div>
                    </nav>
                
                        <!-- Heading -->
                        <h3 class="fs-base px-3 mb-4">Documentation</h3>
                
                        <!-- Nav -->
                        <nav class="navbar-nav mb-xl-7">
                            <div class="nav-item">
                                <a class="nav-link nav-child" href="javascript:;">
                                    <span class="material-symbols-outlined me-3">sticky_note_2</span> Getting started
                                    <span class="badge text-bg-primary ms-auto">v0.0.6</span>
                                </a>
                            </div>
                            <div class="nav-item">
                                <a class="nav-link nav-child" href="<?= PROOT; ?>account/profile">
                                    <span class="material-symbols-outlined me-3">face</span> Account
                                </a>
                            </div>
                            <div class="nav-item">
                                <a class="nav-link nav-child" href="<?= PROOT; ?>auth/logout">
                                    <span class="material-symbols-outlined me-3">login</span> Logout
                                </a>
                            </div>
                        </nav>
                
                        <!-- Divider -->
                        <hr class="my-4 d-xl-none" />
                
                        <!-- Nav -->
                        <nav class="navbar-nav d-xl-none mb-7">
                            <div class="nav-item">
                                <a class="nav-link" href="#"> <span class="material-symbols-outlined me-3">contrast</span> Dark mode </a>
                            </div>
                            <div class="nav-item">
                                <a class="nav-link" href="#"> <span class="material-symbols-outlined me-3">local_mall</span> Trade </a>
                            </div>
                            <div class="nav-item">
                                <a class="nav-link text-danger" href="<?= PROOT; ?>account/end-trade"> <span class="material-symbols-outlined me-3">money_off</span> End trade </a>
                            </div>
                            <div class="nav-item">
                                <a class="nav-link" href="#"> <span class="material-symbols-outlined me-3">alternate_email</span> Contact us </a>
                            </div>
                        </nav>
                
                        <!-- Card -->
                        <div class="card mt-auto">
                        <div class="card-body">
                            <!-- Heading -->
                            <h6>Need help?</h6>
                
                            <!-- Text -->
                            <p class="text-body-secondary mb-0">Feel free to reach out to the IT department, should you have any questions or suggestions.</p>
                        </div>
                        </div>
                    </div>
                </div>
            </nav>
        </aside>
                    