<?php 

    // view admin profile details
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("../includes/header.inc.php");
    include ("../includes/nav.inc.php");



?>
<a href="#" onClick="MyWindow=window.open('http://www.google.com','MyWindow','width=600,height=300'); return false;">Click Here</a>
<a href="#" onClick="MyWindow=window.open('http://www.google.com','MyWindow','width=600,height=300'); return false;">Click Here</a>

    <div class="px-6 px-lg-7 pt-8 border-bottom">
        <div class="d-flex align-items-center">
            <h1>Trades</h1>
            <div class="hstack gap-2 ms-auto">
                <?php if (admin_has_permission()): ?>
                <button type="button" class="btn btn-sm btn-neutral d-none d-lg-inline-flex">
                    <i class="bi bi-arrow-90deg-right me-2"></i> Export
                </button> 
                <?php endif ?>
                <button type="button" class="btn btn-sm btn-primary d-none d-sm-inline-flex" data-bs-target="#buyModal" data-bs-toggle="modal"><span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Trade</span></button>
            </div>
        </div>
           

        <ul class="nav nav-tabs nav-tabs-flush gap-8 overflow-x border-0 mt-1">
            <li class="nav-item">
                <a href="#" class="nav-link active">All</a>
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
                    <?= fetch_all_sales(0, $admin_data[0]['admin_permissions'], $admin_data[0]['admin_id']); ?>
                </tbody>
            </table>
        </div>
        <div class="py-4 px-6"><div class="row align-items-center justify-content-between"><div class="col-md-6 d-none d-md-block"><span class="text-muted text-sm">Showing 10 items out of 250 results found</span></div><div class="col-md-auto"><nav aria-label="Page navigation example"><ul class="pagination pagination-spaced gap-1"><li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li><li class="page-item"><a class="page-link" href="#">1</a></li><li class="page-item"><a class="page-link" href="#">2</a></li><li class="page-item"><a class="page-link" href="#">3</a></li><li class="page-item"><a class="page-link" href="#">4</a></li><li class="page-item"><a class="page-link" href="#">5</a></li><li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li></ul></nav></div>



        </div>
    </div>


<?php include ("../includes/footer.inc.php"); ?>