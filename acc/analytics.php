<?php 

    // expenditure
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    if ($admin_data[0]['admin_permissions'] == 'supervisor') {
        redirect(PROOT . 'index');
    }

    include ("../includes/header.inc.php");
    include ("../includes/nav.inc.php");



?>

<div class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
    <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8 ">
        <div class="mb-6 mb-xl-10">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h1 class="ls-tight">Analytics</h1>
                </div>
                <div class="col">
                    <div class="hstack gap-2 justify-content-end">
                    <div class="d-flex scrollable-x justify-content-between gap-1 p-1 align-items-center bg-body-secondary rounded text-xs fw-semibold"><a href="#" class="px-3 py-1 text-muted bg-white-hover bg-opacity-70-hover rounded">1H </a><a href="#" class="px-3 py-1 text-muted bg-white rounded shadow-1">1D </a><a href="#" class="px-3 py-1 text-muted bg-white-hover bg-opacity-50-hover rounded">1W </a><a href="#" class="px-3 py-1 text-muted bg-white-hover bg-opacity-50-hover rounded">1M </a><a href="#" class="d-none d-sm-inline-block px-3 py-1 text-muted bg-white-hover bg-opacity-50-hover rounded">1Y</a></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="vstack gap-3 gap-xl-6">
            <div class="row row-cols-xl-4 g-3 g-xl-6">
                <div class="col-xxl-8">
                    <div class="card">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5>Balance</h5>
                            </div>
                            <div>
                                <span class="text-heading fw-bold"><i class="bi bi-arrow-up me-2"></i>7.8%</span></div>
                            </div>
                            <div class="text-2xl fw-bolder text-heading ls-tight">23.863,21 USDT</div>
                            <div class="d-flex align-items-center justify-content-between mt-8">
                            <div class="">
                                <div class="d-flex gap-3 align-items-center">
                                <div class="icon icon-sm icon-shape text-sm rounded-circle bg-dark text-success"><i class="bi bi-arrow-down"></i></div><span class="h6 fw-semibold text-muted">Income</span>
                                </div>
                                <div class="fw-bold text-heading mt-3">$23.863,21 USD</div>
                            </div><span class="vr bg-dark bg-opacity-10"></span>
                            <div class="">
                                <div class="d-flex gap-3 align-items-center">
                                <div class="icon icon-sm icon-shape text-sm rounded-circle bg-dark text-danger"><i class="bi bi-arrow-up"></i></div><span class="h6 fw-semibold text-muted">Expenses</span>
                                </div>
                                <div class="fw-bold text-heading mt-3">$5.678,45 USD</div>
                            </div>
                            </div>
                        </div>
                     </div>

                </div>
                <div class="col-xxl-4">
                    <div class="row g-3">
                        <div class="col">
                            <div class="card">
                                <div class="p-4">
                                    <h6 class="text-limit text-muted mb-3">Orders</h6>
                                    <span class="text-sm text-muted text-opacity-90 fw-semibold">EUR</span> <span class="d-block h3 ls-tight fw-bold">25.040,00</span>
                                    <p class="mt-1">
                                        <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>20% </span>
                                        <span class="text-muted text-xs text-opacity-75">vs last week</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="p-4">
                                    <h6 class="text-limit text-muted mb-3">Orders</h6>
                                    <span class="text-sm text-muted text-opacity-90 fw-semibold">EUR</span> <span class="d-block h3 ls-tight fw-bold">25.040,00</span>
                                    <p class="mt-1">
                                        <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>20% </span>
                                        <span class="text-muted text-xs text-opacity-75">vs last week</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="p-4">
                                    <h6 class="text-limit text-muted mb-3">Orders</h6>
                                    <span class="text-sm text-muted text-opacity-90 fw-semibold">EUR</span> <span class="d-block h3 ls-tight fw-bold">25.040,00</span>
                                    <p class="mt-1">
                                        <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>20% </span>
                                        <span class="text-muted text-xs text-opacity-75">vs last week</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="p-4">
                                    <h6 class="text-limit text-muted mb-3">Orders</h6>
                                    <span class="text-sm text-muted text-opacity-90 fw-semibold">EUR</span> <span class="d-block h3 ls-tight fw-bold">25.040,00</span>
                                    <p class="mt-1">
                                        <span class="text-success text-xs"><i class="fas fa-arrow-up me-1"></i>20% </span>
                                        <span class="text-muted text-xs text-opacity-75">vs last week</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
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
    });
    
    // SEARCH AND PAGINATION FOR LIST
    function load_data(page, query = '') {
        $.ajax({
            url : "<?= PROOT; ?>auth/expenditure.list.php",
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