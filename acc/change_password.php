<?php 

    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("includes/header.inc.php");
    include ("includes/nav.inc.php");
    include ("includes/left-side-bar.inc.php");

    $errors = '';
    $hashed = $admin_data['admin_password'];
    $old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
    $old_password = trim($old_password);
    $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
    $password = trim($password);
    $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
    $confirm = trim($confirm);
    $new_hashed = password_hash($password, PASSWORD_BCRYPT);
    $admin_id = $admin_data['admin_id'];
    //echo $admin_id;

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
                UPDATE garypie_admin 
                SET admin_password = :admin_password 
                WHERE admin_id = :admin_id
            ';
            $satement = $conn->prepare($query);
            $result = $satement->execute(
                array(
                    ':admin_password' => $new_hashed,
                    ':admin_id' => $admin_id
                )
            );
            if (isset($result)) {
                $_SESSION['flash_success'] = 'Password successfully <span class="bg-info">UPDATED</span></div>';
                echo "<script>window.location = '".PROOT."gpmin/profile';</script>";
            }
        }
    }


?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Change password</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?= PROOT ?>gpmin/index" class="btn btn-sm btn-outline-secondary">Home</a>
                    <a href="<?= PROOT ?>gpmin/change_password" class="btn btn-sm btn-outline-secondary">Refresh</a>
                </div>
                <a href="<?= PROOT ?>gpmin/profile" class="btn btn-sm btn-outline-secondary">
                    <span data-feather="skip-back"></span>
                    GO back
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="change_password.php" id="edit_passwordForm">
                    <span class="text-danger lead"><?= $errors; ?></span>
                    <div class="mb-3">
                        <label for="old_password" class="form-label">Old password</label>
                        <input type="password" class="form-control form-control-sm" name="old_password" id="old_password" value="<?= $old_password; ?>" required>
                        <div class="form-text">enter old password in this field</div>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New password</label>
                        <input type="password" class="form-control form-control-sm" name="password" id="password" value="<?= $password; ?>" required>
                        <div class="form-text">enter new password in this field</div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm" class="form-label">Confirm new password</label>
                        <input type="password" class="form-control form-control-sm" name="confirm" id="confirm" value="<?= $confirm; ?>" required>
                        <div class="form-text">enter confirm new password in this field</div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline-warning" name="edit_pasword" id="edit_pasword">Edit</button>&nbsp;
                    <a href="profile" class="btn btn-sm btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>


    </main>




<?php 

    include ("includes/footer.inc.php");

?>
