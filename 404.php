<?php require_once ("db_connection/conn.php"); ?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc." />
    
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
    <title>You lost in J.Spence Dashboard</title>

    <style>
    </style>
  </head>

  <body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
                <div class="col-12" style="max-width: 25rem">
                    <!-- Heading -->
                    <h1 class="fs-1 text-center">😅</h1>

                    <!-- Subheading -->
                    <p class="lead text-center text-body-secondary">Oops! Something went wrong, but we'll get you back on track.</p>

                <!-- Button -->
                    <a href="<?= PROOT; ?>index" class="btn btn-secondary w-100">Return to dashboard</a>
                </div>
            </div>
        </div>

    <!-- JAVASCRIPT -->
    <!-- Map JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js'></script>
    
    <!-- Vendor JS -->
    <script src="<?= PROOT; ?>assets/js/vendor.bundle.js"></script>
    
    <!-- Theme JS -->
    <script src="<?= PROOT; ?>assets/js/theme.bundle.js"></script>

</body>
</html>
