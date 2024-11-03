<?php 

    require_once ("../db_connection/conn.php");

    $message = "logged out from system";
    add_to_log($message, $_SESSION['JSAdmin']);

    $query = "
		UPDATE jspence_admin_login_details 
		SET updatedAt = ? 
		WHERE login_details_admin_id = ?
	";
	$statement = $conn->prepare($query);
	$statement->execute([
        date("Y-m-d H:i:s"), 
        $_SESSION['JSAdmin']
    ]);

    unset($_SESSION['JSAdmin']);

    redirect(PROOT . 'auth/login');
