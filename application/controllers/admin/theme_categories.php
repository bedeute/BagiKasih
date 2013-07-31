<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Theme_categories extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/theme_categories";
		//error_reporting(E_ALL);
		
		$this->cu = $cu = get_current_admin();
		if(!$cu) admin_logout();
	}
	
	public function index()
	{
		$this->listing(0);
	}
	
	public function listing($id, $start=0)
	{
		extract($_POST);
		
		if(!empty($id))
		{
			$OThemecategory = new OThemecategory($id);
			
			if(!empty($id))
			{			
				if($OThemecategory->row == FALSE)
				{
					$this->session->set_flashdata('warning', 'ID does not exist.');
					redirect($this->curpage);
				}
			}
			
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');			
			
			if($this->form_validation->run() == TRUE)
			{
				$arr = array( 	'name' 			=> $name,	
								'category_id' => $category_id
							);
				$res = $OThemecategory->edit($arr);
				
				if($res) warning("edit", "success");
				else warning("edit", "fail");				
				//header("location: {$_SERVER['HTTP_REFERER']}");
				redirect($this->curpage);
				exit();
			}
			$data['row'] = $OThemecategory->row;
		}
		else 
		{
			$this->form_validation->set_rules('name', 'Name', 'trim|required');			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if($this->form_validation->run() == TRUE)
			{
				$arr = array( 	'name' 			=> $name,
							 	'category_id'   => $category_id
							  // 'parent_id'	=> $parent_id
							);
				$new = OThemecategory::add($arr);				
				
				if($new) warning("add", "success");
				else warning("add", "fail");
				//header("location: {$_SERVER['HTTP_REFERER']}");
				redirect($this->curpage);
				exit();										
			}			
		}
		
		$start 				= $_GET['page'];
		$perpage 			= $_GET['perpage'];
		if(empty($start))	$start = 0;
		if(empty($perpage))	$perpage = 100;
		
		if($_GET['keyword'] != "") $data['list'] = OThemecategory::search($_GET['keyword']);
		else $data['list'] = OThemecategory::get_list($start, $perpage, "ordering ASC", $_GET['category_id']);
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= $this->curpage."/listing?perpage=$perpage";
 		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'theme_category_list_form', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function delete($id)
	{
		$OThemecategory = new OThemecategory($id);
		
		$res = $OThemecategory->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		//header("location: {$_SERVER['HTTP_REFERER']}");
		redirect($this->curpage);
		exit();
	}	
	
	/*public function make_default($id)
	{
		$this->db->update('type_categories',array('default' => 0));
		$res = $this->db->update('type_categories',array('default' => 1), array('id' => $id));
		if($res) warning("edit", "success");
		else warning("edit", "fail");
		redirect($this->curpage);
	}*/
	public function sorting()
	{
		$sorts = explode(",",$this->input->post('sorts'));
		$this->db->update('theme_categories',array('ordering' => 0));
		$count = 1;
		foreach($sorts as $id)
		{
			$this->db->update('theme_categories',array('ordering' => $count),array('id' => intval($id)));
			$count++;
		}
		die("DONE");
	}
}

/* End of file subcategories.php */
/* Location: ./application/controllers/admin/subcategories.php */