<?php 

    require_once ("../db_connection/conn.php");

    $query = "
		UPDATE giltmarket_admin_login_details 
		SET updateAt = ? 
		WHERE login_details_admin_id = ? 
        AND login_details_id = ?
	";
	$statement = $conn->prepare($query);
	$statement->execute([
        date("Y-m-d H:i:s"), 
        $_SESSION['JSAdmin'], 
        $admin_data['login_details_id']
    ]);
    
    $message = "logged out from system";
    add_to_log($message, $_SESSION['JSAdmin']);
    
    unset($_SESSION['JSAdmin']);
    unset($_SESSION['last_activity']);

    redirect(PROOT . 'auth/login');
