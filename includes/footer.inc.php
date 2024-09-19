    <!-- FOOTER -->
    
    </main>

    <!-- JAVASCRIPT -->
    <!-- Map JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.js'></script>
    
    <!-- Vendor JS -->
    <script src="<?= PROOT; ?>assets/js/vendor.bundle.js"></script>
    
    <!-- Theme JS -->
    <script src="<?= PROOT; ?>assets/js/theme.bundle.js"></script>
    <script src="<?= PROOT; ?>dist/js/switcher.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            // Fade out messages
            $("#temporary").fadeOut(5000);

            // Calculation made with current price input
            $('#current_price').on('keyup', function(e) {
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
                                    $('#calculation-result').html('<img class="img-fluid" src="<?= PROOT; ?>dist/media/loading_v2.gif"/>');
                                    $('#next-1').attr('disabled', true);
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
                                    $('#carat').text(response["carat"] + ' Karat');
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

            })


            // Calculation made with gram input
            $('#gram-amount').on('keyup', function(e) {
                e.preventDefault();

                // var step = this.getAttribute('data-step');
                
                var current_price = $('#current_price').val();
                var gram = $('#gram-amount').val();
                var volume = $('#volume-amount').val();

                // if (gram < step) {
                //  console.log('not accept');
                // }

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
                                    $('#calculation-result').html('<img class="img-fluid" src="<?= PROOT; ?>dist/media/loading_v2.gif"/>');
                                    $('#next-1').attr('disabled', true);
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
                                    $('#carat').text(response["carat"] + ' Karat');
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
                                    $('#calculation-result').html('<img class="img-fluid" src="<?= PROOT; ?>dist/media/loading_v2.gif"/>');
                                    $('#next-1').attr('disabled', true);
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
                                    $('#carat').text(response["carat"] + ' Karat');
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
                        <small class="text-muted">Karat</small>
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
                $('#carat').text('0.00 Karat');
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
                                const response = JSON.parse(data);
                                if (response && typeof response === "object") {

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
