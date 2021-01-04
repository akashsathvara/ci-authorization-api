<?php
ob_start();
require_once('../includes/config.inc.php');
require_once MODEL_PATH . 'eventUsers.model.php';

$model_eventUser = new ModeleventUsers();

$action = $_POST['action'];
//--------------------------- Login user ---------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == "userlogin") {
	if (isset($_POST['email_id']) && isset($_POST['password']) && !empty($_POST['email_id'] && !empty($_POST['password']))) {
		$user  = $model_eventUser->wd_get_eventLoginUser($_POST);
		if ($user) {
			//$getphoto = SITE_URL . "user_uploads/" . $user[0]->photo;
			$resultForm[] = array("user_id" => $user[0]->user_id, "fname" => $user[0]->fname, "lname" => $user[0]->lname, "mobile" => $user[0]->mobile, "email_id" => $user[0]->email_id, "register_date" => $user[0]->register_date);
			$json = array("status" => 200,"error" => false, "data" => $resultForm, "message" => "success");
		} else {
			$json = array("status" => 200,"error" => true, "message" => "Login failed.");
		}
	} else {
		$json = array("status" => 500,"error" => true, "message" => "Missing parameters.");
	}
	echo json_encode($json);
	exit;
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == "getUserdetail") {
	// --------------------------- get user data ---------------------------
	if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
		$user  = $model_eventUser->wd_getUserdata($_POST['user_id']);
		if ($user) {
			//$getphoto = SITE_URL . "user_uploads/" . $user[0]->photo;
			$resultForm[] = array("user_id" => $user[0]->user_id, "fname" => $user[0]->fname, "lname" => $user[0]->lname, "mobile" => $user[0]->mobile, "email_id" => $user[0]->email_id, "register_date" => $user[0]->register_date);
			$json = array("status" => 200,"error" => false, "data" => $resultForm, "message" => "success");
		} else {
			$json = array("status" => 200,"error" => true, "message" => "Invalid data.");
		}
	} else {
		$json = array("status" => 500,"error" => true, "message" => "Missing parameters.");
	}
	echo json_encode($json);
	exit;
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == "getEmailexistdata") {
	// --------------------------- get user data ---------------------------
	if (isset($_POST['email_id']) && !empty($_POST['email_id'])) {
		$user  = $model_eventUser->wd_chkExistUserEmail($_POST['email_id']);
		if ($user) {
			//$getphoto = SITE_URL . "user_uploads/" . $user[0]->photo;
			$resultForm[] = array("user_id" => $user[0]->user_id, "email_id" => $user[0]->email_id);
			$json = array("status" => 200,"error" => false, "data" => $resultForm, "message" => "success");
		} else {
			$json = array("status" => 200,"error" => true, "message" => "Data not exist.");
		}
	} else {
		$json = array("status" => 500,"error" => true, "message" => "Missing parameters.");
	}
	echo json_encode($json);
	exit;
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == "insertUserdata") {
	// --------------------------- Insert user data ---------------------------
	if (isset($_POST['email_id']) && !empty($_POST['email_id']) && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['mobile']) && !empty($_POST['mobile'])) {
		$email_id = trim($_POST['email_id']);
		$user  = $model_eventUser->wd_chkExistUserEmail($email_id);
		if ($user) {
			$json = array("status" => 0, "message" => "Data already exist.");
		}else{
			$res = $model_eventUser->wd_addUser($_POST);
			if ($res > 0) {
				$resultForm[] = array("user_id" => $res , "fname" => $_POST['fname'], "lname" => $_POST['lname'], "mobile" => $_POST['mobile'], "email_id" => $_POST['email_id']);
				//$json = array("status" => 1, "message" => "success", "data" => $resultForm);
				$json = array("status" => 200,"error" => false, "data" => $resultForm, "message" => "success");
			} else {
				$json = array("status" => 200,"error" => true, "message" => "Data not exist.");
			}
		}
	} else {
		$json = array("status" => 500,"error" => true, "message" => "Missing parameters.");
	}
	echo json_encode($json);
	exit;
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == "updateUserdata") {
	// --------------------------- Update user data ---------------------------
	if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
		$user  = $model_eventUser->wd_getUserdata($_POST['user_id']);
		if($user){
			$res = $model_eventUser->wd_updateUser($_POST);
			if ($res > 0) {
				$resultForm[] = array("user_id" => $user[0]->user_id, "fname" => $user[0]->fname, "lname" => $user[0]->lname, "mobile" => $user[0]->mobile, "email_id" => $user[0]->email_id, "register_date" => $user[0]->register_date);
				$json = array("status" => 200,"error" => false, "data" => $resultForm, "message" => "success");
			} else {
				$json = array("status" => 200,"error" => true, "message" => "Somthing goes wrong.");
			}
		}else{
			$json = array("status" => 200,"error" => true, "message" => "Data not exist.");
		}
	} else {
		$json = array("status" => 500,"error" => true, "message" => "Missing parameters.");
	}
	echo json_encode($json);
	exit;
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == "changeUserpassword") {
	// --------------------------- Update user password ---------------------------
	if (isset($_POST['user_id']) && !empty($_POST['user_id']) && isset($_POST['old_password']) && !empty($_POST['old_password']) && isset($_POST['new_password']) && !empty($_POST['new_password'])) {
		$res = $model_eventUser->wd_userChangepassword($_POST['user_id'],$_POST['old_password'],$_POST['new_password']);
		if ($res > 0) {
			$json = array("status" => 200,"error" => false, "data" => array(), "message" => "success");
		} else {
			$json = array("status" => 200,"error" => true, "message" => "Somthing goes wrong.");
		}
	} else {
		$json = array("status" => 500,"error" => true, "message" => "Missing parameters.");
	}
	echo json_encode($json);
	exit;
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == "deleteUserdata") {
	// --------------------------- Delete user data ---------------------------
	if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
		$res = $model_eventUser->wd_UserDelete($_POST['user_id']);
		if ($res) {
			$json = array("status" => 200,"error" => false, "data" => array(), "message" => "success");
		} else {
			$json = array("status" => 200,"error" => true, "message" => "Somthing goes wrong.");
		}
	} else {
		$json = array("status" => 500,"error" => true, "message" => "Missing parameters.");
	}
	echo json_encode($json);
	exit;
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == "userForgotpassword") {
	// --------------------------- Delete user data ---------------------------
	if (isset($_POST['email_id']) && !empty($_POST['email_id'])) {
		$email_id = $_POST['email_id'];
		$user  = $model_eventUser->wd_chkExistUserEmail($_POST['email_id']);
		$code = '12345';//rand(10000,99999);
		if ($user) {
			// update verify code in user
			$res = $model_eventUser->wd_updateUser_verifycode($code,$email_id);			
			/*require '../PHPMailer-master/PHPMailerAutoload.php';
			try {
			    $mail = new PHPMailer;
			    $mail->isSMTP();                                      // Set mailer to use SMTP
			    $mail->Host = 'mail.itechgaints.com';                       // Specify main and backup server
			    $mail->SMTPAuth = true;                               // Enable SMTP authentication
			    $mail->Username = 'xxx@xxx.com';                   // SMTP username
			    $mail->Password = 'xx@123';               // SMTP password
			                // Enable encryption, 'ssl' also accepted
			    $mail->Port = 25;                                    //Set the SMTP port number - 587 for authenticated TLS
			    $mail->setFrom('xx@xx.com', 'CBLT.in');     //Set who the message is to be sent from
			    $mail->addAddress('xx@xx.in', 'xxx xx');  // Add a recipient
			    $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
			    $mail->isHTML(true);                                  // Set email format to HTML

			    $mail->Subject = 'Virification Code | Wedding Planner';
			    $mail->Body    = 'Hello User,<br> <b>Your Verification code : </b>'.$code.'<br><b>Please verify this code. <a href="#">Verify Here..</a><br><br>Thanks.';
			    $mail->send();
			    //echo 'Message has been sent';
			} catch (Exception $e) {
			    //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}*/
			$json = array("status" => 200,"error" => false, "data" => array(), "message" => "Email verify code sent");
		} else {
			$json = array("status" => 200,"error" => true, "message" => "Data not exist.");
		}
	} else {
		$json = array("status" => 500,"error" => true, "message" => "Missing parameters.");
	}
	echo json_encode($json);
	exit;
}elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == "verifyUserdata") {
	// --------------------------- get user data ---------------------------
	if (isset($_POST['email_id']) && !empty($_POST['email_id']) && isset($_POST['sentcode']) && !empty($_POST['sentcode'])) {
		$email_id = trim($_POST['email_id']);
		$code = trim($_POST['sentcode']);
		$user  = $model_eventUser->wd_verifyUser($email_id,$code);
		if ($user) {
			// send temp password on register email
			$json = array("status" => 200,"error" => false, "data" => array(), "message" => "success");
		} else {
			$json = array("status" => 200,"error" => true, "message" => "Invalid data.");
		}
	} else {
		$json = array("status" => 500,"error" => true, "message" => "Missing parameters.");
	}
	echo json_encode($json);
	exit;
} else {
	$json = array("status" => 500,"error" => true, "message" => "Bad request!");
}
echo json_encode($json);
exit;
?>
