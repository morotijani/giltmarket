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

    $where = '';
    if (!admin_has_permission()) {
        $where = ' WHERE jspence_admin.admin_id = "'.$admin_data['admin_id'].'" ';
    }

    $sql = "
        SELECT * FROM jspence_logs 
        INNER JOIN jspence_admin 
        ON jspence_admin.admin_id = jspence_logs.log_admin
        $where 
        ORDER BY jspence_logs.createdAt DESC
    ";
    $statement = $conn->prepare($sql);
    $statement->execute();
    $count_row = $statement->rowCount();
    $rows = $statement->fetchAll();

    $c_logs = '';
    if ($count_row > 0) {
        $c_logs = '(' . $count_row . ')' ;
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
                        <li class="breadcrumb-item"><a class="text-body-secondary" href="#">System</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Logs</li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="fs-4 mb-0">Logs</h1>
            </div>
            <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                <!-- Action -->
                <a class="btn btn-secondary d-block" href="javascript:;" data-bs-target="#buyModal" data-bs-toggle="modal"> <span class="material-symbols-outlined me-1">add</span> New Trade </a>
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
                                        <a class="btn btn-dark active" aria-current="page" href="<?= PROOT; ?>account/logs">Logs <?= $c_logs; ?></a>
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
                                    <h4 class="fs-lg mb-4">Export trades</h4>
                                    <form style="width: 350px" id="exportForm" action="<?= PROOT; ?>auth/export.logs.php">
                                        <div class="row gx-3">
                                        <div class="col-sm-12 mb-3">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input export_type" type="radio" name="export-status" id="inlineRadioStatus1" required value="all" checked>
                                                    <label class="form-check-label" for="inlineRadioStatus1">All</label>
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
    
    <div class="card">
        <div class="card-header">
            <h3 class="fs-6 mb-0">Recent activity</h3>
        </div>
        <div class="card-body">
            <ul class="activity">
                <?php foreach ($rows as $row): ?>
                <li data-icon="account_circle">
                    <div>
                        <h6 class="fs-base mb-1"><?= (($row["log_admin"] == $admin_data['admin_id']) ? 'You' : ucwords($row['admin_fullname'])); ?>  <span class="fs-sm fw-normal text-body-secondary ms-1"><?= pretty_date($row['createdAt']); ?></span></h6>
                        <p class="mb-0"><?= $row['log_message']; ?></p>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    </div>

<?php include ("../includes/footer.inc.php"); ?>
