<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_reviews extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/product_reviews";
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
		
		if($_GET['keyword'] != "") $data['list'] = OProductReview::search($_GET['keyword']);
		else $data['list'] 	= OProductReview::get_list($start, $perpage);
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= "{$this->curpage}/page?perpage=$perpage";
 		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'product_review_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function add()
	{
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			
			$include_arr = array("product_id", "user_id", "description", "status");
			
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					if(trim($val) != "") $arr[$key] = trim($val);	
				}
				$arr['rating'] = $entity_score;
				$arr['status'] = "pending";
			}
			
			$new = OProductReview::add($arr);	
				
			if($new) warning("add", "success");
			else warning("add", "fail");			
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;		
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'product_review_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	
	public function edit($id)
	{
		$PR = new OProductReview($id);
		
		if(!empty($id))
		{			
			if($PR->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);		
			
			$include_arr = array("product_id", "user_id", "description", "status");
			
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					if(trim($val) != "") $arr[$key] = trim($val);	
				}
				$arr['rating'] = $entity_score;
				$arr['status'] = "pending";
			}
			
			$res = $PR->edit($arr);
			
			if($res) warning("edit", "success");
			else warning("edit", "fail");				
			redirect($this->curpage);
			exit();			
		}
		
		$data['cu'] = $this->cu;
		$data['row'] = $PR->row;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'product_review_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	
	public function delete($id)
	{
		$PR = new OProductReview($id);
		
		if(!empty($id))
		{			
			if($PR->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		$res = $PR->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
	}
	
	public function act($act="", $id)
	{
		$PR = new OProductReview($id);	
		
		if(!empty($id))
		{			
			if($PR->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		if($act == "published") $arr['status'] = "published";
		if($act == "pending") $arr['status'] = "pending";
		
		$res = $PR->edit($arr);
			
		if($res) warning("edit", "success");
		else warning("edit", "fail");				
		redirect($this->curpage);
		exit();			
	}	
}

/* End of file product_reviews.php */
/* Location: ./application/controllers/admin/product_reviews.php */