<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;
class User extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Authorization_Token');
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Methods: GET, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

		$this->load->model('UserModel');
		$this->load->library('Curl');
		//$this->load->library('Appconstant');
	}
	// this is test method
  	public function test_post()
	{
		//$post_data = $this->input->post();
		$array  = array('status'=>'ok','data'=>1);
		$this->response($array); 
	}
	##############################################################################
	###############################  User Data  ##################################
	##############################################################################
	// register new user
	public function register_post()
	{
		$post_data = $this->input->post();
		/*$token_data['register_from'] = 'web';
		$token_data['first_name'] = $post_data['first_name'];
		$token_data['last_name'] = $post_data['last_name'];
		$token_data['mobile'] = $post_data['mobile'];
		$token_data['email'] = $post_data['email'];
		
		$getpassword = $post_data['password'];
		$password = password_hash($getpassword, PASSWORD_DEFAULT);
		$token_data['password'] = $password;
		$token_data['created_date'] = date("Y-m-d H:i:s");
		$token_data['updated_date'] = date("Y-m-d H:i:s");*/

		$resultGetdata = $this->UserModel->wd_check_userdata($post_data['email']);
		if ($resultGetdata >= 1) {
			$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Data already exist.");
		}else{
			//$tokenData = $this->authorization_token->generateToken($token_data);
			//$json = array("status" => 200,"error" => false, "token" => $tokenData, "message" => "success");
			$getpassword = $post_data['password'];
			$password = password_hash($getpassword, PASSWORD_DEFAULT);
			$getdate = date("Y-m-d H:i:s");
			$data = array(
				'source' => 'web',
				'first_name' => $post_data['first_name'],
				'last_name' => trim($post_data['last_name']),
				'mobile' => $post_data['mobile'],
				'email' => $post_data['email'],
				'password' => $password,
				'created_date' => $getdate,
				'updated_date' => $getdate
			);
			$resp = $this->UserModel->wd_add_userdata($data);
			$user_id = $this->db->insert_id();
			if (!file_exists('user_uploads/' . $user_id)) {
				mkdir('user_uploads/' . $user_id, 0777, true);
			}
			/*$this->load->config('email');
			$this->load->library('Email');
			$from = $this->config->item('smtp_user');
			$to = $post_data['email']; //$this->input->post('to');
			$subject = 'Welcome to Wedding'; //$this->input->post('subject');
			$message = 'Hello ,Thanks.'; //$this->input->post('message');

			$this->email->set_newline("\r\n");
			$this->email->from($from);
			$this->email->to($to);
			//$this->email->cc('akashsathvara91@gmail.com');
			$this->email->subject($subject);
			$this->email->message($message);

			if ($this->email->send()) {
				//echo 'Your Email has successfully been sent.';
			} else {
				//show_error($this->email->print_debugger());
			}*/
			$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "user record added successfully.");
		}
		//$this->response($json); 
		echo json_encode($json);
	}
	// verify token
	public function verifyapi_post()
	{
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		$this->response($decodedToken);  
	}
	// user login
	public function login_post()
	{
		$post_data = $this->input->post();
		$resultGetuser = $this->UserModel->wd_loginuser($post_data['email'], $post_data['password']);
		if ($resultGetuser >= 1) {
			$token_data['user_id'] = $resultGetuser[0]['user_id'];
			$tokenData = array('token'=>$this->authorization_token->generateToken($token_data),'first_name'=>$resultGetuser[0]['first_name'],'last_name'=>$resultGetuser[0]['last_name'],'mobile'=>$resultGetuser[0]['mobile'],'email'=>$resultGetuser[0]['email'],'created_date'=>$resultGetuser[0]['created_date']);
			$json = array("status" => 200,"error" => false, "data" => $tokenData, "message" => "success");
			//$this->response($final); 
		}else{
			$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Login failed.");
		}
		echo json_encode($json);
	}
	// get user details
	public function getUserInfo_get()
	{
		$get_data = $this->input->get();
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($decodedToken['status'] == '1'){
			$user_id = $decodedToken['data']->user_id;
			$resultGetuser = $this->UserModel->wd_get_userdata($user_id);
			if ($resultGetuser >= 1) {
				$get_data = array('user_id'=>$resultGetuser[0]['user_id'],'first_name'=>$resultGetuser[0]['first_name'],'last_name'=>$resultGetuser[0]['last_name'],'mobile'=>$resultGetuser[0]['mobile'],'email'=>$resultGetuser[0]['email'],'created_date'=>$resultGetuser[0]['created_date']);
				//$tokenData = $this->authorization_token->generateToken($token_data);
				$json = array("status" => 200,"error" => false, "data" => $get_data, "message" => "success");
				//$this->response($final); 
			}else{
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Invalid user data.");
			}
		}else{
			$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		}
		echo json_encode($json);
	}
	// check email exist or not
	public function checkEmailExist_post()
	{
		$post_data = $this->input->post();
		//$headers = $this->input->request_headers(); 
		//$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		$email = $post_data['email'];
		$resultGetuser = $this->UserModel->wd_check_userdata($email);
		if ($resultGetuser >= 1) {
			//$tokenData = $this->authorization_token->generateToken($token_data);
			$json = array("status" => 200,"error" => false, "data" => "yes");
			//$this->response($final); 
		}else{
			$json = array("status" => 200,"error" => false, "data" => "no");
		}
		echo json_encode($json);
	}
	// Change user password
	public function changePassword_post()
	{
		$post_data = $this->input->post();
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($decodedToken['status'] == '1'){
			$user_id = $decodedToken['data']->user_id;
			$oldpassword = $post_data['oldpassword'];
			$newpassword = $post_data['newpassword'];
			$resultGetuser = $this->UserModel->wd_userChangepassword($user_id,$oldpassword,$newpassword);
			if ($resultGetuser >= 1) {
				$json = array("status" => 200,"error" => false,"data"=>array(), "data" => array(), "message" => "success"); 
			}else{
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Invalid data.");
			}
		}else{
			$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		}
		echo json_encode($json);
	}
	// Change user status
	public function updateUserStatus_delete()
	{
		$post_data = $this->input->post();
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($decodedToken['status'] == '1'){
			$user_id = $decodedToken['data']->user_id;
			//$resultGetuser = $this->UserModel->wd_delete_user($user_id);
			$data = array('status'=>'0');
			$resultupdateUser = $this->UserModel->wd_update_user_data($user_id, $data);
			if ($resultupdateUser >= 1) {
				$json = array("status" => 200,"error" => false,"data"=>array(), "data" => array(), "message" => "success");
			}else{
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Invalid data.");
			}
		}else{
			$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		}
		echo json_encode($json);
	}
	// Forgot password
	public function forgotPassword_post()
	{
		$post_data = $this->input->post();
		//$headers = $this->input->request_headers(); 
		//$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		$email = $post_data['email'];
		if(empty($email)){
			$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Missing parameter.");
		}else{
			$resultGetuser = $this->UserModel->wd_check_userdata($email);
			if ($resultGetuser >= 1) {
				$user_id = $resultGetuser[0]['user_id'];
		        $salt=date("dm");
		        $encrypted_id = base64_encode($user_id."-".$salt);
				//$data = array('code'=>$code);
				//$resultupdateUser = $this->UserModel->wd_update_user_data($user_id, $data);
				/*$this->load->config('email');
				$this->load->library('Email');
				$from = $this->config->item('smtp_user');
				$to = $post_data['email']; //$this->input->post('to');
				$subject = 'Welcome to Wedding'; //$this->input->post('subject');
				$message = 'Hello ,Thanks.'; //$this->input->post('message');

				$this->email->set_newline("\r\n");
				$this->email->from($from);
				$this->email->to($to);
				//$this->email->cc('akashsathvara91@gmail.com');
				$this->email->subject($subject);
				$this->email->message($message);

				if ($this->email->send()) {
					//echo 'Your Email has successfully been sent.';
				} else {
					//show_error($this->email->print_debugger());
				}*/
				//$tokenData = $this->authorization_token->generateToken($token_data);
				$json = array("status" => 200,"error" => false, "data" => array(), "message" => "Email verify code sent");
				//$this->response($final); 
			}else{
				$json = array("status" => 200,"error" => true,"data"=>array(), "message" => "Invalid data.");
			}
		}
		echo json_encode($json);
	}
	// verify user
	public function verifyUser_post()
	{
		$post_data = $this->input->post();
		//$headers = $this->input->request_headers(); 
		//$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		$email = $post_data['email'];
		$code = trim($post_data['code']);
		if(empty($email) || empty($code)){
			$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Missing parameter.");
		}else{
			$resultGetuser = $this->UserModel->wd_user_verifydata($email,$code);
			if ($resultGetuser >= 1) {
				$temp_password = '123456';
				$user_id = $resultGetuser[0]['user_id'];
				$password = password_hash($temp_password, PASSWORD_DEFAULT);
				$data = array('password'=>$password);
				$resultupdateUser = $this->UserModel->wd_update_user_data($user_id, $data);
				/*$this->load->config('email');
				$this->load->library('Email');
				$from = $this->config->item('smtp_user');
				$to = $post_data['email']; //$this->input->post('to');
				$subject = 'Welcome to Wedding'; //$this->input->post('subject');
				$message = 'Hello ,Thanks.'; //$this->input->post('message');

				$this->email->set_newline("\r\n");
				$this->email->from($from);
				$this->email->to($to);
				//$this->email->cc('akashsathvara91@gmail.com');
				$this->email->subject($subject);
				$this->email->message($message);

				if ($this->email->send()) {
					//echo 'Your Email has successfully been sent.';
				} else {
					//show_error($this->email->print_debugger());
				}*/
				//$tokenData = $this->authorization_token->generateToken($token_data);
				$json = array("status" => 200,"error" => false, "data" => array(), "message" => "success");
				//$this->response($final); 
			}else{
				$json = array("status" => 200,"error" => true,"data"=>array(), "message" => "Invalid data.");
			}
		}
		echo json_encode($json);
	}
	###########################################################################
	###############################  Create Website ###########################
	###########################################################################
	// add event data
	public function addEvent_post()
	{
		$post_data = $this->input->post();
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($decodedToken['status'] == '1'){
			$user_id = $decodedToken['data']->user_id;
			//$resultGetdata = $this->UserModel->wd_check_user_eventdata($post_data['email']);
			// if ($resultGetdata >= 1) {
			// 	$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Data already exist.");
			// }else{
				//$tokenData = $this->authorization_token->generateToken($token_data);
				//$json = array("status" => 200,"error" => false, "token" => $tokenData, "message" => "success");
				$getdate = date("Y-m-d H:i:s");
				$data = array(
					'user_id' => $user_id,
					'event_type' => trim($post_data['event_type']),
					'remarks' =>trim($post_data['remarks']),
					'template_id' => $post_data['template_id'],
					'is_domain' => $post_data['is_domain'],
					'site_url' => $post_data['site_url'],
					'selected_date' => $post_data['selected_date'],
					'city' => $post_data['city'],
					'location' => $post_data['location'],
					'created_date' => $getdate,
					'updated_date' => $getdate
				);
				$resp = $this->UserModel->wd_add_user_eventdata($data);
				$event_id = $this->db->insert_id();
				$eventData = array('event_id'=>$event_id,'event_type' => trim($post_data['event_type']),'remarks' =>trim($post_data['remarks']),'template_id' => $post_data['template_id'],'is_domain' => $post_data['is_domain'],'site_url' => $post_data['site_url'],'selected_date' => $post_data['selected_date'],'city' => $post_data['city'],'location' => $post_data['location']);
				/*$this->load->config('email');
				$this->load->library('Email');
				$from = $this->config->item('smtp_user');
				$to = $post_data['email']; //$this->input->post('to');
				$subject = 'Welcome to Wedding'; //$this->input->post('subject');
				$message = 'Hello ,Thanks.'; //$this->input->post('message');

				$this->email->set_newline("\r\n");
				$this->email->from($from);
				$this->email->to($to);
				//$this->email->cc('akashsathvara91@gmail.com');
				$this->email->subject($subject);
				$this->email->message($message);

				if ($this->email->send()) {
					//echo 'Your Email has successfully been sent.';
				} else {
					//show_error($this->email->print_debugger());
				}*/
				$json = array("status" => 200,"error" => false,"data"=>$eventData, "message" => "user event added successfully.");
			//}
		}else{
			$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		}
		//$this->response($json); 
		echo json_encode($json);
	}
	// get event data
	public function getEventInfo_post()
	{
		$post_data = $this->input->post();
		$event_id = $post_data['event_id'];
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($decodedToken['status'] == '1'){
			$user_id = $decodedToken['data']->user_id;
			$resultGetdata = $this->UserModel->wd_check_user_eventdata($user_id,$event_id);
			if ($resultGetdata >= 1) {
				$data['result'] = $resultGetdata;
				$result[] = array('event_id'=>$resultGetdata[0]['event_id'],'user_id'=>$resultGetdata[0]['user_id'],'event_type'=>$resultGetdata[0]['event_type'],'remarks'=>$resultGetdata[0]['remarks'],'is_domain'=>$resultGetdata[0]['is_domain'],'site_url'=>$resultGetdata[0]['site_url'],'selected_date'=>$resultGetdata[0]['selected_date'],'city'=>$resultGetdata[0]['city'],'location'=>$resultGetdata[0]['location']);
				$json = array("status" => 200,"error" => false,"data"=>$result, "message" => "success");
			}else{
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Invalid user data.");
			}
		}else{
			$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		}
		//$this->response($json); 
		echo json_encode($json);
	}
	// update user event data
	public function updateEvent_post()
	{
		$post_data = $this->input->post();
		$event_id = $post_data['event_id'];
		$getdate = date("Y-m-d H:i:s");
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($decodedToken['status'] == '1'){
			$user_id = $decodedToken['data']->user_id;
			$resultGeteventdata = $this->UserModel->wd_check_user_eventdata($user_id,$event_id);
			if ($resultGeteventdata >= 1) {
				$data['result'] = $resultGeteventdata;
				$remarks = isset($post_data['remarks']) ? $post_data['remarks'] : $resultGeteventdata[0]['remarks'];
				$is_domain = isset($post_data['is_domain']) ? $post_data['is_domain'] : $resultGeteventdata[0]['is_domain'];
				$site_url = isset($post_data['site_url']) ? $post_data['site_url'] : $resultGeteventdata[0]['site_url'];
				$selected_date = isset($post_data['selected_date']) ? $post_data['selected_date'] : $resultGeteventdata[0]['selected_date'];
				$city = isset($post_data['city']) ? $post_data['city'] : $resultGeteventdata[0]['city'];
				$location = isset($post_data['location']) ? $post_data['location'] : $resultGeteventdata[0]['location'];
				$data = array(
					'remarks' => $remarks,
					'is_domain' => $is_domain,
					'site_url' => $site_url,
					'selected_date' => $selected_date,
					'city' => $city,
					'location' => $location,
					'updated_date' => $getdate
				);
				$resp = $this->UserModel->wd_update_user_eventdata($event_id, $data);
				// get updated details
				$resultGetdata = $this->UserModel->wd_check_user_eventdata($user_id,$event_id);
				$result[] = array('event_id'=>$resultGetdata[0]['event_id'],'user_id'=>$resultGetdata[0]['user_id'],'event_type'=>$resultGetdata[0]['event_type'],'remarks'=>$resultGetdata[0]['remarks'],'is_domain'=>$resultGetdata[0]['is_domain'],'site_url'=>$resultGetdata[0]['site_url'],'selected_date'=>$resultGetdata[0]['selected_date'],'city'=>$resultGetdata[0]['city'],'location'=>$resultGetdata[0]['location']);
				$json = array("status" => 200,"error" => false,"data"=>$result, "message" => "success");
			}else{
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Invalid user data.");
			}
		}else{
			$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		}
		echo json_encode($json);
	}
	###########################################################################
	###############################  Blog Data  ###############################
	###########################################################################
	// add blog data
	public function addBlog_post()
	{
		$post_data = $this->input->post();
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($decodedToken['status'] == '1'){
			if($post_data['event_id'] == "" || empty($post_data['event_id'])){
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Missing parameter.");
			}else{
				$user_id = $decodedToken['data']->user_id;
				$getdate = date("Y-m-d H:i:s");
				if($_FILES){
					if ($_FILES['blog_image']['name'] != "") {
						$imagePrefix = time();
						$imagename = $user_id . $imagePrefix;
						$config['upload_path'] = './user_uploads/' . $user_id . '/';
						$config['allowed_types'] = '*';
						$config['file_name'] = $imagename;
						$this->load->library('upload', $config);
						if (!$this->upload->do_upload('blog_image')) {
							$error = array('error' => $this->upload->display_errors());
						} else {
							$upload_data = $this->upload->data();
							$photo_name = $imagename . $this->upload->data('file_ext');
							$blog_image = user_profile . $user_id."/".$photo_name;
						}
					} else {
						$blog_image = no_profile;
					}
				}else{
					$blog_image = no_profile;
				}
				
				$data = array(
					'event_id' => $post_data['event_id'],
					'user_id' => $user_id,
					'blog_title' => $post_data['blog_title'],
					'blog_image' => $blog_image,
					'blog_content' => $post_data['blog_content'],
					'posted_date' => $getdate,
					'updated_date' => $getdate
				);
				$resp = $this->UserModel->wd_add_user_blogdata($data);
				$blog_id = $this->db->insert_id();
				//$blogData = array('blog_id'=>$blog_id);
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "event blog added successfully.");
			}
			
		}else{
			$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		}
		//$this->response($json); 
		echo json_encode($json);
	}
	// get public blog details
	public function getBlogInfo_get()
	{
		$get_data = $this->input->get();
		// $headers = $this->input->request_headers(); 
		// $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		// //$this->response($decodedToken);
		// if($decodedToken['status'] == '1'){
		// 	$user_id = $decodedToken['data']->user_id;
			//$blog_id = $get_data['blog_id'];
			$blog_id = $this->uri->segment(4);
			if(isset($blog_id) && !empty($blog_id)){
				$resultGetresult = $this->UserModel->wd_get_blogdetail($blog_id);
				if ($resultGetresult >= 1) {
					//$blog_image = user_profile . $resultGetresult[0]['user_id'] . '/' . $resultGetresult[0]['blog_image'];
					$get_data = array('blog_id'=>$resultGetresult[0]['blog_id'],'event_id'=>$resultGetresult[0]['event_id'],'blog_title'=>$resultGetresult[0]['blog_title'],'blog_image'=>$resultGetresult[0]['blog_image'],'blog_content'=>$resultGetresult[0]['blog_content'],'posted_date'=>$resultGetresult[0]['posted_date']);
					//$tokenData = $this->authorization_token->generateToken($token_data);
					$json = array("status" => 200,"error" => false, "data" => $get_data, "message" => "success");
					//$this->response($final); 
				}else{
					$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Invalid user data.");
				}
			}else{
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Invalid blog data.");
			}
		// }else{
		// 	$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		// }
		echo json_encode($json);
	}
	// get user blog details
	public function getUserBloginfo_get()
	{
		$get_data = $this->input->get();
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($decodedToken['status'] == '1'){
			$user_id = $decodedToken['data']->user_id;
			//$blog_id = $get_data['blog_id'];
			$blog_id = $this->uri->segment(3);
			if(isset($blog_id) && !empty($blog_id)){
				$resultGetresult = $this->UserModel->wd_get_blogdetail($blog_id);
				if ($resultGetresult >= 1) {
					//$blog_image = user_profile . $resultGetresult[0]['user_id'] . '/' . $resultGetresult[0]['blog_image'];
					$get_data = array('blog_id'=>$resultGetresult[0]['blog_id'],'event_id'=>$resultGetresult[0]['event_id'],'blog_title'=>$resultGetresult[0]['blog_title'],'blog_image'=>$resultGetresult[0]['blog_image'],'blog_content'=>$resultGetresult[0]['blog_content'],'posted_date'=>$resultGetresult[0]['posted_date']);
					//$tokenData = $this->authorization_token->generateToken($token_data);
					$json = array("status" => 200,"error" => false, "data" => $get_data, "message" => "success");
					//$this->response($final); 
				}else{
					$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Invalid user data.");
				}
			}else{
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Invalid blog data.");
			}
		}else{
			$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		}
		echo json_encode($json);
	}
	//user created blog list
	public function getUserBlogList_get() {
		$get_data = $this->input->get();
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($decodedToken['status'] == '1'){
			$user_id = $decodedToken['data']->user_id;
			//$event_id = $get_data['event_id'];
			$event_id = $this->uri->segment(3);
			if ($event_id == "") {
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Missing parameter.");
			} else {
				$resultGetresult = $this->UserModel->wd_get_bloglist($event_id);
				if ($resultGetresult >= 1) {
					$data['result'] = $resultGetresult;
					foreach ($data['result'] as $getValue) {
						//$blog_image = user_profile . $getValue['user_id'] . '/' . $getValue['blog_image'];
						$result[] = array('blog_id' => $getValue['blog_id'], 'event_id' => $getValue['event_id'], 'blog_title' => $getValue['blog_title'], 'blog_image' => $resultGetresult[0]['blog_image'], 'blog_content' => $getValue['blog_content'], 'posted_date' => $getValue['posted_date'], 'remarks' => $getValue['remarks']);
						$json = array("status" => 200,"error" => false,"data"=>$result, "message" => "Invalid user data.");
					}
				} else {
					$json = array("status" => 0, "message" => "No data found.");
				}
			}
		}else{
			$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		}
		echo json_encode($json);
	}
	//public blog list
	public function getBlogList_get() {
		$get_data = $this->input->get();
		//$event_id = $get_data['event_id'];
		$event_id = $this->uri->segment(4);
		if ($event_id == "") {
			$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Missing parameter.");
		} else {
			$resultGetresult = $this->UserModel->wd_get_bloglist($event_id);
			if ($resultGetresult >= 1) {
				$data['result'] = $resultGetresult;
				foreach ($data['result'] as $getValue) {
					//$blog_image = user_profile . $getValue['user_id'] . '/' . $getValue['blog_image'];
					$result[] = array('blog_id' => $getValue['blog_id'], 'event_id' => $getValue['event_id'], 'blog_title' => $getValue['blog_title'], 'blog_image' => $resultGetresult[0]['blog_image'], 'blog_content' => $getValue['blog_content'], 'posted_date' => $getValue['posted_date'], 'remarks' => $getValue['remarks']);
					$json = array("status" => 200,"error" => false,"data"=>$result, "message" => "Invalid user data.");
				}
			} else {
				$json = array("status" => 0, "message" => "No data found.");
			}
		}
		echo json_encode($json);
	}
	// update user event data
	public function updateBlog_post()
	{
		$post_data = $this->input->post();
		$blog_id = $post_data['blog_id'];
		$getdate = date("Y-m-d H:i:s");
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($decodedToken['status'] == '1'){
			$user_id = $decodedToken['data']->user_id;
			$resultGetblogresult = $this->UserModel->wd_check_user_blogdata($user_id,$blog_id);
			if ($resultGetblogresult >= 1) {
				$data['result'] = $resultGetblogresult;
				if ($_FILES['blog_image']['name'] != "") {
					$imagePrefix = time();
					$imagename = $user_id . $imagePrefix;
					$config['upload_path'] = './user_uploads/' . $user_id . '/';
					$config['allowed_types'] = '*';
					$config['file_name'] = $imagename;
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('blog_image')) {
						$error = array('error' => $this->upload->display_errors());
					} else {
						$upload_data = $this->upload->data();
						$photo_name = $imagename . $this->upload->data('file_ext');
						$blog_image = user_profile . $user_id . '/' . $photo_name;
					}
				} else {
					$photo_name = $resultGetblogresult[0]['blog_image'];
					$blog_image = user_profile . $user_id . '/' . $photo_name;
				}
				$blog_title = isset($post_data['blog_title']) ? $post_data['blog_title'] : $resultGetblogresult[0]['blog_title'];
				//$blog_image = isset($post_data['blog_image']) ? $post_data['blog_image'] : $resultGetblogresult[0]['blog_image'];
				$blog_content = isset($post_data['blog_content']) ? $post_data['blog_content'] : $resultGetblogresult[0]['blog_content'];
				$data = array(
					'blog_title' => $blog_title,
					'blog_image' => $blog_image,
					'blog_content' => $blog_content,
					'updated_date' => $getdate
				);
				$resp = $this->UserModel->wd_update_user_blogdata($blog_id, $data);
				// get updated details
				$resultGetresult = $this->UserModel->wd_check_user_blogdata($user_id,$blog_id);
				$result[] = array('blog_id'=>$resultGetresult[0]['blog_id'],'event_id'=>$resultGetresult[0]['event_id'],'blog_title'=>$resultGetresult[0]['blog_title'],'blog_image'=>$resultGetresult[0]['blog_image'],'blog_content'=>$resultGetresult[0]['blog_content'],'posted_date'=>$resultGetresult[0]['posted_date']);
				$json = array("status" => 200,"error" => false,"data"=>$result, "message" => "success");
			}else{
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Invalid user data.");
			}
		}else{
			$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		}
		echo json_encode($json);
	}
	// delete blog
	public function deleteBlog_delete()
	{
		$post_data = $this->input->post();
		$blog_id = $post_data['blog_id'];
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if(isset($blog_id) && !empty($blog_id)){
			$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Missing parameter.");
		}else{
			if($decodedToken['status'] == '1'){
				$user_id = $decodedToken['data']->user_id;
				$resultGetuser = $this->UserModel->wd_delete_blog($blog_id);
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "success");
			}else{
				$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
			}
		}
		echo json_encode($json);
	}
	// add like in blog
	public function likeBlog_post()
	{
		$post_data = $this->input->post();
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($decodedToken['status'] == '1'){
			$user_id = $decodedToken['data']->user_id;
			$getdate = date("Y-m-d H:i:s");
			$data = array(
				'blog_id' => $post_data['blog_id'],
				'user_id' => $user_id,
				'user_id' => $getdate
			);
			$resp = $this->UserModel->wd_add_like_blogdata($data);
			$like_id = $this->db->insert_id();
			$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "event blog added successfully.");
		}else{
			$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		}
		//$this->response($json); 
		echo json_encode($json);
	}
	// dislike blog
	public function dislikeBlog_delete()
	{
		$post_data = $this->input->post();
		$like_id = $post_data['like_id'];
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if(isset($like_id) && !empty($like_id)){
			$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Missing parameter.");
		}else{
			if($decodedToken['status'] == '1'){
				$user_id = $decodedToken['data']->user_id;
				$resultGetuser = $this->UserModel->wd_dislike_blog($like_id);
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "success");
			}else{
				$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
			}
		}
		echo json_encode($json);
	}
	###########################################################################
	################################  Comments  ###############################
	###########################################################################
	// comments
	public function addComment_post()
	{
		$post_data = $this->input->post();
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($decodedToken['status'] == '1'){
			if($post_data['blog_id'] == "" || empty($post_data['blog_id']) || empty($post_data['comment'])){
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Missing parameter.");
			}else{
				$user_id = $decodedToken['data']->user_id;
				$getdate = date("Y-m-d H:i:s");
				if(isset($post_data['parent_comment_id']) && !empty($post_data['parent_comment_id'])){
					$parent_comment_id = $post_data['parent_comment_id'];
				}else{
					$parent_comment_id = '0';
				}			
				$data = array(
					'blog_id' => $post_data['blog_id'],
					'user_id' => $user_id,
					'comment' => $post_data['comment'],
					'parent_comment_id' => $parent_comment_id,
					'created_date' => $getdate,
					'updated_date' => $getdate
				);
				$resp = $this->UserModel->wd_add_blog_commentdata($data);
				$comment_id = $this->db->insert_id();
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "comment added successfully.");
			}
		}else{
			$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		}
		//$this->response($json); 
		echo json_encode($json);
	}
	// get user created comment
	public function getUserComment_get()
	{
		$get_data = $this->input->get();
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($decodedToken['status'] == '1'){
			$user_id = $decodedToken['data']->user_id;
			//$blog_id = $get_data['blog_id'];
			$comment_id = $this->uri->segment(3);
			if(isset($comment_id) && !empty($comment_id)){
				$resultGetresult = $this->UserModel->wd_get_commentdetail($comment_id);
				if ($resultGetresult >= 1) {
					//$blog_image = user_profile . $resultGetresult[0]['user_id'] . '/' . $resultGetresult[0]['blog_image'];
					$get_data = array('comment_id'=>$resultGetresult[0]['comment_id'],'comment'=>$resultGetresult[0]['comment'],'created_date'=>$resultGetresult[0]['created_date']);
					//$tokenData = $this->authorization_token->generateToken($token_data);
					$json = array("status" => 200,"error" => false, "data" => $get_data, "message" => "success");
					//$this->response($final); 
				}else{
					$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Invalid user data.");
				}
			}else{
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Invalid blog data.");
			}
		}else{
			$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
		}
		echo json_encode($json);
	}
	// remove Comment
	public function removeComment_delete()
	{
		//$post_data = $this->input->request();
		//$comment_id = $post_data['comment_id'];
		$comment_id = $this->uri->segment(3);
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if($comment_id =="" && empty($comment_id)){
			$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Missing parameter.");
		}else{
			if($decodedToken['status'] == '1'){
				$user_id = $decodedToken['data']->user_id;
				$resultGetuser = $this->UserModel->wd_delete_comment($comment_id);
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "success");
			}else{
				$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
			}
		}
		echo json_encode($json);
	}
	// update user comment
	public function updateComment_post()
	{
		$post_data = $this->input->post();
		$comment_id = $post_data['comment_id'];
		$getdate = date("Y-m-d H:i:s");
		$headers = $this->input->request_headers(); 
		$decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
		//$this->response($decodedToken);
		if ($comment_id == "") {
			$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Missing parameter.");
		} else {
			if($decodedToken['status'] == '1'){
				$user_id = $decodedToken['data']->user_id;
				$resultGetcommentresult = $this->UserModel->wd_check_user_commentdata($user_id,$comment_id);
				if ($resultGetcommentresult >= 1) {
					$data['result'] = $resultGetcommentresult;
					$comment = isset($post_data['comment']) ? $post_data['comment'] : $resultGetcommentresult[0]['comment'];
					$data = array(
						'comment' => $comment,
						'updated_date' => $getdate
					);
					$resp = $this->UserModel->wd_update_user_commentdata($comment_id, $data);
					// get updated details
					$resultGetresult = $this->UserModel->wd_check_user_commentdata($user_id,$comment_id);
					$result[] = array('comment_id'=>$resultGetresult[0]['comment_id'],'blog_id'=>$resultGetresult[0]['blog_id'],'user_id'=>$resultGetresult[0]['user_id'],'comment'=>$resultGetresult[0]['comment']);
					$json = array("status" => 200,"error" => false,"data"=>$result, "message" => "success");
				}else{
					$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Invalid user data.");
				}
			}else{
				$json = array("status" => 401,"error" => true,"data"=>array(), "message" => "Invalid token.");
			}
		}
		
		echo json_encode($json);
	}
	//public comment list
	public function listComment_get() {
		//$get_data = $this->input->get();
		$blog_id = $this->uri->segment(3);
		$from = $this->uri->segment(4);
		$to_limit = 10;
		if ($blog_id == "") {
			$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "Missing parameter.");
		} else {
			if (isset($from) && !empty($from)) {
				$from_limit = $from - 1;
			} else {
				$from_limit = 0;
			}
			$resultGetresult = $this->UserModel->wd_get_commentlist($blog_id,$from_limit,$to_limit);
			if ($resultGetresult >= 1) {
				$data['result'] = $resultGetresult;
				foreach ($data['result'] as $getValue) {
					// get comment data if have
					$resultGetsubresult = $this->UserModel->wd_get_replylist($getValue['comment_id']);
					if ($resultGetsubresult >= 1) {
						$jsonReply = array();
						$dataReply['result'] = $resultGetsubresult;
						foreach ($dataReply['result'] as $getReply) {
							$jsonReply[] = array('comment_id'=>$getReply['comment_id'],'comment'=>$getReply['comment'],'first_name'=>$getReply['first_name'],'last_name'=>$getReply['last_name']);
						}
					}else{
						$jsonReply = array();
					}
					$result[] = array('comment_id'=>$getValue['comment_id'],'blog_id'=>$getValue['blog_id'],'comment'=>$getValue['comment'],'first_name'=>$getValue['first_name'],'last_name'=>$getValue['last_name'],'replyData'=>$jsonReply);
					$json = array("status" => 200,"error" => false,"data"=>$result, "message" => "success");
				}
			} else {
				$json = array("status" => 200,"error" => false,"data"=>array(), "message" => "No data found.");
			}
		}
		echo json_encode($json);
	}

}

