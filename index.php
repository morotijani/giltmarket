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
<body class="p-1 p-lg-2 bg-body-tertiary">
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
								<a class="nav-item nav-link rounded-pill d-none d-lg-block" href="javascript:;" data-bs-target="#connectWalletModal" data-bs-toggle="modal">Connect</a> 
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
			<div class="pt-56 pb-10 pt-lg-56 pb-lg-0 mt-n40 position-relative">
				<div class="container">
					<?php if (admin_is_logged_in()): ?>
					<!-- <div class="row align-items-center g-10">
						<div class="col-lg-8">
							<h1 class="ls-tight fw-bolder display-3 text-white mb-5">Build Professional Dashboards, Faster than Ever.</h1>
							<p class="w-xl-75 lead text-white">With our intuitive tools and expertly designed components, you'll have the power to create professional dashboards quicker than ever.</p>
						</div>
						<div class="col-lg-4 align-self-end">
							<div class="hstack gap-3 justify-content-lg-end"><a href="https://themes.getbootstrap.com/product/satoshi-defi-and-crypto-exchange-theme/" class="btn btn-lg btn-white rounded-pill bg-dark-hover border-0 shadow-none px-lg-8" target="_blank">Purchase now </a><a href="/pages/dashboard.html" class="btn btn-lg btn-dark rounded-pill border-0 shadow-none px-lg-8">Explore more</a>
							</div>
						</div>
					</div> -->




					<div class="mb-6 mb-xl-10">
						<div class="row g-3 align-items-center">
							<div class="col">
								<h1 class="ls-tight">Dashboard</h1>
							</div>
							<div class="col">
								<div class="hstack gap-2 justify-content-end">
									<button type="button" class="btn btn-sm btn-square btn-neutral rounded-circle d-xxl-none" data-bs-toggle="offcanvas" data-bs-target="#responsiveOffcanvas" aria-controls="responsiveOffcanvas"><i class="bi bi-three-dots"></i></button> <button type="button" class="btn btn-sm btn-neutral d-none d-sm-inline-flex" data-bs-target="#depositLiquidityModal" data-bs-toggle="modal"><span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Liquidity</span></button> 
									<a href="/pages/page-overview.html" class="btn d-inline-flex btn-sm btn-dark"><span>Trade</span></a>
								</div>
							</div>
						</div>
					</div>

					<div class="modal fade" id="cryptoModal" tabindex="-1" aria-labelledby="cryptoModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered">
							<div class="modal-content overflow-hidden">
								<div class="modal-header pb-0 border-0">
									<h1 class="modal-title h4" id="cryptoModalLabel">Select token</h1>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body p-0">
									<div class="px-6 py-5 border-bottom">
										<input type="text" class="form-control" placeholder="Search token or paste address" aria-label="Search">
									</div>
									<div class="p-2"></div>
									<div class="px-6 py-5 bg-body-secondary d-flex justify-content-center">
										<button class="btn btn-sm btn-dark"><i class="bi bi-gear me-2"></i>Manage tokens</button>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row g-3 g-xxl-6">
						<div class="col-xxl-8">
							<div class="vstack gap-3 gap-md-6">
								<div class="row g-3">
									<div class="col-md col-sm-6">
										<div class="card border-primary-hover">
											<div class="card-body p-4">
												<div class="d-flex align-items-center gap-2">
													<img src="../../img/crypto/icon/btc.svg" class="w-rem-5 flex-none" alt="..."> <a href="/pages/page-details.html" class="h6 stretched-link">Today</a>
												</div>
												<div class="text-sm fw-semibold mt-3">3.2893 USDT</div>
												<div class="d-flex align-items-center gap-2 mt-1 text-xs">
													<span class="badge badge-xs bg-success"><i class="bi bi-arrow-up-right"></i> </span><span>+13.7%</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md col-sm-6">
										<div class="card border-primary-hover">
											<div class="card-body p-4">
												<div class="d-flex align-items-center gap-2">
													<img src="../../img/crypto/icon/ada.svg" class="w-rem-5 flex-none" alt="..."> 
													<a href="/pages/page-details.html" class="h6 stretched-link">This Month</a>
												</div>
												<div class="text-sm fw-semibold mt-3">10.745,49 ADA</div>
												<div class="d-flex align-items-center gap-2 mt-1 text-xs"><span class="badge badge-xs bg-danger"><i class="bi bi-arrow-up-right"></i> </span><span>-3.2%</span></div>
											</div>
										</div>
									</div>
									<div class="col-md col-sm-6">
										<div class="card border-primary-hover">
											<div class="card-body p-4">
												<div class="d-flex align-items-center gap-2">
													<img src="../../img/crypto/icon/eos.svg" class="w-rem-5 flex-none" alt="..."> 
													<a href="/pages/page-details.html" class="h6 stretched-link">Orders</a></div>
													<div class="text-sm fw-semibold mt-3">7.890,00 EOS</div>
													<div class="d-flex align-items-center gap-2 mt-1 text-xs">
														<span class="badge badge-xs bg-danger"><i class="bi bi-arrow-up-right"></i> </span><span>-2.2%</span>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-1 d-none d-md-block">
											<div class="card h-md-100 d-flex flex-column align-items-center justify-content-center py-4 bg-body-secondary bg-opacity-75 bg-opacity-100-hover">
												<a href="#cryptoModal" class="stretched-link text-body-secondary" data-bs-toggle="modal"><i class="bi bi-pencil"></i></a>
											</div>
										</div>
									</div>
									<div class="card">
										<div class="card-body pb-0">
											<div class="d-flex justify-content-between align-items-center"><div>
												<h5>Earnings</h5>
											</div>
											<div class="hstack align-items-center">
												<a href="#" class="text-muted"><i class="bi bi-arrow-repeat"></i></a>
											</div>
										</div>
										<div class="mx-n4">
											<div id="chart-users" data-height="270"></div>
										</div>
									</div>
								</div>
								<div class="card">
									<div class="card-body pb-0">
										<div class="d-flex justify-content-between align-items-center">
											<div>
												<h5>Transaction History</h5>
											</div>
											<div class="hstack align-items-center">
												<a href="#" class="text-muted">
													<i class="bi bi-arrow-repeat"></i>
												</a>
											</div>
										</div>
										<div class="list-group list-group-flush">
											<div class="list-group-item d-flex align-items-center justify-content-between gap-6">
												<div class="d-flex align-items-center gap-3">
													<div class="icon icon-shape rounded-circle icon-sm flex-none w-rem-10 h-rem-10 text-sm bg-primary bg-opacity-25 text-primary">
														<i class="bi bi-send-fill"></i>
													</div>
													<div class="">
														<span class="d-block text-heading text-sm fw-semibold">Bitcoin </span>
														<span class="d-none d-sm-block text-muted text-xs">2 days ago</span>
													</div>
												</div>
												<div class="d-none d-md-block text-sm">0xd029384sd343fd...eq23</div>
												<div class="d-none d-md-block">
													<span class="badge bg-body-secondary text-warning">Pending</span>
												</div>
												<div class="text-end">
													<span class="d-block text-heading text-sm fw-bold">+0.2948 BTC </span>
													<span class="d-block text-muted text-xs">+$10,930.90</span>
												</div>
											</div>
											<div class="list-group-item d-flex align-items-center justify-content-between gap-6">
												<div class="d-flex align-items-center gap-3">
													<div class="icon icon-shape rounded-circle icon-sm flex-none w-rem-10 h-rem-10 text-sm bg-primary bg-opacity-25 text-primary">
														<i class="bi bi-send-fill"></i>
													</div>
													<div class="">
														<span class="d-block text-heading text-sm fw-semibold">Cardano </span>
														<span class="d-none d-sm-block text-muted text-xs">2 days ago</span>
													</div>
												</div>
												<div class="d-none d-md-block text-sm">0xd029384sd343fd...eq23</div>
												<div class="d-none d-md-block">
													<span class="badge bg-body-secondary text-danger">Canceled</span>
												</div>
												<div class="text-end">
													<span class="d-block text-heading text-sm fw-bold">+0.2948 BTC </span>
													<span class="d-block text-muted text-xs">+$10,930.90</span>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="card">
									<div class="card-body">
										<div class="d-flex justify-content-between align-items-center mb-5">
											<div>
												<h5>Recent transactions</h5>
											</div>
											<div class="hstack align-items-center">
												<a href="#" class="text-muted">
													<i class="bi bi-arrow-repeat"></i>
												</a>
											</div>
										</div>
										<div class="vstack gap-6">
											<div>
												<div class="d-flex align-items-center gap-3">
													<div class="icon icon-shape flex-none text-base text-bg-dark rounded-circle">
														<img src="../../img/crypto/white/btc.svg" class="w-rem-6 h-rem-6" alt="...">
													</div>
													<div>
														<h6 class="progress-text mb-1 d-block">Bitcoin</h6>
														<p class="text-muted text-xs">Pending - 3 min ago</p>
													</div>
													<div class="text-end ms-auto">
														<span class="h6 text-sm">-1,500 USD</span>
													</div>
												</div>
											</div>
											<div>
												<div class="d-flex align-items-center gap-3">
													<div class="icon icon-shape flex-none text-base text-bg-dark rounded-circle">
														<img src="../../img/crypto/white/ada.svg" class="w-rem-6 h-rem-6" alt="...">
													</div>
													<div>
														<h6 class="progress-text mb-1 d-block">Cardano</h6>
														<p class="text-muted text-xs">Canceled - 3 min ago</p>
													</div>
													<div class="text-end ms-auto">
														<span class="h6 text-sm">-1,500 USD</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="col-xxl-4">
							<div class="offcanvas-xxl m-xxl-0 rounded-sm-4 rounded-xxl-0 offcanvas-end overflow-hidden m-sm-4" tabindex="-1" id="responsiveOffcanvas" aria-labelledby="responsiveOffcanvasLabel">
								<div class="offcanvas-header rounded-top-4 bg-light">
									<h5 class="offcanvas-title" id="responsiveOffcanvasLabel">Quick Stats</h5>
									<button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#responsiveOffcanvas" aria-label="Close"></button>
								</div>
								<div class="offcanvas-body d-flex flex-column p-3 p-sm-6 p-xxl-0 gap-3 gap-xxl-6">
									<div class="vstack gap-6 gap-xxl-6">
										<div class="card border-0 border-xxl">
											<div class="card-body d-flex flex-column p-0 p-xxl-6">
												<div class="d-flex justify-content-between align-items-center mb-3">
													<div>
														<h5>Balance</h5>
													</div>
													<div>
														<span class="text-heading fw-bold">
															<i class="bi bi-arrow-up me-2"></i>7.8%</span>
														</div>
													</div>
													<div class="text-2xl fw-bolder text-heading ls-tight">23.863,21 USDT</div>
													<div class="d-flex align-items-center justify-content-between mt-8">
														<div class="">
															<div class="d-flex gap-3 align-items-center">
																<div class="icon icon-sm icon-shape text-sm rounded-circle bg-dark text-success">
																	<i class="bi bi-arrow-down"></i>
																</div>
																<span class="h6 fw-semibold text-muted">Income</span>
															</div>
															<div class="fw-bold text-heading mt-3">$23.863,21 USD</div>
														</div>
														<span class="vr bg-dark bg-opacity-10"></span>
														<div class="">
															<div class="d-flex gap-3 align-items-center">
																<div class="icon icon-sm icon-shape text-sm rounded-circle bg-dark text-danger">
																	<i class="bi bi-arrow-up"></i>
																</div>
																<span class="h6 fw-semibold text-muted">Expenses</span>
															</div>
															<div class="fw-bold text-heading mt-3">$5.678,45 USD</div>
														</div>
													</div>
												</div>
											</div>
											<hr class="my-0 d-xxl-none">
											<div class="card border-0 border-xxl">
												<div class="card-body p-0 p-xxl-6">
													<div class="d-flex justify-content-between align-items-center mb-5"><div>
														<h5>Logs</h5>
													</div>
													<div class="hstack align-items-center">
														<a href="#" class="text-muted">
															<i class="bi bi-arrow-repeat"></i>
														</a>
													</div>
												</div>
												<div class="vstack gap-1">
													

												</div>
											</div>
										</div>
										<hr class="my-0 d-xxl-none">
									</div>
								</div>
							</div>
						</div>
					</div>




					<?php else: ?>
						<div class="mt-10 d-lg-block">
							<!-- <img src="/img/marketing/hero-img-1.png"> -->
							<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 
							<dotlottie-player src="https://lottie.host/4d04debe-13b3-4271-b223-0b0be5e90fb3/KOeuAPsQrt.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></dotlottie-player>
						</div>
					<?php endif; ?>


				</div>
			</div>
		</main>

	<!-- BUY -->
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
									<span class="fw-semibold">Gram</span> <span>Balance: 10,000 ADA</span>
								</div>
								<div class="d-flex justify-content-between gap-2 mt-4">
									<input type="tel" class="form-control form-control-flush text-xl fw-bold flex-fill" placeholder="0.00"> <button class="btn btn-neutral shadow-none rounded-pill flex-none d-flex align-items-center gap-2 py-2 ps-2 pe-4"><img src="<?= PROOT; ?>dist/media/grams.svg" class="w-rem-6 h-rem-6" alt="..."> <span class="text-xs fw-semibold text-heading ms-1">GRM</span></button>
								</div>
							</div>
							<div class="position-relative text-center my-n4 overlap-10">
								<div class="icon icon-sm icon-shape bg-body shadow-soft-3 rounded-circle text-sm text-body-tertiary">
									<i class="bi bi-arrow-down-up"></i>
								</div>
							</div>
							<div class="bg-body-secondary rounded-3 p-4">
								<div class="d-flex justify-content-between text-xs text-muted">
									<span class="fw-semibold">Volume</span> <span>Balance: 0 SUN</span>
								</div>
								<div class="d-flex justify-content-between gap-2 mt-4">
									<input type="tel" class="form-control form-control-flush text-xl fw-bold flex-fill" placeholder="0.00"> <button class="btn btn-neutral shadow-none rounded-pill flex-none d-flex align-items-center gap-2 py-2 ps-2 pe-4"><img src="<?= PROOT; ?>dist/media/volume.png" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span class="text-xs fw-semibold text-heading ms-1">VLM</span></button>
								</div>
							</div>
						</div>
						<div>
							<label class="form-label">Total Amount</label>
							<div class="d-flex flex-wrap gap-1 gap-sm-2">
								<div class="w-sm-56 input-group input-group-sm input-group-inline">
									<input type="text" readonly class="form-control" placeholder="0.00"> <span class="input-group-text">₵</span>
								</div>
								<div class="flex-fill">
									<input type="radio" title="Density" class="btn-check" name="options" id="option1" checked="checked"> <label class="btn btn-sm btn-neutral w-100" for="option1">0.5 Density</label>
								</div>
								<div class="flex-fill">
									<input type="radio" class="btn-check" title="Pounds" name="options" id="option2" checked="checked"> <label class="btn btn-sm btn-neutral w-100" for="option2">1 Pounds</label>
								</div>
								<div class="flex-fill">
									<input type="radio" class="btn-check" name="options" title="Karat" id="option3" checked="checked"> <label class="btn btn-sm btn-neutral w-100" for="option3">3 Carat</label>
								</div>
							</div>
						</div>
						<button type="button" class="btn btn-primary w-100">Provide liquidity</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- LOGIN -->
	<div class="modal fade" id="connectWalletModal" tabindex="-1" aria-labelledby="connectWalletModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content overflow-hidden">
				<div class="modal-header pb-0 border-0">
					<h1 class="modal-title h4" id="connectWalletModalLabel">Connect your account</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body undefined">
					<div class="list-group list-group-flush gap-2">
						<div class="list-group-item border rounded d-flex gap-3 p-4 bg-body-secondary-hover">
							<div class="icon flex-none">
								<img src="../../img/wallets/metamask.png" class="w-rem-8 h-rem-8" alt="...">
							</div>
							<div class="d-flex align-items-center flex-fill">
								<div>
									<a href="#" class="stretched-link text-heading text-sm fw-bold">MetaMask</a>
								</div>
								<div class="ms-auto">
									<span class="badge badge-md text-bg-primary">Popular</span>
								</div>
							</div>
						</div>
						<div class="list-group-item border rounded d-flex gap-3 p-4 bg-body-secondary-hover">
							<div class="icon flex-none"><img src="../../img/wallets/coinbase.webp" class="w-rem-8 h-rem-8" alt="..."></div><div class="d-flex align-items-center flex-fill"><div><a href="#" class="stretched-link text-heading text-sm fw-bold">Coinbase Wallet</a></div></div></div>
							<div class="list-group-item border rounded d-flex gap-3 p-4 bg-body-secondary-hover"><div class="icon flex-none"><img src="../../img/wallets/walletconnect.png" class="w-rem-8 h-rem-8" alt="..."></div><div class="d-flex align-items-center flex-fill"><div><a href="#" class="stretched-link text-heading text-sm fw-bold">WalletConnect</a></div></div></div>
					</div>
					<div class="text-xs text-muted mt-6">By connecting wallet, you agree to Satoshi's <a href="#" class="fw-bold">Terms of Service</a></div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
	<script src="<?= PROOT; ?>dist/js/main.js"></script>
</body>
</html>