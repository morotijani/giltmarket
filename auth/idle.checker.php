<?php
    // check if user is idle 

    require_once ("../db_connection/conn.php");

    if (isset($_POST['type'])) 
        if ($_POST['type'] == 'idle') {
            if (!idle_user()) {
                
                session_unset();
                session_destroy();

                echo 'idle';
    
                // Redirect to the login page or show a message
                // $_SESSION['flash_error'] = 'Session expired. Please log in again!';
                // redirect(PROOT . 'auth/login');
                // exit;
            }
        }
    