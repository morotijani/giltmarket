<?php 

    require_once ("../db_connection/conn.php");

    if (admin_is_logged_in()) {
        redirect(PROOT);
    }

    $error = '';

    if (isset($_POST['submit_login'])) {
        if (empty($_POST['admin_email']) || empty($_POST['admin_password'])) {
            $error = 'You must provide email and password.';
        }
        $query = "
            SELECT * FROM jspence_admin 
            WHERE admin_email = ? 
            LIMIT 1 
        ";
        $statement = $conn->prepare($query);
        $statement->execute([sanitize($_POST['admin_email'])]);
        $count_row = $statement->rowCount();
        $row = $statement->fetchAll();

        if ($count_row < 1) {
            $error = 'Unkown admin!';
        } else {
            if (!password_verify($_POST['admin_password'], $row[0]['admin_password'])) {
                $error = 'Unkown admin!';
            }
        }

        if (!empty($error)) {
            $_SESSION['flash_error'] = $error;
            redirect(PROOT . 'auth/login');
        } else {
            $admin_id = $row[0]['admin_id'];
            adminLogin($admin_id);
        }
        
    }
?>


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
    <title>Dashbrd . JSPENCE</title>

    <style>
    </style>
  </head>

<body class="d-flex align-items-center">
    <div class="container">
    <?= $flash; ?>
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 25rem">
                <!-- Heading -->
                <h1 class="fs-1 text-center">Sign in</h1>

                <!-- Subheading -->
                <p class="lead text-center text-body-secondary">Access our dashboard and start tracking your tasks.</p>

                <!-- Form -->
                <form class="mb-5" method="POST">
                    <div class="mb-4">
                        <label class="visually-hidden" for="email">Email Address</label>
                        <input class="form-control" autofocus autocomplete="off" name="admin_email" id="admin_email" type="email" placeholder="Enter your email address..." reguired />
                    </div>
                    <div class="mb-4">
                        <label class="visually-hidden" for="email">Email Address</label>
                        <input class="form-control" id="admin_password" name="admin_password" type="password" placeholder="***" required />
                    </div>
                    <button name="submit_login" class="btn btn-secondary w-100" type="submit">Sign in</button>
                </form>

                <!-- Text -->
                <p class="text-center text-body-secondary mb-0">Missing password? <a href="javascript:;">Recover here</a>.</p>
				<p class="text-center text-body-secondary">By connecting, know that we save all actions into logs for future references. You agree to J-Spence <a href="#">Terms of Service</a></p>
				
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="<?= PROOT; ?>assets/js/jquery-3.7.1.min.js"></script>

    <!-- Map JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js'></script>
    <script src="<?= PROOT; ?>assets/js/switcher.js"></script>

    
    <!-- Vendor JS -->
    <script src="<?= PROOT; ?>assets/js/vendor.bundle.js"></script>
    
    <!-- Theme JS -->
    <script src="<?= PROOT; ?>assets/js/theme.bundle.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            // Fade out messages
            $("#temporary").fadeOut(5000);
        });
    </script>
</body>
</html>
