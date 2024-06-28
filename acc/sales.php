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


<?php include ("../includes/footer.inc.php"); ?>