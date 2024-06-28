<?php 

function dnd($data) {
	echo "<pre>";
	print_r($data);
	echo "</pre>";
    die;
}

// Make Date Readable
function pretty_date($date){
	return date("M d, Y h:i A", strtotime($date));
}

// Display money in a readable way
function money($number) {
	return '₵' . number_format($number, 2);
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

function goBack() {
	$previous = "javascript:history.go(-1)";
	if (isset($_SERVER['HTTP_REFERER'])) {
	    $previous = $_SERVER['HTTP_REFERER'];
	}
	return $previous;
}





////////////////////////////////////////////////////////////////////////////////////////////////////////


function get_header_information() {
	global $conn;
	$siteQuery = "
	    SELECT * FROM garypie_about 
	    LIMIT 1
	";
	$statement = $conn->prepare($siteQuery);
	$statement->execute();
	$site_result = $statement->fetchAll();

	foreach ($site_result as $site_row) {
	    $phone_1 = $site_row["about_phone"];
	}
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$actual_linkBreakDown = explode('/', $actual_link);
	$actual_linkLast = end($actual_linkBreakDown);

	$output = '';
	if ($actual_linkLast != 'signin' && $actual_linkLast != 'signin.php') {

		$output .= '
		<div class="header-eyebrow bg-dark">
		<div class="container">
		<div class="navbar navbar-dark row">
		<div class="col">
		<ul class="navbar-nav mr-auto">
		<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="javascript:;" id="curency" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		USD
		</a>
		<ul class="dropdown-menu" aria-labelledby="curency">
		<li><a class="dropdown-item" href="javascript:;">EUR</a></li>
		<li><a class="dropdown-item" href="javascript:;">RUB</a></li>
		</ul>
		</li>
		<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="#!" id="language" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		EN
		</a>
		<ul class="dropdown-menu" aria-labelledby="language">
		<li><a class="dropdown-item" href="#!">Deutsch</a></li>
		<li><a class="dropdown-item" href="#!">Russian</a></li>
		<li><a class="dropdown-item" href="#!">French</a></li>
		</ul>
		</li>
		</ul>
		</div>
		<div class="col text-right">
		<span class="phone text-white">'.$phone_1.'</span>
		</div>
		</div>
		</div>
		</div>
		';

	} else {
		$output = '';
	}
	return $output;
}

function getTitle() {
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$actual_linkBreakDown = explode('/', $actual_link);
	$actual_linkLast = end($actual_linkBreakDown);

	$output = '';
	if ($actual_linkLast == 'signin' || $actual_linkLast == 'signin.php') {
		$output = 'Sigin · ';
	} elseif ($actual_linkLast == 'index.php' || $actual_linkLast == 'index' || $actual_linkLast == '') {
		$output = 'Welcome · ';
	} elseif ($actual_linkLast == 'products' || $actual_linkLast == 'products.php') {
		$output = 'Products · ';
	} elseif ($actual_linkLast == 'profile' || $actual_linkLast == 'profile.php') {
		$output = 'Profile · ';
	} elseif ($actual_linkLast == 'yourpassword' || $actual_linkLast == 'yourpassword.php') {
		$output = 'My Password · ';
	} elseif ($actual_linkLast == 'youraddress' || $actual_linkLast == 'youraddress.php') {
		$output = 'My Address · ';
	} elseif ($actual_linkLast == 'yourorders' || $actual_linkLast == 'yourorders.php') {
		$output = 'My Orders · ';
	} elseif ($actual_linkLast == 'cart' || $actual_linkLast == 'cart.php') {
		$output = 'My Cart · ';
	} elseif ($actual_linkLast == 'forgotPassword' || $actual_linkLast == 'forgotPassword.php') {
		$output = 'Forgot Password · ';
	} elseif ($actual_linkLast == 'verify' || $actual_linkLast == 'verify.php') {
		$output = 'Verify Account · ';
	} elseif ($actual_linkLast == 'verified' || $actual_linkLast == 'verified.php') {
		$output = 'Account on Verification · ';
	} elseif ($actual_linkLast == 'contact-us' || $actual_linkLast == 'contact-us.php') {
		$output = 'Contact Us · ';
	} elseif ($actual_linkLast == 'resendVericode' || $actual_linkLast == 'resendVericode.php') {
		$output = 'Resend Verification Code · ';
	} elseif ($actual_linkLast == 'resetPassword' || $actual_linkLast == 'resetPassword.php') {
		$output = 'Reset Password · ';
	} elseif ($actual_linkLast == 'thankYou' || $actual_linkLast == 'thankYou.php') {
		$output = 'Thank You · ';
	}
	return $output;
}








/////////////////////////////////////////////////////////////////////////////////////////////////




// Sessions For login
function adminLogin($admin_id) {
	$_SESSION['JSAdmin'] = $admin_id;
	global $conn;

	$data = array(date("Y-m-d H:i:s A"), $admin_id);
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

		$_SESSION['flash_success'] = 'You are now logged in!';
		redirect(PROOT . 'index');
	}
}

function admin_is_logged_in(){
	if (isset($_SESSION['JSAdmin']) && $_SESSION['JSAdmin'] > 0) {
		return true;
	}
	return false;
}

// Redirect admin if !logged in
function admn_login_redirect($url = 'index') {
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
	$permissions = explode(',', $admin_data[0]['admin_permissions']);
	if (in_array($permission, $permissions, true)) {
		return true;
	}
	return false;
}







/////////////////////////////////////////////////////////////////////////////////////////////////////////



// GET PRODUCT CATEGORY
function get_category($category_id) {
	global $conn;
	$output = '';

	$query = "
		SELECT * FROM garypie_category 
		WHERE category_id = :category_id
		AND category_trash = :category_trash 
		LIMIT 1
	";
	$statement = $conn->prepare($query);
	$statement->execute(
		[
			':category_id' 	=> $category_id,
			':category_trash' 	=> 0
		]
	);
	$result = $statement->fetchAll();
	foreach ($result as $row) 
		return $row['category'];
}

function get_entire_category($child_id){
	global $conn;
	$id = sanitize($child_id);
	$sql = "
	

		SELECT p.category_parent AS 'pid', p.category AS 'parent', c.category_id AS 'cid', c.category AS 'child'
		FROM garypie_category c
		INNER JOIN garypie_category p
		ON c.category_parent = p.category_id
		WHERE c.category_id = '$id'";

	$statement = $conn->query($sql);
	$categories = $statement->fetchAll();
	foreach ($categories as $category) {
		// code...
	return $category;
	}
}


	// GET PRODUCT CATEGORY
function get_product_category($product_category) {
	global $conn;
	$output = '';

	$query = "
		SELECT * FROM garypie_category 
		WHERE category_trash = :category_trash
	";
	$statement = $conn->prepare($query);
	$statement->execute(
		[
			':category_trash' 	=> 0
		]
	);
	$result = $statement->fetchAll();
	$count_category = $statement->rowCount();
	if ($count_category > 0) {
		foreach ($result as $row) {
			$output .= '
				<option value="'.$row['category_id']. '" '.(($product_category == $row['category_id'])? "selected" : "").'>'.ucwords($row['category']).'</option>
			';
		}
	} else {
		$output = '<option value="">No category found.</option>';
	}
	return $output;
}

// GET ALL PRODUCTS WHERE TRASH = 0
function  get_all_product($product_trash = '') {
	global $conn;
	$output = '';

	$query = "
		SELECT * FROM garypie_product 
		INNER JOIN garypie_category
		ON garypie_category.category_id = garypie_product.product_category
		LEFT JOIN garypie_admin
		ON garypie_admin.admin_id = garypie_product.product_added_by
		WHERE garypie_product.product_trash = :product_trash
		AND garypie_category.category_trash = :category_trash
		ORDER BY garypie_product.id DESC
	";
	$statement = $conn->prepare($query);
	$statement->execute([
		':product_trash' 	=> $product_trash,
		':category_trash' 	=> 0
	]);
	$count_products = $statement->rowCount();
	$result = $statement->fetchAll();

	if ($count_products > 0) {
		$i = 1;
		foreach ($result as $key => $row) {
			$output .= '
				<tr>
					<td>'.$i.'</td>
					<td>'.ucwords($row["product_name"]).'</td>
					<td>'.ucwords($row["category"]).'</td>
					<td>'.money($row["product_price"]).'</td>
					<td>'.$row["product_sizes"].'</td>
					<td>'.ucwords($row["admin_fullname"]).'</td>
					<td>'.pretty_date($row["product_added_date"]).'</td>
				';
				if ($product_trash == 0) {
					$output .= '
						<td>
							<a href="'.PROOT.'gpmin/products?featured='.(($row['product_featured'] == 0)?"1":"0").'&id='.$row["product_id"].'" class="btn btn-sm btn-light">
								<span data-feather="'.(($row['product_featured'] == 0)?"plus":"minus").'"></span> '.(($row['product_featured'] == 0)?"":"Featured product").'
							</a>
						</td>
						<td>
					';
				} else {
					$output .= '
						<td>
						</td>
						<td>
					';
				}
				if ($product_trash == 1) {
					$output .= '
						<a href="'.PROOT.'gpmin/products?permanent_delete='.$row["product_id"].'&upload_product_image_name='.$row["product_image"].'" class="btn btn-sm btn-outline-primary"><span data-feather="trash"></span></a>&nbsp;
                          <a href="'.PROOT.'gpmin/products?restore='.$row["product_id"].'" class="btn btn-sm btn-outline-danger"><span data-feather="refresh-cw"></span></a>&nbsp;
					';
				} else {
					$output .= '
							<a href="'.PROOT.'gpmin/products?edit='.$row["product_id"].'" class="btn btn-sm btn-info"><span data-feather="edit-2"></span></a>
							<a href="'.PROOT.'gpmin/products?delete='.$row["product_id"].'" class="btn btn-sm btn-secondary"><span data-feather="trash-2"></span></a>
						';
				}
				$output .= '
						</td>
					</tr>
				';
			$i++;
		}
	} else {
		$output = '
			<tr>
				<td colspan="9">No products found in the database...</h3></td>
			</tr>
		';
	}
	return $output;
}

// GET ALL CATEGORIES
function get_all_category($category_trash = 0) {
	global $conn;
	$output = '';

	$query = "
		SELECT * FROM garypie_category 
		WHERE category_parent = :category_parent 
		AND category_trash = :category_trash 
		ORDER BY category_id DESC
	";
	$statement = $conn->prepare($query);
	$statement->execute([
		':category_parent' => 0,
		':category_trash' => $category_trash
	]);
	$result = $statement->fetchAll();
	$count_row = $statement->rowCount();

	if ($count_row > 0) {
		foreach ($result as $row) {
			$parent_id = (int)$row["category_id"];
			$child = $conn->query("
				SELECT * FROM garypie_category 
				WHERE category_parent = {$parent_id}
			")->fetchAll();


			$output .= '
				<tr class="bg-info">
					<td>
						<a href="' . PROOT . 'gpmin/category/'.$row["category_id"].'" class="btn btn-sm btn-secondary"><i data-feather="edit"></i></a>
					</td>
					<td>'.ucwords($row["category"]).'</td>
					<td>Parent</td>
					<td>'.pretty_date($row["category_added_date"]).'</td>
					<td>
						<span id="'.$row["category_id"].'" onclick="perm_delete_category(category_id = '.$row["category_id"].');" class="btn btn-sm btn-light"><i data-feather="trash-2"></i></span>
					</td>
				</tr>
			';
			foreach ($child as $child_row) {
				// code...
				$output .= '

				<tr class="bg-light">
					<td>
						<a href="' . PROOT . 'gpmin/category/'.$child_row["category_id"].'" class="btn btn-sm btn-secondary"><i data-feather="edit"></i></a>
					</td>
					<td>'.ucwords($row["category"]).'</td>
					<td>'.ucwords($child_row["category"]).'</td>
					<td>'.pretty_date($child_row["category_added_date"]).'</td>
					<td>
						<span id="'.$child_row["category_id"].'" onclick="temp_delete_category(category_id = '.$child_row["category_id"].');" class="btn btn-sm btn-light">Delete</span>
						</td>
					</tr>
				';
			}
		}
	} else {
		$output = '
			<tr>
				<td colspan="4">No category found.</td>
			</tr>
		';
	}
	return $output;
}


// GET ALL CATEGORIES
function get_all_faqs() {
	global $conn;
	$output = '';

	$query = "
		SELECT * FROM garypie_faq 
		ORDER BY id DESC
	";
	$statement = $conn->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$count_row = $statement->rowCount();

	if ($count_row > 0) {
		foreach ($result as $row) {
			$parent_id = (int)$row["id"];
			$child = $conn->query("
				SELECT * FROM garypie_faq_details 
				WHERE faq_parent = {$parent_id}
			")->fetchAll();


			$output .= '
				<tr class="bg-warning">
					<td>
						<a href="admin.faq?edit-parent='.$row["id"].'" class="btn btn-sm btn-secondary"><i data-feather="edit"></i></a>
					</td>
					<td>'.ucwords($row["faq_heading"]).'</td>
					<td>Parent</td>
					<td>'.pretty_date($row["faq_added_date"]).'</td>
					<td>
						<span id="'.$row["id"].'" onclick="delete_faq_all(id = '.$row["id"].');" class="btn btn-sm btn-light"><i data-feather="trash-2"></i></span>
					</td>
				</tr>
			';
			foreach ($child as $child_row) {
				// code...
				$output .= '

				<tr class="bg-light">
					<td>
						<a href="admin.faq?edit='.$child_row["id"].'&parent='.$row['id'].'" class="btn btn-sm btn-secondary"><i data-feather="edit"></i></a>
					</td>
					<td>'.ucwords($row["faq_heading"]).'</td>
					<td>'.ucwords($child_row["faq_question"]).'</td>
					<td>'.ucwords($child_row["faq_answer"]).'</td>
					<td>'.pretty_date($child_row["faq_added_date"]).'</td>
					<td>
						<span id="'.$child_row["faq_parent"].'" onclick="delete_faq(id = '.$child_row["id"].');" class="btn btn-sm btn-light">Delete</span>
						</td>
					</tr>
				';
			}
		}
	} else {
		$output = '
			<tr>
				<td colspan="4">No category found.</td>
			</tr>
		';
	}
	return $output;
}

// CHECK IF FAQ EXISTS
function faq_exist($id) {
	global $conn;

	$query = "
        SELECT * FROM garypie_faq_details 
        INNER JOIN garypie_faq 
        ON garypie_faq.id = garypie_faq_details.faq_parent
        WHERE garypie_faq_details.id = :id 
        LIMIT 1
    ";
    $statement = $conn->prepare($query);
    $statement->execute([':id' => $id]);

    $arr['counting'] = $statement->rowCount();;
    $arr['row'] = $statement->fetchAll();

    return $arr;
}



function low_inventory_access() {
	global $conn;
	$inventoryQ = "
        SELECT * FROM garypie_product 
        WHERE product_trash = ?
    ";
    $statement = $conn->prepare($inventoryQ);
    $statement->execute([0]);
    $inventory_result = $statement->fetchAll();
    $lowItems = array();
    foreach ($inventory_result as $inventory_row) {
    	$item = array();
    	$sizes = sizesToArray($inventory_row['product_sizes']);
    	foreach ($sizes as $size) {
    		// code...
    		if ($size['quantity'] <= $size['threshold']) {
    			// code...
		    	$cat = get_entire_category($inventory_row['product_category']);
		    	$item = array(
		    		'title' => $inventory_row['product_name'],
		    		'size' => $size['size'],
		    		'quantity' => $size['quantity'],
		    		'threshold' => $size['threshold'],
		    		'category' => $cat['parent'] . ' ~ ' . $cat['child']
		    	);
	        	$lowItems[] = $item;
	    	}
    	}
         
        
    }
    return $lowItems;
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
					
		if ($row['admin_id'] != $admin_data[0]['admin_id']) {
			$output .= '
				<a href="' . PROOT . 'acc/admins?delete='.$row["admin_id"].'" class="btn btn-sm btn-light"><i class="bi bi-trash3"></i></a>
			';
		}

		$output .= '
				</td>
				<td>' . ucwords($row["admin_fullname"]) . '</td>
				<td>' . $row["admin_email"] . '</td>
				<td>' . pretty_date($row["admin_joined_date"]) . '</td>
				<td>' . $admin_last_login . '</td>
				<td>' . strtoupper($row["admin_permissions"]) . '</td>
			</tr>
		';
	}
	return $output;
}

// GET ADMIN PROFILE DETAILS
function get_admin_profile() {
	global $conn;
	global $admin_data;
	$output = '';

	$query = "
		SELECT * FROM jspence_admin 
		WHERE admin_status = ? 
		LIMIT 1
	";
	$statement = $conn->prepare($query);
	$statement->execute([0]);
	$row = $statement->fetchAll();

	if ($row[0]['admin_id'] == $admin_data[0]['admin_id']) {
		$output = '
			<li class="list-group-item" style="padding: 0.1rem 1rem;">
		  		<small class="text-muted">Name,</small>
		  		<p>' . ucwords($row[0]["admin_fullname"]) . '</p>
		  	</li>
			<li class="list-group-item" style="padding: 0.1rem 1rem;">
		  		<small class="text-muted">Email,</small>
		  		<p>' . $row[0]["admin_email"] . '</p>
		  	</li>
			<li class="list-group-item" style="padding: 0.1rem 1rem;">
		  		<small class="text-muted">Joined Date,</small>
		  		<p>' . pretty_date($row[0]["admin_joined_date"]) . '</p>
		  	</li>
			<li class="list-group-item" style="padding: 0.1rem 1rem;">
		  		<small class="text-muted">Last Login,</small>
		  		<p>' . pretty_date($row[0]["admin_last_login"]) . '</p>
		  	</li>
		';
	}
	
	return $output;
}

// LIST * USERS
function get_all_users($user_trash = 0) {
	global $conn;

	$query = "
		SELECT * FROM garypie_user
		WHERE user_trash = :user_trash
	";
	$statement = $conn->prepare($query);
	$statement->execute([':user_trash' => $user_trash]);
	$result = $statement->fetchAll();
	$row_count = $statement->rowCount();

	$output = '';
	if ($row_count > 0) {

		$i = 1;
		foreach ($result as $row) {
			$user_last_login = $row["user_last_login"];
			if ($user_last_login == NULL) {
				$user_last_login = '<span class="text-secondary">Never</span>';
			} else {
				$user_last_login = pretty_date($user_last_login);
			}

			$output .= '
				<td>'.$i.'</td>
				<td>'.ucwords($row["user_fullname"]).'</td>
				<td>'.$row["user_email"].'</td>
				<td>'.(($row["user_phone"] != '')?$row["user_phone"]:'<span class="text-secondary">Empty</span>').'</td>
				<td>'.(($row["user_address"] != '')?ucwords($row["user_address"]):'<span class="text-secondary">Empty</span>').'</td>
				<td>'.pretty_date($row["user_joined_date"]).'</td>
				<td>'.$user_last_login.'</td>
				<td>
					<a href="users/'.$row["user_id"].'" class="btn btn-sm btn-light"><span data-feather="eye"></span></a>&nbsp;
			';
			if ($user_trash == 1) {
				$output .= '
					<a href="users?restore='.$row["user_id"].'" class="btn btn-sm btn-secondary"><span data-feather="refresh-ccw"></span></a>&nbsp;
					<a href="users?delete='.$row["user_id"].'" class="btn btn-sm btn-warning"><span data-feather="trash"></span></a>&nbsp;
				';
			} else {
				$output .= '
					<a href="users?terminate='.$row["user_id"].'" class="btn btn-sm btn-secondary"><span data-feather="user-x"></span></a>&nbsp;
				';
			}
			$output .= '
					</td>
				</tr>
			';
			$i++;
		}
	} else {
		$output = '
			<tr>
				<td colspan="8"> - No data found under users table.</td>
			</tr>
		';
	}
	return $output;
}

// LIST * USERS
function subscribed_emails() {
	global $conn;

	$query = "
		SELECT * FROM garypie_subscription 
		ORDER BY subscription_id DESC
	";
	$statement = $conn->prepare($query);
	$statement->execute();
	$row_count = $statement->rowCount();
	$result = $statement->fetchAll();

	$output = '';
	$i = 1;
	if ($row_count > 0) {
		foreach ($result as $row) {
			$output .= '
				<tr>
					<td>'.$i.'</td>
					<td>'.$row["subscription_email"].'</td>
					<td>'.pretty_date($row["subscription_date"]).'</td>
				</tr>
			';
			$i++;
		}
	} else {
		$output = '
			<tr>
				<td colspan="3">No emails under subscription table.</td>
			</tr>
		';
	}
	return $output;
}

// CHECK IF USER EXISTS
function user_exist($user_id) {
	global $conn;

	$query = "
        SELECT * FROM garypie_user 
        WHERE user_id = :user_id 
        LIMIT 1
    ";
    $statement = $conn->prepare($query);
    $statement->execute([':user_id' => $user_id]);
    $count_row = $statement->rowCount();
    return $count_row;
}


// CHECK IF USER EXISTS
function category_exist($category_id) {
	global $conn;

	$query = "
        SELECT * FROM garypie_category 
        WHERE category_id = :category_id 
        LIMIT 1
    ";
    $statement = $conn->prepare($query);
    $statement->execute([':category_id' => $category_id]);
    $count_row = $statement->rowCount();
    $row = $statement->fetchAll();
    $arr['row'] = $row;
    $arr['counting'] = $count_row;

    return $arr;
}











//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Get categories
function get_all_categories() {
	global $conn;
	$output = '';

	$query = '
		SELECT * FROM garypie_category 
		WHERE category_parent = :category_parent 
		AND category_trash = :category_trash 
		ORDER BY category ASC
		LIMIT 5
	';
	$statement = $conn->prepare($query);
	$statement->execute([
		':category_parent' 	=> 0,
		':category_trash' 	=> 0
	]);
	$result = $statement->fetchAll();

	foreach ($result as $row) {
		$output .= '
			<a class="nav-link" href="'.PROOT.'store/category/'.$row["category_url"].'">'.ucwords($row["category"]).'</a>
		';
	}
	return $output;
}


// Get featured products
function get_featured_products($featured = 1) {
	global $conn;
	$output = '';

	$query = '
		SELECT * FROM garypie_product 
		INNER JOIN garypie_category 
		ON garypie_category.category_id = garypie_product.product_category 
		INNER JOIN garypie_brand
		ON garypie_brand.brand_id = garypie_product.product_brand 
		WHERE garypie_product.product_trash = 0
	';
	if ($featured == 1) {
		$query .= ' AND garypie_product.product_featured = 1';
	}
	$query .= ' ORDER BY garypie_product.product_name ASC';
	if ($featured == 1) {
		$query .= ' LIMIT 4';
	}

	$statement = $conn->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();

	if ($statement->rowCount() > 0) {
		$n = 0;
		foreach ($result as $row) {
			$product_images = explode(",", $row['product_image']);
			if (count($product_images) == 1) {
				$product_image = '
					<img class="card-img-top card-img" src="' . $row['product_image'] . '" alt="' . $row['category'] . ' image.">
				';
			} else {
				$product_image = '
					<img class="card-img-top card-img-back" src="' . $product_images[0] . '" alt="...">
					<img class="card-img-top card-img-front" src="' . $product_images[1] . '" alt="' . $row['category'] . ' image.">
				';
			}
			
			$output .= '
				<div class="col-6 col-md-3 col-lg">
                	<div class="card mb-7">
                		' . soldOut($row['product_id']) . '
                        <div class="card-img">
                            <a class="card-img-hover" href="' . PROOT . 'store/products/' . $row['product_url'] . '">
                                ' . $product_image . '
                            </a>
                            <div class="card-actions">
                                <span class="card-action">
                                    <button class="btn btn-xs btn-circle btn-white-primary" onclick="detailsmodal(&quot;' . $row['product_id'] . '&quot;)">
                                        <i class="fe fe-eye"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="card-body px-0">
                            <!-- Category -->
                            <div class="fs-xs">
                                <a class="text-muted" href="' . PROOT . 'store/brand/' . $row['brand_url'] . '">' . ucwords($row['brand_name']) . '</a>&nbsp;.&nbsp;<a class="text-muted" href="' . PROOT . 'store/category/' . $row['category_url'] . '">' . ucwords($row['category']) . '</a>
                            </div>
                            <!-- Title -->
                            <div class="fw-bold">
                                <a class="text-body" href="' . PROOT . 'store/products/'.$row['product_url'].'">
                                    ' . ucwords($row["product_name"]) . '
                                </a>
                            </div>
                            <!-- Price -->
                            <div class="fw-bold text-muted">
                                '.money($row["product_price"]).'
                            </div>
                        </div>
                    </div>
                </div>
			';
			$n++;
			if ($n == 4) {
				$output .= '
					<div class="w-100 d-none d-lg-block"></div>
				';
			}
		}
	}
	return $output;
}








///////////////////////////////////////////////////////////////////////////////////////////////////////////

// FIND USER WITH EMAIL
 function findUserByEmail($email) {
 	global $conn;
    $sql = "
    	SELECT * FROM garypie_user 
    	WHERE user_email = :user_email
    ";
    $statement = $conn->prepare($sql);
    $statement->execute([':user_email' => $email]);
    $result = $statement->fetchAll();
    foreach ($result as $row) {
    	return $row;
    }
}

// Sessions For login
function userLogin($user_id) {
	$_SESSION['GPUser'] = $user_id;
	global $conn;
	$data = array(
		':user_last_login' => date("Y-m-d H:i:s"),
		':user_id' => (int)$user_id
	);
	$query = "
		UPDATE garypie_user 
		SET user_last_login = :user_last_login 
		WHERE user_id = :user_id";
	$statement = $conn->prepare($query);
	$result = $statement->execute($data);
	if (isset($result)) {
		$access_token = IPINFO_KEY;
		$client = new IPinfo($access_token);
        $ip = $client->getDetails();
        $ip_address = $ip->ip;

        // $bo = getBrowserAndOs();
        // $browser = $bo['browser'];
        // $os = $bo['operatingSystem'];

		$user_data = $conn->query("SELECT * FROM garypie_user WHERE user_id = '" . $user_id . "' LIMIT 1")->fetchAll();
		$fn = explode(' ', $user_data[0]['user_fullname']);
		$fn = ucwords($fn[0]);
    	$to = $user_data[0]['user_email'];
     	$subject = "Login Notification | Garypie";
		$body = "
			<h3>
			Hello {$fn},</h3>
			<p>
				We detected a recent sign-in to your Garypie Account.
				<br>
				<b>Sign-in details<b>
				<br>
				<b>IP Address</b>: " . $ip_address . "
				<br>
				<b>Date</b>: " . date('m/d/y' .", ". 'h:i:s A') . "
				<br>
				<b>User Agent</b>: " . $_SERVER['HTTP_USER_AGENT'] . "
				<br>
				If you did not initiate this, kindly reset your password and contact us immediately at <a href='mailto:contact@inqoins.io'>hello@garypie.com</a>
				<a href='" . PROOT . "store/forgot-password'>Change password</a>
				<br>
				<br>
				With love,
				<br>
				- Garypie Team.
			</p>
		";
		send_email($fn, $to, $subject, $body);

		$_SESSION['flash_success'] = 'You are now logged in!';
		redirect(PROOT . 'store/index');
	}
}

function user_is_logged_in(){
	if (isset($_SESSION['GPUser']) && $_SESSION['GPUser'] > 0) {
		return true;
	}
	return false;
}

// Redirect admin if !logged in
function user_login_redirect($url = 'login') {
	$_SESSION['flash_error'] = '<div class="text-center" id="temporary" style="margin-top: 60px;">You must be logged in to access that page.</div>';
	header('Location: '.$url);
}


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











////////////////////////////////////////////////////////////////////////////////////

// count products in cart
function count_cart_new() {
	global $conn;
	global $user_data;
	$output = 0;

	$cart_id = '';
 	if (isset($_COOKIE[CART_COOKIE])) {
 		$cart_id = sanitize($_COOKIE[CART_COOKIE]);
 	}

	if (!empty($cart_id)) {
      	$sql = "
        	SELECT * FROM garypie_cart 
        	WHERE cart_id = ?
      	";
      	$statement = $conn->prepare($sql);
      	$statement->execute([$cart_id]);
      	$row = $statement->fetchAll();
      	if ($statement->rowCount() > 0) {
          	$items = json_decode($row[0]['items'], true);
          	$user_idArray = array();
          	foreach ($items as $item) {
          		// code...
				$user_idArray[] = $item['user_id'];
		    	$logged_user = (user_is_logged_in() ? $user_data['user_id'] : '');
		        if ($item['user_id'] == $logged_user && $item['pays'] == 0) {
		        	$output = count($user_idArray);
		        } else {

		        }
          	}
	    }
    }
    if ($output == 0) {
    	$output = '';
    }
	
	return $output;
}

// sizes to array
function sizesToArray($string) {
	$sizesArray = explode(',', $string);
	$returnArray = array();
	foreach ($sizesArray as $size) {
		// code...
		$s = explode(":", $size);
		$returnArray[] = array('size' => $s[0], 'quantity' => $s[1], 'threshold' => $s[2]);
						//Array ( [0] => Array ( [size] => small [quantity] => 11 [threshold] => 2 ) )
	}
	return $returnArray;
}

// 
function sizesToString($sizes) {
	$sizeString = '';
	foreach ($sizes as $size) {
		// code...
		$sizeString .= $size['size'] . ':' . $size['quantity'] . ':' . $size['threshold'] . ',';
	}
	$trimmed = rtrim($sizeString, ',');
	return $trimmed;
}


// get sold out products
function soldOut($product_id) {
	$product_id = sanitize($product_id);
	global $conn;
	$output = '';

	$sql = "
        SELECT * FROM garypie_product 
        WHERE garypie_product.product_id = ? 
        AND garypie_product.product_trash = ? 
        LIMIT 1
    ";
    $statement = $conn->prepare($sql);
    $statement->execute([$product_id, 0]);
    $count_row = $statement->rowCount();
    $row = $statement->fetchAll();
    if ($count_row > 0) {
       $sizeString = $row[0]['product_sizes']; 
       $size_array = explode(',', $sizeString); 

	    foreach ($size_array as $string) { 
	        $string_array = explode(':', $string);
	        $size = $string_array[0];
	        $available = $string_array[1];

	        if ($available > 0) {
		        if ($row[0]['product_featured'] == 0) {
		        	$output = '<div class="badge bg-white text-body card-badge card-badge-start text-uppercase">New</div>';
		        } else {
		        	$output = '<div class="badge bg-warning text-body card-badge card-badge-start text-uppercase">Sale</div>';
		        }
	        } else {
	            $output = '<div class="badge bg-danger text-body card-badge card-badge-start text-uppercase">Sold out</div>';
	        }
		}                   
   }
   return $output;
}

// get all brands
function getall_brands($limit) {
	global $conn;
 	$sql = "
        SELECT * FROM garypie_brand 
        ORDER BY brand_id DESC 
        LIMIT $limit
    ";
    $statement = $conn->prepare($sql);
    $statement->execute();
    return $statement->fetchAll();
}

// get category parent and attach to child to form a unique slug url
function parent_child_url($id) {
	global $conn;

	$sql = "
		SELECT * FROM garypie_category 
		WHERE category_id = ? 
		LIMIT 1
	";
	$statement = $conn->prepare($sql);
	$statement->execute([$id]);
	$row = $statement->fetchAll();

	if ($statement->rowCount() > 0) {
		$parent_name = $row[0]['category'];
		return $parent_name;
	} else {
		return false;
	}
	
}
