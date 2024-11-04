<?php 
	require_once ("../db_connection/conn.php");
	include ("../includes/header.inc.php");

	// activate press enter
	if ($_SERVER['REQUEST_METHOD'] == "POST") {

 		$WshShell = new COM("WScript.Shell");
 		// $obj = $WshShell->Run("cmd /c wscript.exe www/public/file.vbs",0, true); 
 		$obj = $WshShell->Run("cmd /c wscript.exe " . BASEURL . "/pressenter.vbs",0, true); 
 		
 		$WshShell = new COM("WScript.Shell");
 		// $obj = $WshShell->Run("cmd /c wscript.exe www/public/file.vbs",0, true); 
 		$obj = $WshShell->Run("cmd /c wscript.exe " . BASEURL . "/pressenter.vbs",0, true); 
 	 
	}

	$crow = company_data();
	$data = $_GET['data'] ?? "";
	$obj = json_decode($data, true);

	$d = date('Y-m-d h:i:s', $obj['date']);
?>

		<div class="card">
			<div class="card-header pb-0 border-0">
				<h1 class="modal-title h4 text-center" id="connectWalletModalLabel">
					<?= ucwords($crow['company_name']); ?>
					<br>
					<?= $crow['company_address']; ?>
					<br>
					<?= $crow['company_phone1'] . (($crow['company_phone2'] == '') ? '' : ' / ' . $crow['company_phone2']); ?>
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
								<?= pretty_date($d); ?>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="text-xs text-muted mt-6">
					<em>Thank you for your purchase.<a href="#" class="fw-bold"> J-Spence LTD.</a></em>
				</div>
			</div>
				<div class="card-footer">
					<!-- bar code; -->
				</div>
			</div>
		</div>

	</div>

	<script>
		window.print();

		window.onafterprint = function() {
			setTimeout(function() {
				window.close();
			}, 500);

			return false;
		}

		var ajax = new XMLHttpRequest();

		ajax.addEventListener('readystatechange',function() {

			if (ajax.readyState == 4) {
				//console.log(ajax.responseText);
			}
		});

		ajax.open('POST','',true);
		// ajax.send();
	</script>
</body>
</html>