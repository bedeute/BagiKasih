<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testimonials extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/testimonials";
		//error_reporting(E_ALL);
		
		$this->cu = $cu = get_current_admin();
		if(!$cu) admin_logout();
	}
	
	public function index()
	{
		$this->page(0);
	}
	
	public function page($start=0)
	{
		$start 				= $_GET['page'];
		$perpage 			= $_GET['perpage'];
		if(empty($start))	$start = 0;
		if(empty($perpage))	$perpage = 10;
		$data['list'] 		= OTestimonial::get_list($start, $perpage);
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= "{$this->curpage}/page?perpage=$perpage";
 		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'testimonial_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function add()
	{
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			
			$include_arr = array("user_id", "message");
			
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					if(trim($val) != "") $arr[$key] = trim($val);	
				}
			}
			
			$new = OTestimonial::add($arr);	
				
			if($new) warning("add", "success");
			else warning("add", "fail");			
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;		
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'testimonial_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	
	public function edit($id)
	{
		$T = new OTestimonial($id);
		
		if(!empty($id))
		{			
			if($T->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);		
			
			$include_arr = array("user_id", "message");
			
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					if(trim($val) != "") $arr[$key] = trim($val);	
				}
			}
			
			$res = $T->edit($arr);
			
			if($res) warning("edit", "success");
			else warning("edit", "fail");				
			redirect($this->curpage);
			exit();			
		}
		
		$data['cu'] = $this->cu;
		$data['row'] = $T->row;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'testimonial_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	
	public function delete($id)
	{
		$T = new OTestimonial($id);
		
		$res = $T->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
	}
	
	public function act($act="", $id)
	{
		$T = new OTestimonial($id);
		
		if(!empty($id))
		{			
			if($T->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		if($act == "published") $arr['status'] = "published";
		if($act == "pending") $arr['status'] = "pending";
		
		$res = $T->edit($arr);
			
		if($res) warning("edit", "success");
		else warning("edit", "fail");				
		redirect($this->curpage);
		exit();			
	}
}

/* End of file testimonials.php */
/* Location: ./application/controllers/admin/testimonials.php */