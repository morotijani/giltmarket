<?php
    // check if user is iddle 

    if (isset($_POST['type'])) 
        if ($_POST['type']) {
            
            if (!idle_user()) {
                session_unset();
                session_destroy();

                echo 'iddle';
    
                // Redirect to the login page or show a message
                // $_SESSION['flash_error'] = 'Session expired. Please log in again!';
                // redirect(PROOT . 'auth/login');
                // exit;
            }
        }
        
        
    