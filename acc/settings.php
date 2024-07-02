<?php 

    // update admin profile details
    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("../includes/header.inc.php");
    include ("../includes/nav.inc.php");


    $errors = '';
    $admin_fullname = ((isset($_POST['admin_fullname'])) ? sanitize($_POST['admin_fullname']) : $admin_data[0]['admin_fullname']);
    $admin_email = ((isset($_POST['admin_email'])) ? sanitize($_POST['admin_email']) : $admin_data[0]['admin_email']);

    if ($_POST) {
        if (empty($_POST['admin_email']) && empty($_POST['admin_email'])) {
            $errors = 'Fill out all empty fileds';
        }

        if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
            $errors = 'The email you provided is not valid';
        }

        if (!empty($errors)) {
            $errors;
        } else {
            $data = [$admin_fullname, $admin_email, $admin_data[0]['admin_id']];
            $query = "
                UPDATE jspence_admin 
                SET admin_fullname = ?, admin_email = ? 
                WHERE admin_id = ?
            ";
            $statement = $conn->prepare($query);
            $result = $statement->execute($data);
            if (isset($result)) {

                $message = "updated profile details";
                add_to_log($message, $admin_data[0]['admin_id']);

                $_SESSION['flash_success'] = 'Admin has been updated!';
                redirect(PROOT . "acc/profile");
            } else {
                echo js_alert("Something went wrong!");
                redirect(PROOT . "acc/profile");
            }
        }
    }

?>

    <div class="mb-6 mb-xl-10">
        <div class="row g-3 align-items-center">
            <div class="col">
                <h1 class="ls-tight">Update profile details</h1>
            </div>
            <div class="col">
                <div class="hstack gap-2 justify-content-end">
                    <a href="<?= goBack(); ?>" class="btn btn-sm btn-neutral d-none d-sm-inline-flex"><span class="pe-2"><i class="bi bi-arrow-90deg-left"></i> </span><span>Go back</span></a> 
                    <a href="<?= PROOT; ?>acc/change-password" class="btn d-inline-flex btn-sm btn-dark"><span>Change password</span></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <label class="form-label">Avatar</label>
                            </div>
                            <div class="col-md-8 col-xl-5">
                                <div class="">
                                    <div class="d-flex align-items-center">
                                        <a href="<?= PROOT . $admin_data[0]['admin_profile']; ?>" class="avatar avatar-lg bg-warning rounded-circle text-white">
                                            <img src="<?= PROOT . (($admin_data[0]['admin_profile'] == NULL) ? 'dist/media/avatar.png' : $admin_data[0]['admin_profile']); ?>" style="object-fit: cover; object-position: center; width: 35px; height: 35px" alt="<?=ucwords($admin_data[0]['admin_fullname']); ?>'s profile.">
                                        </a>
                                        <div class="hstack gap-2 ms-5">
                                            <label for="file_upload" class="btn btn-sm btn-neutral">
                                                <span>Upload</span> 
                                                <input type="file" name="file_upload" id="file_upload" class="visually-hidden">
                                            </label> 
                                            <a href="#" class="btn d-inline-flex btn-sm btn-neutral text-danger">
                                                <span><i class="bi bi-trash"></i> </span>
                                                <span class="d-none d-sm-block me-2">Remove</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-6">

                    <form method="POST">
                        <div class="text-danger"><?= $errors; ?></div>
                        <div class="mb-3">
                            <label for="admin_fullname" class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="admin_fullname" id="admin_fullname" value="<?= $admin_fullname; ?>" required>
                            <div class="text-sm text-muted">Change your full name in this field</div>
                        </div>
                        <div class="mb-3">
                            <label for="admin_email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="admin_email" id="admin_email" value="<?= $admin_email; ?>" required>
                            <div class="text-sm text-muted">Change your email in this field</div>
                        </div>
                        <button type="submit" class="btn btn-dark" name="submit_settings" id="submit_settings">Update</button>&nbsp;
                    </form>
                </div>
            </div>
        </div>
    </div>


<?php include ("../includes/footer.inc.php"); ?>
