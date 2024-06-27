<?php 

require_once ("../../connection/conn.php");

$message = '';

if (isset($_POST['gram'])) {
	$gram = (isset($_POST['gram']) ? $_POST['gram'] : '');
	$volume = (isset($_POST['volume']) ? $_POST['volume'] : '');

	// 

	if (empty($message)) {

		$density = calculateDensity($gram, $volume);
		$pounds = calculatePounds($gram);
		$carat = calculateCarat($gram, $volume);
		$total_amount = calculateTotalAmount($gram, $volume, $current_price = '12.99');

		$message = 'Calculations made correctly.';
	}

}

$arrayOutput = array('density' => $density, 'message' => $message, 'pounds' => $pounds, 'carat' => $carat, 'total_amount' => $total_amount);
$ouput = json_encode($arrayOutput);
echo $ouput;