<?php 

require_once ("../db_connection/conn.php");


if (isset($_POST['gram-amount'])) {
	
	$gram = (isset($_POST['gram-amount']) ? sanitize($_POST['gram-amount']) : '');
	$volume = (isset($_POST['volume-amount']) ? sanitize($_POST['volume-amount']) : '');
	$pin = sanitize((int)$_POST['pin']);
	$note = (isset($_POST['note']) ? sanitize($_POST['note']) : '');;

	if ($pin == 1234) {

		$density = calculateDensity($gram, $volume);
		$pounds = calculatePounds($gram);
		$carat = calculateCarat($gram, $volume);
		$total_amount = calculateTotalAmount($gram, $volume, $current_price = '12.99');

		$sale_id = guidv4();
		$createdAt = date("Y-m-d H:i:s A");;
		$sql = "
			INSERT INTO `jspence_sales`(`sale_id`, `sale_gram`, `sale_volume`, `sale_density`, `sale_pounds`, `sales_carat`, `sale_total_amount`, `sale_comment`, `sale_by`, `createdAt`) 
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		";
		$statement = $conn->prepare($sql);
		$result = $statement->execute([$sale_id, $gram, $volume, $density, $pounds, $carat, $total_amount, $note, 1, $createdAt]);
		if (isset($result)) {
			// code...
			$message = "added new sale with gram of " . $gram . " and volume of " . $volume . " and total amount of " . $total_amount ." ";
			$log_admin = $admin_data['admin_id'];
			add_to_log($message, $log_admin);

			echo "";
		} else {
			echo 'Something went wrong.';
		}

	} else {
		echo "Your PIN is invalid!";
	}

}
