<?php 

    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("includes/header.inc.php");
    include ("includes/nav.inc.php");

?>
    
    <div class="mb-6 mb-xl-10">
        <div class="row g-3 align-items-center">
            <div class="col">
                <h1 class="ls-tight">Profile details</h1>
            </div>
            <div class="col">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-sm btn-square btn-neutral rounded-circle d-xxl-none" data-bs-toggle="offcanvas" data-bs-target="#responsiveOffcanvas" aria-controls="responsiveOffcanvas"><i class="bi bi-three-dots"></i></button> <button type="button" class="btn btn-sm btn-neutral d-none d-sm-inline-flex" data-bs-target="#buyModal" data-bs-toggle="modal"><span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Go back</span></button> 
                    <a href="/pages/page-overview.html" class="btn d-inline-flex btn-sm btn-dark"><span>Referesh</span></a>
                </div>
            </div>
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
