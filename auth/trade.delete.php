<?php 

	// delete trade 

	require_once ("../db_connection/conn.php");

	if (!admin_is_logged_in()) {
        admin_login_redirect();
    }

	if (admin_has_permission()) {
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
		$find = $conn->query("SELECT * FROM jspence_sales WHERE sale_id = '" . $id . "'")->fetchAll();
		if (count($find) > 0) {
			if (isset($_POST['admin_pin'])) {
				if (!empty($_POST['admin_pin']) || $_POST['admin_pin'] != '') {

					$reason = ((isset($_POST['reason']) && !empty($_POST['reason'])) ? sanitize($_POST['reason']) : '');
					$pin = ((isset($_POST['admin_pin']) && !empty($_POST['admin_pin'])) ? sanitize($_POST['admin_pin']) : '');
					if ($pin == $admin_data['admin_pin']) {
                        
                        $sql = "
                            UPDATE jspence_sales 
                            SET sale_status = ?, sale_delete_request_reason = ?
                            WHERE sale_id = ?
                        ";
                        $statement = $conn->prepare($sql);
                        $result = $statement->execute([1, $reason, $id]);
                        if (isset($result)) {                
                            $message = "delete request for trade id: '".$id."'";
                            add_to_log($message, $admin_data['admin_id']);

                            $_SESSION['flash_success'] = ' Sale delete request successfully sent!';
                            redirect(PROOT . 'account/trades');
                        } else {
                            echo js_alert("Something went wrong, please try again!");
                        }
						
					} else {
						$_SESSION['flash_error'] = 'Invalid admin pin provided!';
						redirect(PROOT . 'auth/trade.delete/' . $id);
					}
					
				} else {
					$_SESSION['flash_error'] = 'Provide admin pin to verify this deletion!';
					redirect(PROOT . 'auth/trade.delete/' . $id);
				}
			}
		} else {
            $_SESSION['flash_error'] = ' Could not find trade to delete!';
            redirect(PROOT . 'auth/trade.delete/' . $id);
		}
	} else {
		$_SESSION['flash_error'] = "What do you want?";
		redirect(PROOT . 'account/trades');
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
                        <li class="breadcrumb-item active" aria-current="page">Trede</li>
                    </ol>
                </nav>

                <!-- Heading -->
                <h1 class="fs-4 mb-0">Delete trade</h1>
            </div>
            <div class="col-12 col-sm-auto mt-4 mt-sm-0">
                <!-- Action -->
                <a class="btn btn-light d-block" href="<?= PROOT; ?>account/trades"> Cancel delete trade</a>
            </div>
        </div>

		<!-- Page content -->
		<div class="row">
        	<div class="col-12 col-lg-3">
				<!-- Nav -->
				<nav class="nav nav-pills position-sticky flex-column mb-8" id="accountNav" style="top: 32px">
					<a class="nav-link active" href="javascript:;">Delete trade</a>
					<a class="nav-link" href="<?= PROOT; ?>account/trades">Cancel</a>
				</nav>
          	</div>
          	<div class="col-12 col-lg-9" data-bs-spy="scroll" data-bs-target="#accountNav" data-bs-smooth-scroll="true" tabindex="0">
				<!-- General -->
				<section class="card bg-body-tertiary border-transparent mb-5" id="general">
					<div class="card-body">
						<h2 class="fs-5 mb-1">Delete trade with an amount equivallent to <?= money($find[0]['sale_total_amount']); ?></h2>
						<p class="text-body-secondary">You are to delete a trade you made; 
                        <br> Gram: <?= $find[0]['sale_gram']; ?>, Volume: <?= $find[0]['sale_volume']; ?>, Density: <?= $find[0]['sale_density']; ?>, Pounds: <?= $find[0]['sale_pounds']; ?>, Carat: <?= $find[0]['sale_carat']; ?>
                        <br />Provide reason and pin to complete the deletion.</p>
						<form id="DeleteForm" method="POST">
							<div class="mb-3">
								<label class="form-label" for="fullName">Reason</label>
								<textarea class="form-control bg-body" type="reason" id="reason" name="reason" maxlength="300" required></textarea>
							</div>
							<div class="mb-4">
								<label class="form-label" for="fullName">Enter PIN</label>
								<input class="form-control bg-body" type="number" id="admin_pin" name="admin_pin" autocomplete="off" inputmode="numeric" data-maxlength="4" oninput="this.value=this.value.slice(0,this.dataset.maxlength)" required />
							</div>
							<button class="btn btn-outline-danger" id="submitDelete">Delete</button>
						</form>
					</div>
				</section>
			</div>
		</div>

<?php include ("../includes/footer.inc.php"); ?>

<script>

	$('#submitDelete').on('click', function() {

		$('#submitDelete').attr('disabled', true);
		$('#submitDelete').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span> deleting ...</span>');
		setTimeout(function () {
			$('#DeleteForm').submit();

			$('#submitDelete').attr('disabled', false);
			$('#submitDelete').text('Delete');
		}, 2000)

	});
</script>
