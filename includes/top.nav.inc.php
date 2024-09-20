
        <!-- Topnav -->
        <nav class="navbar d-none d-xl-flex px-xl-6">
            <div class="container flex-column align-items-stretch">
                <div class="row">
                    <div class="col">
                        <!-- Search -->
                        <div class="input-group" style="max-width: 400px">
                            <input class="form-control" id="topnavSearchInput" type="search" placeholder="Search" aria-label="Search" aria-describedby="navbarSearch" />
                            <span class="input-group-text" id="navbarSearch">
                                <kbd class="badge bg-body-secondary text-black">⌘</kbd>
                                <kbd class="badge bg-body-secondary text-black ms-1">K</kbd>
                            </span>
                        </div>
                    </div>

                    <div class="col-auto">
                        <!-- User -->
                        <div class="dropdown my-n2">
                            <a class="btn btn-link d-inline-flex align-items-center dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="avatar avatar-sm avatar-status avatar-status-warning me-3">
                                    <img class="avatar-img" src="<?= PROOT . (($admin_data["admin_profile"] != '') ? $admin_data["admin_profile"] : 'assets/media/avatar.png'); ?>" alt="..." />
                                </span>
                                <?= ucwords($admin_data['admin_fullname']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= PROOT; ?>account/profile">Account</a></li>
                                <li><a class="dropdown-item" href="<?= PROOT; ?>account/change-password">Change password</a></li>
                                <li>
                                <hr class="dropdown-divider" />
                                </li>
                                <li><a class="dropdown-item" href="<?= PROOT; ?>auth/logout">Sign out</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main -->
        <main class="main px-lg-6">
            <?= $flash; ?>