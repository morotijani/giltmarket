
	<!-- TOAST FOR LIVE MESSAGES -->
    <div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center w-100">
        <div id="live-toast" class="toast fade hide position-fixed bg-light rounded" role="alert" aria-live="assertive" aria-atomic="true" style="top: 5% !important; z-index: 99999;">
            <div class="toast-header small p-1 border-bottom">
                <img src="<?= PROOT; ?>dist/media/logo.jpeg" style="width: 35px; height: 35px;" class="rounded me-2" alt="J-Spence Logo">
                <strong class="me-auto small">J-Spence</strong>
                <small>notification . just now</small>
                <button type="button" class="btn-close small" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body p-1 small">
                
            </div>
        </div>
    </div>

    <script src="<?= PROOT; ?>dist/js/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
	<script src="<?= PROOT; ?>dist/js/main.js"></script>

