    <!-- FOOTER -->

    </main>

    <!-- TRADE MODAL -->
    <div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" style="backdrop-filter: blur(5px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden bg-body-tertiary">
                <div class="modal-header w-400 pb-0 border-0">
                    <h1 class="modal-title h4 fw-medium" id="buyModalLabel"><?= admin_has_permission('supervisor') ? 'Sell' : 'Buy'; ?> trade</h1>
                    <button type="button" class="btn-close btn-close-buyform" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body undefined">
                    <div class="buy-msg p-1 small"></div>
                    <form class="vstack gap-6" id="buyForm">
                        <div id="step-1">
                            <div class="mb-3">
                                <input type="number" name="current_price" id="current_price" class="form-control fw-semibold" placeholder="Current price" style="border: none;" required autocomplete="off" inputmode="numeric" min="0.00" step="0.01" autofocus>
                            </div>
                            <div class="vstack gap-1">
                                <div class="bg-body-secondary rounded-3 p-4">
                                    <div class="d-flex justify-content-between text-xs text-muted">
                                        <span class="fw-semibold">Gram</span> <span class="gramMsg">...</span>
                                    </div>
                                    <div class="d-flex justify-content-between gap-2 mt-4">
                                        <input type="number" inputmode="numeric" class="form-control form-control-flush fw-bold text-xl flex-fill" placeholder="0.00" id="gram-amount" name="gram-amount" required autocomplete="off" min="0.00" step="0.01"> <button type="button" class="btn btn-outline-light shadow-none rounded-pill flex-none d-flex align-items-center gap-2 py-2 ps-2 pe-4"><img src="<?= PROOT; ?>assets/media/grams.svg" class="w-rem-6 h-rem-6" alt="..."> <span class="text-xs text-heading ms-1">GRM</span>&nbsp;</button>
                                    </div>
                                </div>
                                <div class="position-relative text-center my-n4 overlap-10" style="margin-top: -1rem !important; margin-bottom: -1rem !important;">
                                    <div class="icon icon-sm icon-shape bg-body shadow-soft-3 rounded-circle text-sm text-body-tertiary" style="box-shadow: 0 9px 9px -1px rgba(10,22,70,.04)!important">
                                        <span class="material-symbols-outlined">swap_vert</span>
                                    </div>
                                </div>
                                <div class="bg-body-secondary rounded-3 p-4">
                                    <div class="d-flex justify-content-between text-xs text-muted">
                                        <span class="fw-semibold">Volume</span> <span class="volumeMsg">...</span>
                                    </div>
                                    <div class="d-flex justify-content-between gap-2 mt-4">
                                        <input type="number" inputmode="numeric" class="form-control form-control-flush fw-bold text-xl flex-fill" placeholder="0.00" id="volume-amount" name="volume-amount" required autocomplete="off" min="0.00" step="0.01"> <button class="btn btn-outline-light shadow-none rounded-pill flex-none d-flex align-items-center gap-2 py-2 ps-2 pe-4" type="button"><img src="<?= PROOT; ?>assets/media/volume.png" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span class="text-xs text-heading ms-1">VLM</span>&nbsp;</button>
                                    </div>
                                </div>
                            </div>
                            <div id="calculation-result" class="d-flex justify-content-center"></div>
                            <br>
                            <div id="result-view">
                                <label class="form-label">Total Amount</label>
                                <div class="d-flex flex-wrap gap-1 gap-sm-2">
                                    <div class="w-sm-56 input-group input-group-sm input-group-inline">
                                        <input type="text" readonly class="form-control fw-semibold" placeholder="0.00" id="total-amount" style="border: none;"> <span class="input-group-text">₵</span>
                                    </div>
                                    <div class="flex-fill">
                                        <input type="radio" title="Density" class="btn-check"> <label class="btn btn-sm w-100" id="density" for="option1">0.0 Density</label>
                                    </div>
                                    <div class="flex-fill">
                                        <input type="radio" class="btn-check" title="Pounds"> <label class="btn btn-sm w-100" id="pounds" for="option2">0.00 Pounds</label>
                                    </div>
                                    <div class="flex-fill">
                                        <input type="radio" class="btn-check" title="Carat"> <label class="btn btn-sm w-100" id="carat" for="option3">0.00 Carat</label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="mb-3">
                                <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Customer name" style="border: none;" required autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <input type="text" name="customer_contact" id="customer_contact" class="form-control" placeholder="Customer contact" style="border: none;" required autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control form-control-flush flex-fill" style="overflow: hidden; resize: none;" placeholder="Leave a comment here" id="note" name="note"></textarea>
                            </div>
                            <button type="button" class="btn btn-warning w-100" id="next-1">Continue</button>
                        </div>
                        <div id="step-2" class="d-none text-center">
                            <ul class="list-group" id="buysummary"></ul>
                            <button type="button" class="btn btn-warning mt-4" id="next-2">Confirm Sale</button>
                            <br><br>
                            <a href="javascript:;" class="text-danger" id="prev-1"><< Go Back</a>
                        </div>
                        <div id="step-3" class="d-none">
                            <div class="inputpin mb-3">
                                <div>
                                    <?php if (is_capital_given()): ?>
                                        <?php if (is_capital_exhausted($conn, $admin_data['admin_id'])): ?>
                                            <label class="form-label">Enter pin</label>
                                            <div class="d-flex justify-content-between p-4 bg-body-secondary rounded">
                                                <input type="number" class="form-control form-control-flush text-xl fw-bold w-rem-40" placeholder="0000" name="pin" id="pin" autocomplete="off" inputmode="numeric" data-maxlength="4" oninput="this.value=this.value.slice(0,this.dataset.maxlength)">
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm btn-light rounded-pill shadow-none flex-none d-flex align-items-center gap-2 p-2" style="border: 1px solid #cbd5e1;">
                                                        <img src="<?= PROOT; ?>assets/media/pin.jpg" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span>PIN</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="h5 text-muted">
                                        <?php else: ?>
                                            Trade ended: the capital given for today's trade has been exhausted!
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Please you are to provide today's capital given before you can complete a trade!
                                    <?php endif; ?>
                                            </p>
                                </div>
                            </div>
                            <?php if (is_capital_given()): ?>
                                <?php if (is_capital_exhausted($conn, $admin_data['admin_id'])): ?>
                                    <button type="submit" class="btn btn-warning mt-4" id="submitSend" name="submitSend">Complete Sale</button>
                                <?php endif; ?>
                            <?php endif; ?>
                            <br><br><a href="javascript:;" class="text-danger" id="prev-2"><< Go Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Coffers modal -->
    <?php if (admin_has_permission('supervisor')): ?>
	<div class="modal fade" id="modalCapital" tabindex="-1" aria-labelledby="modalCapital" aria-hidden="true" style="backdrop-filter: blur(5px);">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content shadow-3">
				<div class="modal-header justify-content-start">
					<div class="icon icon-shape rounded-3 bg-primary-subtle text-primary text-lg me-4">
						<i class="bi bi-currency-exchange"></i>
					</div>
					<div>
						<h5 class="mb-1">Fund coffers</h5>
						<small class="d-block text-xs text-muted">Add an amount from trades or other options to fund coffers.</small>
					</div>
				</div>
				<form method="POST" id="fundCoffersForm" action="<?= PROOT; ?>auth/add.amount.to.coffers.php">
					<div class="modal-body">
						<div class="mb-4">
							<label class="form-label">Today's Date</label> 
							<input class="form-control" name="today_date" id="today_date" readonly type="date" value="<?php echo date('Y-m-d'); ?>" required>
						</div>
						<div class="mb-4">
							<div class="form-check mb-2">
								<input class="form-check-input for_class" type="radio" name="add_from" id="flexRadioDefault1" value="trades" required>
								<label class="form-check-label" for="flexRadioDefault1">
									From today trades
								</label>
							</div>
							<div class="form-check mb-2">
								<input class="form-check-input for_class" type="radio" name="add_from" id="flexRadioDefault2" value="cash" required>
								<label class="form-check-label" for="flexRadioDefault2">
									Physical cash
								</label>
							</div>
						</div>
						<div class="">
							<label class="form-label">Amount</label> 
							<input type="number" class="form-control" placeholder="0.00" name="add_amount" id="add_amount" autocomplete="off" min="0.00" step="0.01" required />
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-light" id="close-fund-coffers-form" data-bs-dismiss="modal">Close</button>&nbsp;&nbsp;
						<button type="button" id="submitFundCoffers" class="btn btn-sm btn-warning">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
    <?php endif; ?>

    <!-- TOAST FOR LIVE MESSAGES -->
    <div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center w-100">
        <div id="live-toast" class="toast fade hide position-fixed rounded" role="alert" aria-live="assertive" aria-atomic="true" style="background-color: #6e46cc; right: 6px; bottom: 0; z-index: 99999;">
            <div class="toast-header small p-1 border-bottom">
                <img src="<?= PROOT; ?>assets/media/logo.jpeg" style="width: 35px; height: 35px;" class="rounded me-2" alt="J-Spence Logo">
                <strong class="me-auto small">J-Spence</strong>
                <small>notification . just now</small>
                <button type="button" class="btn-close small" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body p-1 small"></div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="<?= PROOT; ?>assets/js/jquery-3.7.1.min.js"></script>
    <!-- Map JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js'></script>
    
    <!-- Vendor JS -->
    <script src="<?= PROOT; ?>assets/js/vendor.bundle.js"></script>
    
    <!-- Theme JS -->
    <script src="<?= PROOT; ?>assets/js/theme.bundle.js"></script>

    <script type="text/javascript">

        // activate left nav link upon url

        if (window.location.href.indexOf("index") > -1) {
            $('.nav-dashboard').addClass('active');
            $('.nav-dashboard').attr('aria-expanded', true);
            $('#dashboard').addClass('show');
        }
        
        if (window.location.href.indexOf("analytics") > -1) {
            $('.nav-dashboard').addClass('active');
            $('.nav-dashboard').attr('aria-expanded', true);
            $('#dashboard').addClass('show');
        }

        if ((window.location.href.indexOf("trades") > -1) || (window.location.href.indexOf("end-trade") > -1)) {
            $('.nav-market').addClass('active');
            $('.nav-market').attr('aria-expanded', true);
            $('#market').addClass('show');
        }

        if (window.location.href.indexOf("expenditure") > -1) {
            $('.nav-expenditure').addClass('active');
            $('.nav-expenditure').attr('aria-expanded', true);
            $('#expenditure').addClass('show');
        }
        
        if (window.location.href.indexOf("pushes") > -1) {
            $('.nav-pushes').addClass('active');
            $('.nav-pushes').attr('aria-expanded', true);
            $('#pushes').addClass('show');
        }

        if (window.location.href.indexOf("admins") > -1) {
            $('.nav-admins').addClass('active');
            $('.nav-admins').attr('aria-expanded', true);
            $('#admins').addClass('show');
        }

        if ((window.location.href.indexOf("profile") > -1) || window.location.href.indexOf("settings") > -1) {
            $('.nav-account').addClass('active');
            $('.nav-account').attr('aria-expanded', true);
            $('#account').addClass('show');
        }

        if (window.location.href.indexOf("logs") > -1) {
            $('.nav-logs').addClass('active');
            $('.nav-logs').attr('aria-expanded', true);
            $('#logs').addClass('show');
        }

        var childpath = window.location.href;
        $('nav a.nav-child').each(function() {
            if (this.href === childpath) {
                $(this).addClass('active');
            }
        });


        // 
        $(document).ready(function() {

            // executing a function after the user has stopped typing for a specified amount of time or in events that fire at a high rate
            function delay(callback, ms) {
                var timer = 0;
                return function() {
                    var context = this, args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                    callback.apply(context, args);
                    }, ms || 0);
                };
                }

            // Fade out messages
            $("#temporary").fadeOut(5000);

            // prevent enter key on form
            $(document).on("keydown", "form :input:not(textarea)", function(event) {
                return event.key != "Enter";
            });

            // make a push
            $('#close-fund-coffers-form').on('click', function() {
                $('#fundCoffersForm')[0].reset();
            });

            $('#submitFundCoffers').on('click', function() {
                if (!$("input[name='add_from']:checked").val()) {
                    alert("You will have to select a where the money is from!");
                    $("input[name='add_from']").focus()
                    return false;
                } else {
                    if ($("input[name='add_from']:checked").val() == 'trades') {
                        var a = '<?= total_amount_today($admin_id); ?>'
                        if ($('#add_amount').val() > +a) {
                            alert("Invalid Fund amount!");
                            $('#add_amount').focus() 
                            return false;
                        }
                    }
                }

                if ($('#add_amount').val() <= 0) {
                    // alert("Fund amount cannot be less than 0!");
                    $('#add_amount').focus() 
                    return false;
                }
                
                $('#submitFundCoffers').attr('disabled', true);
                $('#submitFundCoffers').text('Adding ...');
                
                setInterval(function () {
                    $('#fundCoffersForm').submit();
                }, 2000)
            })

            // Calculation made with current price input
            $('#current_price').on('keyup', delay(function(e) {
                e.preventDefault();
                
                var current_price = $('#current_price').val();
                var gram = $('#gram-amount').val();
                var volume = $('#volume-amount').val();

                if (current_price != '' && current_price > 0) {
                    if (gram != '' && gram > 0) {
                        if (volume != '' && volume > 0) {
                            $('.buy-msg').text('typing ...');
                            $('#next-1').attr('disabled', false);

                            $.ajax({
                                url : '<?= PROOT; ?>auth/gold.calculation.php',
                                method : 'POST',
                                data : {
                                    gram : gram,
                                    volume : volume,
                                    current_price : current_price,
                                },
                                beforeSend : function () {
                                    // body...
                                    $('#calculation-result').html('<img class="img-fluid" src="<?= PROOT; ?>assets/media/loading_v2.gif"/>');
                                    $('#next-1').attr('disabled', true);
                                    $('#result-view').addClass('d-none');
                                },
                                success: function(data) {
                                    console.log(data)
                                    const response = JSON.parse(data);
                                    //if (response["message"] != '') {
                                        $('.toast-body').html(response["message"]);
                                        $('.toast').toast('show');
                                    //}
                                    $('#density').text(response["density"] + ' Density');
                                    $('#pounds').text(response["pounds"] + ' Pounds');
                                    $('#carat').text(response["carat"] + ' Carat');
                                    $('#total-amount').val(response["total_amount"]);

                                    if (response['continue'] == 'no') {
                                        $('#next-1').attr('disabled', true);
                                    } else if (response['continue'] == 'yes') {
                                        $('#next-1').attr('disabled', false);
                                    }

                                    $('#calculation-result').html('')
                                    $('#calculation-result').addClass('d-none');
                                    $('#result-view').removeClass('d-none');

                                    $('.buy-msg').text('');
                                },
                                error: function() {
                                    return false;
                                }
                            })
                        } else {
                            $('.buy-msg').text('');
                        }
                    }
                }

            }, 500));


            // Calculation made with gram input
            $('#gram-amount').on('keyup', function(e) {
                e.preventDefault();

                // var step = this.getAttribute('data-step');
                
                var current_price = $('#current_price').val();
                var gram = $('#gram-amount').val();
                var volume = $('#volume-amount').val();

                if (current_price != '' && current_price > 0) {
                    if (gram != '' && gram > 0) {
                        if (volume != '' && volume > 0) {
                            $('.volumeMsg').text('');
                            $('.gramMsg').text('...');
                            $('#next-1').attr('disabled', false);

                            $.ajax({
                                url : '<?= PROOT; ?>auth/gold.calculation.php',
                                method : 'POST',
                                data : {
                                    gram : gram,
                                    volume : volume,
                                    current_price : current_price,
                                },
                                beforeSend : function () {
                                    // body...
                                    $('#calculation-result').html('<img class="img-fluid" src="<?= PROOT; ?>assets/media/loading_v2.gif"/>');
                                    $('#next-1').attr('disabled', true);
                                    $('#result-view').addClass('d-none');
                                },
                                success: function(data) {
                                    console.log(data)
                                    const response = JSON.parse(data);
                                    //if (response["message"] != '') {
                                        $('.toast-body').html(response["message"]);
                                        $('.toast').toast('show');
                                    //}
                                    $('#density').text(response["density"] + ' Density');
                                    $('#pounds').text(response["pounds"] + ' Pounds');
                                    $('#carat').text(response["carat"] + ' Carat');
                                    $('#total-amount').val(response["total_amount"]);

                                    if (response['continue'] == 'no') {
                                        $('#next-1').attr('disabled', true);
                                    } else if (response['continue'] == 'yes') {
                                        $('#next-1').attr('disabled', false);
                                    }

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
                }

            })

            // Calculation made with volume input
            $('#volume-amount').on('keyup', function(e) {
                e.preventDefault();

                var current_price = $('#current_price').val();
                var gram = $('#gram-amount').val();
                var volume = $('#volume-amount').val();

                if (current_price != '' && current_price > 0) {
                    if (volume != '' && volume > 0) {
                        if (gram != '' && gram > 0) {
                            $('.volumeMsg').text('...');
                            $('.gramMsg').text('');
                            $('#next-1').attr('disabled', false);

                            $.ajax ({
                                url : '<?= PROOT; ?>auth/gold.calculation.php',
                                method : 'POST',
                                data : {
                                    current_price : current_price,
                                    gram : gram,
                                    volume : volume,
                                },
                                beforeSend : function () {
                                    // body...
                                    $('#calculation-result').html('<img class="img-fluid" src="<?= PROOT; ?>assets/media/loading_v2.gif"/>');
                                    $('#next-1').attr('disabled', true);
                                    $('#result-view').addClass('d-none');
                                },
                                success: function(data) {
                                    console.log(data)
                                    const response = JSON.parse(data);
                                    //if (response["message"] != '') {
                                        $('.toast-body').html(response["message"]);
                                        $('.toast').toast('show');
                                    //}
                                    $('#density').text(response["density"] + ' Density');
                                    $('#pounds').text(response["pounds"] + ' Pounds');
                                    $('#carat').text(response["carat"] + ' Carat');
                                    $('#total-amount').val(response["total_amount"]);

                                    if (response['continue'] == 'no') {
                                        $('#next-1').attr('disabled', true);
                                    } else if (response['continue'] == 'yes') {
                                        $('#next-1').attr('disabled', false);
                                    }

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
                }
            });

            // Next to 1
            $('#next-1').click(function(e) {
                e.preventDefault();

                $('.gramMsg').text('...');
                $('.volumeMsg').text('...');
                $('.buy-msg').text('');

                if ($("#current_price").val() <= 0) {
                    $('.buy-msg').html('* Invalid current price!');
                    $("#current_price").focus()
                    return false;
                }

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

                if (+$("#volume-amount").val() > +$("#gram-amount").val()) {
                    $('.volumeMsg').html('* Volume can not be  greater than Gram!');
                    $("#volume-amount").focus()
                    return false;
                }
                <?php if (admin_has_permission('salesperson')): ?>
                if ($("#total-amount").val() <= 0) {
                    $('.buy-msg').text('* There is a problem with the calculation, please check your inputs well!');
                    return false;
                }
                <?php endif; ?>

                if ($("#customer_name").val() == '') {
                    $('.buy-msg').html('* Invalid customer name!');
                    $("#customer_name").focus()
                    return false;
                }

                if ($("#customer_contact").val() == '') {
                    $('.buy-msg').html('* Invalid cutomer contact!');
                    $("#customer_contact").focus()
                    return false;
                }

                $('#buysummary').html(
                `
                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
                        <small class="text-muted">Total amount,</small>
                        <p>₵` + $("#total-amount").val() + `</p>
                    </li>
                    <li class="list-group-item" style="padding: 0.1rem 1rem;">
                        <small class="text-muted">Curent price</small>
                        <p>₵` + Number($("#current_price").val()).toFixed(2) + `</p>
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

                var current_price = $('#current_price').val();
                var gram = $('#gram-amount').val();
                var volume = $('#volume-amount').val();
                var pin = $('#pin').val();

                if (current_price == '' || current_price <= 0) {
                    $('#submitSend').attr('disabled', false);
                    $state.html('* Invalid Curent price provided!');
                    $('.toast').toast('show');
                    return false;
                }

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

                if ($("#customer_name").val() == '') {
                    $('#submitSend').attr('disabled', false);
                    $state.html('* Invalid customer name!');
                    $('.toast').toast('show');
                    return false;
                }

                if ($("#customer_contact").val() == '') {
                    $('#submitSend').attr('disabled', false);
                    $state.html('* Invalid cutomer contact!');
                    $('.toast').toast('show');
                    return false;
                }

                if (pin != '') {
                    $.ajax({
                        url : '<?= PROOT; ?>auth/make.sale.php',
                        method : 'POST',
                        data : $(this).serialize(),
                        beforeSend : function() {
                            $this.find('#submitSend').attr("disabled", true);
                            $this.find('#submitSend').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span> Loading...</span>');
                        },
                        success : function(data) {
                            try {
                                console.log(data)
                                const response = JSON.parse(data);
                                if (response && typeof response === "object") {

                                    $state.removeClass('text-danger');
                                    $state.addClass('text-warning');
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

                                    // print receipt
                                    print_receipt({
                                        reference: response["reference"], 
                                        customername: response["customername"], 
                                        date: response["date"], 
                                        gram: response["gram"], 
                                        volume: response["volume"], 
                                        density: response["density"], 
                                        pounds: response["pounds"], 
                                        carat: response["carat"], 
                                        current_price: response["current_price"], 
                                        total_amount: response["total_amount"], 
                                        by: response["by"], 
                                    });
                                }
                            } catch (e) {
                                var errors = data;
                                $this.find('#submitSend').attr("disabled", false);
                                $this.find('#submitSend').text("Complete sale");
                                $state.html(errors);
                                $('.toast').toast('show');
                                $('#buyModalLabel').html('Make a sale');
                                $('#step-1').removeClass('d-none');
                                $('#step-2').addClass('d-none');
                                $('#step-3').addClass('d-none');
                                $('#pin').val('');

                                return false;
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

            // open receipt window
            var RECEIPT_WINDOW = null;
            function print_receipt(obj) {
                var vars = JSON.stringify(obj);
                RECEIPT_WINDOW = window.open('<?= PROOT; ?>auth/print?data='+vars,'1429893142534','width=' + (parseInt(window.innerWidth) * 0.4) + ',height=' + (parseInt(window.innerHeight) * .6) + ',toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=0,left=0,top=0');

                setTimeout(function() {
                    RECEIPT_WINDOW.close();
                    location.reload();
                }, 3000);

                return false;
            }

        });
    </script>
</body>
</html>
