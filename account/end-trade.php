<?php 

    // view admin profile details
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("../includes/header.inc.php");
    include ("../includes/aside.inc.php");
    include ("../includes/left.nav.inc.php");
    include ("../includes/top.nav.inc.php");

    $today = date("Y-m-d");
    $where = '';
    if (!admin_has_permission()) {
        $where = ' AND sale_by = "'.$admin_data["admin_id"].'" AND CAST(jspence_sales.createdAt AS date) = "' . $today . '" ';
    }
    $total_trades = $conn->query("SELECT * FROM jspence_sales INNER JOIN jspence_admin ON jspence_admin.admin_id = jspence_sales.sale_by WHERE sale_status = 0 $where")->rowCount();
    $trades_count = '';
    if ($total_trades > 0) {
        $trades_count = '(' . $total_trades . ')';
    }

    //
    if (isset($_GET['delete_request']) && !empty($_GET['delete_request'])) {
        // code...
        $id = sanitize($_GET['delete_request']);

        $findSale = $conn->query("SELECT * FROM jspence_sales WHERE sale_id = '".$id."'")->rowCount();
        if ($findSale > 0) {
            // code...
            $sql = "
                UPDATE jspence_sales 
                SET sale_status = ?, sale_delete_request_status = ?
                WHERE sale_id = ?
            ";
            $statement = $conn->prepare($sql);
            $result = $statement->execute([1, 1, $id]);
            if (isset($result)) {                
                $message = "delete request for trade id: '".$id."'";
                add_to_log($message, $admin_data[0]['admin_id']);

                $_SESSION['flash_success'] = ' Sale delete request successfully sent!';
                redirect(PROOT . 'acc/trades');
            } else {
                echo js_alert("Something went wrong, please try again!");
            }
        } else {
            $_SESSION['flash_error'] = ' Could not find sale to send a delete request!';
            redirect(PROOT . 'acc/trades');
        }

    }



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
                    <div class="col-6 col-sm-auto">
                        <a class="btn btn-light d-block" href="<?= PROOT; ?>account/trades"> <span class="material-symbols-outlined me-1">close</span> Cancel </a>
                    </div>
                    <div class="col-6 col-sm-auto">
                        <a class="btn btn-secondary d-block" href="<?= PROOT; ?>account/end-trade"> <span class="material-symbols-outlined me-1">money_off</span> Save and End trade </a>
                    </div>
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
                <hr>
                <form method="POST" id="changePasswordForm">
                    <div class="text-danger mb-3"><?= $errors; ?></div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>a</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-4">
                        <label for="old_password" class="form-label">Old password</label>
                        <input type="password" class="form-control bg-body" name="old_password" id="old_password" value="<?= $old_password; ?>" required>
                        <div class="text-sm text-muted">Enter old password in this field</div>
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="form-label">New password</label>
                        <input type="password" class="form-control bg-body" name="password" id="password" value="<?= $password; ?>" required>
                        <div class="text-sm text-muted">Enter new password in this field</div>
                    </div>
                    <div class="mb-4">
                        <label for="confirm" class="form-label">Confirm new password</label>
                        <input type="password" class="form-control bg-body" name="confirm" id="confirm" value="<?= $confirm; ?>" required>
                        <div class="text-sm text-muted">Enter confirm new password in this field</div>
                    </div>
                </form>
            </div>
        </section>

    </div>


<?php include ("../includes/footer.inc.php"); ?>

<script>
    
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

    load_data(1);
    $('#search').keyup(function() {
        var query = $('#search').val();
        load_data(1, query);
    });

    $(document).on('click', '.page-link-go', function() {
        var page = $(this).data('page_number');
        var query = $('#search').val();
        load_data(page, query);
    });
</script>
