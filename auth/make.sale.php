<?php 

require_once ("../db_connection/conn.php");


if (isset($_POST['gram-amount'])) {
	
	$gram = (isset($_POST['gram-amount']) ? sanitize($_POST['gram-amount']) : '');
	$volume = (isset($_POST['volume-amount']) ? sanitize($_POST['volume-amount']) : '');
	$pin = sanitize((int)$_POST['pin']);
	$note = (isset($_POST['note']) ? sanitize($_POST['note']) : '');;

	if ($pin == $admin_data['admin_pin']) {

		$density = calculateDensity($gram, $volume);
		$pounds = calculatePounds($gram);
		$carat = calculateCarat($gram, $volume);
		$total_amount = calculateTotalAmount($gram, $volume, $current_price = '12.99');



		echo '';
	} else {
		echo "Your PIN is invalid!";
	}

}
