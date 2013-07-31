<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_supplier extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->cu = $cu = get_logged_in_supplier();
		//error_reporting(E_ALL);	
	}
	
	public function index()
	{
		if($this->cu) supplier_home();
		$this->login(); 
	}
	
	public function login()
	{
		redirect('account_supplier/register_login_form');
		
		if($this->cu) supplier_home();
		
		if(sizeof($_POST) > 0)
		{
			extract($_POST);
			
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
			$this->form_validation->set_error_delimiters('', '<br />');
			
			if ($this->form_validation->run() == TRUE)
			{
				$user = get_supplier($email,md5(md5($password)));
				
				if(!$user) 
				{
					$data['error_string'] = "Invalid Email and Password Combination.";					
				}
				else 
				{
					set_login_session($email, md5(md5($password)), $type="supplier");
					
					supplier_home();
					$referal = $this->session->userdata("after_login_url");					
					
					if($referal == "" || $referal == site_url()) redirect('home');
					else redirect($referal);
					
					redirect("/");
				}
			}
		}
		$referal = $_SERVER['HTTP_REFERER'];
		if(!empty($referal))
		{
			$this->session->set_userdata("after_login_url", $referal);
		}
		
		$this->load->view("header", $data);
		$this->load->view("login_supplier", $data);
		$this->load->view("footer", $data);
	}
	
	public function register()
	{
		redirect('account_supplier/register_login_form');
		
		if($this->cu) supplier_home();

		/*require_once($_SERVER['DOCUMENT_ROOT']."/recaptchalib.php");
		
		$publickey = RECAPTCHA_PUBLIC_KEY;
		$privatekey = RECAPTCHA_PRIVATE_KEY;
		
		$resp = null;
		$error = null;*/
		
		if(sizeof($_POST) > 0)
		{
			extract($_POST);
			
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('address', 'Address', 'trim|required');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
			/*$this->form_validation->set_rules('fax', 'Fax', 'trim|required');
			$this->form_validation->set_rules('website', 'Website', 'trim|required');
			$this->form_validation->set_rules('description', 'Description', 'trim|required');*/
			$this->form_validation->set_rules('location_id', 'Location', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|matches[confirm_password]|md5');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[6]');
			/*$this->form_validation->set_rules('recaptcha_response_field', "'Security Code'", 'trim|required');*/
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
						$inc_arr = array("name", "address", "phone", /*"fax", */"email", "password"/*, "description"*/);
						$arr = NULL;
						foreach($_POST as $key => $val)
						{
							if(in_array($key, $inc_arr))
							{
								if($key == "password") $val = md5($val);								
								$arr[$key] = $val;
							}
							
							if($location_id != "") $arr['location_id'] = $location_id;
							if($website != "") $arr['website'] = prep_url($website);
							if($fax != "") $arr['fax'] = $fax;
							if($description != "") $arr['description'] = $description;
						}
						// var_dump($arr); die();
						$exec = OSupplier::add($arr); 
						
						if($exec)
						{			
							set_login_session($email, md5(md5($password)), $type="supplier");
							supplier_home();						
						}
						else $data['error_string'] = "There is an error in the system. Please contact the website administrator.";						
					/*}
					else
					{
						$data['error_string'] = "Wrong code! Please try again.";
					}
				}*/
			}
		}
		//$data['captcha'] = recaptcha_get_html($publickey);
		$this->load->view("header", $data);
		$this->load->view("register_supplier", $data);
		$this->load->view("footer", $data);
	}
	
	public function email_check($str)
	{
		$res = $this->db->query("SELECT * FROM suppliers WHERE email = ?", array($str));
		
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
		unset_login_session($type="supplier");
		redirect("");
	}	
	
	public function forgot_password($a, $param1, $param2)
	{
		if($this->cu) supplier_home();
		
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
							$res = $this->db->query("SELECT * FROM suppliers WHERE email = ?", array($forgot_email));
							if(!empty($res))
							{
								$supplier 	= $res->row();
								$arr		= array('name' 	=> $supplier->name,
													'email'	=> $supplier->email,
													'pass'	=> $supplier->password);
								$body 		= $this->load->view('tpl_mail_forgot_password_supplier',$arr,true);
								$to			= $supplier->email;
								$subject	= "Forgot Password - Password Confirmation | Hapikado.com";
								
								noreply_mail($to,$subject,$body);
								$this->session->set_flashdata($arr);
								redirect('account_supplier/forgot_password/thank_you');
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
			$this->load->view("forgot_form_supplier", $data);
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
			
			$res = $this->db->query("SELECT * FROM suppliers WHERE md5(md5(`email`)) = ? AND password = ?", array($md5_email, $md5_password));
			if(emptyres($res))
			{
				$data['sess'] = "expired";
			}
			else
			{
				$user = $res->row();
				$U = new OSupplier();
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
						set_login_session($user->email, md5(md5($password)), $type="supplier");
						$this->session->set_flashdata("success", "Your password has been changed!");
						//redirect('user/profile');
						supplier_home();
					}
				}
			}
			$this->load->view('header',$data);
			$this->load->view('forgot_reset_form_supplier', $data);
			$this->load->view('footer');
		endif;
	}
	
	public function email_forgot_check($str)
	{
		$res = $this->db->query("SELECT * FROM suppliers WHERE email = ?", array($str));
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
	
	public function register_login_form($a = "")
	{
		if($a == "login")
		{
			$data['act'] = "login";
			if($this->cu) supplier_home();
		
			if(sizeof($_POST) > 0)
			{
				extract($_POST);
				
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
				$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
				$this->form_validation->set_error_delimiters('', '<br />');
				
				if ($this->form_validation->run() == TRUE)
				{
					$user = get_supplier($email,md5(md5($password)));
					
					if(!$user) 
					{
						$data['error_string'] = "Invalid Email and Password Combination.";					
					}
					else 
					{
						set_login_session($email, md5(md5($password)), $type="supplier");
						
						supplier_home();
						$referal = $this->session->userdata("after_login_url");					
						
						if($referal == "" || $referal == site_url()) redirect('home');
						else redirect($referal);
						
						redirect("/");
					}
				}
			}
			$referal = $_SERVER['HTTP_REFERER'];
			if(!empty($referal))
			{
				$this->session->set_userdata("after_login_url", $referal);
			}	
		}
		
		if($a == "register")
		{
			$data['act'] = "register";
			if($this->cu) supplier_home();
			
			if(sizeof($_POST) > 0)
			{
				extract($_POST);
				
				$this->form_validation->set_rules('name', 'Name', 'trim|required');
				$this->form_validation->set_rules('address', 'Address', 'trim|required');
				$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
				$this->form_validation->set_rules('location_id', 'Location', 'trim|required');
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check');
				$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|matches[confirm_password]|md5');
				$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[6]');
				$this->form_validation->set_error_delimiters('', '<br />');
				
				if ($this->form_validation->run() == TRUE)
				{					
					$inc_arr = array("name", "address", "phone", "email", "password");
					$arr = NULL;
					foreach($_POST as $key => $val)
					{
						if(in_array($key, $inc_arr))
						{
							if($key == "password") $val = md5($val);								
							$arr[$key] = $val;
						}
						
						if($location_id != "") $arr['location_id'] = $location_id;
						if($website != "") $arr['website'] = prep_url($website);
						if($fax != "") $arr['fax'] = $fax;
						if($description != "") $arr['description'] = $description;
					}
					
					$exec = OSupplier::add($arr); 
					
					if($exec)
					{			
						set_login_session($email, md5(md5($password)), $type="supplier");
						supplier_home();						
					}
					else $data['error_string'] = "There is an error in the system. Please contact the website administrator.";
				}
			}	
		}
		$this->load->view('header',$data);
		$this->load->view('register_login_form', $data);
		$this->load->view('footer', $data);	
	}
}

/* End of file account_supplier.php */
/* Location: ./application/controllers/account_supplier.php */