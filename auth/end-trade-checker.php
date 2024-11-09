<?php 

    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
		admn_login_redirect();
	}

    //
    $capital_mover = capital_mover($admin_id);
	if (is_array($capital_mover) && $capital_mover["msg"] != "touched") {
		redirect(PROOT);
	}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc." />
    <script src="<?= PROOT; ?>assets/js/switcher.js"></script>

    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= PROOT; ?>assets/media/logo.jpeg" type="image/x-icon" />
    
    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" /> 
    
    <!-- Libs CSS -->
    <link rel="stylesheet" href="<?= PROOT; ?>assets/css/libs.bundle.css" />
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="<?= PROOT; ?>assets/css/jspence.css" />
    <link rel="stylesheet" href="<?= PROOT; ?>assets/css/theme.bundle.css" />
    
    <!-- Title -->
    <title>JSPENCE</title>

    <style>
    </style>
</head>

    <body class="d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12" style="max-width: 30rem">
                    <!-- Heading -->
                    <h1 class="fs-1 text-center">😞</h1>

                    <!-- Subheading -->
                    <p class="lead text-center text-body-secondary">Oops! <?= $admin_data['first']; ?>, you did not end your trade the last time you started trade. click on the button below to end that trade to start new trade for this day.</p>
                    <br>
                </div>
                <div class="col-12" style="">
                    <small class="text-muted">Previous data preview</small>
                    <div class="row mt-1 mb-8">
                        <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                    <div class="col">
                                        <!-- Heading -->
                                        <h4 class="fs-sm fw-normal text-body-secondary mb-1">Money given</h4>

                                        <!-- Text -->
                                        <div class="fs-4 fw-semibold"><?= money($capital_mover["capital"]); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <!-- Avatar -->
                                        <div class="avatar avatar-lg bg-body text-primary">
                                        <i class="fs-4" data-duoicon="credit-card"></i>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-xxl-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-sm fw-normal text-body-secondary mb-1">Hours logged</h4>

                                            <!-- Text -->
                                            <div class="fs-4 fw-semibold"><?= money($capital_mover["balance"]); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <!-- Avatar -->
                                            <div class="avatar avatar-lg bg-body text-primary">
                                            <i class="fs-4" data-duoicon="clock"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xxl-3 mb-4 mb-md-0">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-sm fw-normal text-body-secondary mb-1">Avg. time</h4>

                                            <!-- Text -->
                                            <div class="fs-4 fw-semibold"><?= $capital_mover["date"]; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <!-- Avatar -->
                                            <div class="avatar avatar-lg bg-body text-primary">
                                            <i class="fs-4" data-duoicon="slideshow"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xxl-3">
                            <div class="card bg-body-tertiary border-transparent">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- Heading -->
                                            <h4 class="fs-sm fw-normal text-body-secondary mb-1">Total trades</h4>

                                            <!-- Text -->
                                            <div class="fs-4 fw-semibold"><?= 0; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <!-- Avatar -->
                                            <div class="avatar avatar-lg bg-body text-primary">
                                            <i class="fs-4" data-duoicon="discount"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-12" style="max-width: 25rem">
                    <!-- Button -->
                    <button class="btn btn-secondary w-100">Go to End trade</button>
                </div>
            </div>
        </div>

        <!-- JAVASCRIPT -->
        <script src="<?= PROOT; ?>assets/js/jquery-3.7.1.min.js"></script>

        <!-- Map JS -->
        <script src='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js'></script>
        
        <!-- Vendor JS -->
        <script src="<?= PROOT; ?>assets/js/vendor.bundle.js"></script>
        
        <!-- Theme JS -->
        <script src="<?= PROOT; ?>assets/js/theme.bundle.js"></script>
</body>
</html>
