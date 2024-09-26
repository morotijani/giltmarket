
  <aside class="aside aside-sm d-none d-xl-flex">
    <nav class="navbar navbar-expand-xl navbar-vertical">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="sidenavSmallCollapse">
          <!-- Nav -->
          <nav class="navbar-nav h-100">
            <div class="nav-item" data-bs-toggle="tooltip" data-bs-title="Dark mode (coming soon)">
              <a class="nav-link" href="#!">
                <span class="material-symbols-outlined mx-auto"> contrast </span>
              </a>
            </div>
            <div class="nav-item" data-bs-toggle="tooltip" data-bs-title="<?= ((!admin_has_permission()) ? 'Make a trade' : 'All trades'); ?>">
              <?php if (admin_has_permission()): ?>
                <a class="nav-link" href="<?= PROOT . 'account/trades'; ?>">
                  <span class="material-symbols-outlined mx-auto"> local_mall </span>
                </a>
              <?php else: ?>
                <a class="nav-link" href="javascript:;" data-bs-target="#buyModal" data-bs-toggle="modal">
                  <span class="material-symbols-outlined mx-auto"> local_mall </span>
                </a>
              <?php endif; ?>
            </div>
            <?php if (!admin_has_permission()): ?>
            <div class="nav-item" data-bs-toggle="tooltip" data-bs-title="End trade">
              <a class="nav-link text-danger" href="<?= PROOT; ?>account/end-trade">
                  <span class="material-symbols-outlined mx-auto"> money_off </span>
                </a>
            </div>
            <?php endif; ?>

            <div class="nav-item mt-auto" data-bs-toggle="tooltip" data-bs-title="Contact IT department">
              <a class="nav-link" href="mailto:it@jspence.com">
                <span class="material-symbols-outlined mx-auto"> support </span>
              </a>
            </div>
          </nav>
        </div>
      </div>
    </nav>
  </aside>
