<?php

	// Connection To Database
	$servername = 'localhost';
	$username = 'root';
	$password = '';
	$conn = new PDO("mysql:host=$servername;dbname=jspence", $username, $password);

	session_start();

	date_default_timezone_set("Africa/Accra");

	require_once($_SERVER['DOCUMENT_ROOT'].'/jspence/config.php');
 	require_once(BASEURL . 'helpers/helpers.php');
 	require_once(BASEURL . 'helpers/functions.php');


 	// ADMIN LOGIN
 	if (isset($_SESSION['JSAdmin'])) {
 		$admin_id = $_SESSION['JSAdmin'];

 		$sql = "
 			SELECT * FROM jspence_admin 
 			WHERE admin_id = ? 
 			LIMIT 1
 		";
 		$statement = $conn->prepare($sql);
 		$statement->execute([$admin_id]);
 		$admin_data = $statement->fetchAll();

		$fn = explode(' ', $admin_data[0]['admin_fullname']);
		$admin_data['first'] = ucwords($fn[0]);
			$admin_data['last'] = '';
		if (count($fn) > 1) {
			$admin_data['last'] = ucwords($fn[1]);
		}
 		
 	}

 	// Display on Messages on Errors And Success
 	$flash = '';
 	if (isset($_SESSION['flash_success'])) {
 	 	$flash = '
 	 		<div class="bg-success" id="temporary">
 	 			<p class="text-white px-2">'.$_SESSION['flash_success'].'</p>
 	 		</div>';
 	 	unset($_SESSION['flash_success']);
 	}

 	if (isset($_SESSION['flash_error'])) {
 	 	$flash = '
 	 		<div class="bg-danger" id="temporary">
 	 			<p class="text-white px-2">'.$_SESSION['flash_error'].'</p>
 	 		</div>';
 	 	unset($_SESSION['flash_error']);
 	}
