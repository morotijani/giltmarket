<?php 

	// push reverse 

	require_once ("../db_connection/conn.php");

	if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

	if (!admin_has_permission('supervisor')) {
		$_SESSION['flash_error'] = "What do you want?";
		redirect(goBack());
	}

	include ("../includes/header.inc.php");
    include ("../includes/aside.inc.php");
    include ("../includes/left.nav.inc.php");
    include ("../includes/top.nav.inc.php");

	if (isset($_GET['data']) && !empty($_GET['data'])) {
		$id = sanitize($_GET['data']);

		// find push id
		$find = $conn->query("SELECT * FROM jspence_pushes WHERE push_id = '" . $id . "' LIMIT 1")->rowCount();
		if ($find > 0) {
			if (isset($_POST['admin_pin'])) {
				if (!empty($_POST['admin_pin']) || $_POST['admin_pin'] != '') {

					$pin = ((isset($_POST['admin_pin']) && !empty($_POST['admin_pin'])) ? sanitize($_POST['admin_pin']) : '');
					if ($pin == $admin_data['admin_pin']) {
						$query = "
							UPDATE jspence_pushes 
							SET push_status = ? 
							WHERE push_id = ?
						";
						$statement = $conn->prepare($query);
						$result = $statement->execute([1, $id]);
						if ($result) {
							$_SESSION['flash_success'] = 'Push reversed successfully!';
							redirect(goBack());
						}
					} else {
						$_SESSION['flash_error'] = 'Invalid admin pin provided!';
						redirect(goBack());
					}
					
				} else {
					$_SESSION['flash_error'] = 'Provide admin pin to verify this reverse push!';
					redirect(goBack());
				}
			}
		} else {
			$_SESSION['flash_error'] = 'Unknow push id provided!';
			redirect(goBack());
		}
	} else {
		$_SESSION['flash_error'] = "What do you want?";
		redirect(goBack());
	}

?>
	<div class="container-lg">
        <!-- Page header -->
        <div class="row align-items-center mb-7">
            <div class="col-auto">
                <!-- Avatar -->
                <div class="avatar avatar-xl rounded text-warning">
                <i class="fs-2" data-duoicon="menu"></i>
                </div>
            </div>
            <div class="col">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a class="text-body-secondary" href="javascript:;">Market</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pushes</li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="fs-4 mb-0">Pushes</h1>
            </div>
            <?php if ($admin_permission == 'supervisor'): ?>
            <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                <!-- Action -->
                <a class="btn btn-warning d-block" href="javascript:;" data-bs-target="#modalCapital" data-bs-toggle="modal"> Fund coffers</a>
            </div>
            <?php endif; ?>
        </div>

		<!-- Page content -->
        <div class="row">
            <div class="col-12">
				<form action="">
					Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio voluptatibus sapiente ipsam delectus fugiat saepe, eaque, explicabo deserunt blanditiis pariatur eos sunt numquam asperiores corrupti dolore atque iusto perferendis assumenda.
				</form>
        </div>
    </div>

<?php include ("../includes/footer.inc.php"); ?>

