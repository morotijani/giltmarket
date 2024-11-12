<?php 

    // expenditure
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admin_login_redirect();
    }

    $today = date("Y-m-d");
    $where = '';
    if ($admin_data['admin_permissions'] == 'supervisor') {
        $where = ' AND (push_to = "' . $admin_id . '" OR push_from IN (SELECT push_from FROM jspence_pushes WHERE push_from = "' . $admin_id . '")) AND push_date = "' . $today . '" ';
    } else if ($admin_data['admin_permissions'] == 'salesperson') {
        $where = ' AND push_to = "' . $admin_id . '" AND push_date = "' . $today . '" ';
    }
    $total_push = $conn->query("SELECT * FROM jspence_pushes INNER JOIN jspence_admin ON (jspence_admin.admin_id = jspence_pushes.push_from OR jspence_admin.admin_id = jspence_pushes.push_to) WHERE jspence_pushes.push_status = 0 $where GROUP BY push_id")->rowCount();
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
            <?php if ($admin_permission == 'supervisor'): ?>
            <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                <!-- Action -->
                <a class="btn btn-warning d-block" href="javascript:;" data-bs-target="#modalCapital" data-bs-toggle="modal"> Fund coffers</a>
            </div>
            <?php endif; ?>
        </div>

        <?php if (admin_has_permission('supervisor')): ?>
        <div class="row mb-8">
            <div class="col-12 col-md-6 col-xxl-6 mb-4 mb-xxl-0">
                <div class="card bg-warning-subtle border-transparent">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <!-- Heading -->
                                <h4 class="fs-sm fw-normal text-body-secondary mb-1">Total amount in coffers</h4>

                                <!-- Text -->
                                <div class="fs-4 fw-semibold"><?= money(get_admin_coffers($conn, $admin_id)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xxl-6 mb-4 mb-xxl-0">
                <div class="card bg-danger-subtle border-transparent">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <!-- Heading -->
                                <h4 class="fs-sm fw-normal text-body-secondary mb-1">Cash-out of coffers</h4>

                                <!-- Text -->
                                <div class="fs-4 fw-semibold"><?= money(get_admin_coffers($conn, $admin_id, 'send')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    
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
                                        <a class="nav-link bg-dark" aria-current="page" href="<?= PROOT; ?>account/pushes">All data<?= $count_push; ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?= ((isset($_GET['data']) && $_GET['data'] == 'salesperson') ? 'bg-dark active' : ''); ?>" aria-current="page" href="<?= PROOT; ?>account/pushes/salesperson">To sales persons<?= $count_push; ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?= ((isset($_GET['data']) && $_GET['data'] == 'gold-receive') ? 'bg-dark active' : ''); ?>" aria-current="page" href="<?= PROOT; ?>account/pushes/goldreceive">Gold received</a>
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
                                            <h4 class="fs-lg mb-4">Export push</h4>
                                            <form style="width: 350px" id="exportForm" action="<?= PROOT; ?>auth/export.pushes.php">
                                                <div class="row gx-3">
                                                    <div class="col-sm-12 mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input export_type" type="radio" name="export-status" id="inlineRadioStatus1" required value="zero" checked>
                                                            <label class="form-check-label" for="inlineRadioStatus1">All</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 mb-3">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input export_class" type="radio" name="exp_with" id="inlineRadio1" required value="date">
                                                            <label class="form-check-label" for="inlineRadio1">Date</label>
                                                        </div>
                                                        <div class="form-check form-check-inline <?= ((admin_has_permission()) ? '' : 'd-none'); ?>">
                                                            <input class="form-check-input export_class" type="radio" name="exp_with" id="inlineRadio2" required value="month">
                                                            <label class="form-check-label" for="inlineRadio2">Month</label>
                                                        </div>
                                                        <div class="form-check form-check-inline <?= ((admin_has_permission()) ? '' : 'd-none'); ?>">
                                                            <input class="form-check-input export_class" type="radio" name="exp_with" id="inlineRadio3" required value="year">
                                                            <label class="form-check-label" for="inlineRadio3">Year</label>
                                                        </div>
                                                        <div class="form-check form-check-inline <?= ((admin_has_permission()) ? '' : 'd-none'); ?>">
                                                            <input class="form-check-input export_class" type="radio" name="exp_with" id="inlineRadio4" required value="all">
                                                            <label class="form-check-label" for="inlineRadio4">All</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 mb-3 d-none" id="check-date">
                                                        <input type="date" class="form-control form-control-sm" id="export-date" name="export-date" <?= ((admin_has_permission()) ? '' : 'readonly'); ?> value="<?= date("Y-m-d"); ?>">
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
        
            <?php 
                if ((isset($_GET['data']) && $_GET['data'] == 'salesperson') && admin_has_permission('supervisor')): 
                    // all salepersonnles that received a push from supervisor
                    $sp_query = "
                        SELECT * FROM jspence_daily 
                        INNER JOIN jspence_admin 
                        ON jspence_admin.admin_id = jspence_daily.daily_to 
                        INNER JOIN jspence_pushes 
                        ON jspence_pushes.push_to = jspence_daily.daily_to
                        WHERE jspence_pushes.push_from = ? 
                        AND jspence_daily.daily_date = ? 
                        AND jspence_admin.admin_permissions = ? 
                        GROUP BY jspence_pushes.push_to
                    ";
                    $statement = $conn->prepare($sp_query);
                    $statement->execute([
                        $admin_id, date("Y-m-d"), 'salesperson'
                    ]);
                    $sp_count = $statement->rowCount();
                    $sps = $statement->fetchAll();
            ?>

                <!-- Page content -->
                <div class="row">
                    <?php if ($sp_count > 0): ?>
                        <?php 
                        
                            foreach ($sps as $sp): 
                                $QUERY = "
                                    SELECT * FROM jspence_pushes 
                                    WHERE push_to = ? 
                                ";
                                $statement = $conn->prepare($QUERY);
                                $statement->execute([$sp['push_to']]);
                                $pss = $statement->fetchAll();
                        
                        ?>
                            <div class="col-12 col-xxl-4">
                                <div class="position-sticky mb-8" style="top: 40px">
                                    <!-- Card -->
                                    <div class="card bg-body mb-3">
                                        <!-- Image -->
                                        <div
                                        class="card-img-top pb-13"
                                        style="background: no-repeat url(<?= PROOT; ?>assets/media/background-2.jpg) center center / cover"
                                        ></div>

                                        <!-- Avatar -->
                                        <div class="avatar avatar-xl rounded-circle mt-n7 mx-auto">
                                            <img class="avatar-img border border-white border-3" src="<?= PROOT .  (($sp["admin_profile"] == NULL) ? 'assets/media/avatar.png' : $sp["admin_profile"]); ?>" alt="..." />
                                        </div>

                                        <!-- Body -->
                                        <div class="card-body text-center">
                                            <!-- Heading -->
                                            <h1 class="card-title fs-5"><?= ucwords($sp["admin_fullname"]); ?></h1>

                                            <!-- Text -->
                                            <!-- <p class="text-body-secondary mb-6">James is a long-standing customer with a passion for technology.</p> -->

                                            <!-- List -->
                                            <ul class="list-group list-group-flush mb-0">
                                                <li class="list-group-item d-flex align-items-center justify-content-between bg-body px-0">
                                                <span class="text-body-secondary">Title / Role</span>
                                                <span><?= ucwords(_admin_position($sp["admin_permissions"])); ?></span>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center justify-content-between bg-body px-0">
                                                <span class="text-body-secondary">Phone</span>
                                                <a class="text-body" href="tel:<?= $sp["admin_phone"]; ?>"><?= $sp["admin_phone"]; ?></a>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center justify-content-between bg-body px-0">
                                                <span class="text-body-secondary">Login datetime</span>
                                                <span><?= pretty_date($sp["admin_last_login"]); ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="row gx-3">
                                        <div class="col">
                                            <button class="btn btn-light w-100">Money given:<br /> <?= money($sp["daily_capital"]); ?></button>
                                        </div>
                                        <div class="col">
                                            <button class="btn btn-light w-100">Balance:<br /> <?= money($sp["daily_balance"]); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-xxl">
                                <section class="mb-8">
                                    <!-- Header -->
                                    <div class="d-flex align-items-center justify-content-between mb-5">
                                        <h2 class="fs-5 mb-0">Recent pushes</h2>
                                    </div>

                                    <!-- Table -->
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <th>ID</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th></th>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pss as $ps): ?>
                                                <tr>
                                                    <td class="text-body-secondary"><?= $ps["push_id"]; ?></td>
                                                    <td><?= money($ps["push_amount"]); ?></td>
                                                    <td><?= pretty_date($ps["createdAt"]); ?></td>
                                                    <!-- <td>reverse</td> -->
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        no data
                    <?php endif; ?>
                </div>


            <?php else: ?>
            <div id="load-content"></div>
            <?php endif; ?>
        </div>
    </div>

<?php include ("../includes/footer.inc.php"); ?>

<script>
    $(document).ready(function() {

        $(".export_class").change(function(e) {
            event.preventDefault()
            var select_for = $(".export_class:checked").val();

            if (select_for == 'date') {
                $('#check-date').removeClass('d-none');

                // display none
                $('#check-month').addClass('d-none');
                $('#check-year').addClass('d-none');
            } else if (select_for == 'month') {
                $('#check-month').removeClass('d-none');

                // display none
                $('#check-date').addClass('d-none');
                $('#check-year').addClass('d-none');
            } else if (select_for == 'year') {
                $('#check-year').removeClass('d-none');

                // display none
                $('#check-month').addClass('d-none');
                $('#check-date').addClass('d-none');
            } else {
                // display none
                $('#check-date').addClass('d-none');
                $('#check-month').addClass('d-none');
                $('#check-year').addClass('d-none');
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