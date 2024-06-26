<?php 
    require_once ("db_connection/conn.php");

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
	<meta name="color-scheme" content="dark light">
	<title>J.Spence – Web3 and Finance Dashboard</title>
	<link rel="stylesheet" type="text/css" href="<?= PROOT; ?>dist/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?= PROOT; ?>dist/css/utility.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
	<link rel="stylesheet" href="https://api.fontshare.com/v2/css?f=satoshi@900,700,500,300,401,400&display=swap">
	<script defer="defer" data-domain="satoshi.webpixels.io" src="https://plausible.io/js/script.outbound-links.js"></script>
</head>
<body class="p-1 p-lg-2">
	<div class="overflow-x-hidden rounded-top-4 pt-2 pt-lg-4">
		
		<header>
			<div class="w-lg-75 mx-2 mx-lg-auto position-relative z-2 px-lg-3 py-1 shadow-5 rounded-3 rounded-lg-pill bg-dark">
				<nav class="navbar navbar-expand-lg navbar-dark p-0" id="navbar">
					<div class="container px-sm-0">
						<a class="navbar-brand d-inline-block w-lg-64" href="#"><img src="<?= PROOT; ?>dist/media/logo-no-bg.png" class="h-rem-10" alt="..."> </a>
						<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
						<div class="collapse navbar-collapse" id="navbarCollapse">
							<ul class="navbar-nav gap-2 mx-lg-auto">
								<li class="nav-item"><a class="nav-link rounded-pill" href="/" aria-current="page">Statistics</a></li>
								<li class="nav-item"><a class="nav-link rounded-pill" href="/pages/dashboard.html">Dashboard</a></li>
								<li class="nav-item"><a class="nav-link rounded-pill" href="/pages/page-table-listing.html">Trade</a></li>
								<li class="nav-item"><a class="nav-link rounded-pill" href="/pages/page-list.html">Admins</a></li>
							</ul>
							<div class="navbar-nav align-items-lg-center justify-content-end gap-2 ms-lg-4 w-lg-64">
								<a class="nav-item nav-link rounded-pill d-none d-lg-block" href="<?= PROOT; ?>auth/login">Sign in</a> 
								<a href="javascript:;" class="btn btn-sm btn-white border-0 rounded-lg-pill w-100 w-lg-auto mb-4 mb-lg-0" data-bs-target="#depositLiquidityModal" data-bs-toggle="modal">
									<span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Liquidity</span>
								</a>
							</div>
						</div>
					</div>
				</nav>
			</div>
		</header>
		
		<main>
			<div class="pt-56 pb-10 pt-lg-56 pb-lg-0 mt-n40 position-relative bg-warning">
				<div class="container">
					<div class="row align-items-center g-10">
						<div class="col-lg-8">
							<h1 class="ls-tight fw-bolder display-3 text-white mb-5">Build Professional Dashboards, Faster than Ever.</h1>
							<p class="w-xl-75 lead text-white">With our intuitive tools and expertly designed components, you'll have the power to create professional dashboards quicker than ever.</p>
						</div>
						<div class="col-lg-4 align-self-end">
							<div class="hstack gap-3 justify-content-lg-end"><a href="https://themes.getbootstrap.com/product/satoshi-defi-and-crypto-exchange-theme/" class="btn btn-lg btn-white rounded-pill bg-dark-hover border-0 shadow-none px-lg-8" target="_blank">Purchase now </a><a href="/pages/dashboard.html" class="btn btn-lg btn-dark rounded-pill border-0 shadow-none px-lg-8">Explore more</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>

	<div class="modal fade" id="depositLiquidityModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content overflow-hidden">
				<div class="modal-header pb-0 border-0">
					<h1 class="modal-title h4" id="depositLiquidityModalLabel">Deposit liquidity</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body undefined">
					<form class="vstack gap-6">
						<div class="vstack gap-1">
							<div class="bg-body-secondary rounded-3 p-4">
								<div class="d-flex justify-content-between text-xs text-muted">
									<span class="fw-semibold">From</span> <span>Balance: 10,000 ADA</span>
								</div>
								<div class="d-flex justify-content-between gap-2 mt-4">
									<input type="tel" class="form-control form-control-flush text-xl fw-bold flex-fill" placeholder="0.00"> <button class="btn btn-neutral shadow-none rounded-pill flex-none d-flex align-items-center gap-2 py-2 ps-2 pe-4"><img src="../../img/crypto/color/ada.svg" class="w-rem-6 h-rem-6" alt="..."> <span class="text-xs fw-semibold text-heading ms-1">ADA</span></button>
								</div>
							</div>
							<div class="position-relative text-center my-n4 overlap-10">
								<div class="icon icon-sm icon-shape bg-body shadow-soft-3 rounded-circle text-sm text-body-tertiary">
									<i class="bi bi-arrow-down-up"></i>
								</div>
							</div>
							<div class="bg-body-secondary rounded-3 p-4">
								<div class="d-flex justify-content-between text-xs text-muted">
									<span class="fw-semibold">To</span> <span>Balance: 0 SUN</span>
								</div>
								<div class="d-flex justify-content-between gap-2 mt-4">
									<input type="tel" class="form-control form-control-flush text-xl fw-bold flex-fill" placeholder="0.00"> <button class="btn btn-neutral shadow-none rounded-pill flex-none d-flex align-items-center gap-2 py-2 ps-2 pe-4"><img src="../../img/pools/pool-1.png" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span class="text-xs fw-semibold text-heading ms-1">SUN</span></button>
								</div>
							</div>
						</div>
						<div>
							<label class="form-label">Total Amount</label>
							<div class="d-flex flex-wrap gap-1 gap-sm-2">
								<div class="w-sm-56 input-group input-group-sm input-group-inline">
									<input type="search" class="form-control" placeholder="1"> <span class="input-group-text">%</span>
								</div>
								<div class="flex-fill">
									<input type="radio" class="btn-check" name="options" id="option1" checked="checked"> <label class="btn btn-sm btn-neutral w-100" for="option1">0.5%</label>
								</div>
								<div class="flex-fill">
									<input type="radio" class="btn-check" name="options" id="option2" checked="checked"> <label class="btn btn-sm btn-neutral w-100" for="option2">1%</label>
								</div>
								<div class="flex-fill">
									<input type="radio" class="btn-check" name="options" id="option3" checked="checked"> <label class="btn btn-sm btn-neutral w-100" for="option3">3%</label>
								</div>
							</div>
						</div>
						<button type="button" class="btn btn-primary w-100">Provide liquidity</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
	<script src="<?= PROOT; ?>dist/js/main.js"></script>
</body>
</html>