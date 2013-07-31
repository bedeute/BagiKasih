<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add_ons extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/add_ons";
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
		
		if($_GET['keyword'] != "") $data['list'] = OAddOn::search($_GET['keyword']);
		else $data['list'] 		= OAddOn::get_list($start, $perpage, "id ASC");
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= $this->curpage."/listing?perpage=$perpage";
		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'add_on_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function add()
	{
		extract($_POST);
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$include_arr = array("name");
			
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
				$image_file = OAddOn::save_photo($userfile);
				$arr['image'] = $image_file;
			}	
			
			$new = OAddOn::add($arr);
			if($new) warning("add", "success"); 
			else warning("add", "fail");			
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;		
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'add_on_form', $data);
		$this->load->view($this->ffoot, $data);	
	}	
	
	public function edit($id)
	{
		$AO = new OAddOn($id);
		
		if(!empty($id))
		{			
			if($AO->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		extract($_POST);
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');		
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$include_arr = array("name");
			
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					$arr[$key] = trim($val);
				}
			}
			
			if($userfile != NULL)
			{
				$image_file = OAddOn::save_photo($userfile);
				$arr['image'] = $image_file;
				$AO->delete_current_photo();
			}
			
			$res = $AO->edit($arr);
			if($res) warning("edit", "success");
			else warning("edit", "fail");				
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;
		$data['row'] = $AO->row;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'add_on_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	
	public function delete($id)
	{
		$AO = new OAddOn($id);
		
		$res = $AO->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
	}
}

/* End of file add_ons.php */
/* Location: ./application/controllers/admin/add_ons.php */