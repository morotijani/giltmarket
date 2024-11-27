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
<style>
	*, body {
		
    	font-family: monospace !important;
	}
</style>
	<div class="row justify-content-center">
		<div class="col-md-3">
			<div class="card">
				<div class="card-header pb-0 border-0">
					<h6 class="modal-title h6 text-center">
						<?= ucwords($crow['company_name']); ?>
						<br>
						<?= $crow['company_address']; ?>
						<br>
						<?= $crow['company_phone1'] . (($crow['company_phone2'] == '') ? '' : ' / ' . $crow['company_phone2']); ?>
						<br>
						PURCHASE INVOICE
					</h6>
				</div>
				<div class="card-body">
					<ul class="list-group list-group-flush">
						<div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-base fw-normal mb-1">Reference/ID,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025"><?= ucwords($obj['reference']); ?></time>
								</div>
							</div>
						</div>
						<div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-base fw-normal mb-1">Name of Customer,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025"><?= ucwords($obj['customername']); ?></time>
								</div>
							</div>
						</div>
						<div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-base fw-normal mb-1">Gram,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025"><?= $obj['gram']; ?></time>
								</div>
							</div>
						</div>
						<div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-base fw-normal mb-1">Pounds,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025"><?= $obj['pounds']; ?></time>
								</div>
							</div>
						</div>
						<div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-base fw-normal mb-1">Volume,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025"><?= $obj['volume']; ?></time>
								</div>
							</div>
						</div>
						<div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-base fw-normal mb-1">Density,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025"><?= $obj['density']; ?></time>
								</div>
							</div>
						</div>
						<div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-base fw-normal mb-1">Carat,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025"><?= $obj['carat']; ?></time>
								</div>
							</div>
						</div>
						<div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-base fw-normal mb-1">Price,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025"><?= $obj['current_price']; ?></time>
								</div>
							</div>
						</div>
						<div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-base fw-normal mb-1">Amount,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025"><?= $obj['total_amount']; ?></time>
								</div>
							</div>
						</div>
						<div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-base fw-normal mb-1">By,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025"><?= $obj['by']; ?></time>
								</div>
							</div>
						</div>
						<div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-base fw-normal mb-1">Date,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025"><?= pretty_date($d); ?></time>
								</div>
							</div>
						</div>
					</ul>
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
</div>
</div>

	<script>
		// first print
		// window.print();

		// setTimeout(function() {
		// 	// second print
		// 	window.print();
		// }, 1000);

		// window.onafterprint = function() {
		// 	setTimeout(function() {
		// 		window.close();
		// 	}, 500);

		// 	return false;
		// }

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