<?php
class ModeleventUsers {
	private $model_database;
	private $table_database;

	public function __construct() {
		$this->model_database = new Database();
		$this->table_database = "wdp_users";
		$this->table_database_event = "wdp_eventdetails";
	}
	//User Login Detail
	public function wd_get_eventLoginUser($post) {
		$sql = "SELECT * FROM " . $this->table_database . " WHERE email_id = '" . $post['email_id'] . "' and status = 'active'";
		$sqlQry = $this->model_database->executeSqlQueryGetData($sql);
		$getpassword = 	$sqlQry[0]->password;
		$password = password_verify($post['password'], $getpassword);
		if ($password > 0) {
			return $sqlQry;
		} else {
			return false;
		}
		//return $this->model_database->executeSqlQueryGetData($sql);
	}
	//Get userdata
	public function wd_getUserdata($user_id) {
		$sql = "SELECT * FROM " . $this->table_database . " WHERE user_id = '" . $user_id . "' ";
		return $this->model_database->executeSqlQueryGetData($sql);
	}
	//Insert user Data
	public function wd_addUser($post) {
		$getdate = date("Y-m-d H:i:s");
		$code = $post['password'];
		$password = password_hash($code, PASSWORD_DEFAULT);

		// if ($_FILES["photo"]["name"] != '') {
		// 	$extension = explode('.', $_FILES['photo']['name']);
		// 	$getcategory_image = rand() . '.' . $extension[1];
		// 	$destination = 'assets/user-profile/' . $getcategory_image;
		// 	move_uploaded_file($_FILES['photo']['tmp_name'], $destination);
		// } else {
		// 	$getcategory_image = $post['default_photo'];
		// }

		$fname = $this->model_database->escapeString($post['fname']);
		$lname = $this->model_database->escapeString($post['lname']);

		$sql = "INSERT INTO " . $this->table_database . "(`fname`, `lname`, `mobile`, `email_id`, `password`, `register_date`, `updated_date`) VALUES ('" . $fname . "','" . $lname . "', '".$post['mobile']."', '".$post['email_id']."', '".$password."','" . $getdate . "','" . $getdate . "')";
		$user_id = $this->model_database->executeSqlQuery($sql);

		if (!file_exists('./user_uploads/' . $user_id)) {
			mkdir('./user_uploads/' . $user_id, 0777, true);
		}
		// require '../PHPMailer-master/PHPMailerAutoload.php';
		// try {
		//     $mail = new PHPMailer;
		//     $mail->isSMTP();                                      // Set mailer to use SMTP
		//     $mail->Host = 'mail.itechgaints.com';                       // Specify main and backup server
		//     $mail->SMTPAuth = true;                               // Enable SMTP authentication
		//     $mail->Username = 'xxx@xxx.com';                   // SMTP username
		//     $mail->Password = 'xxx@123';               // SMTP password
		//                 // Enable encryption, 'ssl' also accepted
		//     $mail->Port = 25;                                    //Set the SMTP port number - 587 for authenticated TLS
		//     $mail->setFrom('xxx@xx.com', 'Akash');     //Set who the message is to be sent from
		//     $mail->addAddress('akashxxxx@xxx.com', 'Aks');  // Add a recipient
		//     $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		//     $mail->isHTML(true);                                  // Set email format to HTML

		//     $mail->Subject = 'Welcome to Wedding planner';
		//     $mail->Body    = 'Hello '.$addname.',<br><br>Welcome to Wedding planner<br> Here we are sharing your credentials for Login to Website / Mobile APP. <br><br><b>Username : </b>'.$_POST["email"].'<br><b>Password : </b>'.$code.'<br><br><br>Thanks.';
		//     $mail->send();
		//     //echo 'Message has been sent';
		// } catch (Exception $e) {
		//     //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		// }
		return $user_id;
	}
	//Update CBLT Users Data
	public function wd_updateUser($post) {
		$getdate = date("Y-m-d H:i:s");
		$fname = $this->model_database->escapeString($post['fname']);
		$lname = $this->model_database->escapeString($post['lname']);
		// if ($_FILES["photo"]["name"] != '') {
		// 	$extension = explode('.', $_FILES['photo']['name']);
		// 	$getimage_path = rand() . '.' . $extension[1];
		// 	$destination = "./app-api/user_uploads/" . $post['user_id'] . "/" . $getimage_path;
		// 	move_uploaded_file($_FILES['photo']['tmp_name'], $destination);
		// } else {
		// 	$getimage_path = $post['photo'];
		// }
		$sql = "UPDATE " . $this->table_database . " SET
			`fname` = '" . $fname . "',
			`lname` = '" . $lname . "',
			`mobile` = '" . $post['mobile'] . "',
			`email_id` = '".$post['email_id']."',
			`updated_date` = '" . $getdate . "'
		 	WHERE `user_id` = '" . $post['user_id'] . "' ";

		return $this->model_database->executeSqlUpdateQuery($sql);
	}
	// updare user verify code
	public function wd_updateUser_verifycode($code,$email_id) {
		$sql = "UPDATE " . $this->table_database . " SET `sentcode` = '" . $code . "' WHERE `email_id` = '" . $email_id . "' ";
		return $this->model_database->executeSqlUpdateQuery($sql);
	}
	//Delete user data
	public function wd_UserDelete($user_id) {
		$sql = "DELETE FROM " . $this->table_database . " WHERE user_id= '" . $user_id . "' ";
		return $this->model_database->executeSqlQuery($sql);
	}
	//Chnage password
	public function wd_userChangepassword($user_id,$oldpassword,$newpassword) {
		$sql = "SELECT * FROM " . $this->table_database . " WHERE user_id = '" . $user_id . "' and status = 'active'";
		$sqlQry = $this->model_database->executeSqlQueryGetData($sql);
		$getpassword = $sqlQry[0]->password;
		$password = password_verify($oldpassword, $getpassword);
		if ($password > 0) {
			$newpassword = password_hash($newpassword, PASSWORD_DEFAULT);
			$sql = "UPDATE " . $this->table_database . " SET `password` = '" . $newpassword . "' WHERE `user_id` = '" . $user_id . "' ";
			return $this->model_database->executeSqlUpdateQuery($sql);
			//return $sqlQry;
		} else {
			return false;
		}
	}
	// Check Email ID
	public function wd_chkExistUserEmail($email_id) {
		$sql = "SELECT user_id,email_id,sentcode FROM " . $this->table_database . " WHERE email_id = '" . $email_id . "' ";
		return $this->model_database->executeSqlQueryGetData($sql);
	}
	// verify user
	public function wd_verifyUser($email_id,$code) {
		$sql = "SELECT user_id,email_id,sentcode FROM " . $this->table_database . " WHERE email_id = '" . $email_id . "' AND sentcode = '" . $code . "' ";
		return $this->model_database->executeSqlQueryGetData($sql);
	}
}
?>
