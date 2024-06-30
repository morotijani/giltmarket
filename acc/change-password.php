<?php 

    // change admin password
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("../includes/header.inc.php");
    include ("../includes/nav.inc.php");


    $errors = '';
    $hashed = $admin_data[0]['admin_password'];
    $old_password = ((isset($_POST['old_password'])) ? sanitize($_POST['old_password']) : '');
    $old_password = trim($old_password);
    $password = ((isset($_POST['password'])) ? sanitize($_POST['password']) : '');
    $password = trim($password);
    $confirm = ((isset($_POST['confirm'])) ? sanitize($_POST['confirm']) : '');
    $confirm = trim($confirm);
    $new_hashed = password_hash($password, PASSWORD_BCRYPT);
    $admin_id = $admin_data[0]['admin_id'];

    if (isset($_POST['edit_pasword'])) {
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
                UPDATE jspence_admin 
                SET admin_password = ? 
                WHERE admin_id = ?
            ';
            $satement = $conn->prepare($query);
            $result = $satement->execute(array($new_hashed, $admin_id));
            if (isset($result)) {

                $message = "changed password";
                add_to_log($message, $admin_id);

                $_SESSION['flash_success'] = 'Password successfully updated!';
                redirect(PROOT . "acc/profile");
            } else {
                echo js_alert('Something went wrong');
                redirect(PROOT . "acc/change-password");
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

                if ($_POST['oldpin'] != $admin_data[0]['admin_pin']) {
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
                        UPDATE jspence_admin 
                        SET admin_pin = ? 
                        WHERE admin_id = ?
                    ';
                    $satement = $conn->prepare($query);
                    $result = $satement->execute(array(sanitize($_POST['newpin']), $admin_id));
                    if (isset($result)) {

                        $message = "changed PIN";
                        add_to_log($message, $admin_id);

                        $_SESSION['flash_success'] = 'New PIN successfully set!';
                        redirect(PROOT . "acc/profile");
                    } else {
                        echo js_alert('Something went wrong');
                    }
                }

            }

        }
    }


?>

    <div class="mb-6 mb-xl-10">
        <div class="row g-3 align-items-center">
            <div class="col">
                <h1 class="ls-tight">Edit logins</h1>
            </div>
            <div class="col">
                <div class="hstack gap-2 justify-content-end">
                    <a href="<?= goBack(); ?>" class="btn btn-sm btn-neutral"><span class="pe-2"><i class="bi bi-arrow-90deg-left"></i> </span><span>Go back</span></a> 
                    <a href="javascript:;" data-bs-target="#pinModal" data-bs-toggle="modal" class="btn btn-sm btn-dark"><span>Change PIN</span></a>
                </div>
            </div>
        </div>
    </div>

     <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex flex-column">
                    <form method="POST">
                        <div class="text-danger"><?= $errors; ?></div>
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Old password</label>
                            <input type="password" class="form-control" name="old_password" id="old_password" value="<?= $old_password; ?>" required>
                            <div class="text-sm text-muted">Enter old password in this field</div>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New password</label>
                            <input type="password" class="form-control" name="password" id="password" value="<?= $password; ?>" required>
                            <div class="text-sm text-muted">Enter new password in this field</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm" class="form-label">Confirm new password</label>
                            <input type="password" class="form-control" name="confirm" id="confirm" value="<?= $confirm; ?>" required>
                            <div class="text-sm text-muted">Enter confirm new password in this field</div>
                        </div>
                        <button type="submit" class="btn btn-dark" name="edit_pasword" id="edit_pasword">Edit password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


<?php include ("../includes/footer.inc.php"); ?>

<div class="modal fade" id="pinModal" tabindex="-1" aria-labelledby="pinModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden">
            <div class="modal-header pb-0 border-0">
                <h1 class="modal-title h4" id="pinModalLabel">Change PIN</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <form method="POST" action="<?= PROOT; ?>acc/change-password?pin=1">
                    <div class="px-6 py-5 border-bottom">
                        <input type="number" inputmode="numeric" data-maxlength="4" oninput="this.value=this.value.slice(0,this.dataset.maxlength)" min="0" class="form-control" name="oldpin" placeholder="Old PIN" required>
                        <br>
                        <input type="number" inputmode="numeric" min="0" data-maxlength="4" oninput="this.value=this.value.slice(0,this.dataset.maxlength)" class="form-control" name="newpin" placeholder="New PIN" required>
                        <br>
                        <input type="number" inputmode="numeric" min="0" data-maxlength="4" oninput="this.value=this.value.slice(0,this.dataset.maxlength)" class="form-control" name="confirmpin" placeholder="Confirm new PIN" required>
                    </div>
                    <div class="px-6 py-5 bg-body-secondary d-flex justify-content-center">
                        <button name="pin_submit" class="btn btn-sm btn-dark"><i class="bi bi-incognito me-2"></i>Change pin now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
