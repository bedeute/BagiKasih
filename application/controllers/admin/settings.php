<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/settings";
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
		
		$data['list'] = OSetting::get_list($start, $perpage, "id ASC");
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= $this->curpage."/listing?perpage=$perpage";
		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'setting_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	/*
	public function add()
	{
		extract($_POST);
		
		$this->form_validation->set_rules('content', 'Content', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$include_arr = array("content");
			
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					if(trim($val) != "") $arr[$key] = trim($val);	
				}
			}
			if($description) $arr['description'] = $description;			
			$new = OSetting::add($arr);
			
			if($new) 
			{
				$O = new OSetting($new);
				$O->refresh();
				$O->update_key();
				unset($O);
				warning("add", "success"); 
			}
			else warning("add", "fail");			
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;		
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'Setting_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	*/	
	
	public function edit($id)
	{
		$O = new OSetting($id);
		
		if(!empty($id))
		{			
			if($O->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		extract($_POST);
		
		$this->form_validation->set_rules('content', 'Content', 'trim|required');
		
		if($this->form_validation->run() == TRUE)
		{
			$include_arr = array("content");
			
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					$arr[$key] = trim($val);	
				}
			}
			$res = $O->edit($arr);			
			
			if($res) warning("edit", "success");
			else warning("edit", "fail");			
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;
		$data['row'] = $O->row;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'setting_form', $data);
		$this->load->view($this->ffoot, $data);	
		unset($O);
	}
	
	/*public function delete($id)
	{
		$dlsoption = new OSetting($id);
		
		$res = $dlsoption->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
	}*/
}

/* End of file settings.php */
/* Location: ./application/controllers/admin/settings.php */