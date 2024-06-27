<?php 

require_once ("../db_connection/conn.php");


if (isset($_POST['gram'])) {
	
	$gram = (isset($_POST['gram']) ? sanitize($_POST['gram']) : '');
	$volume = (isset($_POST['volume']) ? sanitize($_POST['volume']) : '');
	$pin = sanitize((int)$_POST['pin']);
	$note = (isset($_POST['note']) ? sanitize($_POST['note']) : '');;

	// 
	if ($pin == $user_data['user_pin']) {

		$density = calculateDensity($gram, $volume);
		$pounds = calculatePounds($gram);
		$carat = calculateCarat($gram, $volume);
		$total_amount = calculateTotalAmount($gram, $volume, $current_price = '12.99');

		echo '';
	} else {
		echo "Your PIN is invalid!";
	}

}
