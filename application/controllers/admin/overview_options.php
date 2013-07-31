<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Overview_options extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/overview_options";
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
		$data['list'] 		= DLSOverviewOption::get_list($start, $perpage, "title ASC");
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= $this->curpage."/listing?perpage=$perpage";
		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'overview_option_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function add()
	{
		extract($_POST);
		
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('summary', 'Summary', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$include_arr = array("title", "description", "summary");
			
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					if(trim($val) != "") $arr[$key] = trim($val);	
				}
			}
			
			if($userfile != NULL)
			{
				$image_file = DLSOverviewOption::save_photo($userfile);
				$arr['photo_url'] = $image_file;
			}	
			
			$new = DLSOverviewOption::add($arr);
			if($new) warning("add", "success"); 
			else warning("add", "fail");			
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;		
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'overview_option_form', $data);
		$this->load->view($this->ffoot, $data);	
	}	
	
	public function edit($id)
	{
		$dlsoption = new DLSOverviewOption($id);
		
		if(!empty($id))
		{			
			if($dlsoption->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		extract($_POST);
		
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');	
		$this->form_validation->set_rules('summary', 'Summary', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$include_arr = array("title", "description", "summary");
			
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					$arr[$key] = trim($val);	
				}
			}
			
			if($userfile != NULL)
			{
				$image_file = DLSOverviewOption::save_photo($userfile);
				$arr['photo_url'] = $image_file;
				$dlsoption->delete_current_photo();
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
		$this->load->view($this->fpath.'overview_option_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	
	public function delete($id)
	{
		$dlsoption = new DLSOverviewOption($id);
		
		$res = $dlsoption->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
	}
}

/* End of file overview_options.php */
/* Location: ./application/controllers/admin/overview_options.php */