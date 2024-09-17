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
