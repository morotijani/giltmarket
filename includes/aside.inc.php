
  <aside class="aside aside-sm d-none d-xl-flex">
    <nav class="navbar navbar-expand-xl navbar-vertical">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="sidenavSmallCollapse">
          <!-- Nav -->
          <nav class="navbar-nav nav-pills h-100">
            <div class="nav-item">
              <div data-bs-toggle="tooltip" data-bs-placement="right" data-bs-trigger="hover" data-bs-title="Color mode">
                <a
                  class="nav-link"
                  data-bs-toggle="collapse"
                  data-bs-theme-switcher
                  href="#colorModeOptions"
                  role="button"
                  aria-expanded="false"
                  aria-controls="colorModeOptions"
                >
                  <span class="material-symbols-outlined mx-auto"> </span>
                </a>
              </div>
              <div class="collapse" id="colorModeOptions">
                <div class="border-top border-bottom py-2">
                  <a
                    class="nav-link fs-sm"
                    data-bs-toggle="tooltip"
                    data-bs-placement="right"
                    data-bs-trigger="hover"
                    data-bs-title="Light"
                    data-bs-theme-value="light"
                    href="#"
                    role="button"
                  >
                    <span class="material-symbols-outlined mx-auto"> light_mode </span>
                  </a>
                  <a
                    class="nav-link fs-sm"
                    data-bs-toggle="tooltip"
                    data-bs-placement="right"
                    data-bs-trigger="hover"
                    data-bs-title="Dark"
                    data-bs-theme-value="dark"
                    href="#"
                    role="button"
                  >
                    <span class="material-symbols-outlined mx-auto"> dark_mode </span>
                  </a>
                  <a
                    class="nav-link fs-sm"
                    data-bs-toggle="tooltip"
                    data-bs-placement="right"
                    data-bs-trigger="hover"
                    data-bs-title="Auto"
                    data-bs-theme-value="auto"
                    href="#"
                    role="button"
                  >
                    <span class="material-symbols-outlined mx-auto"> contrast </span>
                  </a>
                </div>
              </div>
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
            <div class="nav-item" data-bs-toggle="tooltip" data-bs-title="Current Gold Proce">
              <a class="nav-link" href="" target="_blank">
                  CGP 
                </a>
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
