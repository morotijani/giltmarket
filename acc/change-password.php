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

    if ($_POST) {
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


?>

    <div class="mb-6 mb-xl-10">
        <div class="row g-3 align-items-center">
            <div class="col">
                <h1 class="ls-tight">Edit logins</h1>
            </div>
            <div class="col">
                <div class="hstack gap-2 justify-content-end">
                    <a href="<?= goBack(); ?>" class="btn btn-sm btn-neutral d-none d-sm-inline-flex"><span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Go back</span></a> 
                    <a href="<?= PROOT; ?>acc/profile" class="btn d-inline-flex btn-sm btn-dark"><span>Cancel</span></a>
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


<?php 

    include ("../includes/footer.inc.php");

?>
