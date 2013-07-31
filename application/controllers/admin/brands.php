<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Brands extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/brands";
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
			$OBrand = new OBrand($id);
			
			if(!empty($id))
			{			
				if($OBrand->row == FALSE)
				{
					$this->session->set_flashdata('warning', 'ID does not exist.');
					redirect($this->curpage);
				}
			}
			
			$this->form_validation->set_rules('name', 'Brand Name', 'trim|required');
			$this->form_validation->set_rules('shipping_fee', 'Shipping Fee', 'trim|required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');			
			
			if($this->form_validation->run() == TRUE)
			{
				$arr = array( 	'name' 			=> $name,
								'category_id' 	=> $category_id,
								'supplier_id' 	=> $supplier_id,
								'shipping_fee' 	=> $shipping_fee
							);
				$res = $OBrand->edit($arr);
				
				if($res) warning("edit", "success");
				else warning("edit", "fail");				
				redirect($this->curpage);
				exit();
			}
			$data['row'] = $OBrand->row;
		}
		else 
		{
			$this->form_validation->set_rules('name', 'Brand Name', 'trim|required');
			$this->form_validation->set_rules('shipping_fee', 'Shipping Fee', 'trim|required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if($this->form_validation->run() == TRUE)
			{
				$arr = array( 	'name' 			=> $name,
								'category_id' 	=> $category_id,
								'supplier_id' 	=> $supplier_id,
								'shipping_fee' 	=> $shipping_fee
							  // 'parent_id'	=> $parent_id
							);
				$new = OBrand::add($arr);				
				
				if($new) warning("add", "success");
				else warning("add", "fail");
				redirect($this->curpage);
				exit();										
			}			
		}
		
		$start 				= intval($_GET['page']);
		$perpage 			= 100;
		
		if($_GET['keyword'] != "") $data['list'] = OBrand::search($_GET['keyword']);
		else $data['list']		= OBrand::get_list($start, $perpage, "ordering ASC", $_GET['category_id']);
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= $this->curpage."/listing?";
 		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'brand_list_form', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function delete($id)
	{
		$OBrand = new OBrand($id);
		
		$res = $OBrand->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
	}
	
	public function act($act = "", $id)
	{	
		$OBrand = new OBrand($id);
				
		if($act == "active")
		{
			$arr['active'] = "1";
		}
		
		if($act == "inactive")
		{
			$arr['active'] = "0";
		}
		
		$res = $OBrand->edit($arr);	
		if($res) warning("edit", "success");
		else warning("edit", "fail");
		redirect($this->curpage);
		exit();
	}
	
	public function make_default($id)
	{
		$this->db->update('brands',array('default' => 0));
		$res = $this->db->update('brands',array('default' => 1), array('id' => $id));
		if($res) warning("edit", "success");
		else warning("edit", "fail");
		redirect($this->curpage);
	}
	public function sorting()
	{
		$sorts = explode(",",$this->input->post('sorts'));
		$this->db->update('brands',array('ordering' => 0));
		$count = 1;
		foreach($sorts as $id)
		{
			$this->db->update('brands',array('ordering' => $count),array('id' => intval($id)));
			$count++;
		}
		die("DONE");
	}
}

/* End of file brands.php */
/* Location: ./application/controllers/admin/brands.php */