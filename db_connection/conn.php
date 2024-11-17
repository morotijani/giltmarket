<?php

	require ($_SERVER['DOCUMENT_ROOT'] . '/jspence/bootstrap.php');

	// Connection To Database
	$driver = $_ENV['DB_DRIVER'];
	$hostname = $_ENV['DB_HOST'];
	$port = $_ENV['DB_PORT'];
	$database = $_ENV['DB_DATABASE'];
	$username = $_ENV['DB_USERNAME'];
	$password = $_ENV['DB_PASSWORD'];

	$conn = new PDO($driver . ":host=$hostname;charset=utf8mb4;dbname=$database", $username, $password);

	session_start();
	date_default_timezone_set("Africa/Accra");

	require_once ($_SERVER['DOCUMENT_ROOT'] . '/jspence/config.php');
    require_once (BASEURL . 'helpers/helpers.php');
    require_once (BASEURL . 'helpers/functions.php');


 	// ADMIN LOGIN
 	if (isset($_SESSION['JSAdmin'])) {
 		$admin_id = $_SESSION['JSAdmin'];

 		$sql = "
 			SELECT * FROM jspence_admin 
 			WHERE jspence_admin.admin_id = ? 
 			LIMIT 1
 		";
 		$statement = $conn->prepare($sql);
 		$statement->execute([$admin_id]);
 		$admin_dt = $statement->fetchAll();
		if ($statement->rowCount() > 0) {
			$admin_data = $admin_dt[0];

			$details_data = $conn->query("SELECT * FROM jspence_admin_login_details WHERE jspence_admin_login_details.login_details_admin_id = '" . $admin_id . "' ORDER BY id DESC LIMIT 1")->fetchAll();
			
			if (is_array($details_data) && count($details_data) > 0) {
				$admin_data = array_merge($admin_data, $details_data[0]);
			}

			$fn = explode(' ', $admin_data['admin_fullname']);
			$admin_data['first'] = ucwords($fn[0]);
			$admin_data['last'] = '';
			if (count($fn) > 1) {
				$admin_data['last'] = ucwords($fn[1]);
			}
			$admin_permission = $admin_data['admin_permissions']; // get admin's permission
		} else {
			redirect(PROOT);
		}
		
 	}

	// check if user is iddle 

	// Check if the user is logged in
	if (isset($_SESSION['JSAdmin']) && $_SESSION['JSAdmin'] === true) {
		// Check the last activity time
		if (isset($_SESSION['last_activity'])) {
			$idleTime = time() - $_SESSION['last_activity'];

			// If the idle time exceeds the timeout period
			if ($idleTime > IDLE_TIMEOUT) {

				//
				$log_msg = "Session expired."
				add_to_log($log_msg, $admin_id);

				// Destroy the session and log out the user
				session_unset();
				session_destroy();

				// Redirect to the login page or show a message
				$_SESSION['flash_error'] = 'Session expired. Please log in again!';
				redirect(PROOT . 'auth/login');
				exit;
			}
		}

		// Update the last activity timestamp
		$_SESSION['last_activity'] = time();
	} else {
		// If not logged in, redirect to login page
		header("Location: login.php");
		exit;
	}

 	// Display on Messages on Errors And Success
 	$flash = '';
 	if (isset($_SESSION['flash_success'])) {
 	 	$flash = '
 	 		<div class="alert alert-success" id="temporary" style="border-left: 6px solid gold;">
 	 			' . $_SESSION['flash_success'] . '
 	 		</div>';
 	 	unset($_SESSION['flash_success']);
 	}

 	if (isset($_SESSION['flash_error'])) {
 	 	$flash = '
 	 		<div class="alert alert-danger" id="temporary" style="border-left: 6px solid gold;">
 	 			' . $_SESSION['flash_error'] . '
 	 		</div>';
 	 	unset($_SESSION['flash_error']);
 	}
