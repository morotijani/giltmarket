<?php 


// get company data
function company_data() {
	global $conn;

	$sql = "
		SELECT * FROM jspence
	";
	$statement = $conn->prepare($sql);
	$statement->execute();
	$row = $statement->fetchAll();

	return $row;
}

// Density calculation
function calculateDensity($gram, $volume) {
	$density = ($gram / $volume);

	return round_to_decimal_place(2, $density);
}

// Density calculation
function calculatePounds($gram) {
	$pounds = ($gram / FIXED_POUNDS_FIGURE);

	return round_to_decimal_place(2, $pounds);
}

// Carat calculation
function calculateCarat($gram, $volume) {
	$density = calculateDensity($gram, $volume);

	$carat = (($density - FIXED_CARAT_FIGURE_1) * (FIXED_CARAT_FIGURE_2 / $density));
	return round_to_decimal_place(2, $carat);
}

// Total amount calculation
function calculateTotalAmount($gram, $volume, $current_price) {
	$carat = calculateCarat($gram, $volume);
	$pounds = calculatePounds($gram);

	$total_amount = (($carat * $current_price) / (FIXED_TOTAL_FIGURE * $pounds));
	return round_to_decimal_place(2, $total_amount);
}

function round_to_decimal_place($decimal_place, $figure) {
	return number_format((float)$figure, $decimal_place, '.', '');
}

// add to logs
function add_to_log($message, $log_admin) {
	global $conn;

	$log_id = guidv4();
	$createdAt = date("Y-m-d H:i:s A");
	$sql = "
		INSERT INTO `jspence_logs`(`log_id`, `log_message`, `log_admin`, `createdAt`) 
		VALUES (?, ?, ?, ?)
	";
	$statement = $conn->prepare($sql);
	$result = $statement->execute([$log_id, $message, $log_admin, $createdAt]);

	return false;
	if ($result) {
		return true;
	}
}

function fetch_all_sales($status) {
	global $conn;
	$output = '';

	$sql = "
		SELECT *, jspence_sales.id AS sid, jspence_sales.createdAt AS sca, jspence_sales.updatedAt AS sau FROM jspence_sales 
		INNER JOIN jspence_admin 
		ON jspence_admin.admin_id = jspence_sales.sale_by 
		WHERE sale_status = ? 
		ORDER BY createdAt DESC
	";
	$statement = $conn->prepare($sql);
	$statement->execute([$status]);
	$rows = $statement->fetchAll();

	if ($statement->rowCount() > 0) {
		// code...
		$i = 1;
		foreach ($rows as $row) {
			$arrayOutput = array('reference' => $row['sale_id'], 'customername' => $row['sale_customer_name'], 'date' => $row['sca'], 'gram' => $row['sale_gram'], 'volume' => $row['sale_volume'], 'density' => $row['sale_density'], 'pounds' => $row['sale_pounds'], 'carat' => $row['sale_carat'], 'total_amount' => $row['sale_total_amount'], 'current_price' => $row['sale_price'], 'by' => $row['sale_by'], 'message' => '',);
			
			$outputData = json_encode($arrayOutput);
			
			$output .= '
				<tr>
	                <td>' . $i . '</td>
	                ' . (admin_has_permission() ? ' <td><span class="d-block text-heading fw-bold">' . ucwords($row["admin_fullname"]) . '</span></td> ' : '') . '
	                <td class="text-xs">' . strtoupper($row["sale_customer_name"]) . ' <i class="bi bi-arrow-right mx-2"></i> ' . $row["sale_customer_contact"] . '</td>
	                <td>' . $row["sale_gram"] . '</td>
	                <td>' . $row["sale_volume"] . '</td>
	                <td>' . money($row["sale_price"]) . '</td>
	                <td>' . money($row["sale_total_amount"]) . '</td>
	                <td>' . pretty_date($row["sca"]) . '</td>
	                <td class="text-end">
	                    <button type="button" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6" title="More" data-bs-target="#saleModal_' . $row["sid"] . '" data-bs-toggle="modal">
	                        <i class="bi bi-three-dots"></i>
	                    </button> <a href="javascript:;" onClick="MyWindow=window.open('.$outputData.',\'MyWindow\',\'width=600,height=300\'); return false;" title="Print receipt" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6">
	                        <i class="bi bi-receipt"></i>
	                    </a>
	                </td>
	            </tr>

	            <div class="modal fade" id="saleModal_' . $row["sid"] . '" tabindex="-1" aria-labelledby="saleModalLabel_' . $row["sid"] . '" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content overflow-hidden">
							<div class="modal-header pb-0 border-0">
								<h1 class="modal-title h4" id="saleModalLabel_' . $row["sid"] . '">' . $row["sale_id"] . ' <br>by ' . (admin_has_permission() ? ucwords($row["admin_fullname"]) : 'you' )  . '</h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body p-0 text-center">
								<ul class="list-group">
									<li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Total amount,</small>
				                        <p>' . money($row["sale_total_amount"]) . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Price,</small>
				                        <p>' . money($row["sale_price"]) . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Gram</small>
				                        <p>' . $row["sale_gram"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Volume</small>
				                        <p>' . $row["sale_volume"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Density</small>
				                        <p>' . $row["sale_density"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Pounds</small>
				                        <p>' . $row["sale_pounds"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Carat</small>
				                        <p id="send-amount">' . $row["sale_carat"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Customer</small>
				                        <p id="send-amount">Name: ' . ucwords($row["sale_customer_name"]) . ' | Contact: ' . $row["sale_customer_contact"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Note</small>
				                        <p>' . $row["sale_comment"] . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Date</small>
				                        <p>' . pretty_date($row["sca"]) . '</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Updated date</small>
				                        <p>' . (($row["updatedAt"] == NULL) ? '---' : pretty_date($row["sua"])) . '</p>
				                    </li>
								</ul>
								<div class="p-2"></div>
								<div class="px-6 py-5 bg-body-secondary d-flex justify-content-center">
									<button class="btn btn-sm btn-neutral"><i class="bi bi-pencil me-2"></i>Edit sale</button>&nbsp;<button class="btn btn-sm btn-dark"><i class="bi bi-receipt me-2"></i>Print receipt</button>&nbsp;<button class="btn btn-sm btn-neutral"><i class="bi bi-trash3 me-2"></i>Delete</button>
								</div>
							</div>
						</div>
					</div>
				</div>';

			$i++;
		}
	} else {
		$output = '
			<tr>
				<td colspan="8">No data found.</td>
			</tr>
		';
	}

	return $output;
}


// get total amount of orders today
function total_amount_today($admin, $permission) {
	global $conn;
	$thisDay = date("d");
	$yesterDay = $thisDay - 1;

	$output = [];

	$where = '';
	if ($permission != 'admin,salesperson') {
		$where = ' sale_by = "'.$admin.'" AND ';
	}

	$thisDaySql = "
		SELECT SUM(sale_total_amount) AS total
		FROM `jspence_sales` 
		WHERE $where
		DAY(createdAt) = '{$thisDay}'
	";
	//dnd($thisDaySql);
	$statement = $conn->prepare($thisDaySql);
	$statement->execute();
	$thisDayrow = $statement->fetchAll();

	$thisDayPercentage = ($thisDayrow[0]['total'] / 100);

	$yesterDaySql = "
		SELECT SUM(sale_total_amount) AS total 
		FROM `jspence_sales` 
		WHERE $where 
		DAY(createdAt) = '{$yesterDay}'
	";
	$statement = $conn->prepare($yesterDaySql);
	$statement->execute();
	$yesterDayrow = $statement->fetchAll();

	$yesterDayPercentage = ($yesterDayrow[0]['total'] / 100);

	if ($thisDayPercentage > $yesterDayPercentage) {
		// going top
		$percentage = (($thisDayPercentage + $yesterDayPercentage) / 100);
		$percentage_color = 'success';
		$percentage_icon = 'up-right';
	} else {
		// going down
		$percentage = (($thisDayPercentage - $yesterDayPercentage) / 100);
		$percentage_color = 'danger';
		$percentage_icon = 'down-left';
	}

	$output = [
		'amount' 		=> money($thisDayrow[0]['total']),
		'percentage' 		=> $percentage,
		'percentage_color' 	=> $percentage_color,
		'percentage_icon' 	=> $percentage_icon,
	];

	return $output;
}

// get total amount of orders in current month
function total_amount_thismonth($admin, $permission) {
	global $conn;
	$thisMonth = date("m");
	$lastMonth = $thisMonth - 1;

	$output = [];

	$where = '';
	if ($permission != 'admin,salesperson') {
		$where = ' sale_by = "'.$admin.'" AND ';
	}

	$thisSql = "
		SELECT SUM(sale_total_amount) AS total 
		FROM `jspence_sales` 
		WHERE $where 
		MONTH(createdAt) = '{$thisMonth}'
	";
	$statement = $conn->prepare($thisSql);
	$statement->execute();
	$thisRow = $statement->fetchAll();

	$thisPercentage = ($thisRow[0]['total'] / 100);

	$lastSql = "
		SELECT SUM(sale_total_amount) AS total 
		FROM `jspence_sales` 
		WHERE $where 
		MONTH(createdAt) = '{$lastMonth}'
	";
	$statement = $conn->prepare($lastSql);
	$statement->execute();
	$lastRrow = $statement->fetchAll();

	$lastPercentage = ($lastRrow[0]['total'] / 100);

	if ($thisPercentage > $lastPercentage) {
		// going top
		$percentage = (($thisPercentage + $lastPercentage) / 100);
		$percentage_color = 'success';
		$percentage_icon = 'up-right';
	} else {
		// going down
		$percentage = (($thisPercentage - $lastPercentage) / 100);
		$percentage_color = 'danger';
		$percentage_icon = 'down-left';
	}

	$output = [
		'amount' 			=> money($thisRow[0]['total']),
		'percentage' 		=> $percentage,
		'percentage_color' 	=> $percentage_color,
		'percentage_icon' 	=> $percentage_icon,
	];

	return $output;
}

// count total orders
function count_total_orders($admin, $permission) {
	global $conn;

	$where = '';
	if ($permission != 'admin,salesperson') {
		$where = 'WHERE sale_by = "'.$admin.'"';
	}

	$sql = "
		SELECT COUNT(sale_id) AS total_number 
		FROM `jspence_sales` 
		$where 
	";
	$statement = $conn->prepare($sql);
	$statement->execute();
	$row = $statement->fetchAll();
	
	return $row[0]['total_number'];
}

// get grand amount of orders
function grand_total_amount($admin, $permission) {
	global $conn;
	$thisYear = date("Y");
	$lastYear = $thisYear - 1;

	$output = [];

	$where = '';
	if ($permission != 'admin,salesperson') {
		$where = ' sale_by = "'.$admin.'" AND ';
	}

	$thisSql = "
		SELECT SUM(sale_total_amount) AS total 
		FROM `jspence_sales` 
		WHERE $where 
		YEAR(createdAt) = '{$thisYear}'
	";
	$statement = $conn->prepare($thisSql);
	$statement->execute();
	$thisRow = $statement->fetchAll();

	$thisPercentage = ($thisRow[0]['total'] / 100);

	$lastSql = "
		SELECT SUM(sale_total_amount) AS total 
		FROM `jspence_sales` 
		WHERE $where 
		YEAR(createdAt) = '{$lastYear}'
	";
	$statement = $conn->prepare($lastSql);
	$statement->execute([]);
	$lastRrow = $statement->fetchAll();

	$lastPercentage = ($lastRrow[0]['total'] / 100);

	if ($thisPercentage > $lastPercentage) {
		// going top
		$percentage = (($thisPercentage + $lastPercentage) / 100);
		$percentage_icon = 'up';
	} else {
		// going down
		$percentage = (($thisPercentage - $lastPercentage) / 100);
		$percentage_icon = 'down';
	}

	$where = '';
	if ($permission != 'admin,salesperson') {
		$where = 'WHERE sale_by = "'.$admin.'"';
	}
	$grandTotalSql = "
		SELECT SUM(sale_total_amount) AS total 
		FROM `jspence_sales` 
		$where
	";
	$statement = $conn->prepare($grandTotalSql);
	$statement->execute();
	$grandTotalRow = $statement->fetchAll();

	$output = [
		'grand_total' 			=> money($grandTotalRow[0]['total']),
		'this_year' 			=> money($thisRow[0]['total']),
		'last_year' 			=> money($lastRrow[0]['total']),
		'percentage' 			=> $percentage,
		'percentage_icon' 		=> $percentage_icon,
	];

	return $output;
}

// get logs for admins
function get_logs($admin, $permission) {
	global $conn;
	$output = '';

	$where = '';
	if ($permission != 'admin,salesperson') {
		$where = ' WHERE log_admin = "'.$admin.'" ';
	}

	$sql = "
		SELECT * FROM jspence_logs 
		$where 
		ORDER BY createdAt DESC
		LIMIT 8
	";
	$statement = $conn->prepare($sql);
	$statement->execute();
	$rows = $statement->fetchAll();

	foreach ($rows as $row) {
		// code...
		$output .= '
			<li class="list-group-item small"><em>' . $row["log_message"] . '</em></li>
		';
	}

	return $output;
}


// get recent trades
function get_recent_trades($admin, $permission) {
	global $conn;
	$output = '';

	$today = date('d');

	$where = '';
	if ($permission != 'admin,salesperson') {
		$where = ' sale_by = "'.$admin.'" AND ';
	}

	$sql = "
		SELECT * FROM jspence_sales 
		WHERE $where 
		DAY(createdAt) = ?
	";
	$statement = $conn->prepare($sql);
	$statement->execute([$today]);
	$rows = $statement->fetchAll();
	$counts = $statement->rowCount();

	if ($counts > 0) {
		// code...
		foreach ($rows as $row) {
			// code...
			$output .= '
				<div>
					<div class="d-flex align-items-center gap-3">
						<div>
							<h6 class="progress-text mb-1 d-block">' . $row["sale_id"] . '</h6>
							<p class="text-muted text-xs">' . pretty_date($row["createdAt"]) . '</p>
						</div>
						<div class="text-end ms-auto">
							<span class="h6 text-sm">' . money($row["sale_total_amount"]) . '</span>
						</div>
					</div>
				</div>
			';
		}
	} else {
		$output = '
			<div>
				<div class="d-flex align-items-center gap-3">
					<div>
						<h6 class="progress-text mb-1 d-block">No data found</h6>
						<p class="text-muted text-xs">Current date time: ' . date("l jS \of F Y h:i:s A") . '</p>
					</div>
				</div>
			</div>
		';
	}

	return $output;
}