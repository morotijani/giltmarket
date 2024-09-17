<?php 

    // view admin profile details
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("../includes/header.inc.php");
    include ("../includes/nav.inc.php");

?>
    
    <div class="mb-6 mb-xl-10">
        <div class="row g-3 align-items-center">
            <div class="col">
                <h1 class="ls-tight">Profile details</h1>
            </div>
            <div class="col">
                <div class="hstack gap-2 justify-content-end">
                    <a href="<?= goBack(); ?>" class="btn btn-sm btn-neutral d-none d-sm-inline-flex"><span class="pe-2"><i class="bi bi-arrow-90deg-left"></i> </span><span>Go back</span></a> 
                    <a href="<?= PROOT; ?>acc/settings" class="btn d-inline-flex btn-sm btn-dark"><span>Update</span></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <ul class="list-group">
                <?= get_admin_profile($admin_data[0]['admin_id']); ?>
            </ul>
        </div>
    </div>

<?php 

    include ("../includes/footer.inc.php");

?>
