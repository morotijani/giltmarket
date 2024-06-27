<?php 

    // ADMINS

require_once ("../db_connection/conn.php");

if (!admin_is_logged_in()) {
    admn_login_redirect();
}

if (!admin_has_permission()) {
    admin_permission_redirect('index');
}

include ("includes/header.inc.php");
include ("includes/nav.inc.php");
include ("includes/left-side-bar.inc.php");

if (isset($_GET['delete'])) {
    $admin_id = (int)$_GET['delete'];

    $query = "
    UPDATE garypie_admin 
    SET admin_trash = :admin_trash 
    WHERE admin_id = :admin_id
    ";
    $statement = $conn->prepare($query);
    $result = $statement->execute([
        ':admin_trash' => 1,
        ':admin_id' => $admin_id
    ]);
    if (isset($result)) {
        $_SESSION['flash_success'] = 'Admin has been <span class="bg-info">Deleted</span></div>';
        echo "<script>window.location = '".PROOT."gpmin/admins';</script>";
    }
}

if (isset($_GET['add'])) {
    $errors = '';
    $admin_fullname = ((isset($_POST['admin_fullname']))?sanitize($_POST['admin_fullname']):'');
    $admin_email = ((isset($_POST['admin_email']))?sanitize($_POST['admin_email']):'');
    $admin_password = ((isset($_POST['admin_password']))?sanitize($_POST['admin_password']):'');
    $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
    $admin_permissions = ((isset($_POST['admin_permissions']))?sanitize($_POST['admin_permissions']):'');

    if ($_POST) {
        $required = array('admin_fullname', 'admin_email', 'admin_password', 'confirm', 'admin_permissions');
        foreach ($required as $f) {
            if (empty($f)) {
                $errors = 'You must fill out all fields';
                break;
            }
        }

        if (strlen($admin_password) < 6) {
            $errors = 'The password must be at least 6 characters';
        }

        if ($admin_password != $confirm) {
            $errors = 'The passwords do not match.';
        }

        if (!empty($errors)) {
            $errors;
        } else {
            $data = array(
                ':admin_fullname'       => $admin_fullname,
                ':admin_email'          => $admin_email,
                ':admin_password'       => $admin_password,
                ':admin_permissions'    => $admin_permissions
            );
            $query = "
            INSERT INTO `garypie_admin`(`admin_fullname`, `admin_email`, `admin_password`, `admin_permissions`) 
            VALUES (:admin_fullname, :admin_email, :admin_password, :admin_permissions)
            ";
            $statement = $conn->prepare($query);
            $result = $statement->execute($data);
            if (isset($result)) {
                $_SESSION['flash_success'] = 'Admin has been <span class="bg-info">Added</span></div>';
                echo "<script>window.location = '".PROOT."gpmin/admins';</script>";
            }
        }
    }
}


?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

    <?php if (isset($_GET['add']) && !empty($_GET['add'])): ?>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Add Admin</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="<?= PROOT; ?>gpmin/index" class="btn btn-sm btn-outline-secondary">Home</a>
                <a href="<?= PROOT; ?>gpmin/admins" class="btn btn-sm btn-outline-secondary">Cancel</a>
            </div>
            <a href="<?= PROOT; ?>gpmin/admins" class="btn btn-sm btn-outline-secondary">
                <span data-feather="skip-back"></span>
                Go back
            </a>
        </div>
    </div>
    <span><?= $flash; ?></span>

    <form method="POST" action="admins.php?add=1" id="add_adminForm">
        <span class="text-danger lead"><?= $errors; ?></span>
        <div class="mb-3">
            <label for="admin_fullname" class="form-label">Full Name</label>
            <input type="text" class="form-control form-control-sm" name="admin_fullname" id="admin_fullname" value="<?= $admin_fullname; ?>" required>
            <div class="form-text">enter full name in this field</div>
        </div>
        <div class="mb-3">
            <label for="admin_email" class="form-label">Email</label>
            <input type="email" class="form-control form-control-sm" name="admin_email" id="admin_email" value="<?= $admin_email; ?>" required>
            <div class="form-text">enter email in this field</div>
        </div>
        <div class="mb-3">
            <label for="admin_password" class="form-label">Password</label>
            <input type="password" class="form-control form-control-sm" name="admin_password" id="admin_password" value="<?= $admin_password; ?>" required>
            <div class="form-text">enter password in this field</div>
        </div>
        <div class="mb-3">
            <label for="confirm" class="form-label">Confirm Password</label>
            <input type="password" class="form-control form-control-sm" name="confirm" id="confirm" value="<?= $confirm; ?>" required>
            <div class="form-text">enter confirm new password in this field</div>
        </div>
        <div class="mb-3">
            <label for="admin_permissions" class="form-label">Permission</label>
            <select class="form-control form-control-sm" name="admin_permissions" id="admin_permissions">
                <option value=""<?= (($admin_permissions == '')?' selected':'') ?>></option>
                <option value="editor"<?= (($admin_permissions == 'editor')?' selected':'') ?>>Editor</option>
                <option value="admin,editor"<?= (($admin_permissions == 'admin,editor')?' selected':'') ?>>Admin, Editor</option>
            </select>
            <div class="form-text">select type of admin permission in this field</div>
        </div>
        <button type="submit" class="btn btn-sm btn-outline-warning" name="submit_admin" id="submit_admin">Add</button>&nbsp;
        <a href="<?= PROOT; ?>gpmin/admins" class="btn btn-sm btn-outline-secondary">Cancel</a>
    </form>

    <?php else: ?>

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Admins</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?= PROOT; ?>gpmin/admins/index" class="btn btn-sm btn-outline-secondary">Home</a>
                    <a href="<?= PROOT; ?>gpmin/admins/admins" class="btn btn-sm btn-secondary">Refresh</a>
                </div>
                <a href="<?= PROOT; ?>gpmin/admins?add=1" class="btn btn-sm btn-outline-secondary">
                    <span data-feather="plus"></span>
                    Add
                </a>
            </div>
        </div>
        <span><?= $flash; ?></span>

        <table class="table table-success table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Joined Date</th>
                    <th>Last Login</th>
                    <th>Permission</th>
                </tr>
            </thead>
            <tbody>
                <?= get_all_admins(); ?>
            </tbody>
        </table>
    <?php endif ?>

</main>




<?php 

include ("includes/footer.inc.php");

?>
