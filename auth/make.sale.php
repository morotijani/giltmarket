<?php 

require_once ("../db_connection/conn.php");

$output = '';
if (isset($_POST['gram-amount'])) {
	
	$log_admin = $admin_data['admin_id'];
	$sale_daily =  _capital($admin_data['admin_id'])['today_capital_id'];
	
	$gram = (isset($_POST['gram-amount']) ? sanitize($_POST['gram-amount']) : '');
	$volume = (isset($_POST['volume-amount']) ? sanitize($_POST['volume-amount']) : '');
	$current_price = (isset($_POST['current_price']) ? sanitize($_POST['current_price']) : '');
	$customer_name = (isset($_POST['customer_name']) ? sanitize($_POST['customer_name']) : '');
	$customer_contact = (isset($_POST['customer_contact']) ? sanitize($_POST['customer_contact']) : '');
	$pin = sanitize((int)$_POST['pin']);
	$note = (isset($_POST['note']) ? sanitize($_POST['note']) : '');
	$sale_type = ((admin_has_permission('supervisor')) ? 'in' : 'out');

	if ($pin == $admin_data['admin_pin']) {

		$density = calculateDensity($gram, $volume);
		$pounds = calculatePounds($gram);
		$carat = calculateCarat($gram, $volume);
		$total_amount = calculateTotalAmount($gram, $volume, $current_price);

		$today_balance = _capital($admin_data['admin_id'])['today_balance'];
		$sale_id = guidv4();
		$createdAt = date("Y-m-d H:i:s");

		if (admin_has_permission('salesperson')) {
			if ($total_amount < 0) {
				$output = "There was a problem with the calculations";
			}

			if ($total_amount > $today_balance) {
				$output = "Today's remaining balance cannot complete this trade!";
			}
		}

		if (empty($output) || $output == '') {
			$sql = "
				INSERT INTO `jspence_sales`(`sale_id`, `sale_gram`, `sale_volume`, `sale_density`, `sale_pounds`, `sale_carat`, `sale_price`, `sale_total_amount`, `sale_customer_name`, `sale_customer_contact`, `sale_comment`, `sale_type`, `sale_by`, `sale_daily`, `createdAt`) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
			";
			$statement = $conn->prepare($sql);
			$result = $statement->execute([$sale_id, $gram, $volume, $density, $pounds, $carat, $current_price, $total_amount, $customer_name, $customer_contact, $note, $sale_type, $log_admin, $sale_daily, $createdAt]);
			if (isset($result)) {
				$today = date("Y-m-d");
				$t = (admin_has_permission('supervisor') ? 'in' : 'out');
				$q = "
					SELECT 
						SUM(jspence_sales.sale_total_amount) AS ttsa, 
						CAST(jspence_sales.createdAt AS date) AS sd 
					FROM `jspence_sales` 
					WHERE CAST(jspence_sales.createdAt AS date) = ? 
					AND jspence_sales.sale_type = ? 
					AND jspence_sales.sale_by = ? 
					AND jspence_sales.sale_status = ?
				";
				$statement = $conn->prepare($q);
				$statement->execute([$today, $t, $admin_data['admin_id'], 0]);
				$r = $statement->fetchAll();
				
				$trade_status = 'out-trade';
				if (admin_has_permission('salesperson')) {
					if ($r[0]['ttsa'] > 0) {
						$today_total_balance = (float)(_capital($admin_data['admin_id'])['today_capital'] - $r[0]['ttsa']);
					}
				}

				if (admin_has_permission('supervisor')) {
					$trade_status = 'in-trade';
					$today_total_balance = $r[0]['ttsa'];
				}

				update_today_capital_given_balance($trade_status, $today_total_balance, $today, $log_admin);

				$message = "added new sale with gram of " . $gram . " and volume of " . $volume . " and total amount of " . money($total_amount) ." and price of " . money($current_price) . " on id " . $sale_id . "";
				add_to_log($message, $log_admin);

				$arrayOutput = array('reference' => $sale_id, 'customername' => $customer_name, 'date' => $createdAt, 'gram' => $gram, 'volume' => $volume, 'density' => $density, 'pounds' => $pounds, 'carat' => $carat, 'total_amount' => $total_amount, 'current_price' => $current_price, 'by' => $log_admin, 'message' => '',);
				$ouput = json_encode($arrayOutput);
					
				echo $ouput;
			} else {
				$output = 'Something went wrong.';
			}
		}
	} else {
		$output = "Your PIN is invalid!";
	}
}

echo $output;
