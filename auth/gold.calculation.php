<?php 

require_once ("../db_connection/conn.php");

$message = '';

if (isset($_POST['gram'])) {
	$gram = (isset($_POST['gram']) ? $_POST['gram'] : '');
	$volume = (isset($_POST['volume']) ? $_POST['volume'] : '');
	$current_price = (isset($_POST['current_price']) ? $_POST['current_price'] : '');

	// 

	if (empty($message)) {

		$density = calculateDensity($gram, $volume);
		$pounds = calculatePounds($gram);
		$carat = calculateCarat($gram, $volume);
		$total_amount = calculateTotalAmount($gram, $volume, $current_price = '12.99');

		$message = 'Calculations made correctly.';
	}

}

$arrayOutput = array('density' => $density, 'message' => $message, 'pounds' => $pounds, 'carat' => $carat, 'current_price' => $current_price, 'total_amount' => $total_amount);
$ouput = json_encode($arrayOutput);
echo $ouput;
