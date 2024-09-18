<?php 
    require_once ("../db_connection/conn.php");

    $error = '';

    if (isset($_POST['submit_form'])) {
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
            $error = 'Unkown admin.';
        } else {
            if (!password_verify($_POST['admin_password'], $row[0]['admin_password'])) {
                $error = 'Unkown admin.';
            }
        }

        if (!empty($error)) {
            $_SESSION['flash_error'] = $error;
            redirect(PROOT);
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
    <link rel="shortcut icon" href="./assets/favicon/favicon.ico" type="image/x-icon" />
    
    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" />
    
    <!-- Libs CSS -->
    <link rel="stylesheet" href="./assets/css/libs.bundle.css" />
    
    <!-- Theme CSS -->
    <link rel="stylesheet" href="./assets/css/theme.bundle.css" />
    
    <!-- Title -->
    <title>Dashbrd</title>
  </head>

  <body class="d-flex align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12" style="max-width: 25rem">
          <!-- Heading -->
          <h1 class="fs-1 text-center">Sign in</h1>

          <!-- Subheading -->
          <p class="lead text-center text-body-secondary">Access our dashboard and start tracking your tasks.</p>

          <!-- Form -->
          <form class="mb-5">
            <div class="mb-4">
              <label class="visually-hidden" for="email">Email Address</label>
              <input class="form-control" id="email" type="email" placeholder="Enter your email address..." />
            </div>
            <button class="btn btn-secondary w-100" type="submit">Sign in</button>
          </form>

          <!-- Text -->
          <p class="text-center text-body-secondary mb-0">Don't have an account yet? <a href="./sign-up.html">Sign up</a>.</p>
        </div>
      </div>
    </div>

    <!-- JAVASCRIPT -->
    <!-- Map JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js'></script>
    
    <!-- Vendor JS -->
    <script src="./assets/js/vendor.bundle.js"></script>
    
    <!-- Theme JS -->
    <script src="./assets/js/theme.bundle.js"></script>
  </body>
</html>
