<?php 

    // view admin profile details
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("../includes/header.inc.php");
    include ("../includes/aside.inc.php");
    include ("../includes/left.nav.inc.php");
    include ("../includes/top.nav.inc.php");

?>

    <!-- Content -->
    <div class="container-lg">
        <!-- Page header -->
        <div class="row align-items-center mb-7">
            <div class="col-auto">
                <!-- Avatar -->
                <div class="avatar avatar-xl rounded text-warning">
                    <i class="fs-2" data-duoicon="user"></i>
                </div>
            </div>
            <div class="col">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a class="text-body-secondary" href="#">Account</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Account</li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="fs-5 mb-0">Account</h1>
            </div>
            <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                <!-- Action -->
                <div class="row gx-2">
                    <div class="col-6 col-sm-auto">
                        <a class="btn btn-danger d-block" href="<?= PROOT; ?>account/settings"> Edit </a>
                    </div>
                    <div class="col-6 col-sm-auto">
                        <a class="btn btn-light d-block" href="<?= goBack(); ?>"> Go back </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="row">
            <div class="col-12 col-lg-3">
                <!-- Nav -->
                <nav class="nav nav-pills position-sticky flex-column mb-8" id="accountNav" style="top: 2rem">
                    <a class="nav-link" href="<?= PROOT; ?>account/profile">General</a>
                    <a class="nav-link" href="<?= PROOT; ?>account/settings">Update account</a>
                    <a class="nav-link" href="<?= PROOT; ?>account/change-password">Change password</a>
                    <a class="nav-link text-danger" href="<?= PROOT; ?>auth/logout">Logout</a>
                </nav>
            </div>
            <div class="col-12 col-lg-9" data-bs-spy="scroll" data-bs-target="#accountNav" data-bs-smooth-scroll="true" tabindex="0">
                <!-- General -->
                <section class="card bg-body-tertiary border-transparent mb-5" id="general">
                    <div class="card-body">
                        <?= get_admin_profile($admin_data['admin_id']); ?>
                    </div>
                </section>
             </div>
        </div>
    </div>


<?php include ("../includes/footer.inc.php"); ?>
