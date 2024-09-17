<?php 

	// DELETE admin profile picture

	require_once ("../db_connection/conn.php");

	if (isset($_POST['tempuploded_file_id'])) {

		$tempuploded_img_id_filePath = BASEURL . $_POST['tempuploded_file_id'];

		$unlink = unlink($tempuploded_img_id_filePath);
		if ($unlink) {
			$sql = "
				UPDATE jspence_admin 
				SET admin_profile = ? 
				WHERE admin_id = ?
			";
			$statement = $conn->prepare($sql);
			$result = $statement->execute(['', $admin_data[0]['admin_id']]);
			if (isset($result)) {
				
				$message = "deleted profile picture";
                add_to_log($message, $admin_data[0]['admin_id']);

				echo '';
			}
		}
	}
