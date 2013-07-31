<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->cu = $cu = get_logged_in_user();
		if(!$cu) user_logout();
		//error_reporting(E_ALL);
	}
	
	public function index()
	{
		$this->profile();
	}
	
	public function home()
	{
		$this->profile();
	}
	
	public function profile()
	{
		$data['cu'] = $cu = $this->cu;
		
		$data['initcss'] = array("additional_user");
		
		$this->load->view("header", $data);
		$this->load->view("user_profile", $data);
		$this->load->view("footer", $data);
	}
		
	public function edit_profile()
	{
		$data['cu'] = $cu = $this->cu;
		
		if(sizeof($_POST) > 0)
		{
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check['.$cu->id.']');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
			$this->form_validation->set_rules('address', 'Address', 'trim|required');
			$this->form_validation->set_rules('city', 'City', 'trim|required');
			$this->form_validation->set_rules('zip_code', 'Zip Code', 'trim|required');
			$this->form_validation->set_rules('state', 'State', 'trim|required');
			$this->form_validation->set_error_delimiters('', '<br />');
			
			extract($_POST);
			
			if(trim($_POST['password']) != "" || trim($_POST['retype_password']) != "") 
			{
				$this->form_validation->set_rules('password', 'Password', 'trim|required||min_length[6]|matches[retype_password]|md5');
				$this->form_validation->set_rules('retype_password', 'Retype Password', 'trim|required|min_length[6]');
			}
			
			if ($this->form_validation->run() == TRUE)
			{
				$inc_arr = array("name", /*"location",*/ "email", "phone", "address", "city", "state", "zip_code", "country");
				$arr = NULL;
				foreach($_POST as $key => $val)
				{
					if(in_array($key, $inc_arr))
					{
						$arr[$key] = $val;
					}
				}
				
				$url_title = url_title($name, 'dash', TRUE);
				$arr['url_title'] = $url_title; 
						
				if(trim($_POST['password']) != "") 
				{
					$arr['password'] = md5($_POST['password']);
				}
				
				$U = new OUser();
				$U->setup($cu);
				
				if($userfile != NULL)
				{
					$image_file = OUser::save_image($userfile);
					$arr['image'] = $image_file;
					$U->delete_current_image();
				}				
				
				$exec = $U->edit($arr);
				if($exec)
				{
					if($_POST['email'] != $cu->email) 
					{
						set_login_session($_POST['email'], $cu->password, $type="user");
					}
					if($_POST['password'] != "")
					{
						set_login_session($cu->email, md5($_POST['password']), $type="user");
					}
					if($_POST['email'] != $cu->email && $_POST['password'] != "") 
					{
						set_login_session($_POST['email'], md5($_POST['password']), $type="user");
					}
					
					$cek = $this->db->query("SELECT * FROM users WHERE url_title = ? AND id <> ?", array($url_title, $U->id));
					$res_cek = $cek->row();
					
					if(sizeof($res_cek) > 0)
					{
						$url_title = $url_title."-".$U->id;						
						$U->edit(array("url_title" => $url_title));
					}
												
					$this->session->set_flashdata("success_profile", "Your profile has been updated.");
					redirect('user/profile');
				}
				else {
					$data['error_string'] = "There is an error in the system. Please contact the website administrator.";					
				}				
			}
		}
		
		$data['initcss'] = array("additional_user");
		
		$this->load->view("header", $data);
		$this->load->view("user_edit_profile", $data);
		$this->load->view("footer", $data);
	}
		
	public function email_check($email, $id_exception)
	{
		if(OUser::check_email_exists($email, $id_exception)) {
			$this->form_validation->set_message('email_check', 'This email has been taken, please use another email');
			return FALSE;
		}else return true;
	}
	
	public function old_password_check($str)
	{
		$email = $this->session->userdata("hapikado_uname");
		$res = $this->db->query("SELECT * FROM users WHERE password = ? AND email = ?", array(md5(md5($str)), $email));
		if (emptyres($res))
		{
			$this->form_validation->set_message('old_password_check', 'Invalid your current password!');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function view_orders($page=0)
	{
		$data['cu'] = $cu = $this->cu;
		
		$data['perpage'] = $perpage = 5;
		$data['list'] = $list = OOrder::get_list(intval($page), $perpage, 'id DESC', $cu->id);
		$total = get_db_total_rows();
		$url = "/user/view_orders/";
		$uri_segment = "3";
		$data['pagination'] = genPagination($total, $perpage, $url, $uri_segment, 5);
		
		$data['initcss'] = array("additional_user");
		
		$this->load->view("header", $data);
		$this->load->view("user_view_order", $data);
		$this->load->view("footer", $data);	
	}
	
	public function view_order_details($id)
	{
		$data['cu'] = $cu = $this->cu;
		$data['O'] = $O = new OOrder($id);
		
		$data['list'] = $list = $O->get_order_detail_by_userid(0, 0, "o.id DESC", $cu->id);
		//$data['list'] = $list = OOrderDetail::get_list(0, 0, "id DESC", $id);
		
		$data['initcss'] = array("additional_user");
		
		$this->load->view("header", $data);
		$this->load->view("user_view_order_detail", $data);
		$this->load->view("footer", $data);	
	}
	
	public function delivery_status()
	{
		$data['cu'] = $cu = $this->cu;		
			
		$data['list'] = OOrder::get_delivery_status(0, 0, "id DESC", $cu->id);
		
		$data['initcss'] = array("additional_user");
		
		$this->load->view("header", $data);
		$this->load->view("user_delivery_status", $data);
		$this->load->view("footer", $data);	
	}
	
	/*public function received_status($id)
	{
		$data['cu'] = $cu = $this->cu;
		
		if($id == "") redirect('user/delivery_status');
		
		$O = new OOrder($id);
		if($O->row == "") redirect('user/delivery_status');
		
		$arr['status_new'] = 'completed';
		$arr['received_dt'] = date('Y-m-d H:i:s');
		$res = $O->edit($arr);
		
		if($res) $this->session->set_flashdata("status_update", "Your new status has changed.");
		else $this->session->set_flashdata("status_update", "Your new status hasn't changed.");
		redirect('user/delivery_status');
	}
	
	public function not_received_order($id)
	{
		$data['cu'] = $cu = $this->cu;
		
		if($id == "") redirect('user/delivery_status');
		
		$O = new OOrder($id);
		if($O->row == "") redirect('user/delivery_status');
		
		$arr['status_new'] = 'not_completed';
		$res = $O->edit($arr);
		
		if($res) $this->session->set_flashdata("status_update", "Your new status has changed.");
		else $this->session->set_flashdata("status_update", "Your new status hasn't changed.");
		redirect('user/delivery_status');
	}
	
	public function address_list()
	{
		$data['cu'] = $cu = $this->cu;
		
		$U = new OUser($cu->id);
		$data['list'] = $U->get_addresses();
		
		$this->load->view("header", $data);
		$this->load->view("user_address", $data);
		$this->load->view("footer", $data);	
	}
	
	public function add_address()
	{
		$data['cu'] = $cu = $this->cu;
		
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_error_delimiters('', '<br />');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			
			$arr = array(	'user_id'	=> $cu->id,
						 	'address'	=> $address,
						 	'city'		=> $city
						 );
			
			$new = OUserAddress::add($arr);
			
			if($new) $this->session->set_flashdata('address_msg', 'Your address has been save.'); 
			else $this->session->set_flashdata('address_msg', 'Your address has not been save.'); 
			redirect('user/address_list');
			
		}
		
		$this->load->view("header", $data);
		$this->load->view("user_add_address", $data);
		$this->load->view("footer", $data);	
	}
	
	public function edit_address($id)
	{
		$data['cu'] = $cu = $this->cu;
		
		$UA = new OUserAddress($id);
		
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_error_delimiters('', '<br />');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			
			$arr = array(	'user_id'	=> $cu->id,
						 	'address'	=> $address,
						 	'city'		=> $city
						 );
			
			$res = $UA->edit($arr);
			
			if($res) $this->session->set_flashdata('address_msg', 'Your address has been save.'); 
			else $this->session->set_flashdata('address_msg', 'Your address has not been save.'); 
			redirect('user/address_list');			
		}
		
		$data['row'] = $UA->row;
		$this->load->view("header", $data);
		$this->load->view("user_add_address", $data);
		$this->load->view("footer", $data);	
	}
	
	public function delete_address($id)
	{
		$data['cu'] = $cu = $this->cu;
		
		$UA = new OUserAddress($id);
		
		$res = $UA->delete();
			
		if($res) $this->session->set_flashdata('address_msg', 'Your address has been save.'); 
		else $this->session->set_flashdata('address_msg', 'Your address has not been save.'); 
		redirect('user/address_list');
	}
	
	public function bank_account_list()
	{
		$data['cu'] = $cu = $this->cu;
		
		$U = new OUser($cu->id);
		$data['list'] = $U->get_bank_accounts();
		
		$this->load->view("header", $data);
		$this->load->view("user_bank_account", $data);
		$this->load->view("footer", $data);	
	}
	
	public function add_bank_account()
	{
		$data['cu'] = $cu = $this->cu;
		
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required');
		$this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
		$this->form_validation->set_rules('account_number', 'Account Number', 'trim|required');
		$this->form_validation->set_error_delimiters('', '<br />');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			
			$arr = array(	'customer_name'	=> $customer_name,
						 	'bank_name'		=> $bank_name,
							'account_number'=> $account_number,
							'user_id'		=> $cu->id
						 );
			
			$new = OUserBankAccount::add($arr);
			
			if($new) $this->session->set_flashdata('account_msg', 'Your bank account has been save.'); 
			else $this->session->set_flashdata('account_msg', 'Your bank account has not been save.'); 
			redirect('user/bank_account_list');
			
		}
		
		$this->load->view("header", $data);
		$this->load->view("user_add_bank_account", $data);
		$this->load->view("footer", $data);	
	}
	
	public function edit_bank_account($id)
	{
		$data['cu'] = $cu = $this->cu;
		
		$UBA = new OUserBankAccount($id);
		
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required');
		$this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
		$this->form_validation->set_rules('account_number', 'Account Number', 'trim|required');
		$this->form_validation->set_error_delimiters('', '<br />');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			
			$arr = array(	'customer_name'	=> $customer_name,
						 	'bank_name'		=> $bank_name,
							'account_number'=> $account_number,
							'user_id'		=> $cu->id
						 );
			
			$res = $UBA->edit($arr);
			
			if($res) $this->session->set_flashdata('account_msg', 'Your bank account has been save.'); 
			else $this->session->set_flashdata('account_msg', 'Your bank account has not been save.');
			redirect('user/bank_account_list');			
		}
		
		$data['row'] = $UBA->row;
		$this->load->view("header", $data);
		$this->load->view("user_add_bank_account", $data);
		$this->load->view("footer", $data);	
	}
	
	public function delete_bank_account($id)
	{
		$data['cu'] = $cu = $this->cu;
		
		$UBA = new OUserBankAccount($id);
		
		$res = $UBA->delete();
			
		if($res) $this->session->set_flashdata('account_msg', 'Your bank account has been save.'); 
		else $this->session->set_flashdata('account_msg', 'Your bank account has not been save.');
		redirect('user/bank_account_list');
	}
	*/
	public function confirm_payment_list()
	{
		$data['cu'] = $cu = $this->cu;
		
		$data['list'] = OOrder::get_list(0, 0, "id DESC", $cu->id, "pending");
		
		$data['initcss'] = array("additional_user");
		
		$this->load->view("header", $data);
		$this->load->view("user_confirm_payment_list", $data);
		$this->load->view("footer", $data);
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */