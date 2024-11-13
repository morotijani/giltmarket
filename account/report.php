<?php 

    // change admin password
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admin_login_redirect();
    }

    include ("../includes/header.inc.php");
    include ("../includes/aside.inc.php");
    include ("../includes/left.nav.inc.php");
    include ("../includes/top.nav.inc.php");

    // get all supervisors
    $supQuery = "SELECT * FROM jspence_admin where admin_permission = ?";
    $satement = $conn->prepare($supQuery);
    $satement->execute(['supervisor']);
    $sup_count = $satement->rowCount();
    $sup_rows = $satement->fetchAll();
    $output = '';
    if ($sup_count > 0) {
        foreach ($sup_rows as $sup_row) {
            $output .= '
                <option value="' . $sup_row["admin_id"] . '">"' . ucwords($sup_row["admin_fullname"]) . '"</option>
            ';
        }
    }

    // get all supervisors
    $salQuery = "SELECT * FROM jspence_admin where admin_permission = ?";
    $satement = $conn->prepare($salQuery);
    $satement->execute(['salesperson']);
    $sal_count = $satement->rowCount();
    $sal_rows = $satement->fetchAll();
    if ($sal_count > 0) {
        foreach ($sal_rows as $sal_row) {
            $output .= '
                <option value="' . $sal_row["admin_id"] . '">"' . ucwords($sal_row["admin_fullname"]) . '"</option>
            ';
        }
    }


    $errors = '';
    $hashed = $admin_data['admin_password'];
    $old_password = ((isset($_POST['old_password'])) ? sanitize($_POST['old_password']) : '');
    $old_password = trim($old_password);
    $password = ((isset($_POST['password'])) ? sanitize($_POST['password']) : '');
    $password = trim($password);
    $confirm = ((isset($_POST['confirm'])) ? sanitize($_POST['confirm']) : '');
    $confirm = trim($confirm);
    $new_hashed = password_hash($password, PASSWORD_BCRYPT);
    $admin_id = $admin_data['admin_id'];

    if (isset($_POST['old_password'])) {
        
    }


?>

     <!-- Content -->
     <div class="container-lg">
        <!-- Page header -->
        <div class="row align-items-center mb-7">
            <div class="col-auto">
                <!-- Avatar -->
                <div class="avatar avatar-xl rounded text-warning">
                    <i class="fs-2" data-duoicon="user"></i>
                </div>
            </div>
            <div class="col">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a class="text-body-secondary" href="#">Market</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Report</li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="fs-5 mb-0">Report</h1>
            </div>
            <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                <!-- Action -->
                <button class="btn btn-warning d-block" id="submitForm"> Print </button>
            </div>
        </div>

        <!-- Page content -->
        <div class="row">
            <div class="col-12 col-lg-3">
                <form class="" id="reportForm">

                    <div class="form-check">
                        <input class="form-check-input role" name="role" type="radio" id="role-1" value="supervisor">
                        <label class="form-check-label" for="no-cash">
                            Supervisor
                        </label>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input role" name="role" type="radio" id="role-2" value="salesperson">
                        <label class="form-check-label" for="no-cash">
                            Salespersonnel
                        </label>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="name">Admin:</label>
                        <select class="form-control" id="name" type="text">
                            <option value=""></option>
                            <?= $output; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="name">From:</label>
                        <input class="form-control" id="name" type="date">
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="name">To:</label>
                        <input class="form-control" id="name" type="date">
                    </div>
                    <button class="btn btn-dark" id="submitReport">Submit</button>
                    <button type="button" class="btn clear">Clear</button>
                </form>
            </div>

            <div class="col-12 col-lg-9" data-bs-spy="scroll" data-bs-target="#accountNav" data-bs-smooth-scroll="true" tabindex="0">
                <!-- General -->
                <section class="card bg-body-tertiary border-transparent mb-5" id="general">
                    <div class="card-body">
                        <h2 class="fs-5 mb-1">Report view</h2>
                        <p class="text-body-secondary">From and To</p>
                        <hr>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Price</th>
                                    <th>Gram</th>
                                    <th>Volume</th>
                                    <th>Density</th>
                                    <th>Pounds</th>
                                    <th>Carat</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </section>
             </div>
        </div>
    </div>

<?php include ("../includes/footer.inc.php"); ?>

<script type="text/javascript">
    $(document).ready(function() {
        // sclear form
        $('.clear').on('click', function() {
            $('#reportForm')[0].reset();
        })

        // 
        $(".role").change(function() {
            if (this.checked) {
                // Do stuff
                var role = $(".role").val()

                if (role == 'supervisor') {

                } else if (role == 'salesperson') {

                }

                // re-check checkbox
                $( this ).prop( "checked", true );
            }
        });
        
        $('#submitForm').on('click', function() {

            $('#submitForm').attr('disabled', true);
            $('#submitForm').text('Changing ...');

            setTimeout(function () {
                $('#changePasswordForm').submit();

                $('#submitForm').attr('disabled', false);
                $('#submitForm').text('Save');
            }, 2000)

        })

    });
</script>
