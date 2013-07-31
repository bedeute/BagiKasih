<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank_accounts extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/bank_accounts";
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
		
		if($_GET['keyword'] != "") $data['list'] = OBankAccount::search($_GET['keyword']);
		else $data['list'] = OBankAccount::get_list($start, $perpage, "name ASC");
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= $this->curpage."/listing?perpage=$perpage";
		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'bank_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function add()
	{
		extract($_POST);
		
		$this->form_validation->set_rules('name', 'Bank Name', 'trim|required');
		$this->form_validation->set_rules('account_number', 'Account Number', 'trim|required');
		$this->form_validation->set_rules('on_behalf_of', 'On Behalf OF', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$include_arr = array("name", "account_number", "on_behalf_of");
			
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					if(trim($val) != "") $arr[$key] = trim($val);	
				}
			}
			
			$new = OBankAccount::add($arr);
			if($new) warning("add", "success"); 
			else warning("add", "fail");			
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;		
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'bank_form', $data);
		$this->load->view($this->ffoot, $data);	
	}	
	
	public function edit($id)
	{
		$dlsoption = new OBankAccount($id);
		
		if(!empty($id))
		{			
			if($dlsoption->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		extract($_POST);
		
		$this->form_validation->set_rules('name', 'Bank Name', 'trim|required');
		$this->form_validation->set_rules('account_number', 'Account Number', 'trim|required');
		$this->form_validation->set_rules('on_behalf_of', 'On Behalf OF', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$include_arr = array("name", "account_number", "on_behalf_of");
			
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					$arr[$key] = trim($val);	
				}
			}
			$res = $dlsoption->edit($arr);
			if($res) warning("edit", "success");
			else warning("edit", "fail");				
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;
		$data['row'] = $dlsoption->row;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'bank_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	
	public function delete($id)
	{
		$dlsoption = new OBankAccount($id);
		
		$res = $dlsoption->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
	}
}

/* End of file bank_accounts.php */
/* Location: ./application/controllers/admin/bank_accounts.php */