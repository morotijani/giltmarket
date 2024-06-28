<?php 
	require_once ("../db_connection/conn.php");

	include ("../includes/header.inc.php");

	$crow = company_data();

	$data = $_GET['data'] ?? "";
	
	$obj = json_decode($data, true);

?>
		<div class="card">
			<div class="card-header pb-0 border-0">
				<h1 class="modal-title h4 text-center" id="connectWalletModalLabel">
					<?= ucwords($crow[0]['company_name']); ?>
					<br>
					<?= $crow[0]['company_address']; ?>
					<br>
					<?= $crow[0]['company_phone1'] . (($crow[0]['company_phone2'] == '') ? '' : ' / ' . $crow[0]['company_phone2']); ?>
					<br>
					PURCHASE INVOICE
				</h1>
			</div>
			<div class="card-body undefined">
				<table class="table table-sm table-striped table-bordered">
					<tbody>
						<tr>
							<td>
								REFERENCE/ID
								<br>
								<?= $obj['reference']; ?>
							</td>
							<td>
								NAME OF CUSTOMER
								<br>
								<?= ucwords($obj['customername']); ?>
							</td>
						</tr>
						<tr>
							<td>
								GRAM
								<br>
								<?= $obj['gram']; ?>
							</td>
							<td>
								POUNDS
								<br>
								<?= $obj['pounds']; ?>
							</td>
						</tr>
						<tr>
							<td>
								VOLUME
								<br>
								<?= $obj['volume']; ?>
							</td>
							<td>
								DENSITY
								<br>
								<?= $obj['density']; ?>
							</td>
						</tr>
						<tr>
							<td>
								
							</td>
							<td>
								CARAT
								<br>
								<?= $obj['carat']; ?>
							</td>
						</tr>
						<tr>
							<td>
								PRICE
							</td>
							<td>
								<?= money($obj['current_price']); ?>
							</td>
						</tr>
						<tr>
							<td>
								AMOUNT
							</td>
							<td>
								<?= money($obj['total_amount']); ?>
							</td>
						</tr>
						<tr>
							<td>
								PREPARED BY
								<br>
								DATE
							</td>
							<td>
								<?= ucwords($obj['by']); ?>
								<br>
								<?= $obj['date']; ?>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="text-xs text-muted mt-6"><em>Thank you for your purchase.<a href="#" class="fw-bold"> J-Spence LTD.</a></em></div>
			</div>
				<div class="card-footer">
					bar code;
				</div>
			</div>
		</div>

	</div>

	<script type="text/javascript">
		window.print();
	</script>
</body>
</html>