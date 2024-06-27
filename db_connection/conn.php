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
 	require_once(BASEURL . 'helpers/function.php');

 ///////////////////////////////////////////////////////////////////////////////////////////
 	// $siteQuery = "
	//     SELECT * FROM garypie_about 
	//     LIMIT 1
	// ";
	// $statement = $conn->prepare($siteQuery);
	// $statement->execute();
	// $site_result = $statement->fetchAll();

	// foreach ($site_result as $site_row) {
	//     $country = ucwords($site_row["about_country"]);
	//     $state = ucwords($site_row["about_state"]);
	//     $city = ucwords($site_row["about_city"]);
	//     $email = $site_row["about_email"];
	//     $phone_1 = $site_row["about_phone"];
	//     $phone_2 = $site_row["about_phone2"];
	//     $fax = $site_row["about_fax"];
	//     $street_1 = ucwords($site_row["about_street1"]);
	//     $street_2 = ucwords($site_row["about_street2"]);
	    
    //     $about_info = $site_row['about_info'];
	// }

 	// ADMIN LOGIN
 	if (isset($_SESSION['ATAdmin'])) {
 		$admin_id = $_SESSION['ATAdmin'];
 		$data = array(
 			':admin_id' => $admin_id
 		);
 		$sql = "
 			SELECT * FROM garypie_admin 
 			WHERE admin_id = :admin_id 
 			LIMIT 1
 		";
 		$statement = $conn->prepare($sql);
 		$statement->execute($data);

 		foreach ($statement->fetchAll() as $admin_data) {
 			$fn = explode(' ', $admin_data['admin_fullname']);
 			$admin_data['first'] = ucwords($fn[0]);
 				$admin_data['last'] = '';
 			if (count($fn) > 1) {
 				$admin_data['last'] = ucwords($fn[1]);
 			}
 		}
 	}

 	// Display on Messages on Errors And Success
 	$flash = '';
 	if (isset($_SESSION['flash_success'])) {
 	 	$flash = '
 	 		<div class="bg-success" id="temporary">
 	 			<p class="text-white">'.$_SESSION['flash_success'].'</p>
 	 		</div>';
 	 	unset($_SESSION['flash_success']);
 	}

 	if (isset($_SESSION['flash_error'])) {
 	 	$flash = '
 	 		<div class="bg-danger" id="temporary">
 	 			<p class="text-white">'.$_SESSION['flash_error'].'</p>
 	 		</div>';
 	 	unset($_SESSION['flash_error']);
 	}
