<?php 

    // view admin profile details
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("../includes/header.inc.php");
    include ("../includes/nav.inc.php");

?>
    
    <div class="px-6 px-lg-7 pt-8 border-bottom">
        <div class="d-flex align-items-center">
            <h1>Sales</h1>
            <div class="hstack gap-2 ms-auto">
                <button type="button" class="btn btn-sm btn-neutral d-none d-lg-inline-flex">
                    <i class="bi bi-arrow-90deg-right me-2"></i> Export
                </button> 
                <button type="button" class="btn btn-sm btn-primary d-none d-sm-inline-flex" data-bs-target="#buyModal" data-bs-toggle="modal"><span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>New sale</span></button>
            </div>
        </div>
           

        <ul class="nav nav-tabs nav-tabs-flush gap-8 overflow-x border-0 mt-1">
            <li class="nav-item">
                <a href="#" class="nav-link active">All</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Succeeded</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Uncaptured</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">Failed</a>
            </li>
        </ul>
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm table-nowrap">
                <thead>
                    <tr>
                        <th scope="col">
                            <div class="d-flex align-items-center gap-2 ps-1">
                                <div class="text-base">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </div>
                                <span>Handler</span>
                            </div>
                        </th>
                        <th scope="col">Currency</th><th scope="col">Rate</th><th scope="col">Amount</th><th scope="col" class="d-none d-xl-table-cell">Date</th><th scope="col" class="d-none d-xl-table-cell">Status</th><th scope="col" class="d-none d-xl-table-cell">Required Action</th><th></th></tr></thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3 ps-1">
                                <div class="text-base">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox">
                                    </div>
                                </div>
                                <div class="d-none d-xl-inline-flex icon icon-shape w-rem-8 h-rem-8 rounded-circle text-sm bg-secondary bg-opacity-25 text-secondary">
                                    <i class="bi bi-file-fill"></i>
                                </div>
                                <div>
                                    <span class="d-block text-heading fw-bold">Bought BTC</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-xs">BTC <i class="bi bi-arrow-right mx-2"></i> USDT</td>
                        <td>1.23</td><td>$1,300,000.00</td>
                        <td class="d-none d-xl-table-cell">3 min ago</td>
                        <td class="d-none d-xl-table-cell">
                            <span class="badge badge-lg badge-dot">
                                <i class="bg-success"></i>Active
                            </span>
                        </td>
                        <td class="d-none d-xl-table-cell">Needs your attention</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6">
                                <i class="bi bi-three-dots"></i>
                            </button>
                        </td>
                    </tr>


                </tbody></table></div><div class="py-4 px-6"><div class="row align-items-center justify-content-between"><div class="col-md-6 d-none d-md-block"><span class="text-muted text-sm">Showing 10 items out of 250 results found</span></div><div class="col-md-auto"><nav aria-label="Page navigation example"><ul class="pagination pagination-spaced gap-1"><li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li><li class="page-item"><a class="page-link" href="#">1</a></li><li class="page-item"><a class="page-link" href="#">2</a></li><li class="page-item"><a class="page-link" href="#">3</a></li><li class="page-item"><a class="page-link" href="#">4</a></li><li class="page-item"><a class="page-link" href="#">5</a></li><li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li></ul></nav></div>



        </div>
    </div>

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

<?php include ("../includes/footer.inc.php"); ?>

    <script>
        $(document).ready(function() {

            // Calculation made with gram input
            $('#gram-amount').on('keyup', function(e) {
                e.preventDefault();

                // var step = this.getAttribute('data-step');
                
                var gram = $('#gram-amount').val();
                var volume = $('#volume-amount').val();

                // if (gram < step) {
                //  console.log('not accept');
                // }

                if (gram != '' && gram > 0) {
                    if (volume != '' && volume > 0) {
                        $('.volumeMsg').text('');
                        $('.gramMsg').text('...');

                        $.ajax({
                            url : '../auth/gold.calculation.php',
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
                            url : '../auth/gold.calculation.php',
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
                        url : '../auth/make.sale.php',
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
                            //  window.location = 'index';
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
