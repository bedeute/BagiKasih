<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banners extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/banners";
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
		
		//if($_GET['keyword'] != "") $data['list'] = OBanner::search($_GET['keyword']);
		//else 
		$data['list'] = OBanner::get_list($start, $perpage, "title ASC");
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= $this->curpage."/listing?perpage=$perpage";
		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'banner_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function add()
	{
		extract($_POST);
		
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		//$this->form_validation->set_rules('url', 'URL', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$include_arr = array("title"/*, "url", "category_id", "location_id"*/);
			
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
				$image_file = OBanner::save_photo($userfile);
				$arr['photo_url'] = $image_file;
			}	
			
			$new = OBanner::add($arr);
			if($new) warning("add", "success"); 
			else warning("add", "fail");			
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;		
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'banner_form', $data);
		$this->load->view($this->ffoot, $data);	
	}	
	
	public function edit($id)
	{
		$dlsoption = new OBanner($id);
		
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
		//$this->form_validation->set_rules('url', 'URL', 'trim|required');	
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$include_arr = array("title"/*, "url", "category_id", "location_id"*/);
			
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					$arr[$key] = trim($val);	
				}
			}
			
			if($userfile != NULL)
			{
				$image_file = OBanner::save_photo($userfile);
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
		$this->load->view($this->fpath.'banner_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	
	public function delete($id)
	{
		$dlsoption = new OBanner($id);
		
		$res = $dlsoption->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
	}
	/*public function featured($id)
	{
		$dlsoption = new OBanner($id);
		
		$res = $dlsoption->featured();
		if($res) warning("edit", "success");
		else warning("edit", "fail");
		redirect($this->curpage);
		exit();
	}
	public function unfeatured($id)
	{
		$dlsoption = new OBanner($id);
		
		$res = $dlsoption->unfeatured();
		if($res) warning("edit", "success");
		else warning("edit", "fail");
		redirect($this->curpage);
		exit();
	}*/
}

/* End of file banners.php */
/* Location: ./application/controllers/admin/banners.php */