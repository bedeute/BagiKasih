<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suppliers extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/suppliers";
		//error_reporting(E_ALL);
		
		$this->cu = $cu = get_current_admin();
		if(!$cu) admin_logout();
	}
	
	public function index()
	{
		$this->listing(0);
	}
	
	public function listing($start=0)
	{
		$start 				= $_GET['page'];
		$perpage 			= $_GET['perpage'];
		if(empty($start))	$start = 0;
		if(empty($perpage))	$perpage = 100;
		
		if($_GET['keyword'] != "") $data['list'] = OSupplier::search($_GET['keyword']);
		else $data['list'] 		= OSupplier::get_list($start, $perpage, "id_supplier ASC",$_GET['location_id']);
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= $this->curpage."/listing?perpage=$perpage";
		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'supplier_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function add()
	{
		extract($_POST);
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check[""]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|matches[confirm_password]|md5');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[6]');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$include_arr = array("name", "address", "description", "id_city", "phone", "fax", "email", "password", "id_bank", "cabang", "no_rekening", "on_behalf");
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					if(trim($val) != "")
					{
						if($key == "password") $arr[$key] = md5($val);
						else $arr[$key] = trim($val);	
					}
				}
			}
			
			/*
			if($userfile != NULL)
			{
				$image_file = OSupplier::save_photo($userfile);
				$arr['photo_url'] = $image_file;
			}	
			*/
			$new = OSupplier::add($arr);
			if($new) warning("add", "success"); 
			else warning("add", "fail");			
			if($_POST['next'] != "")
			{
				header("location: ".$_POST['next']);
			}
			else
			{
				redirect($this->curpage);
			}
			
			exit();
		}
		$data['cu'] = $this->cu;		
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'supplier_form', $data);
		$this->load->view($this->ffoot, $data);	
	}	
	
	public function email_check($email, $id_exception)
	{
		if(OSupplier::check_email_exists($email, $id_exception)) 
		{
			$this->form_validation->set_message('email_check', 'This email has been taken, please use another email');
			return FALSE;
		}
		else return true;
	}
	
	public function edit($id)
	{
		$dlsoption = new OSupplier($id);
		
		if(!empty($id))
		{			
			if($dlsoption->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				header("location: ".$_SERVER['HTTP_REFERER']);
			}
		}
		
		extract($_POST);
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check['.$id.']');
		
		if($password)
		{
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|matches[confirm_password]|md5');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[6]');	
		}
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$include_arr = array("name", "address", "description", "location_id", "phone", "fax", "email", "website", "password", "id_bank", "cabang", "no_rekening", "on_behalf");
			
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					if(trim($val) != "")
					{
						if($key == "password") $arr[$key] = md5($val);
						else $arr[$key] = trim($val);
					}
				}
			}

			/*
			if($userfile != NULL)
			{
				$image_file = OSupplier::save_photo($userfile);
				$arr['photo_url'] = $image_file;
				$dlsoption->delete_current_photo();
			}
			*/
			$res = $dlsoption->edit($arr, $id);
			if($res) warning("edit", "success");
			else warning("edit", "fail");
			if($_POST['next'] != "")
			{
				header("location: ".$_POST['next']);
			}
			else
			{
				redirect($this->curpage);
			}
			exit();
		}
		$data['cu'] = $this->cu;
		$data['row'] = $dlsoption->row;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'supplier_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	
	public function delete($id)
	{
		$S = new OSupplier($id);
		
		$res = $S->delete($id);
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		header("location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}
	public function show($id)
	{
		$dlsoption = new OSupplier($id);
		
		$res = $dlsoption->show();
		if($res) warning("edit", "success");
		else warning("edit", "fail");
		header("location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}
	public function hide($id)
	{
		$dlsoption = new OSupplier($id);
		
		$res = $dlsoption->hide();
		if($res) warning("edit", "success");
		else warning("edit", "fail");
		header("location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}
	
	public function data_order($id)
	{
		$S = new OSupplier($id);
		
		$data['list'] = $S->get_data_order();
		
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'supplier_data_order_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function data_order_pending($id)
	{
		$S = new OSupplier($id);
		
		$data['list'] = $S->get_new_orders(0, 0, "odas.order_detail_id DESC", "pending");		
		
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'supplier_data_pending_list', $data);
		$this->load->view($this->ffoot, $data);
	}
}

/* End of file suppliers.php */
/* Location: ./application/controllers/admin/suppliers.php */