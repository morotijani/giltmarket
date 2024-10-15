<?php 

require_once ("../db_connection/conn.php");

$message = '';
$continue = 'no';
$ouput = '';

if (isset($_POST['gram'])) {
	$gram = (isset($_POST['gram']) ? $_POST['gram'] : '');
	$volume = (isset($_POST['volume']) ? $_POST['volume'] : '');
	$current_price = (isset($_POST['current_price']) ? $_POST['current_price'] : '');

	$density = calculateDensity($gram, $volume);
	$pounds = calculatePounds($gram);
	$carat = calculateCarat($gram, $volume);
	$total_amount = calculateTotalAmount($gram, $volume, $current_price);

	$today_balance = _capital($admin_data['admin_id'])['today_balance'];
	$message = 'Calculations made correctly.';

	if (admin_has_permission('salesperson')) {
		if ($total_amount > 0): 
			if ($total_amount <= $today_balance) {
				$continue = 'yes';
			} else {
				$message = "Today's remaining cash balance cannot complete this trade!";
			}
		else: 
			$message = "There was a problem with the calculations";
		endif;
	} else if (admin_has_permission('supervisor') && remaining_gold_balance($admin_id) < 0) {
		$message = "Today's remaining gold balance cannot complete this trade!";
	} else {
		$continue = 'yes';
	}

	$arrayOutput = array(
		'density' => $density, 
		'message' => $message, 
		'pounds' => $pounds, 
		'carat' => $carat, 
		'current_price' => $current_price, 
		'total_amount' => $total_amount,
		'continue' => $continue,
		'today_balance' => $today_balance
	);
	$ouput = json_encode($arrayOutput);
}

echo $ouput;
