<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->cu = $cu = get_logged_in_user();
		//error_reporting(E_ALL);	
		$this->config->load('facebook');
	}
	
	public function index()
	{
		if($this->cu) user_home();
		$this->login(); 
	}
	
	public function login()
	{
		//if($this->cu) user_home();		
		if(sizeof($_POST) > 0)
		{
			
			$referal = $_SERVER['HTTP_REFERER'];
			if(!empty($referal))
			{
				$this->session->set_userdata("after_login_url", $referal);
			}
		
			extract($_POST);
			
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
			$this->form_validation->set_error_delimiters('', '<br />');
			
			if ($this->form_validation->run() == TRUE)
			{
				//var_dump($this->session->userdata("after_login_url"));
				$user = get_user($email,md5(md5($password)));
				
				if(!$user) 
				{
					//$data['error_string'] = "Invalid Email and Password Combination.";					
					$this->session->set_flashdata('error_string', "Invalid Email and Password Combination.");
					
					redirect($referal);
				}
				else 
				{
					set_login_session($email, md5(md5($password)), $type="user");
					
					/*redirect('user/home');*/
					$referal = $this->session->userdata("after_login_url");					
					
					/*if($referal == "" || $referal == site_url()) redirect('home');
					else */
					if($referal == "" || $referal == site_url('home/?login_status=login_failed')) redirect('home');
					else redirect($referal);
					
					/*redirect("/");*/
				}
			}
			else
			{
				$this->session->set_flashdata('login_error', json_encode(validation_errors()));
				redirect($referal);
			}
		}
		/*$referal = $_SERVER['HTTP_REFERER'];
		if(!empty($referal))
		{
			$this->session->set_userdata("after_login_url", $referal);
		}*/
		
		/*$this->load->view("header", $data);
		$this->load->view("login", $data);
		$this->load->view("footer", $data);*/
	}
	
	public function register()
	{
		/*if($this->cu) user_home();

		require_once($_SERVER['DOCUMENT_ROOT']."/recaptchalib.php");
		
		$publickey = RECAPTCHA_PUBLIC_KEY;
		$privatekey = RECAPTCHA_PRIVATE_KEY;
		
		$resp = null;
		$error = null;*/
		
		if(sizeof($_POST) > 0)
		{
			$referal = $_SERVER['HTTP_REFERER'];
			if(!empty($referal))
			{
				$this->session->set_userdata("after_login_url", $referal);
			}
			
			extract($_POST);
			
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			/*$this->form_validation->set_rules('address', 'Address', 'trim|required');
			$this->form_validation->set_rules('city', 'City', 'trim|required');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
			$this->form_validation->set_rules('fax', 'Fax', 'trim|required');
			$this->form_validation->set_rules('location_id', 'Location', 'trim|required');*/
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|matches[confirm_password]|md5');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[6]');
			//$this->form_validation->set_rules('recaptcha_response_field', "'Security Code'", 'trim|required');
			$this->form_validation->set_error_delimiters('', '<br />');
			
			if ($this->form_validation->run() == TRUE)
			{
				/*if ($_POST["recaptcha_response_field"]) 
				{
					$resp = recaptcha_check_answer ($privatekey,
													$_SERVER["REMOTE_ADDR"],
													$_POST["recaptcha_challenge_field"],
													$_POST["recaptcha_response_field"]);
			
					if ($resp->is_valid)
					{*/
						$inc_arr = array("name", /*"address", "city", "phone", "fax",*/ "email", "password");
						$arr = NULL;
						foreach($_POST as $key => $val)
						{
							if(in_array($key, $inc_arr))
							{
								if($key == "password") $val = md5($val);								
								$arr[$key] = $val;
							}
							
							//if($location_id != "") $arr['location_id'] = $location_id;
						}
						
						$exec = OUser::add($arr);
						
						if($exec)
						{			
							set_login_session($email, md5(md5($password)), $type="user");
							//user_home();
							
							$referal = $this->session->userdata("after_login_url");
							redirect($referal);
						}
						//else $data['error_string'] = "There is an error in the system. Please contact the website administrator.";						
					/*}
					else
					{
						$data['error_string'] = "Wrong code! Please try again.";
					}
				}*/
			}
		}
		/*else redirect('/');
		$data['captcha'] = recaptcha_get_html($publickey);
		$this->load->view("header", $data);
		$this->load->view("register", $data);
		$this->load->view("footer", $data);
		$this->load->view("header", $data);
		$this->load->view("home", $data);
		$this->load->view("footer", $data);*/
	}
	
	public function email_check($str)
	{
		$res = $this->db->query("SELECT * FROM users WHERE email = ?", array($str));
		
		if (!emptyres($res))
		{
			$this->form_validation->set_message('email_check', 'Please choose another email!');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function logout()
	{
		session_start();
		session_destroy();
		unset_login_session($type="user");
		$this->cart->destroy();
		$this->session->unset_userdata('shipping_details');
		$this->session->sess_destroy();
		redirect("");
	}	
	
	public function forgot_password($a, $param1, $param2)
	{
		if($this->cu) redirect('home');
		
		if($a == ""):
		
			require_once($_SERVER['DOCUMENT_ROOT']."/recaptchalib.php");
			
			$publickey = RECAPTCHA_PUBLIC_KEY;
			$privatekey = RECAPTCHA_PRIVATE_KEY;
						
			$resp = null;			
			$error = null;
			
			if(sizeof($_POST) > 0)
			{
				extract($_POST);
				$this->form_validation->set_rules('forgot_email', 'Email', 'trim|required|valid_email|callback_email_forgot_check');
				$this->form_validation->set_rules('recaptcha_response_field', "'Security Code'", 'trim|required');
				$this->form_validation->set_error_delimiters('', '<br />');
				if ($this->form_validation->run() == TRUE)
				{
					if ($_POST["recaptcha_response_field"]) 
					{
						$resp = recaptcha_check_answer ($privatekey,
														$_SERVER["REMOTE_ADDR"],
														$_POST["recaptcha_challenge_field"],
														$_POST["recaptcha_response_field"]);
				
						if ($resp->is_valid)
						{
							$res = $this->db->query("SELECT * FROM users WHERE email = ?", array($forgot_email));
							if(!empty($res))
							{
								$user 		= $res->row();
								$arr		= array('name' 	=> $user->name,
													'email'	=> $user->email,
													'pass'	=> $user->password);
								$body 		= $this->load->view('tpl_mail_forgot_password',$arr,true);
								$to			= $user->email;
								$subject	= "Forgot Password - Password Confirmation | Hapikado.com";
								
								noreply_mail($to,$subject,$body);
								$this->session->set_flashdata($arr);
								redirect('account/forgot_password/thank_you');
							}
							else $data['error_string'] = "Invalid Email!";
						}
						else $data['error_string'] = "Wrong code! Please try again.";
					}
				}
			}
			$referal = $_SERVER['HTTP_REFERER'];
			if(!empty($referal))
			{
				$this->session->set_userdata("after_login_url", $referal);
			}
			$data['captcha'] = recaptcha_get_html($publickey);
			$this->load->view("header", $data);
			$this->load->view("forgot_form", $data);
			$this->load->view("footer", $data);
		
		endif;
		
		if($a == "thank_you"):
			$this->load->view('header',$data);
			$this->load->view('forgot_password_thank_you');
			$this->load->view('footer');
		endif;


		if($a == "reset"):
			$md5_email = $param1;
			$md5_password = $param2;
			
			$res = $this->db->query("SELECT * FROM users WHERE md5(md5(`email`)) = ? AND password = ?", array($md5_email, $md5_password));
			if(emptyres($res))
			{
				$data['sess'] = "expired";
			}
			else
			{
				$user = $res->row();
				$U = new OUser();
				$U->setup($user);
				if(sizeof($_POST)>0)
				{
					extract($_POST);
					$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|matches[confirm_password]|md5');
					$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[6]');
					$this->form_validation->set_error_delimiters('', '<br />');
					if ($this->form_validation->run() == TRUE)
					{
						$arr = array("password" => md5($this->input->post('password')));
						$U->edit($arr);
						set_login_session($user->email, md5(md5($password)), $type="user");
						$this->session->set_flashdata("success", "Your password has been changed!");
						redirect('user/profile');
					}
				}
			}
			$this->load->view('header',$data);
			$this->load->view('forgot_reset_form', $data);
			$this->load->view('footer');
		endif;
	}
	
	public function email_forgot_check($str)
	{
		$res = $this->db->query("SELECT * FROM users WHERE email = ?", array($str));
		if (emptyres($res))
		{
			$this->form_validation->set_message('email_forgot_check', 'Sorry, this email is not registered!');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	/*
	public function fb_login()
	{
		$this->load->library('fb_connect');
		$param['redirect_uri'] = site_url("account/facebook");
		$param['scope'] = "email, user_birthday, user_location, friends_birthday";
		//$param['display']="popup";
		redirect($this->fb_connect->getLoginUrl($param));
	}*/
	
	function facebook()
	{
		$referal = $_SERVER['HTTP_REFERER'];
		
		if(!empty($referal) && !stristr($referal, '/account'))
		{
			$this->session->set_userdata("after_login_url", $referal);
		}
		
		if(!isset($_GET['code']))
		{
			redirect("account"); 
			exit();
		}
		else if(sizeof($_POST) == 0)
		{
			$this->load->library("curl");
			
			$token_url = 'https://graph.facebook.com/oauth/access_token?client_id='.$this->config->item('appId').'&redirect_uri='.base_url("account/facebook").'&client_secret='.$this->config->item('secret').'&code='.$_GET['code'];
									
			$token_data = file_get_contents($token_url); // grab data token
			
			preg_match("/access_token=([^&]+)/",$token_data,$token); // filter grab data untuk dapatkan token
            
			$access_token = $token[1]; // ambil token FB

			$url = 'https://graph.facebook.com/me?access_token='.$access_token; # url untuk ambil data user Login FB
			$data = file_get_contents($url);
			
			$this->session->set_userdata("fb_data", $data);
			$this->session->set_userdata("fb_access_token", $access_token);
			
            $data_b['my_data']  =json_decode(file_get_contents($url),true); // ambil data user login FB
		} else {
			if($this->session->userdata("fb_data") == "") { redirect($this->session->userdata("after_login_url")); exit(); }
			$data_b['my_data'] = json_decode($this->session->userdata("fb_data"));
		}
		
		$dataku = json_decode($this->session->userdata("fb_data"));
		$cek = $this->db->query("SELECT * FROM users WHERE email = ? LIMIT 1", array($dataku->email));
				
		if(!emptyres($cek)) 
		{
			
			
			$r = $cek->row();
			extract(get_object_vars($r));
			$U = new OUser();
			$U->setup($r);
			$U->edit(array(
				'fb_id' 			=> $dataku->id,
				'fb_access_token' 	=> $access_token
				));
			set_login_session($r->email, $r->password, $type="user");
			
			$referal = $this->session->userdata("after_login_url");
			redirect($referal);
		}
		else
		{
			$this->form_validation->set_rules('password', 'Password', 'trim|required|matches[confirm_password]|md5|min_length[6]');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[6]');
			$this->form_validation->set_rules('no_handphone', 'No. Handphone', 'trim|required');
			$this->form_validation->set_error_delimiters('', '<br />');
			
			if ($this->form_validation->run() == TRUE)
			{
				
				
				// cek dulu, apa $_GET['code'] ada
				if(isset($_GET['code'])) 
				{
					$FB = json_decode($this->session->userdata("fb_data"));
					$this->session->unset_userdata("fb_data");					
					
					$access_token_b = $this->session->userdata("fb_access_token");
					$this->session->unset_userdata("fb_access_token");
					
						$password = $_POST['password'];
										
						$location = $FB->location->name;
						if($location == NULL)
						{
							$location = "-";
						}
						$arr = array(	'name' 				=> $FB->name,							  	
										'email'				=> $FB->email,
										'password'			=> md5($password),							
										'address'			=> $location,
										'city'				=> $location,
										'fb_id' 			=> $FB->id,
										'fb_access_token' 	=> $access_token_b,
										'phone'				=> $_POST['no_handphone']
									);
						$exec = OUser::add($arr);
						
						set_login_session($FB->email, md5($password), $type="user");
						
						$referal = $this->session->userdata("after_login_url");
						redirect($referal);
						
				}
				elseif(isset($_GET['error_reason'])) {
					// untuk menangkap user yang klik "Dont Allow" atau "Cancel di Facebook"
					// buat variabel untuk ditampilkan di view
					//$data['tolak'] = "Uh oh, saya ditolak T_T";
					redirect("");
				}
			}
			$this->load->view('header',$data_b);
			$this->load->view('fb_password_form', $data_b);
			$this->load->view('footer', $data_b);
		}
	}
	
	public function twitter_login()
	{
		session_start();
		require_once($_SERVER['DOCUMENT_ROOT'].'/twitteroauth/twitteroauth/twitteroauth.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/twitteroauth/config.php');
		
		
		/* If access tokens are not available redirect to connect page. */
		if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
			header('Location: ./twitter.php?logout');
			//redirect('twitter.php?logout');
			exit();
		}
		/* Get user access tokens out of the session. */
		$access_token = $_SESSION['access_token'];
		
		/* Create a TwitterOauth object with consumer/user tokens. */
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
		
		/* If method is set change API call made. Test is called by default. */
		$TW = $connection->get('account/verify_credentials');		
		
		/*echo '<pre>';
		print_r($TW);
		echo '</pre>';*/
		
		
		/*foreach($TW as $key => $val)
		{
			$arr[$key] = $val;
		}
		//var_dump($arr['screen_name'], $content->screen_name); die();*/
		
		$cek = $this->db->query("SELECT * FROM users WHERE twitter_id = ? LIMIT 1", array($TW->screen_name));
			
		if(!emptyres($cek))
		{
			$r = $cek->row();
			extract(get_object_vars($r));
			$U = new OUser();
			$U->setup($r);
			$U->edit(array(
				'avatar'	=> $TW->profile_image_url,
				'twitter_id'=> $TW->screen_name
			));
			set_login_session($TW->screen_name, $password, "user");
			redirect('user/home');						
		}
		else
		{
			$location = $TW->location;
			if($location == NULL)
			{
				$location = "-";
			}
			$password = random_string("alnum", 20);
			$arr_user = array(	'name' 		=> $TW->name,
								'password'	=> md5(md5($password)),								
								'address'	=> $location,
								'city'		=> $location,
								'avatar'	=> $TW->profile_image_url,
								'twitter_id'=> $TW->screen_name
							);
			$exec = OUser::add($arr_user);
			
			set_login_session($TW->screen_name, md5(md5($password)), "user");
			redirect('user/home');
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	/*public function twitter_login()
	{
		require_once($_SERVER['DOCUMENT_ROOT'].'/twitteroauth/config.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/twitteroauth/twitteroauth/twitteroauth.php');
		
		if (CONSUMER_KEY === '' || CONSUMER_SECRET === '') 
		{
			  echo 'You need a consumer key and secret to test the sample code. Get one from <a href="https://twitter.com/apps">https://twitter.com/apps</a>';
			  exit;
		}
		else
		{
			
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
			 
			
			$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
			
			
			$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
			$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
			 
			
			switch ($connection->http_code) {
			  case 200:
				$url = $connection->getAuthorizeURL($token);
				header('Location: ' . $url); 
				break;
			  default:
				echo 'Could not connect to Twitter. Refresh the page or try again later.';
			}
		}
	}*/
	
	
	/*public function twitter_login()
	{
		$this->data['consumer_key'] = "kxp3XMvRqP1seIwaYXXyxA";
  		$this->data['consumer_secret'] = "SiGDUVGKfTmbsBzYuwoJuHVLVN94mMLB6NMkAtx3eI";
  
		$this->load->library('twitter_oauth', $this->data);
	
		$token = $this->twitter_oauth->get_request_token();
		
		$_SESSION['oauth_request_token'] = $token['oauth_token'];
		$_SESSION['oauth_request_token_secret'] = $token['oauth_token_secret'];
		
		$request_link = $this->twitter_oauth->get_authorize_URL($token);
		
		$data['link'] = $request_link;
		
		$this->load->view('twitter/home', $data);
	}
	
	public function access()
	{
		$this->data['oauth_token'] = $_SESSION['oauth_request_token'];
		$this->data['oauth_token_secret'] = $_SESSION['oauth_request_token_secret'];
		$this->load->library('twitter_oauth', $this->data);
		
		
		$tokens = $this->twitter_oauth->get_access_token();
		
		$_SESSION['oauth_access_token'] = $tokens['oauth_token'];
		$_SESSION['oauth_access_token_secret'] = $tokens['oauth_token_secret'];
		
		$this->load->view('twitter/accessed', $tokens);
	}*/
	
	
	
	public function ajax($a, $param1, $param2)
	{
		extract($_POST);
		
		if($a == "login")
		{
			die($this->load->view("tpl_login_form_popup", "", TRUE));
		}
	}
}

/* End of file account.php */
/* Location: ./application/controllers/account.php */