<?php
function redirect_to($url) {
	if (isset($url)) {
		header("Location: " . $url);
	}
}

function sanitize_output($string) {
	return htmlspecialchars($string);
}
// get month
function get_month($month){
	if($month == "1"){
		return "January";
	}elseif($month == "2"){
		return "February";
	}elseif($month == "3"){
		return "March";
	}elseif($month == "4"){
		return "April";
	}elseif($month == "5"){
		return "May";
	}elseif($month == "6"){
		return "June";
	}elseif($month == "7"){
		return "July";
	}elseif($month == "8"){
		return "August";
	}elseif($month == "9"){
		return "September";
	}elseif($month == "10"){
		return "October";
	}elseif($month == "11"){
		return "November";
	}elseif($month == "12"){
		return "December";
	}
}
// filse uploads
function upload_image($uploadpath, $fileobject) {

	$fileName = $fileobject["file_upload"]["name"];
	$fileTmpLoc = $fileobject["file_upload"]["tmp_name"];
	$fileName = time() . $fileName;
	$pathAndName = $uploadpath . $fileName;
	$moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);

	if ($moveResult) {
		return $fileName;
	} else {
		return false;
	}
}
// multiple file uploads
function upload_multiple_image($uploadpath, $fileobject) {
	$number_of_file_fields = 0;
	$number_of_uploaded_files = 0;
	$number_of_moved_files = 0;
	$uploaded_files = array();
	$upload_directory = $uploadpath; //set upload directory

	for ($i = 0; $i < count($fileobject['screen']['name']); $i++) {
		$number_of_file_fields++;
		if ($fileobject['screen']['name'][$i] != '') {
			//check if file field empty or not
			$number_of_uploaded_files++;
			$uploaded_files[] = $fileobject['screen']['name'][$i];
			if (move_uploaded_file($fileobject['screen']['tmp_name'][$i], $upload_directory . $fileobject['screen']['name'][$i])) {
				$number_of_moved_files++;
			}
		}

	} // end of for loop
	return $uploaded_files;
}

// generate password or code
function generate_password($l = 15, $c = 1, $n = 3, $s = 2) {
	$generate_pass = '';
	// get count of all required minimum special chars
	$count = $c + $n + $s;

	// sanitize inputs; should be self-explanatory
	if (!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
		trigger_error('Argument(s) not an integer', E_USER_WARNING);
		return false;
	} elseif ($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
		trigger_error('Argument(s) out of range', E_USER_WARNING);
		return false;
	} elseif ($c > $l) {
		trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
		return false;
	} elseif ($n > $l) {
		trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
		return false;
	} elseif ($s > $l) {
		trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
		return false;
	} elseif ($count > $l) {
		trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
		return false;
	}

	// all inputs clean, proceed to build password

	// change these strings if you want to include or exclude possible password characters
	$chars = "abcdefghijklmnopqrstuvwxyz";
	$caps = strtoupper($chars);
	$nums = "0123456789";
	//$syms = "!@#$%^&*()-+?";
	$syms = "!@#$^&*";

	// build the base password of all lower-case letters
	for ($i = 0; $i < $l; $i++) {
		$generate_pass .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	}

	// create arrays if special character(s) required
	if ($count) {
		// split base password to array; create special chars array
		$tmp1 = str_split($generate_pass);
		$tmp2 = array();

		// add required special character(s) to second array
		for ($i = 0; $i < $c; $i++) {
			array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
		}
		for ($i = 0; $i < $n; $i++) {
			array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
		}
		for ($i = 0; $i < $s; $i++) {
			array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
		}

		// hack off a chunk of the base password array that's as big as the special chars array
		$tmp1 = array_slice($tmp1, 0, $l - $count);
		// merge special character(s) array with base password array
		$tmp1 = array_merge($tmp1, $tmp2);
		// mix the characters up
		shuffle($tmp1);
		// convert to string for output
		$generate_pass = implode('', $tmp1);
	}

	return $generate_pass;
}

// sending mail script
function send_email($to, $subject, $body, $headers_from_name, $headers_from_email, $cc_emails) {

	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

	// More headers
	$headers .= 'From: $headers_from_name <$headers_from_email>' . "\r\n";
	if ($cc_emails != '') {$headers .= 'Cc: myboss@example.com' . "\r\n";}

	mail($to, $subject, $body, $headers);
}

function p($data, $continue = false) {
	echo '<pre>';
	print_r($data);
	if (!$continue) {
		die;
	}
}

function admin_login_user() {
	$return = array();
	if (isset($_SESSION['admin_id'])) {
		$sql = "SELECT * FROM tbl_admin_login WHERE admin_id = '" . $_SESSION['admin_id'] . "'";
		$model_database = new Database();
		$return = $model_database->executeSqlQueryGetRow($sql);
	}
	return $return;
}
function login_user() {
	$return = array();

	if (isset($_SESSION['user_id'])) {
		$sql = "SELECT * FROM tbl_ef_users WHERE user_id = '" . $_SESSION['user_id'] . "'";
		$model_database = new Database();
		$return = $model_database->executeSqlQueryGetRow($sql);
	}

	return $return;
}


function time_ago($datetime) {
	$estimate_time = time() - strtotime($datetime);

	if ($estimate_time < 1) {
		return 'just now';
	}

	$condition = array(
		12 * 30 * 24 * 60 * 60 => 'YR Ago',
		30 * 24 * 60 * 60 => 'MN Ago',
		7 * 24 * 60 * 60 => 'W Ago',
		24 * 60 * 60 => 'D Ago',
		60 * 60 => 'H Ago',
		60 => 'M Ago',
		1 => 'S Ago',
	);

	foreach ($condition as $secs => $str) {
		$d = $estimate_time / $secs;

		if ($d >= 1) {
			$r = round($d);
			return $r . '' . $str . ($r > 1 ? '' : '') . '';
		}
	}
}

function time_ago2($datetime, $full = false) {
	$now = new DateTime();
	$ago = new DateTime($datetime);
	$diff = $now->diff($ago);

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);
	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}

	if (!$full) {
		$string = array_slice($string, 0, 1);
	}

	return $string ? implode(', ', $string) . ' ago' : 'just now';
}

?>