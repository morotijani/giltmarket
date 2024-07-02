
		
		<header>
			<div class="w-lg-75 mx-2 mx-lg-auto position-relative z-2 px-lg-3 py-1 shadow-5 rounded-3 rounded-lg-pill bg-dark">
				<nav class="navbar navbar-expand-lg navbar-dark p-0" id="navbar">
					<div class="container px-sm-0">
						<a class="navbar-brand d-inline-block w-lg-64" href="#"><img src="<?= PROOT; ?>dist/media/logo-no-bg.png" class="h-rem-10" alt="..."> </a>
						<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
						<div class="collapse navbar-collapse" id="navbarCollapse">
							<ul class="navbar-nav gap-2 mx-lg-auto">
								<li class="nav-item"><a class="nav-link rounded-pill" href="<?= PROOT; ?>" aria-current="page">Dashboard</a></li>
								<?php if (admin_is_logged_in() && admin_has_permission('admin')): ?>
									<li class="nav-item"><a class="nav-link rounded-pill" href="<?= PROOT; ?>acc/admins">Admins</a></li>
								<?php endif; ?>
								<?php if (admin_is_logged_in()): ?>
									<li class="nav-item"><a class="nav-link rounded-pill" href="<?= PROOT; ?>acc/trades">Trades</a></li>
									<li class="nav-item"><a class="nav-link rounded-pill" href="<?= PROOT; ?>acc/logs">Logs</a></li>
									<li class="nav-item"><a class="nav-link rounded-pill" href="<?= PROOT; ?>documentation">Documentation</a></li>
									<li class="nav-item"><a class="nav-link rounded-pill" href="<?= PROOT; ?>auth/logout">Logout</a></li>
								<?php endif; ?>
								<li class="nav-item"><a class="nav-link rounded-pill" href="https://goldprice.org"><i class="bi bi-sign-turn-slight-right"></i> Gold Price</a></li>
							</ul>
							<div class="navbar-nav align-items-lg-center justify-content-end gap-2 ms-lg-4 w-lg-64">
								<?php if (admin_is_logged_in()): ?>
								<a class="nav-item nav-link rounded-pill d-none d-lg-block" href="<?= PROOT; ?>acc/profile">Hello <?= $admin_data['first']; ?>!</a>
								<?php else: ?>
								<a class="nav-item nav-link rounded-pill d-none d-lg-block" href="javascript:;" data-bs-target="#connectWalletModal" data-bs-toggle="modal">Connect</a>
								<?php endif; ?>
								<?php if (admin_is_logged_in()): ?>
									<a href="javascript:;" class="btn btn-sm btn-white border-0 rounded-lg-pill w-100 w-lg-auto mb-4 mb-lg-0" data-bs-target="#buyModal" data-bs-toggle="modal">
										<span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Trade</span>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</nav>
			</div>
		</header>
		
		<main>
			<div class="pt-56 pb-10 pt-lg-56 pb-lg-0 mt-n40 position-relative">
				<div class="container">
					<div><?= $flash; ?></div>

					<!-- Begin Gold Price Script - GOLDPRICE.ORG --><div style="border: 1px solid #000000; width: 210px; height: auto; font-family: Arial; background-color: #FFFFFF;"><div style="margin: 0px auto; width: 100%; height: 24px; text-align: center; padding-top: 0px; font-size: 18px; font-weight: bold; background-color: #000000;"><a style="color: #FFFFFF; background-color: #000000; text-decoration: none;" href="https://goldprice.org" target="_blank">Gold Price </a></div><div id="gold-price" data-gold_price="USD-o-1d"> </div><script type="text/javascript" src="http://charts.goldprice.org/gold-price.js"> </script></div><br /><!-- End Gold Price Script - GOLDPRICE.ORG -->