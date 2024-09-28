<?php 

    // expenditure
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    $today = date("Y-m-d");
    $where = '';
    if (!admin_has_permission()) {
        $where = ' AND (push_to = "' . $admin_data["admin_id"] . '" OR push_from = "' . $admin_data["admin_id"] . '" ) AND CAST(jspence_pushes.createdAt AS date) = "' . $today . '" ';
    }
    $total_push = $conn->query("SELECT * FROM jspence_pushes INNER JOIN jspence_admin ON (jspence_admin.admin_id = jspence_pushes.push_from OR jspence_admin.admin_id = jspence_pushes.push_to) WHERE jspence_pushes.push_status = 0 $where")->rowCount();
    $count_push = '';
    if ($total_push > 0) {
        $count_push = ' (' . $total_push . ')';
    }

    include ("../includes/header.inc.php");
    include ("../includes/aside.inc.php");
    include ("../includes/left.nav.inc.php");
    include ("../includes/top.nav.inc.php");

    

?>

    <div class="container-lg">
        <!-- Page header -->
        <div class="row align-items-center mb-7">
            <div class="col-auto">
                <!-- Avatar -->
                <div class="avatar avatar-xl rounded text-warning">
                <i class="fs-2" data-duoicon="menu"></i>
                </div>
            </div>
            <div class="col">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a class="text-body-secondary" href="javascript:;">Market</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pushes</li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="fs-4 mb-0">Pushes</h1>
            </div>
            <?php if (!admin_has_permission()): ?>
            <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                <!-- Action -->
                <a class="btn btn-warning d-block" href="<?= ((isset($_GET['add']) && !empty($_GET['add'])) ? goBack() : PROOT . 'account/expenditure?add=1'); ?>"> <?= ((isset($_GET['add']) && !empty($_GET['add'])) ? 'Go back' : 'Add expenditure'); ?></a>
            </div>
            <?php endif; ?>
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
                                    <a class="nav-link bg-dark active" aria-current="page" href="<?= PROOT; ?>account/expenditure">All data<?= $count_push; ?></a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg">
                            <div class="row gx-3">
                                <div class="col col-lg-auto ms-auto">
                                    <div class="input-group bg-body">
                                        <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search" id="search" />
                                        <span class="input-group-text" id="search">
                                            <span class="material-symbols-outlined">search</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-auto ms-n2">
                                    <div class="dropdown">
                                    <button class="btn btn-dark px-3" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <span class="material-symbols-outlined">export_notes</span>
                                    </button>
                                    <div class="dropdown-menu rounded-3 p-6">
                                        <h4 class="fs-lg mb-4">Export expenditure</h4>
                                        <form style="width: 350px" id="exportForm" action="<?= PROOT; ?>auth/export.expenditure.php">
                                            <div class="row gx-3">
                                                <div class="col-sm-12 mb-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input export_type" type="radio" name="export-status" id="inlineRadioStatus1" required value="zero" checked>
                                                        <label class="form-check-label" for="inlineRadioStatus1">All</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input export_type" type="radio" name="export-status" id="inlineRadioStatus2" value="one" required>
                                                        <label class="form-check-label" for="inlineRadioStatus2">Archived</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input export_type" type="radio" name="export-status" id="inlineRadioStatus3" value="1and0" required>
                                                        <label class="form-check-label" for="inlineRadioStatus3">Both</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 mb-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input export_class" type="radio" name="exp_with" id="inlineRadio1" required value="date">
                                                        <label class="form-check-label" for="inlineRadio1">Date</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input export_class" type="radio" name="exp_with" id="inlineRadio2" required value="month">
                                                        <label class="form-check-label" for="inlineRadio2">Month</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input export_class" type="radio" name="exp_with" id="inlineRadio3" required value="year">
                                                        <label class="form-check-label" for="inlineRadio3">Year</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input export_class" type="radio" name="exp_with" id="inlineRadio4" required value="all">
                                                        <label class="form-check-label" for="inlineRadio4">All</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 mb-3 d-none" id="check-date">
                                                    <input type="date" class="form-control form-control-sm" id="export-date" name="export-date" value="<?= date("Y-m-d"); ?>">
                                                </div>
                                                <div class="col-sm-12 mb-3 d-none" id="check-month">
                                                    <select id='export-month' name="export-month" class="form-control form-control-sm">
                                                        <option value=''>Select Month</option>
                                                        <option value="1"<?= ((date('m') == '1') ? ' selected' : ''); ?>>January</option>
                                                        <option value='2'<?= ((date('m') == '2') ? ' selected' : ''); ?>>February</option>
                                                        <option value='3'<?= ((date('m') == '3') ? ' selected' : ''); ?>>March</option>
                                                        <option value='4'<?= ((date('m') == '4') ? ' selected' : ''); ?>>April</option>
                                                        <option value='5'<?= ((date('m') == '5') ? ' selected' : ''); ?>>May</option>
                                                        <option value='6'<?= ((date('m') == '6') ? ' selected' : ''); ?>>June</option>
                                                        <option value='7'<?= ((date('m') == '7') ? ' selected' : ''); ?>>July</option>
                                                        <option value='8'<?= ((date('m') == '8') ? ' selected' : ''); ?>>August</option>
                                                        <option value='9'<?= ((date('m') == '9') ? ' selected' : ''); ?>>September</option>
                                                        <option value='10<?= ((date('m') == '10') ? ' selected' : ''); ?>'>October</option>
                                                        <option value='11<?= ((date('m') == '11') ? ' selected' : ''); ?>'>November</option>
                                                        <option value='12<?= ((date('m') == '12') ? ' selected' : ''); ?>'>December</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 mb-3 d-none" id="check-year">
                                                    <select id='export-year' name="export-year" class="form-control form-control-sm">
                                                        <option value=''>Select Year</option>
                                                        <?php for ($i = date('Y'); $i >= 2020; $i--) : ?>
                                                            <option value='<?= $i; ?>' <?= ((date('Y') == $i)? 'selected' : ''); ?>><?= $i; ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 mb-3">
                                                    <div class="btn-group w-100" role="group" aria-label="Basic radio toggle button group">
                                                        <input type="radio" class="btn-check" name="export-type" id="export_xlsx" autocomplete="off" checked value="xlsx" required />
                                                        <label class="btn btn-light" for="export_xlsx" data-bs-toggle="tooltip" data-bs-title="XLSX">
                                                        <img src="<?= PROOT; ?>assets/media/XLSX.png" class="w-rem-6 h-rem-6 rounded-circle" alt="...">
                                                        </label>
                                                        <input type="radio" class="btn-check" name="export-type" id="export_csv" autocomplete="off" value="csv" required />
                                                        <label class="btn btn-light" for="export_csv" data-bs-toggle="tooltip" data-bs-title="CSV">
                                                        <img src="<?= PROOT; ?>assets/media/CSV.png" class="w-rem-6 h-rem-6 rounded-circle" alt="...">
                                                        </label>
                                                        <input type="radio" class="btn-check" name="export-type" id="export_pdf" autocomplete="off" value="pdf" required />
                                                        <label class="btn btn-light" for="export_pdf" data-bs-toggle="tooltip" data-bs-title="PDF">
                                                        <img src="<?= PROOT; ?>assets/media/PDF.png" class="w-rem-6 rh-rem-6 ounded-circle" alt="...">
                                                        </label>
                                                        <input type="radio" class="btn-check" name="export-type" id="export_xls" autocomplete="off" value="xls" required />
                                                        <label class="btn btn-light" for="export_xls" data-bs-toggle="tooltip" data-bs-title="XLS">
                                                        <img src="<?= PROOT; ?>assets/media/XLS.png" class="w-rem-6 h-rem-6 rounded-circle" alt="...">
                                                        </label>
                                                    </div>
                                                </div>
                                                <button id="submit-export" type="button" class="btn btn-warning">Export</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div id="load-content"></div>
    </div>

<?php include ("../includes/footer.inc.php"); ?>

<script>
    $(document).ready(function() {
        $('#submitExpenditure').on('click', function() {
            if (confirm("By clicking on ok, this expenditure will be recorded!")) {
                expenditureForm.submit()
            }
            return false
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
                    $('#submit-export').text('Export1');
                    // location.reload();
                }, 2000)
            } else {
                return false;
            }
        });


    });
    
    // SEARCH AND PAGINATION FOR LIST
    function load_data(page, query = '') {
        $.ajax({
            url : "<?= PROOT; ?>auth/push.list.php",
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