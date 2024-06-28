<?php 

// ADMINS

require_once ("../db_connection/conn.php");

if (!admin_is_logged_in()) {
    admn_login_redirect();
}

if (!admin_has_permission()) {
    admin_permission_redirect('index');
}

include ("../includes/header.inc.php");
include ("../includes/nav.inc.php");

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
    $admin_fullname = ((isset($_POST['admin_fullname'])) ? sanitize($_POST['admin_fullname']) : '');
    $admin_email = ((isset($_POST['admin_email'])) ? sanitize($_POST['admin_email']) : '');
    $admin_password = ((isset($_POST['admin_password'])) ? sanitize($_POST['admin_password']) : '');
    $confirm = ((isset($_POST['confirm']))? sanitize($_POST['confirm']) : '');
    $admin_permissions = ((isset($_POST['admin_permissions']))? sanitize($_POST['admin_permissions']) : '');
    $admin_id = guidv4();

    if ($_POST) {
        $required = array('admin_fullname', 'admin_email', 'admin_password', 'confirm', 'admin_permissions');
        foreach ($required as $f) {
            if (empty($f)) {
                $errors = 'You must fill out all fields!';
                break;
            }
        }

        if (strlen($admin_password) < 6) {
            $errors = 'The password must be at least 6 characters!';
        }

        if ($admin_password != $confirm) {
            $errors = 'The passwords do not match!';
        }

        if (!empty($errors)) {
            $errors;
        } else {
            $data = array($admin_id, $admin_fullname, $admin_email, password_hash($admin_password, PASSWORD_BCRYPT), $admin_permissions);
            $query = "
                INSERT INTO `jspence_admin`(`admin_id`, `admin_fullname`, `admin_email`, `admin_password`, `admin_permissions`) 
                VALUES (?, ?, ?, ?, ?)
            ";
            $statement = $conn->prepare($query);
            $result = $statement->execute($data);
            if (isset($result)) {

                $message = "changed password";
                add_to_log($message, $admin_data[0]['admin_id']);

                $_SESSION['flash_success'] = 'Admin has been Added!';
                redirect(PROOT . "acc/admins");
            } else {
                echo js_alert("Something went wrong!");
                redirect(PROOT . "acc/admins?add=1");
            }
        }
    }
}


?>

    <?php if (isset($_GET['add']) && !empty($_GET['add'])): ?>
        <div class="mb-6 mb-xl-10">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h1 class="ls-tight">Add new admin</h1>
                </div>
                <div class="col">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="<?= goBack(); ?>" class="btn btn-sm btn-neutral d-none d-sm-inline-flex"><span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Go back</span></a> 
                        <a href="<?= PROOT; ?>acc/admins" class="btn d-inline-flex btn-sm btn-dark"><span>Cancel</span></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex flex-column">
                        <form method="POST" action="<?= PROOT; ?>acc/admins.php?add=1">
                            <div class="text-danger"><?= $errors; ?></div>
                            <div class="mb-3">
                                <label for="admin_fullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="admin_fullname" id="admin_fullname" value="<?= $admin_fullname; ?>" required>
                                <div class="text-sm text-muted">Enter full name in this field</div>
                            </div>
                            <div class="mb-3">
                                <label for="admin_email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="admin_email" id="admin_email" value="<?= $admin_email; ?>" required>
                                <div class="text-sm text-muted">Enter email in this field</div>
                            </div>
                            <div class="mb-3">
                                <label for="admin_password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="admin_password" id="admin_password" value="<?= $admin_password; ?>" required>
                                <div class="text-sm text-muted">Enter password in this field</div>
                            </div>
                            <div class="mb-3">
                                <label for="confirm" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="confirm" id="confirm" value="<?= $confirm; ?>" required>
                                <div class="text-sm text-muted">Enter confirm new password in this field</div>
                            </div>
                            <div class="mb-3">
                                <label for="admin_permissions" class="form-label">Permission</label>
                                <select class="form-control" name="admin_permissions" id="admin_permissions" required>
                                    <option value=""<?= (($admin_permissions == '')?' selected' : '') ?>></option>
                                    <option value="salesperson"<?= (($admin_permissions == 'salesperson')?' selected' : '') ?>>Salesperson</option>
                                    <option value="admin,salesperson"<?= (($admin_permissions == 'admin,salesperson')?' selected' : '') ?>>Admin,  Salesperson</option>
                                </select>
                                <div class="text-sm text-muted">Select type of admin permission in this field</div>
                            </div>
                            <button type="submit" class="btn btn-dark" name="submit_admin" id="submit_admin">Add admin</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>

        <div class="mb-6 mb-xl-10">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h1 class="ls-tight">Admins</h1>
                </div>
                <div class="col">
                    <div class="hstack gap-2 justify-content-end">
                        <a href="<?= goBack(); ?>" class="btn btn-sm btn-neutral d-none d-sm-inline-flex"><span class="pe-2"><i class="bi bi-plus-circle"></i> </span><span>Go back</span></a> 
                        <a href="<?= PROOT; ?>acc/admins?add=1" class="btn d-inline-flex btn-sm btn-dark"><span>Add admin</span></a>
                    </div>
                </div>
            </div>
        </div>

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

<?php include ("../includes/footer.inc.php"); ?>
