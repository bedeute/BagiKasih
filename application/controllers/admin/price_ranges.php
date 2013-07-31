<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Price_ranges extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/price_ranges";
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
			$O = new OPriceRange($id);
			
			if(!empty($id))
			{			
				if($O->row == FALSE)
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
								'category_id' 	=> $category_id
							);
				$res = $O->edit($arr);
				
				if($res) warning("edit", "success");
				else warning("edit", "fail");				
				redirect($this->curpage);
				exit();
			}
			$data['row'] = $O->row;
		}
		else 
		{
			$this->form_validation->set_rules('name', 'Name', 'trim|required');			
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if($this->form_validation->run() == TRUE)
			{
				$arr = array( 	'name' 			=> $name,
								'category_id' 	=> $category_id
							  // 'parent_id'	=> $parent_id
							);
				$new = OPriceRange::add($arr);				
				
				if($new) warning("add", "success");
				else warning("add", "fail");
				redirect($this->curpage);
				exit();										
			}			
		}
		
		$start 				= intval($_GET['page']);
		$perpage 			= 100;
		
		if($_GET['keyword'] != "") $data['list'] = OPriceRange::search($_GET['keyword']);
		else $data['list']		= OPriceRange::get_list($start, $perpage, "ordering ASC", $_GET['category_id']);
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= $this->curpage."/listing?";
 		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'price_range_list_form', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function delete($id)
	{
		$O = new OPriceRange($id);
		
		$res = $O->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
	}
	
	public function act($act = "", $id)
	{	
		$O = new OPriceRange($id);
				
		if($act == "active")
		{
			$arr['active'] = "1";
		}
		
		if($act == "inactive")
		{
			$arr['active'] = "0";
		}
		
		$res = $O->edit($arr);	
		if($res) warning("edit", "success");
		else warning("edit", "fail");
		redirect($this->curpage);
		exit();
	}
	
	/*public function make_default($id)
	{
		$this->db->update('brands',array('default' => 0));
		$res = $this->db->update('brands',array('default' => 1), array('id' => $id));
		if($res) warning("edit", "success");
		else warning("edit", "fail");
		redirect($this->curpage);
	}*/
	public function sorting()
	{
		$sorts = explode(",",$this->input->post('sorts'));
		$this->db->update('price_ranges',array('ordering' => 0));
		$count = 1;
		foreach($sorts as $id)
		{
			$this->db->update('price_ranges',array('ordering' => $count),array('id' => intval($id)));
			$count++;
		}
		die("DONE");
	}
}

/* End of file brands.php */
/* Location: ./application/controllers/admin/brands.php */