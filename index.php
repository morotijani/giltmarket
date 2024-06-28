<?php 
    require_once ("db_connection/conn.php");

    include ("includes/header.inc.php");
    include ("includes/nav.inc.php");

?>


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
									<button type="button" class="btn btn-sm btn-square btn-neutral rounded-circle d-xxl-none" data-bs-toggle="offcanvas" data-bs-target="#responsiveOffcanvas" aria-controls="responsiveOffcanvas"><i class="bi bi-three-dots"></i></button> <button type="button" class="btn btn-sm btn-neutral d-none d-sm-inline-flex" data-bs-target="#buyModal" data-bs-toggle="modal"><span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Liquidity</span></button> 
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
						<div class="mt-10 d-flex justify-content-center">
							<!-- <img src="/img/marketing/hero-img-1.png"> -->
							<script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script> 
							<dotlottie-player src="<?= PROOT; ?>dist/media/bg.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></dotlottie-player>
						</div>
					<?php endif; ?>


				</div>
			</div>
		</main>

	<!-- BUY -->
	<div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content overflow-hidden">
				<div class="modal-header pb-0 border-0">
					<h1 class="modal-title h4" id="buyModalLabel">Make a sale</h1>
					<button type="button" class="btn-close btn-close-buyform" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body undefined">
					<div class="buy-msg p-1 small"></div>
					<form class="vstack gap-6" id="buyForm">
						<div id="step-1">
							<div class="vstack gap-1">
								<div class="bg-body-secondary rounded-3 p-4">
									<div class="d-flex justify-content-between text-xs text-muted">
										<span class="fw-semibold">Gram</span> <span class="gramMsg">...</span>
									</div>
									<div class="d-flex justify-content-between gap-2 mt-4">
										<input type="tel" inputmode="numeric" class="form-control form-control-flush text-xl fw-bold flex-fill" placeholder="0.00" id="gram-amount" name="gram-amount" autofocus required autocomplete="nope" data-step="2"> <button type="button" class="btn btn-neutral shadow-none rounded-pill flex-none d-flex align-items-center gap-2 py-2 ps-2 pe-4"><img src="<?= PROOT; ?>dist/media/grams.svg" class="w-rem-6 h-rem-6" alt="..."> <span class="text-xs fw-semibold text-heading ms-1">GRM</span></button>
									</div>
								</div>
								<div class="position-relative text-center my-n4 overlap-10">
									<div class="icon icon-sm icon-shape bg-body shadow-soft-3 rounded-circle text-sm text-body-tertiary">
										<i class="bi bi-arrow-down-up"></i>
									</div>
								</div>
								<div class="bg-body-secondary rounded-3 p-4">
									<div class="d-flex justify-content-between text-xs text-muted">
										<span class="fw-semibold">Volume</span> <span class="volumeMsg">...</span>
									</div>
									<div class="d-flex justify-content-between gap-2 mt-4">
										<input type="tel" inputmode="numeric" class="form-control form-control-flush text-xl fw-bold flex-fill" placeholder="0.00" id="volume-amount" name="volume-amount" required autocomplete="nope" data-step="2"> <button class="btn btn-neutral shadow-none rounded-pill flex-none d-flex align-items-center gap-2 py-2 ps-2 pe-4" type="button"><img src="<?= PROOT; ?>dist/media/volume.png" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span class="text-xs fw-semibold text-heading ms-1">VLM</span></button>
									</div>
								</div>
							</div>
							<div id="calculation-result" class="d-flex justify-content-center"></div>
							<br>
							<div id="result-view">
								<label class="form-label">Total Amount</label>
								<div class="d-flex flex-wrap gap-1 gap-sm-2">
									<div class="w-sm-56 input-group input-group-sm input-group-inline">
										<input type="text" readonly class="form-control" placeholder="0.00" id="total-amount"> <span class="input-group-text">₵</span>
									</div>
									<div class="flex-fill">
										<input type="radio" title="Density" class="btn-check"> <label class="btn btn-sm btn-neutral w-100" id="density" for="option1">0.0 Density</label>
									</div>
									<div class="flex-fill">
										<input type="radio" class="btn-check" title="Pounds"> <label class="btn btn-sm btn-neutral w-100" id="pounds" for="option2">0.00 Pounds</label>
									</div>
									<div class="flex-fill">
										<input type="radio" class="btn-check" title="Carat"> <label class="btn btn-sm btn-neutral w-100" id="carat" for="option3">0.00 Carat</label>
									</div>
								</div>
							</div>
							<br>
							<div class="mb-3">
							 	<textarea class="form-control form-control-flush flex-fill" style="overflow: hidden; resize: none;" placeholder="Leave a comment here" id="note" name="note"></textarea>
							</div>
							<button type="button" class="btn btn-primary w-100" id="next-1">Continue</button>
						</div>
						<div id="step-2" class="d-none text-center">
				        	<ul class="list-group" id="buysummary"></ul>
				        		<button type="button" class="btn btn-warning mt-4" id="next-2">Confirm Sale</button>
				        		<br><a href="javascript:;" class="text-dark" id="prev-1"><< Go Back</a>
				      	</div>
						<div id="step-3" class="d-none">
							<div class="inputpin mb-3">
								<input type="number" class="form-control form-control-xl fw-bolder" min="1" placeholder="Enter pin" name="pin" id="pin" autocomplete="nope">
							</div>
			        		<button type="submit" class="btn btn-warning mt-4" id="submitSend" name="submitSend">Complete Sale</button>
				        	<br><a href="javascript:;" class="text-dark" id="prev-2"><< Go Back</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- LOGIN -->
	<div class="modal fade" id="connectWalletModal" tabindex="-1" aria-labelledby="connectWalletModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content overflow-hidden">
				<div class="modal-header pb-0 border-0">
					<h1 class="modal-title h4" id="connectWalletModalLabel">Connect your account</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body undefined">
					<div class="list-group list-group-flush gap-2">
						<form method="POST" action="<?= PROOT; ?>auth/login">
							<div class="list-group-item border rounded p-4 bg-body-secondary-hover">
								<div class="">
									<input type="email" name="admin_email" class="form-control" placeholder="Email">
								</div>
							</div>
							<div class="list-group-item border rounded p-4 bg-body-secondary-hover">
								<div class="">
									<input type="password" name="admin_password" class="form-control" placeholder="******">
								</div>
							</div>
							<div class="list-group-item border rounded p-4 bg-body-secondary-hover">
								<div class="">
									<input type="submit" name="submit_form" class="form-control" value="Connect">
								</div>
							</div>
						</form>
					</div>
					<div class="text-xs text-muted mt-6">Missing password? <a href="auth/recover-password" class="fw-bold">Recover here.</a></div>
					<div class="text-xs text-muted mt-6">By connecting, know that we save all actions into logs for future references. You agree to J-Spence <a href="#" class="fw-bold">Terms of Service</a></div>
				</div>
			</div>
		</div>
	</div>

	<?php include ("includes/footer.inc.php"); ?>


    <script>
        $(document).ready(function() {

	        // Fade out messages
	        $("#temporary").fadeOut(5000);

        	// Calculation made with gram input
            $('#gram-amount').on('keyup', function(e) {
                e.preventDefault();

                // var step = this.getAttribute('data-step');
                
                var gram = $('#gram-amount').val();
                var volume = $('#volume-amount').val();

      			// if (gram < step) {
      			// 	console.log('not accept');
      			// }

	            if (gram != '' && gram > 0) {
                 	if (volume != '' && volume > 0) {
                 		$('.volumeMsg').text('');
		            	$('.gramMsg').text('...');

		                $.ajax({
							url : 'auth/gold.calculation.php',
							method : 'POST',
							data : {
								gram : gram,
								volume : volume,
							},
							beforeSend : function () {
								// body...
								$('#calculation-result').html('<img class="img-fluid" src="<?= PROOT; ?>dist/media/loading_v2.gif"/>');
								$('#result-view').addClass('d-none');
							},
							success: function(data) {
								const response = JSON.parse(data);
								//if (response["message"] != '') {
									$('.toast-body').html(response["message"]);
						    		$('.toast').toast('show');
								//}
								$('#density').text(response["density"] + ' Density');
								$('#pounds').text(response["pounds"] + ' Pounds');
								$('#carat').text(response["carat"] + ' Carat');
								$('#total-amount').val(response["total_amount"]);
								$('#calculation-result').html('')
								$('#calculation-result').addClass('d-none');
								$('#result-view').removeClass('d-none');

								$('.gramMsg').text('...');
				            	$('.volumeMsg').text('...');
							},
							error: function() {
								return false;
							}
						})
		            } else {
		            	$('.volumeMsg').text('typing ...');
		            	$('.gramMsg').text('');
		            }
		        }

            })

            // Calculation made with volume input
            $('#volume-amount').on('keyup', function(e) {
                e.preventDefault();

                var gram = $('#gram-amount').val();
                var volume = $('#volume-amount').val();

                if (volume != '' && volume > 0) {
	                if (gram != '' && gram > 0) {
                 		$('.volumeMsg').text('...');
		            	$('.gramMsg').text('');

		                $.ajax ({
							url : 'auth/gold.calculation.php',
							method : 'POST',
							data : {
								gram : gram,
								volume : volume,
							},
							beforeSend : function () {
								// body...
								$('#calculation-result').html('<img class="img-fluid" src="<?= PROOT; ?>dist/media/loading_v2.gif"/>');
								$('#result-view').addClass('d-none');
							},
							success: function(data) {
								const response = JSON.parse(data);
								//if (response["message"] != '') {
									$('.toast-body').html(response["message"]);
						    		$('.toast').toast('show');
								//}
								$('#density').text(response["density"] + ' Density');
								$('#pounds').text(response["pounds"] + ' Pounds');
								$('#carat').text(response["carat"] + ' Carat');
								$('#total-amount').val(response["total_amount"]);
								$('#calculation-result').html('')
								$('#calculation-result').addClass('d-none');
								$('#result-view').removeClass('d-none');


			                	$('.gramMsg').text('...');
				            	$('.volumeMsg').text('...');
							},
							error: function() {
								return false;
							}
						})
	                } else {
	                	$('.gramMsg').text('typing ...');
		            	$('.volumeMsg').text('...');
	                }
                }
            });

            // Next to 1
            $('#next-1').click(function(e) {
		       	e.preventDefault();

				$('.gramMsg').text('...');
		        $('.volumeMsg').text('...');
		        $('.buy-msg').text('');

		        if ($("#gram-amount").val() <= 0) {
		            $('.gramMsg').html('* Invalid gram amount!');
		            $("#gram-amount").focus()
		            return false;
		        }

		        if ($("#volume-amount").val() <= 0) {
		            $('.volumeMsg').html('* Invalid volume amount!');
		            $("#volume-amount").focus()
		            return false;
		        }

				$('#buysummary').html(
				`
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
				  		<small class="text-muted">Note</small>
				  		<p>` + $("#note").val() + `</p>
				  	</li>
				`
				);
				
				$('#buyModalLabel').html('Sale Summary');
				$('#step-1').addClass('d-none');
		        $('#step-2').removeClass('d-none');
		        $('#step-3').addClass('d-none');
		    })

            // Next to 3
            $('#next-2').click(function(e) {
		       	e.preventDefault();

				$('#buyModalLabel').html('Authentication for sale.');
		        $('#step-1').addClass('d-none');
		        $('#step-2').addClass('d-none');
		        $('#step-3').removeClass('d-none');

		    })

            // Back to 1
        	$("#prev-1").click(function() {
				$('#buyModalLabel').html('Make a sale');
		        $('#step-1').removeClass('d-none')
		        $('#step-2').addClass('d-none')
		        $('#step-3').addClass('d-none')
		    });

        	// Back to 2
		    $("#prev-2").click(function() {
				$('#buyModalLabel').html('Sale Summary');
		        $('#step-2').removeClass('d-none')
		        $('#step-3').addClass('d-none')
		        $('#step-1').addClass('d-none')
		    });

        	// when buy modal is to be closed
        	 $('.btn-close-buyform').click(function(e) {
		    	e.preventDefault()

				$('#density').text('0.00 Density');
				$('#pounds').text('0.00 Pounds');
				$('#carat').text('0.00 Carat');
				$('#total-amount').val('');

		    	$('#buyForm')[0].reset();

				$('.buy-msg').text('');
				$('#gramMsg').text('');
				$('#volumeMsg').text('');

		    	$('#step-1').removeClass('d-none');
		        $('#step-2').addClass('d-none');
		        $('#step-3').addClass('d-none');

		    	$('#buyModal').modal('hide');
		    })
        	
        	// Add a made sale
	        var $this = $('#buyForm');
			var $state = $('.toast-body');
			$('#buyForm').on('submit', function(event) {
				event.preventDefault();

				var gram = $('#gram-amount').val();
                var volume = $('#volume-amount').val();
				var pin = $('#pin').val();

				if (gram == '' || gram <= 0) {
			        $('#submitSend').attr('disabled', false);
					$state.html('* Invalid gram provided!');
				    $('.toast').toast('show');
		            
		            // $('.gramMsg').html('* Invalid gram provided.');
		            // $("#gram-amount").focus()
		            
		            return false;
		        }

		        if (volume == '' || volume <= 0) {
			        $('#submitSend').attr('disabled', false);
					$state.html('* Invalid volume provided!');

				    $('.toast').toast('show');
		            // $('.volumeMsg').html('* Invalid volume provided.');
		            // $("#volume-amount").focus();
		            
		            return false;
		        }


		        if (pin != '') {
					$.ajax({
			          	url : 'auth/make.sale.php',
			          	method : 'POST',
			          	data : $(this).serialize(),
			          	beforeSend : function() {
				            $this.find('#submitSend').attr("disabled", true);
				            $this.find('#submitSend').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span> Loading...</span>');
			          	},
			          	success : function(data) {
			            	if (data == '') {
			              		$state.removeClass('text-danger');
			              		$state.addClass('text-info');
			              		$state.html('Sale successfully made!');
			              		$this.find('#submitSend').text("Complete Sale");
			              		$('.toast').toast('show');
			              		$('#buyModalLabel').html('Make a sale');
								$('.volumeMsg').text('')
								$('.gramMsg').text('');
								$('buy-msg').text('');
			              		$('#buyForm')[0].reset();
			              		$('#step-1').removeClass('d-none');
			              		$('#step-2').addClass('d-none');
			              		$('#step-3').addClass('d-none');
				            	$this.find('#submitSend').attr("disabled", false);
			              		$('#buyModal').modal('hide');
			            	} else {
			              		var errors = data;
			              		$this.find('#submitSend').attr("disabled", false);
			              		$this.find('#submitSend').text("Complete sale");
			              		$state.html(errors);
			              		$('.toast').toast('show');
			              		$('#buyModalLabel').html('Make a sale');
			              		// $('#buyForm')[0].reset();
						    	$('#step-1').removeClass('d-none');
					        	$('#step-2').addClass('d-none');
					        	$('#step-3').addClass('d-none');
		              		// setTimeout(function () {
		                	// 	window.location = 'index';
		              		// }, 100);
		            		}
		          		}
		        	});
				} else {
			        $('#submitSend').attr('disabled', false);
					$state.html('* Pin required!');
				    $('.toast').toast('show');
		            $("#pin").val()
				}
			})


        });
    </script>
</body>
</html>