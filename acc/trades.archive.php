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
            <h1 class="text-danger">Delete trade requests</h1>
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
                <?php endif ?>
                <button type="button" class="btn btn-sm btn-primary d-none d-sm-inline-flex" data-bs-target="#buyModal" data-bs-toggle="modal"><span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Trade</span></button>
            </div>
        </div>
           

        <ul class="nav nav-tabs nav-tabs-flush gap-8 overflow-x border-0 mt-1">
            <li class="nav-item">
                <a href="<?= PROOT; ?>acc/trades" class="nav-link">All data</a>
            </li>
            <li class="nav-item">
                <a href="<?= PROOT; ?>acc/trades.archive" class="nav-link active">Delete request</a>
            </li>
            <li class="nav-item">
                <a href="<?= PROOT; ?>acc/trades.archive" class="nav-link">Archive</a>
            </li>
        </ul>
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm table-nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <?php if (admin_has_permission()): ?>
                            <th scope="col">Handler</th>
                        <?php endif; ?>
                        <th scope="col">Customer</th>
                        <th scope="col">Gram</th>
                        <th scope="col">Volume</th>
                        <th scope="col">Price</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Date</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?= fetch_all_sales(1, $admin_data[0]['admin_permissions'], $admin_data[0]['admin_id']); ?>
                </tbody>
            </table>
        </div>
        <div class="py-4 px-6"><div class="row align-items-center justify-content-between"><div class="col-md-6 d-none d-md-block"><span class="text-muted text-sm">Showing 10 items out of 250 results found</span></div><div class="col-md-auto"><nav aria-label="Page navigation example"><ul class="pagination pagination-spaced gap-1"><li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li><li class="page-item"><a class="page-link" href="#">1</a></li><li class="page-item"><a class="page-link" href="#">2</a></li><li class="page-item"><a class="page-link" href="#">3</a></li><li class="page-item"><a class="page-link" href="#">4</a></li><li class="page-item"><a class="page-link" href="#">5</a></li><li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li></ul></nav></div>



        </div>
    </div>


<?php include ("../includes/footer.inc.php"); ?>