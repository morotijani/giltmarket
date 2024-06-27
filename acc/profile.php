<?php 

    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("includes/header.inc.php");
    include ("includes/nav.inc.php");
    include ("includes/left-side-bar.inc.php");

?>

	<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Profile Details</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?= PROOT; ?>gpmin/index" class="btn btn-sm btn-outline-secondary">Home</a>
                    <a href="<?= PROOT; ?>gpmin/profile" class="btn btn-sm btn-outline-secondary">Refresh</a>
                </div>
                <a href="<?= PROOT; ?>gpmin/profile" class="btn btn-sm btn-outline-secondary">
                    <span data-feather="skip-back"></span>
                    GO back
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
	        <div class="col-md-6">
		        <div class="card">
		        	<div class="card-body">
		        		<?= get_admin_profile(); ?>
		        	</div>
		        </div>
	        </div>
        </div>

    </main>





<?php 

    include ("includes/footer.inc.php");

?>
