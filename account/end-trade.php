<?php 

    // view admin profile details
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    if (admin_has_permission()) {
        redirect(PROOT . 'accounts/trades');
    }

    include ("../includes/header.inc.php");
    include ("../includes/aside.inc.php");
    include ("../includes/left.nav.inc.php");
    include ("../includes/top.nav.inc.php");

    $errors = "";



?>

    <!-- Content -->
    <div class="container-lg">
        <!-- Page header -->
        <div class="row align-items-center mb-7">
            <div class="col-auto">
                <!-- Avatar -->
                <div class="avatar avatar-xl rounded text-warning">
                    <i class="fs-2" data-duoicon="credit-card"></i>
                </div>
            </div>
            <div class="col">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a class="text-body-secondary" href="#">Market</a></li>
                        <li class="breadcrumb-item text-danger active" aria-current="page">End trade</li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="fs-4 mb-0">Trades</h1>
            </div>
            <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                <!-- Action -->
                <div class="row gx-2">
                    <a class="btn btn-light d-block" href="<?= PROOT; ?>account/trades"> <span class="material-symbols-outlined me-1">close</span> Cancel </a>
                </div>

            </div>
        </div>

        <!-- Page content -->
        <div class="row">
            <div class="col-12">
                <!-- Filters -->
                <div class="card card-line bg-body-tertiary border-transparent mb-7">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-12 col-lg-auto mb-3 mb-lg-0">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="btn btn-dark active" aria-current="page" href="<?= PROOT; ?>account/end-trades">Denomination</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="card bg-body-tertiary border-transparent mb-5" id="general">
            <div class="card-body">
                <h2 class="fs-5 mb-1">Denomination</h2>
                <p class="text-body-secondary">Complete the form below to end trade.</p>
                <form method="POST" id="denominationForm">
                    <div class="text-danger mb-3"><?= $errors; ?></div>
                    <div class="table-responsive mb-7">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Count</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>TWO HUNDRED Ghana cedis (200 GHS) X </td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_200c" id="denomination_200c" value="0" /></td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_200c_amt" id="denomination_200c_amt" placeholder="0" disabled /></td>
                                </tr>
                                <tr>
                                    <td>HUNDRED Ghana cedis (100 GHS) X </td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_100c" id="denomination_100c" value="0" /></td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_100c_amt" id="denomination_100c_amt" placeholder="0" disabled /></td>
                                </tr>
                                <tr>
                                    <td>FIFTY Ghana cedis (50 GHS) X </td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_50c" id="denomination_50c" value="0" /></td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_50c_amt" id="denomination_50c_amt" placeholder="0" disabled /></td>
                                </tr>
                                <tr>
                                    <td>TWENTY Ghana cedis (20 GHS) X </td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_20c" id="denomination_20c" value="0" /></td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_20c_amt" id="denomination_20c_amt" placeholder="0" disabled /></td>
                                </tr>
                                <tr>
                                    <td>TEN Ghana cedis (10 GHS) X </td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_10c" id="denomination_10c" value="0" /></td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_10c_amt" id="denomination_10c_amt" placeholder="0" disabled /></td>
                                </tr>
                                <tr>
                                    <td>FIVE Ghana cedis (5 GHS) X </td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_5c" id="denomination_5c" value="0" /></td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_5c_amt" id="denomination_5c_amt" placeholder="0" disabled /></td>
                                </tr>
                                <tr>
                                    <td>TWO Ghana cedis (2 GHS) X </td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_2c" id="denomination_2c" value="0" /></td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_2c_amt" id="denomination_2c_amt" placeholder="0" disabled /></td>
                                </tr>
                                <tr>
                                    <td>ONE Ghana cedis (1 GHS) X </td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_1c" id="denomination_1c" value="0" /></td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_1c_amt" id="denomination_1c_amt" placeholder="0" disabled /></td>
                                </tr>
                                <tr>
                                    <td>FIFTY Ghana pesswas (50 P) X </td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_50p" id="denomination_50p" value="0" /></td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_50p_amt" id="denomination_50p_amt" placeholder="0" disabled /></td>
                                </tr>
                                <tr>
                                    <td>TWENTY Ghana pesswas (20 P) X </td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_20p" id="denomination_20p" value="0" /></td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_20p_amt" id="denomination_20p_amt" placeholder="0" disabled /></td>
                                </tr>
                                <tr>
                                    <td>TEN Ghana pesswas (10 P) X </td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_10p" id="denomination_10p" value="0" /></td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_10p_amt" id="denomination_10p_amt" placeholder="0" disabled /></td>
                                </tr>
                                <tr>
                                    <td>FIVE Ghana pesswas (5 P) X </td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_5p" id="denomination_5p" value="0" /></td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_5p_amt" id="denomination_5p_amt" placeholder="0" disabled /></td>
                                </tr>
                                <tr>
                                    <td>ONE Ghana pesswas (1 P) X </td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_1p" id="denomination_1p" value="0" /></td>
                                    <td><input type="number" min="0" step="1" class="form-control" name="denomination_1p_amt" id="denomination_1p_amt" placeholder="0" disabled /></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Total</td>
                                    <td>0.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-secondary w-100">
                        <span class="material-symbols-outlined me-1">money_off</span> Save and End trade 
                    </button>
                    <a href="<?= PROOT; ?>account/end-trade" class="btn btn-link w-100 mt-3">
                        Reset form
                    </a>
                </form>
            </div>
        </section>

    </div>


<?php include ("../includes/footer.inc.php"); ?>

<script>

    // denomination_200c
    $('#denomination_200c').keyup(function() {
        var a = +200 * +$('#denomination_200c').val()
        $('#denomination_200c_amt').val(a);
    });

    // denomination_100c
    $('#denomination_100c').keyup(function() {
        var a = +100 * +$('#denomination_100c_amt').val()
        $('#denomination_100c_amt').val(a);
    });

    // denomination_50c
    $('#denomination_50c').keyup(function() {
        var a = +50 * +$('#denomination_50c_amt').val()
        $('#denomination_50c_amt').val(a);
    });

    // denomination_20c
    $('#denomination_20c').keyup(function() {
        var a = +20 * +$('#denomination_20c_amt').val()
        $('#denomination_20c_amt').val(a);
    });

    // denomination_10c
    $('#denomination_10c').keyup(function() {
        var a = +200 * +$('#denomination_10c_amt').val()
        $('#denomination_10c_amt').val(a);
    });

    // denomination_5c
    $('#denomination_5c').keyup(function() {
        var a = +200 * +$('#denomination_5c_amt').val()
        $('#denomination_5c_amt').val(a);
    });

    // denomination_2c
    $('#denomination_2c').keyup(function() {
        var a = +200 * +$('#denomination_2c_amt').val()
        $('#denomination_2c_amt').val(a);
    });
    
    // denomination_1c
    $('#denomination_100c').keyup(function() {
        var a = +200 * +$('#denomination_1c_amt').val()
        $('#denomination_1c_amt').val(a);
    });
// denomination_50p
// denomination_50p_amt
$('#denomination_100c').keyup(function() {
        var a = +200 * +$('#denomination_100c_amt').val()
        $('#denomination_100c_amt').val(a);
    });
// denomination_20p
// denomination_20p_amt
$('#denomination_100c').keyup(function() {
        var a = +200 * +$('#denomination_100c_amt').val()
        $('#denomination_100c_amt').val(a);
    });
// denomination_10p
// denomination_10p_amt
$('#denomination_100c').keyup(function() {
        var a = +200 * +$('#denomination_100c_amt').val()
        $('#denomination_100c_amt').val(a);
    });
// denomination_5p
// denomination_5p_amt
$('#denomination_100c').keyup(function() {
        var a = +200 * +$('#denomination_100c_amt').val()
        $('#denomination_100c_amt').val(a);
    });
// denomination_1p
// denomination_1p_amt
$('#denomination_100c').keyup(function() {
        var a = +200 * +$('#denomination_100c_amt').val()
        $('#denomination_100c_amt').val(a);
    });

    
    $(".export_class").change(function(e) {
        event.preventDefault()
        var select_for = $(".export_class:checked").val();

        if (select_for == 'date') {
            $('#check-date').removeClass('d-none');

            // display none
            $('#check-month').addClass('d-none');
            $('#check-year').addClass('d-none');

            // empty values
            // $('#export-month').val('');
            // $('#export-year').val('');
        } else if (select_for == 'month') {
            $('#check-month').removeClass('d-none');

            // display none
            $('#check-date').addClass('d-none');
            $('#check-year').addClass('d-none');

            // empty values
            // $('#export-date').val('');
            // $('#export-year').val('');
        } else if (select_for == 'year') {
            $('#check-year').removeClass('d-none');

            // display none
            $('#check-month').addClass('d-none');
            $('#check-date').addClass('d-none');

            // empty values
            // $('#export-date').val('');
            // $('#export-month').val('');
        } else {
            // display none
            $('#check-date').addClass('d-none');
            $('#check-month').addClass('d-none');
            $('#check-year').addClass('d-none');

            // empty values
            // $('#export-date').val('');
            // $('#export-month').val('');
            // $('#export-year').val('');
        }
    });


    $('#submit-export').on('click', function() {

        if ($(".export_class:checked").val()) {
            var select_for = $(".export_class:checked").val();

            if (select_for == 'date' && $("#export-date").val() == '') {
                alert("You will have to select date!");
                $("#export-date").focus();
                return false;
            } else if (select_for == 'month' && $("#export-month").val() == '') {
                alert("You will have to select month!");
                $("#export-month").focus();
                return false;
            } else if (select_for == 'year' && $("#export-year").val() == '') {
                alert("You will have to select year!");
                $("#export-year").focus();
                return false;
            }

            // var formData = $('#exportForm');
            // $.ajax({
            //     method : "GET",
            //     url : "<?= PROOT; ?>auth/export",
            //     data : formData.serialize(),
            //     beforeSend : function() {
            //         $('#submit-export').attr('disabled', true);
            //         $('#submit-export').text('Exporting ...');
            //     },
            //     success : function (data) {
            //         console.log(data)
            //         $('#submit-export').attr('disabled', false);
            //         $('#submit-export').text('Export');
            //         location.reload();
            //     },
            //     error : function () {

            //     }
            // })

            $('#submit-export').attr('disabled', true);
            $('#submit-export').text('Exporting ...');
            setTimeout(function () {
                $('#exportForm').submit();

                $('#submit-export').attr('disabled', false);
                $('#submit-export').text('Export');
                // location.reload();
            }, 2000)
        } else {
            return false;
        }
    });
    
    // SEARCH AND PAGINATION FOR LIST
    function load_data(page, query = '') {
        $.ajax({
            url : "<?= PROOT; ?>auth/trade.list.php",
            method : "POST",
            data : {
                page : page, 
                query : query
            },
            success : function(data) {
                $("#load-content").html(data);
            }
        });
    }
</script>
