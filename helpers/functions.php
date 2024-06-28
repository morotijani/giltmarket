<?php 


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

	$total_amount = (($carat * $current_price) / (FIXED_TOTAL_FIGURE / $pounds));
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
		SELECT * FROM jspence_sales 
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
			// code...
			$output .= '
				<tr>
	                <td>' . $i . '</td>
	                <td><span class="d-block text-heading fw-bold">' . $row["sale_by"] . '</span></td>
	                <td class="text-xs">' . strtoupper($row["sale_customer_name"]) . ' <i class="bi bi-arrow-right mx-2"></i> ' . $row["sale_customer_contact"] . '</td>
	                <td>' . $row["sale_gram"] . '</td>
	                <td>' . $row["sale_volume"] . '</td>
	                <td>' . money($row["sale_total_amount"]) . '</td>
	                <td>' . pretty_date($row["createdAt"]) . '</td>
	                <td class="text-end">
	                    <button type="button" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6" title="More" data-bs-target="#saleModal_' . $row["id"] . '" data-bs-toggle="modal">
	                        <i class="bi bi-three-dots"></i>
	                    </button> <button type="button" title="Print receipt" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6">
	                        <i class="bi bi-receipt"></i>
	                    </button>
	                </td>
	            </tr>

	            <div class="modal fade" id="saleModal_' . $row["id"] . '" tabindex="-1" aria-labelledby="saleModalLabel_' . $row["id"] . '" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content overflow-hidden">
							<div class="modal-header pb-0 border-0">
								<h1 class="modal-title h4" id="saleModalLabel_' . $row["id"] . '">Select token</h1>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body p-0">
								<ul class="list-group">
									<li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Total amount,</small>
				                        <p>` + $("#total-amount").val() + ` ₵</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Gram</small>
				                        <p>` + Number($("#gram-amount").val()).toFixed(2) + `</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Volume</small>
				                        <p>` + Number($("#volume-amount").val()).toFixed(2) + `</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Density</small>
				                        <p>` + $("#density").text() + `</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Pounds</small>
				                        <p>` + $("#pounds").text() + `</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Carat</small>
				                        <p id="send-amount">` + $("#carat").text() + `</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Customer</small>
				                        <p id="send-amount">Name: ` + $("#customer_name").val() + ` | Contact: ` + $("#customer_contact").val() + `</p>
				                    </li>
				                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
				                        <small class="text-muted">Note</small>
				                        <p>` + $("#note").val() + `</p>
				                    </li>
								</ul>
								<div class="p-2"></div>
								<div class="px-6 py-5 bg-body-secondary d-flex justify-content-center">
									<button class="btn btn-sm btn-dark"><i class="bi bi-gear me-2"></i>Manage tokens</button>&nbsp;<button class="btn btn-sm btn-dark"><i class="bi bi-receipt me-2"></i>Print receipt</button>&nbsp;<button class="btn btn-sm btn-dark"><i class="bi bi-trash3 me-2"></i>Delete</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
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
