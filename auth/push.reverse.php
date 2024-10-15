<?php 

	// push reverse 

	require_once ("../db_connection/conn.php");

	if (isset($_POST['push_id'])) {

		if (!empty($_POST['push_id']) || $_POST['push_id'] != '') {
			if (!empty($_POST['admin_pin']) || $_POST['admin_pin'] != '') {

				$id = ((isset($_POST['push_id']) && !empty($_POST['push_id'])) ? sanitize($_POST['push_id']) : '');
				$pin = ((isset($_POST['admin_pin']) && !empty($_POST['admin_pin'])) ? sanitize($_POST['admin_pin']) : '');

				// find push id
				$find = $conn->query("SELECT * FROM jspence_pushes WHERE push_id = '" . $id . "' LIMIT 1")->rowCount();
				if ($find > 0) {
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
						}
					} else {
						$_SESSION['flash_error'] = 'Invalid admin pin provided!';
					}
				} else {
					$_SESSION['flash_error'] = 'Unknow push id provided!';
				}
			} else {
				$_SESSION['flash_error'] = 'Provide admin pin to verify this reverse push!';
			}
		} else {
			$_SESSION['flash_error'] = 'Unknow push id provided!';
		}
		redirect(goBack());
    }
?>

