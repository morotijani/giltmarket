<?php 

    // change admin password
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admin_login_redirect();
    }

    //
	if (is_array(capital_mover($admin_id)) && capital_mover($admin_id)["msg"] == "touched") {
		redirect(PROOT . 'auth/end-trade-checker');
	}

    include ("../includes/header.inc.php");
    include ("../includes/aside.inc.php");
    include ("../includes/left.nav.inc.php");
    include ("../includes/top.nav.inc.php");


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
        if (empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])) {
            $errors = 'You must fill out all fields';
        } else {

            if (strlen($password) < 6) {
                $errors = 'Password must be at least 6 characters';
            }

            if ($password != $confirm) {
                $errors = 'The new password and confirm new password does not match.';
            }

            if (!password_verify($old_password, $hashed)) {
                $errors = 'Your old password does not our records.';
            }
        }

        if (!empty($errors)) {
            $errors;
        } else {
            $query = '
                UPDATE giltmarket_admin 
                SET admin_password = ? 
                WHERE admin_id = ?
            ';
            $satement = $conn->prepare($query);
            $result = $satement->execute(array($new_hashed, $admin_id));
            if (isset($result)) {

                $message = "changed password";
                add_to_log($message, $admin_id);

                $_SESSION['flash_success'] = 'Password successfully updated!';
                redirect(PROOT . "account/profile");
            } else {
                echo js_alert('Something went wrong');
                redirect(PROOT . "account/change-password");
            }
        }
    }

    // change pin
    if (isset($_GET['pin']) && !empty($_GET['pin'])) {
        if (isset($_POST['pin_submit'])) {
            $msg = '';

            if (empty($_POST['oldpin']) || empty($_POST['newpin']) || empty($_POST['confirmpin'])) {
                $msg = 'You must fill out all fields!';
            } else {

                if ($_POST['oldpin'] != $admin_data['admin_pin']) {
                    $msg = 'Incorrect Old PIN provided!';
                }

                if (strlen($_POST['newpin']) < 4) {
                    $msg = 'PIN must be 4 characters!';
                }

                if ($_POST['newpin'] != $_POST['confirmpin']) {
                    $msg = 'The new PIN and confirm new PIN does not match!';
                    //
                }

                if ($msg != '') {
                    // code...
                    echo js_alert($msg);
                } else {
                    $query = '
                        UPDATE giltmarket_admin 
                        SET admin_pin = ? 
                        WHERE admin_id = ?
                    ';
                    $satement = $conn->prepare($query);
                    $result = $satement->execute(array(sanitize($_POST['newpin']), $admin_id));
                    if (isset($result)) {

                        $message = "changed PIN";
                        add_to_log($message, $admin_id);

                        $_SESSION['flash_success'] = 'New PIN successfully set!';
                        redirect(PROOT . "account/profile");
                    } else {
                        echo js_alert('Something went wrong');
                    }
                }

            }

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
                    <i class="fs-2" data-duoicon="user"></i>
                </div>
            </div>
            <div class="col">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a class="text-body-secondary" href="#">Account</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Logins</li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="fs-5 mb-0">Account</h1>
            </div>
            <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                <!-- Action -->
                <button class="btn btn-warning d-block" id="submitForm"> Save </button>
            </div>
        </div>

        <!-- Page content -->
        <div class="row">
            <div class="col-12 col-lg-3">
                <!-- Nav -->
                <nav class="nav nav-pills position-sticky flex-column mb-8" id="accountNav" style="top: 2rem">
                    <a class="nav-link" href="<?= PROOT; ?>account/profile">General</a>
                    <a class="nav-link" href="<?= PROOT; ?>account/settings">Update account</a>
                    <a class="nav-link" data-bs-target="#pinModal" data-bs-toggle="modal" href="javascript:;">Change PIN</a>
                    <a class="nav-link" href="<?= PROOT; ?>account/change-password">Change password</a>
                    <a class="nav-link text-danger" href="<?= PROOT; ?>auth/logout">Logout</a>
                </nav>
            </div>

            <div class="col-12 col-lg-9" data-bs-spy="scroll" data-bs-target="#accountNav" data-bs-smooth-scroll="true" tabindex="0">
                <!-- General -->
                <section class="card bg-body-tertiary border-transparent mb-5" id="general">
                    <div class="card-body">
                        <h2 class="fs-5 mb-1">Password</h2>
                        <p class="text-body-secondary">Change your login information.</p>
                        <hr>
                        <form method="POST" id="changePasswordForm">
                            <div class="text-danger mb-3"><?= $errors; ?></div>
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
        </div>
    </div>

    <!-- CHANGE PIN -->
    <div class="modal fade" id="pinModal" tabindex="-1" aria-labelledby="pinModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" style="backdrop-filter: blur(5px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4" id="pinModalLabel">Change PIN</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <form method="POST" action="<?= PROOT; ?>account/change-password?pin=1">
                        <div class="px-6 py-5">
                            <input type="number" inputmode="numeric" data-maxlength="4" oninput="this.value=this.value.slice(0,this.dataset.maxlength)" class="form-control" name="oldpin" placeholder="Old PIN" required>
                            <br>
                            <input type="number" inputmode="numeric" min="0" data-maxlength="4" oninput="this.value=this.value.slice(0,this.dataset.maxlength)" class="form-control" name="newpin" placeholder="New PIN" required>
                            <br>
                            <input type="number" inputmode="numeric" min="0" data-maxlength="4" oninput="this.value=this.value.slice(0,this.dataset.maxlength)" class="form-control" name="confirmpin" placeholder="Confirm new PIN" required>
                        </div>
                        <div class="px-6 py-5 d-flex justify-content-center">
                            <button name="pin_submit" class="btn btn-warning"><i data-duoicon="user-card" class="me-2 fs-2"></i>Change pin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include ("../includes/footer.inc.php"); ?>

<script type="text/javascript">
    $(document).ready(function() {
        // save password changes
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
