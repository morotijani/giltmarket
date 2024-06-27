<?php 

    // ADMIN SETTINGS

    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("includes/header.inc.php");
    include ("includes/nav.inc.php");
    include ("includes/left-side-bar.inc.php");

    $errors = '';
    $admin_fullname = ((isset($_POST['admin_fullname']))?sanitize($_POST['admin_fullname']):$admin_data['admin_fullname']);
    $admin_email = ((isset($_POST['admin_email']))?sanitize($_POST['admin_email']):$admin_data['admin_email']);

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
            $data = [
                ':admin_fullname' => $admin_fullname,
                ':admin_email' => $admin_email,
                ':admin_id' => $admin_data['admin_id']
            ];
            $query = "
                UPDATE garypie_admin 
                SET admin_fullname = :admin_fullname, admin_email = :admin_email 
                WHERE admin_id = :admin_id
            ";
            $statement = $conn->prepare($query);
            $result = $statement->execute($data);
            if (isset($result)) {
                $_SESSION['flash_success'] = 'Admin has been <span class="bg-info">Updated</span></div>';
                echo "<script>window.location = '".PROOT."gpmin/admins';</script>";
            }
        }
    }

?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Edit Admin</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?= PROOT; ?>gpmin/index" class="btn btn-outline-secondary">Home</a>
                    <a href="<?= PROOT; ?>gpmin/change_password" class="btn btn-outline-secondary">Change password</a>
                </div>
            </div>
        </div>
        <span><?= $flash; ?></span>

        <form method="POST" action="settings.php" id="settingsForm">
            <span class="text-danger lead"><?= $errors; ?></span>
            <div class="mb-3">
                <label for="admin_fullname" class="form-label">Full Name</label>
                <input type="text" class="form-control" name="admin_fullname" id="admin_fullname" value="<?= $admin_fullname; ?>" required>
                <div class="form-text">change your full name in this field</div>
            </div>
            <div class="mb-3">
                <label for="admin_email" class="form-label">Email</label>
                <input type="email" class="form-control" name="admin_email" id="admin_email" value="<?= $admin_email; ?>" required>
                <div class="form-text">change your email in this field</div>
            </div>
            <button type="submit" class="btn btn-warning" name="submit_settings" id="submit_settings">Update</button>&nbsp;
            <a href="<?= PROOT; ?>gpmin/profile" class="btn btn-secondary">Cancel</a>
        </form>

    </main>


<?php 
    include ("includes/footer.inc.php");
?>