<?php 

// print out results for development usages
function dnd($data) {
	echo "<pre>";
	print_r($data);
	echo "</pre>";
    die;
}

// Make Date Readable
function pretty_date($date) {
	if ($date != null ||$date != '') 
		return date("M d, Y h:i A", strtotime($date));

	return false;
}

// get only date from full datetime
function pretty_date_only($date) {
	if ($date != null ||$date != '') 
		return date("F j, Y", strtotime($date));

	return false;
}

// extract time from full date
function time_from_date($date) {
	$dt = new DateTime($date);

	$date = $dt->format('d-m-Y');
	$time = $dt->format('h:i:s A');

	return $time;
}

// Display money in a readable way
function money($number) {
	$output = '0.00';
	if ($number != NULL || $number != '') 
		$output = number_format($number, 2);

	return '₵' . $output;
}

// Check For Incorrect Input Of Data
function sanitize($dirty) {
    $clean = htmlentities($dirty, ENT_QUOTES, "UTF-8");
    return trim($clean);
}

function cleanPost($post) {
    $clean = [];
    foreach ($post as $key => $value) {
      	if (is_array($value)) {
        	$ary = [];
        	foreach($value as $val) {
          		$ary[] = sanitize($val);
        	}
        	$clean[$key] = $ary;
      	} else {
        	$clean[$key] = sanitize($value);
      	}
    }
    return $clean;
}

//
function php_url_slug($string) {
 	$slug = preg_replace('/[^a-z0-9-]+/', '-', trim(strtolower($string)));
 	return $slug;
}

// REDIRECT PAGE
function redirect($url) {
    if(!headers_sent()) {
      	header("Location: {$url}");
    } else {
      	echo '<script>window.location.href="' . $url . '"</script>';
    }
    exit;
}

function issetElse($array, $key, $default = "") {
    if(!isset($array[$key]) || empty($array[$key])) {
      return $default;
    }
    return $array[$key];
}


// Email VALIDATION
function isEmail($email) {
	return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
}

// GET USER IP ADDRESS
function getIPAddress() {  
    //whether ip is from the share internet  
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  
        $ip = $_SERVER['HTTP_CLIENT_IP'];  
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  // whether ip is from the proxy
       $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
     } else {  // whether ip is from the remote address 
        $ip = $_SERVER['REMOTE_ADDR'];  
    }  
    return $ip;  
}

// PRINT OUT RANDAM NUMBERS
function digit_random($digits) {
  	return rand(pow(10, $digits - 1) - 1, pow(10, $digits) - 1);
}

function js_alert($msg) {
	return '<script>alert("' . $msg . '");</script>';
}


// 
function sms_otp($msg, $phone) {
	$sender = urlencode("Inqoins VER");
    $api_url = "https://api.innotechdev.com/sendmessage.php?key=".SMS_API_KEY."&message={$msg}&senderid={$sender}&phone={$phone}";
    $json_data = file_get_contents($api_url);
    $response_data = json_decode($json_data);
    // Can be use for checks on finished / unfinished balance
    $fromAPI = 'insufficient balance, kindly credit your account';  
    if ($api_url)
    	return 1;
	else
		return 0;
}

//
function send_email($name, $to, $subject, $body) {
	$mail = new PHPMailer(true);
	try {
        $fn = $name;
        $to = $to;
        $from = MAIL_MAIL;
        $from_name = 'Garypie, Shop.';
        $subject = $subject;
        $body = $body;

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        $mail->IsSMTP();
        $mail->SMTPAuth = true;

        $mail->SMTPSecure = 'ssl'; 
        $mail->Host = 'smtp.garypie.com';
        $mail->Port = 465;  
        $mail->Username = $from;
        $mail->Password = MAIL_KEY; 

        $mail->IsHTML(true);
        $mail->WordWrap = 50;
        $mail->From = $from;
        $mail->FromName = $from_name;
        $mail->Sender = $from;
        $mail->AddReplyTo($from, $from_name);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);
        $mail->send();
        return true;
    } catch (Exception $e) {
    	//return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    	return false;
        //$message = "Please check your internet connection well...";
    }
}

// Generate UUID VERSION 4
function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

/// find user agent
function getBrowserAndOs() {

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = "N/A";

    $browsers = array(
        '/msie/i' => 'Internet explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/mobile/i' => 'Mobile browser'
    );

    foreach ($browsers as $regex => $value) {
        if (preg_match($regex, $user_agent)) { $browser = $value; }
    }

    $visitor_agent_division = explode("(", $user_agent)[1];
    list($os, $division_two) = explode(")", $visitor_agent_division);

    $refferer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

    $visitor_broswer_os = array(
        'browser' => $browser,
        'operatingSystem' => $os,
        'refferer' => $refferer
    );

   	$output = json_encode($visitor_broswer_os);

    return $output;
}

// get user/visitor device
function getDeviceType() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Check if it's a mobile device
    if (preg_match('/mobile/i', $userAgent)) {
        if (preg_match('/android/i', $userAgent)) {
            return "Mobile (Android)";
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            return "Mobile (iOS)";
        } else {
            return "Mobile (Other)";
        }
    }

    // Check if it's a tablet
    if (preg_match('/tablet|ipad/i', $userAgent)) {
        return "Tablet";
    }

    // Default to desktop
    return "Desktop";
} 

function goBack() {
	$previous = "javascript:history.go(-1)";
	if (isset($_SERVER['HTTP_REFERER'])) {
	    $previous = $_SERVER['HTTP_REFERER'];
	}
	return $previous;
}


function idle_user() {

    // Check the last activity time
    if (isset($_SESSION['last_activity'])) {
        $idleTime = time() - $_SESSION['last_activity'];

        // If the idle time exceeds the timeout period
        if ($idleTime > IDLE_TIMEOUT) {
            // Destroy the session and log out the user
            //session_unset();
            //session_destroy();

            // Redirect to the login page or show a message
			// $_SESSION['flash_error'] = 'Session expired. Please log in again!';
			//redirect(PROOT . 'auth/login');
            //exit;
			return false;
        }
    }

    // Update the last activity timestamp
    $_SESSION['last_activity'] = time();
	return true;
}

/////////////////////////////////////////////////////////////////////////////////////////////////




// Sessions For login
function adminLogin($admin_id) {
	$_SESSION['JSAdmin'] = $admin_id;
	global $conn;

	$data = array(date("Y-m-d H:i:s"), $admin_id);
	$query = "
		UPDATE jspence_admin 
		SET admin_last_login = ? 
		WHERE admin_id = ?
	";
	$statement = $conn->prepare($query);
	$result = $statement->execute($data);
	if (isset($result)) {
		$message = "logged into the system";
		add_to_log($message, $admin_id);

		// get other details
		$a = getBrowserAndOs();
		$a = json_decode($a);

		$browser = $a->browser;
		$operatingSystem = $a->operatingSystem;
		$refferer = $a->refferer;

		// insert into login details table
		$SQL = "
			INSERT INTO `jspence_admin_login_details`(`login_details_id`, `login_details_admin_id`, `admin_device`, `admin_os`, `admin_refferer`, `admin_browser`, `admin_ip`, `createdAt`) 
			VALUE (?, ?, ?, ?, ?, ?, ?, ?)
		";
		$statement = $conn->prepare($SQL);
		$statement->execute([
			guidv4(), 
			$admin_id, 
			getDeviceType(), 
			$operatingSystem, 
			$refferer, 
			$browser, 
			getIPAddress(),
			date("Y-m-d H:i:s")
		]);

		$_SESSION['last_activity'] = time();
		$_SESSION['flash_success'] = 'You are now logged in!';
		redirect(PROOT . 'index');
	}
}

function admin_is_logged_in() {
	if (isset($_SESSION['JSAdmin']) && $_SESSION['JSAdmin'] > 0) {
		return true;
	}
	return false;
}

// Redirect admin if !logged in
function admin_login_redirect($url = 'auth/login') {
	$_SESSION['flash_error'] = 'You must be logged in to access that page.';
	redirect(PROOT . $url);
}

// Redirect admin if do not have permission
function admin_permission_redirect($url = 'index') {
	$_SESSION['flash_error'] = 'You do not have permission in to access that page.';
	redirect(PROOT . $url);
}

function admin_has_permission($permission = 'admin') {
	global $admin_data;
	$permissions = explode(',', $admin_data['admin_permissions']);
	if (in_array($permission, $permissions, true)) {
		return true;
	}
	return false;
}





////////////////////////////////////////////////////////////////////////////////////////////////////

// GET ALL ADMINS
function get_all_admins() {
	global $conn;
	global $admin_data;
	$output = '';

	$query = "
		SELECT * FROM jspence_admin 
		WHERE admin_status = ?
	";
	$statement = $conn->prepare($query);
	$statement->execute([0]);
	$result = $statement->fetchAll();

	foreach ($result as $row) {
		$admin_last_login = $row["admin_last_login"];
		if ($admin_last_login == NULL) {
			$admin_last_login = '<span class="text-secondary">Never</span>';
		} else {
			$admin_last_login = pretty_date($admin_last_login);
		}
		$output .= '
			<tr>
				<td>
		';
					
		if ($row['admin_id'] != $admin_data['admin_id']) {
			$output .= '
				<a href="' . PROOT . 'acc/admins?delete='.$row["admin_id"].'" class="btn btn-sm btn-light"><span class="material-symbols-outlined">delete</span></a>
			';
		}

		$output .= '
				</td>
				<td>
					<div class="d-flex align-items-center">
                        <div class="avatar">
                          <img class="avatar-img" src="' . PROOT . (($row["admin_profile"] != NULL) ? $row["admin_profile"] : 'assets/media/avatar.png') . '" alt="..." />
                        </div>
                        <div class="ms-4">
                          <div>' . ucwords($row["admin_fullname"]) . '</div>
                          <div class="fs-sm text-body-secondary">
                            <a class="text-reset" href="mailto:' . $row["admin_email"] . '">' . $row["admin_email"] . '</a>
                          </div>
                        </div>
                      </div>
				<td>' . strtoupper($row["admin_permissions"]) . '</td>
				<td><a class="text-muted" href="tel:' . $row["admin_phone"] . '">' . $row["admin_phone"] . '</a></td>
				<td>' . pretty_date($row["admin_joined_date"]) . '</td>
				<td>' . $admin_last_login . '</td>
			</tr>
		';
	}
	return $output;
}

// GET ADMIN PROFILE DETAILS
function get_admin_profile($id) {
	global $conn;
	$output = '';

	$query = "
		SELECT * FROM jspence_admin 
		WHERE admin_id = ? 
		AND admin_status = ? 
		LIMIT 1
	";
	$statement = $conn->prepare($query);
	$statement->execute([$id, 0]);
	$rows = $statement->fetchAll();
	$row = $rows[0];

	$output = '
		<div class="row align-items-center">
			<div class="col-auto">
				<div class="avatar avatar-xl">
					<img class="avatar-img" src="' . PROOT .  (($row["admin_profile"] == NULL) ? 'assets/media/avatar.png' : $row["admin_profile"]) . '" alt="..." />
				</div>
			</div>
			<div class="col">
				<h2 class="fs-5 mb-0"> ' . ucwords($row["admin_fullname"]) . ' </h2>
				<div class="text-body-secondary"> ' . ucwords($row['admin_permissions']) . ' </div>
			</div>
		</div>
		<hr />
		<div class="mb-4">
			<div class="form-label">Bio</div>
			<div>
				Hi! I\'m ...
			</div>
		</div>
		<div class="mb-4">
			<div class="form-label">Email</div>
			<a href="mailto:michael.johnson@company.com" class="text-body"> ' . $row["admin_email"] . ' </a>
		</div>
		<div class="mb-4">
			<div class="form-label">Phone</div>
			<a href="tel:+1234567890" class="text-body"> ' . $row["admin_phone"] . ' </a>
		</div>

		<div class="card border-transparent">
			<div class="card-body py-0">
				<ul class="list-group list-group-flush">
					<li class="list-group-item px-0">
						<div class="row align-items-center">
							<div class="col-auto">
								<span class="material-symbols-outlined text-body-tertiary">credit_card</span>
							</div>
							<div class="col">Joined at <small class="text-body-secondary ms-1">(' . pretty_date($row["admin_joined_date"]) . ')</small></div>
						</div>
					</li>
					<li class="list-group-item px-0">
						<div class="row align-items-center">
							<div class="col-auto">
								<span class="material-symbols-outlined text-body-tertiary">credit_card</span>
							</div>
							<div class="col">Last login <small class="text-body-secondary ms-1">(' . pretty_date($row["admin_last_login"]) . ')</small></div>
							<div class="col-auto">
								<span class="badge bg-success-subtle text-success">' . date("F j, Y, g:i a") . '</span>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	';

	return $output;
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////


function send_vericode($email) {
	global $conn;
    $success = false;
    $user = findUserByEmail($email);

    if($user) {
      	$vericode = md5(time());
      	$sql = "
      		UPDATE garypie_user 
      		SET user_vericode = :user_vericode 
      		WHERE user_id = :user_id
      	";
      	$statement = $conn->prepare($sql);
      	$result = $statement->execute([
      		':user_vericode' => $vericode,
      		':user_id' => $user['user_id']
      	]);
      	if ($result) {
        	$fn = ucwords($user['user_fullname']);
        	$to = $email;
         	$subject = "Please Verify Your Account";
			$body = "
				<h3>
					{$fn},</h3>
					<p>
						Thank you for regestering. Please verify your account by clicking 
          				<a href=\"https://garypie/garypie/verify/{$vericode}\" target=\"_blank\">here</a>.
        		</p>
			";

			$mail = send_email($fn, $to, $subject, $body);
			if ($mail) {
				$success = 'Message has been sent';
			} else {
			    return "Message could not be sent.";
			    //return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
      	}
    }
    return $success;
 }
