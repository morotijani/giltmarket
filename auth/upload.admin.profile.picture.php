<?php 

	// Upload admin profile

	require_once ("../db_connection/conn.php");

	if ($_FILES["file_upload"]["name"]  != '') {

		$test = explode(".", $_FILES["file_upload"]["name"]);

		$extention = end($test);

		$name = md5(microtime()) . '.' . $extention;

		$name = 'dist/media/admin-profiles/' . $name;

		$location = BASEURL . $name;

		//check if user dexist
		$move = move_uploaded_file($_FILES["file_upload"]["tmp_name"], $location);
		if ($move) {
			$sql = "
				UPDATE jspence_admin 
				SET admin_profile = ?
				WHERE admin_id  = ? 
			";
			$statement = $conn->prepare($sql);
			$result = $statement->execute([$name, $admin_data[0]['admin_id']]);

			if (isset($result)) {
				$message = "updated profile picture";
                add_to_log($message, $admin_data[0]['admin_id']);

				echo '';
			}
		} else {
			echo 'Something went wrong, please try again!';
		}
	}
