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
								GRAM
								<br>
								<?= $obj['gram']; ?>
							</td>
						</tr>
						<tr>
							<td>
								VOLUME
								<br>
								<?= $obj['volume']; ?>
							</td>
							<td>
								
							</td>
							<td>
								AMOUNT
							</td>
						</tr>
						<tr>
							<td>
								PREPARED BY
							</td>
							<td>
								NAME OF CUSTOMER
								<br>
								<?= ucwords($obj['customername']); ?>
							</td>
						</tr>
						<tr>
							<td>
								POUNDS
								<br>
								<?= $obj['pounds']; ?>
							</td>
							<td>
								DENSITY
								<br>
								<?= $obj['density']; ?>
							</td>
						</tr>
						<tr>
							<td>
								CARAT
								<br>
								<?= $obj['carat']; ?>
							</td>
							<td>
								<?= money($obj['total_amount']); ?>
							</td>
						</tr>
						<tr>
							<td>
								kofi
							</td>
						</tr>
					</tbody>
				</table>
				<!-- <div class="list-group list-group-flush gap-2">
					<div class="list-group-item border rounded d-flex gap-3 p-4 bg-body-secondary-hover"><div class="icon flex-none"><img src="../../img/wallets/metamask.png" class="w-rem-8 h-rem-8" alt="..."></div><div class="d-flex align-items-center flex-fill"><div><a href="#" class="stretched-link text-heading text-sm fw-bold">MetaMask</a></div><div class="ms-auto"><span class="badge badge-md text-bg-primary">Popular</span></div></div></div><div class="list-group-item border rounded d-flex gap-3 p-4 bg-body-secondary-hover"><div class="icon flex-none"><img src="../../img/wallets/coinbase.webp" class="w-rem-8 h-rem-8" alt="..."></div><div class="d-flex align-items-center flex-fill"><div><a href="#" class="stretched-link text-heading text-sm fw-bold">Coinbase Wallet</a></div></div></div><div class="list-group-item border rounded d-flex gap-3 p-4 bg-body-secondary-hover"><div class="icon flex-none"><img src="../../img/wallets/walletconnect.png" class="w-rem-8 h-rem-8" alt="..."></div><div class="d-flex align-items-center flex-fill"><div><a href="#" class="stretched-link text-heading text-sm fw-bold">WalletConnect</a></div></div></div><div class="list-group-item border rounded d-flex gap-3 p-4 bg-body-secondary-hover"><div class="icon flex-none"><img src="../../img/wallets/phantom.png" class="w-rem-8 h-rem-8" alt="..."></div><div class="d-flex align-items-center flex-fill"><div><a href="#" class="stretched-link text-heading text-sm fw-bold">Phantom</a></div><div class="ms-auto"><span class="badge badge-md text-bg-light">Solana</span></div></div></div><div class="list-group-item border rounded d-flex gap-3 p-4 bg-body-secondary-hover"><div class="icon flex-none"><img src="../../img/wallets/core.png" class="w-rem-8 h-rem-8" alt="..."></div><div class="d-flex align-items-center flex-fill"><div><a href="#" class="stretched-link text-heading text-sm fw-bold">Core</a></div><div class="ms-auto"><span class="badge badge-md text-bg-light">Avalanche</span></div></div></div><div class="list-group-item border rounded d-flex gap-3 p-4 bg-body-secondary-hover"><div class="icon flex-none"><img src="../../img/wallets/glow.svg" class="w-rem-8 h-rem-8" alt="..."></div><div class="d-flex align-items-center flex-fill"><div><a href="#" class="stretched-link text-heading text-sm fw-bold">Glow</a></div><div class="ms-auto"><span class="badge badge-md text-bg-light">Solana</span></div></div></div></div> -->

					<div class="text-xs text-muted mt-6">By connecting wallet, you agree to Satoshi's <a href="#" class="fw-bold">Terms of Service</a></div>
				</div>
				<div class="card-footer">
					bar code;
				</div>
			</div>
		</div>

	</div>
</body>
</html>