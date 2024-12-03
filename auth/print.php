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

	$d = date('Y-m-d h:i:s A', $obj['date']);
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
						<span class="h5 fw-bold"><?= ucwords($crow['company_name']); ?></span>
						<br>
						<?= $crow['company_address']; ?>
						<br>
						<?= $crow['company_phone1'] . (($crow['company_phone2'] == '') ? '' : ' / ' . $crow['company_phone2']); ?>
						<br>
						PURCHASE INVOICE
					</h6>
				</div>
				<div class="card-body">

                <div class="row gx-3">
                    <div class="col fs-1"><?= $obj['gram']; ?></div>
                    <div class="col text-center"><hr style="width: 50px; border-style: dashed; border: 2px double #000; margin-top: 1.5rem;"></div>
                    <div class="col fs-1"><?= $obj['pounds']; ?></div>
                </div>

                <div class="row gx-3">
                    <div class="col fs-1"><?= $obj['volume']; ?></div>
                    <div class="col text-center"><hr style="width: 50px; border-style: dashed; border: 2px double #000; margin-top: 1.5rem;"></div>
                    <div class="col fs-1"><?= $obj['density']; ?></div>
                </div>

				<div class="row gx-3">
                    <div class="col fs-5 fw-bold" style="line-height: 3;">Karat</div>
                    <div class="col text-center"><hr style="width: 50px; border-style: dashed; border: 2px double #000; margin-top: 1.5rem;"></div>
                    <div class="col fs-1 fw-bold"><?= $obj['carat']; ?></div>
                </div>
                <!-- <div class="text-center">
                    <span class="fs-1 fw-bold"><?= $obj['carat']; ?></span>
                </div> -->

				<div class="row gx-3">
                    <div class="col fs-5 fw-bold" style="line-height: 2.5;">Price:</div>
                    <div class="col text-center"></div>
                    <div class="col fs-3 fw-normal"><?= money($obj['current_price']); ?></div>
                </div>
				
				<div class="row gx-3">
                    <div class="col fs-5 fw-bold" style="line-height: 3;">Amount:</div>
                    <div class="col text-center"></div>
                    <div class="col fs-1 fw-bold"><?= money($obj['total_amount']); ?></div>
                </div>
                <!-- <div class="">
                    <span class="fs-3 fw-bold text-body-secondary"><?= money($obj['current_price']); ?></span>
                    <br>
                    <span class="fs-1 fw-bold"><?= money($obj['total_amount']); ?></span>
                </div> -->

                <hr>
					<ul class="list-group list-group-flush">
						<!-- <div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-base fw-normal mb-1">Reference/ID,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025"><?= ucwords($obj['reference']); ?></time>
								</div>
							</div>
						</div> -->
						<div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-6 fw-bold mb-1">Name of Customer,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary fs-6 fw-bold" datetime="01/01/2025"><?= ucwords($obj['customername']); ?></time>
								</div>
							</div>
						</div>
						<!-- <div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-base fw-normal mb-1">Handler,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary" datetime="01/01/2025"><?= $obj['by']; ?></time>
								</div>
							</div>
						</div> -->
						<div class="list-group-item px-0">
							<div class="row align-items-center">
								<div class="col ms-n2">
									<h6 class="fs-6 fw-bold mb-1">Date,</h6>
								</div>
								<div class="col-auto">
									<time class="text-body-secondary fs-6 fw-bold" datetime="01/01/2025"><?= pretty_date($d); ?></time>
								</div>
							</div>
						</div>
					</ul>
					<div class="text-sm text-center mt-6">
						<em class="fw-bold">Thank you!</em>
					</div>
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
