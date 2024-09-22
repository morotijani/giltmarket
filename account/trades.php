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
                <div class="avatar avatar-xl rounded text-primary">
                    <i class="fs-2" data-duoicon="credit-card"></i>
                </div>
            </div>
            <div class="col">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a class="text-body-secondary" href="#">Market</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Trades</li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="fs-4 mb-0">Trades</h1>
            </div>
            <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                <!-- Action -->
                <a class="btn btn-secondary d-block" href="#!"> <span class="material-symbols-outlined me-1">add</span> New Order </a>
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
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="text-body-secondary">No orders selected.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg">
                                <div class="row gx-3">
                                    <div class="col col-lg-auto ms-auto">
                                        <div class="input-group bg-body">
                                            <input type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search" />
                                            <span class="input-group-text" id="search">
                                                <span class="material-symbols-outlined">search</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="dropdown">
                                    <button class="btn btn-dark px-3" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <span class="material-symbols-outlined">filter_list</span>
                                    </button>
                                    <div class="dropdown-menu rounded-3 p-6">
                                        <h4 class="fs-lg mb-4">Filter</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto ms-n2">
                                <div class="dropdown">
                                <button class="btn btn-dark px-3" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <span class="material-symbols-outlined">export_notes</span>
                                </button>
                                <div class="dropdown-menu rounded-3 p-6">
                                    <h4 class="fs-lg mb-4">Sort</h4>
                                    <form style="width: 350px" id="filterForm" method="GET" action="<?= PROOT; ?>account/export">
                                        <div class="row gx-3">
                                            <div class="col mb-2">
                                                <input type="date" class="form-control" id="export-date" name="export-date">
                                            </div>
                                            <div class="col-auto mb-2">
                                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" name="export_type" id="export_xlsx" autocomplete="off" checked />
                                                    <label class="btn btn-light" for="export_xlsx" data-bs-toggle="tooltip" data-bs-title="XLSX">
                                                    <img src="<?= PROOT; ?>assets/media/XLSX.png" width="30" height="30" class="w-rem-6 h-rem-6 rounded-circle" alt="...">
                                                    </label>
                                                    <input type="radio" class="btn-check" name="export_type" id="export_csv" autocomplete="off" />
                                                    <label class="btn btn-light" for="export_csv" data-bs-toggle="tooltip" data-bs-title="CSV">
                                                    <img src="<?= PROOT; ?>assets/media/CSV.png" width="30" height="30" class="rounded-circle" alt="...">
                                                    </label>
                                                    <input type="radio" class="btn-check" name="export_type" id="export_pdf" autocomplete="off" />
                                                    <label class="btn btn-light" for="export_pdf" data-bs-toggle="tooltip" data-bs-title="PDF">
                                                    <img src="<?= PROOT; ?>assets/media/PDF.png" width="30" height="30" class="rounded-circle" alt="...">
                                                    </label>
                                                    <input type="radio" class="btn-check" name="export_type" id="export_xls" autocomplete="off" />
                                                    <label class="btn btn-light" for="export_xls" data-bs-toggle="tooltip" data-bs-title="XLS">
                                                    <img src="<?= PROOT; ?>assets/media/XLS.png" width="30" height="30" class="rounded-circle" alt="...">
                                                    </label>
                                                </div>
                                                <button type="submit" class="btn btn-light">Export</button>
                                            </div>
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


        






    <div class="px-6 px-lg-7 pt-8 border-bottom">
        <div class="d-flex align-items-center">
            <h1>Trades</h1>
            <div class="hstack gap-2 ms-auto">
                <?php if (admin_has_permission()): ?>
                <div class="dropdown">
                    <button class="btn btn-sm btn-neutral flex-none d-flex align-items-center gap-2 py-1 px-2" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= PROOT; ?>dist/media/export.png" class="w-rem-5 h-rem-5 rounded-circle" alt="..."> <span>Export</span> <i class="bi bi-chevron-down text-xs me-1"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-sm">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="<?= PROOT; ?>acc/export/all/xlsx">
                                <img src="<?= PROOT; ?>dist/media/XLSX.png" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span>XLSX</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="<?= PROOT; ?>acc/export/all/xls">
                                <img src="<?= PROOT; ?>dist/media/XLS.png" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span>XLS</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="<?= PROOT; ?>acc/export/all/csv">
                                <img src="<?= PROOT; ?>dist/media/CSV.png" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span>CSV</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="<?= PROOT; ?>acc/export/all/pdf">
                                <img src="<?= PROOT; ?>dist/media/CSV.png" class="w-rem-6 h-rem-6 rounded-circle" alt="..."> <span>PDF</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <?php else: ?>
                    <button type="button" class="btn btn-sm btn-primary d-none d-sm-inline-flex" data-bs-target="#buyModal" data-bs-toggle="modal"><span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Trade</span></button>
                <?php endif ?>
            </div>
        </div>
       <div class="row align-items-center g-6 mt-0 mb-6">
            <div class="col-sm-6">
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm input-group-inline w-100 w-md-50">
                        <span class="input-group-text"><i class="bi bi-search me-2"></i> </span>
                        <input type="search" class="form-control ps-0" placeholder="Search all trades" aria-label="Search" id="search">
                    </div>
                </div>
            </div>
        </div>
        <div id="load-content"></div>


<?php include ("../includes/footer.inc.php"); ?>

<script>
    
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
