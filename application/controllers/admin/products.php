<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/products";
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
		$start 				= intval($_GET['page']);
		$perpage 			= 10;
		
		if($_GET['keyword'] != "") $data['list'] = OProduct::search($_GET['keyword']);
		else $data['list'] 		= OProduct::get_list($start, $perpage);
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= $this->curpage."/listing?";
		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'product_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function add()
	{
		extract($_POST); 
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('deskripsi', 'Description', 'trim|required');
		$this->form_validation->set_rules('id_category', 'Category', 'trim|required');
		$this->form_validation->set_rules('id_supplier', 'Supplier', 'trim|required');
		$this->form_validation->set_rules('harga', 'Price', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(trim($val) != "") $arr[$key] = trim($val);
			}
			
			$this->load->library('upload');

			$config['upload_path'] = './assets/images';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = '1024';
			$config['encrypt_name'] = TRUE;
			
			$this->upload->initialize($config);
			
			if( ! $this->upload->do_upload("userfile")){
				echo $this->upload->display_errors();
			}

			$file_name = $this->upload->file_name;
			$arr = array_merge($arr, array('foto' => $file_name));
				
			$new_id = OProduct::add($arr);
			
			if($new_id) warning("add", "success");
			else warning("add", "fail");			
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;
		$data['act'] = "add";
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'product_form', $data);
		$this->load->view($this->ffoot, $data);	
	}	
	
	
	public function edit($id)
	{
		$dlsproduct = new OProduct($id);
		
		if(!empty($id))
		{			
			if($dlsproduct->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		extract($_POST);
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('deskripsi', 'Description', 'trim|required');
		$this->form_validation->set_rules('id_category', 'Category', 'trim|required');
		$this->form_validation->set_rules('id_supplier', 'Supplier', 'trim|required');
		$this->form_validation->set_rules('harga', 'Price', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{	
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(trim($val) != "") $arr[$key] = trim($val);
			}
			
			$this->load->library('upload');

			$config['upload_path'] = './assets/images';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = '1024';
			$config['encrypt_name'] = TRUE;
			
			$this->upload->initialize($config);
			
			if( ! $this->upload->do_upload("userfile")){
				echo $this->upload->display_errors();
			}

			$file_name = $this->upload->file_name;
			$arr = array_merge($arr, array('foto' => $file_name));
			
			$res = $dlsproduct->edit($arr);
			if($res) warning("edit", "success");
			else warning("edit", "fail");				
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;
		$data['row'] = $dlsproduct->row;
		$data['act'] = "edit";
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'product_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	
	public function delete($id)
	{
		$dlsproduct = new OProduct($id);		
		
		$res = $dlsproduct->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
	}
	
	public function delete_photo($product_id, $id)
	{
		$dlsproductphoto = new OProductPhoto($id);
				
		$res = $dlsproductphoto->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage.'/edit/'.$product_id);
		exit();
	}
	
	public function assign_suppliers($id, $start=0)
	{
		$dlsproduct = new OProduct($id);
		
		$data['product_id'] = $id;
		
		if(intval($_POST['location_id']) != 0)
		{
			$SPS = new OSupplierProductSize();
			$SPS->delete_by_product_id_and_location($id,$_POST['location_id']);
//			echo $this->db->last_query();
			unset($SPS);
		
			extract($_POST);
			
			$suppliers = $_POST['supplier'];
			foreach($suppliers as $count => $supplier_id)
			{
				if(intval($supplier_id) == 0) continue;
				$product_id = $id;
				$size_id = intval($_POST['size'][$count]);
				$price = $_POST['price'][$count];
				$primary = $_POST['primary_supplier'][$count];
				
				$arr = array(	'supplier_id'		=> $supplier_id,
								'product_id'		=> $id,
								'size_id'			=> $size_id,
								'price'				=> $price,
								'primary'			=> intval($primary)
							);


				$new = OSupplierProductSize::add($arr);
			}
			if($new) warning("add", "success");
			else warning("add", "fail");			
			redirect($this->curpage);
			exit();
			/*foreach($supplier_product_size_id as $supplierproductsizeid)
			{
				$supplier_product_size_id_arr[] = array(  	 "product_id" 		=> $id,
															 "supplier_id" 		=> $id,
															 "size_id" 			=> $sizeid,
															 "price"			=> $price[$supplierproductsizeid]							 
															);
				
				echo '<pre>';
				print_r($supplier_product_size_id_arr);
				echo '</pre>';
			} */
			//if(count($supplier_product_size_id_arr) > 0) $dlsproduct->set_supplier_product_sizes($supplier_product_size_id_arr);	
		}
		$data['product_id'] = $id;
		$data['category_id'] = $dlsproduct->row->category_id;
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'assign_supplier_list', $data);
		$this->load->view($this->ffoot, $data);
	}


	public function act($act = "", $id)
	{	
		$P = new OProduct($id);
				
		if($act == "featured")
		{
			$arr['featured'] = 1;
		}
		
		if($act == "not_featured")
		{
			$arr['featured'] = 0;
		}
		
		$res = $P->edit($arr);	
		if($res) warning("edit", "success");
		else warning("edit", "fail");
		redirect($this->curpage);
		exit();
	}
	
	public function manage_add_ons($id)
	{
		if($id == "") redirect($this->curpage);
		
		$P = new OProduct($id);
		if($P->row == "") redirect($this->curpage);
		
		if(sizeof($_POST) > 0)
		{
			extract($_POST);
			
			$addon_id_arr = NULL;
						
			foreach($addon_id as $addonid)
			{
				if(sizeof($price[$addonid]) > 0)
				{
					$addon_id_arr[] = array(  	"product_id" 	=> $id,
												"addon_id" 		=> $addonid,
												"price"			=> $price[$addonid]
											);
				}
			}
			if(count($addon_id_arr) > 0) $res = $P->set_addons($addon_id_arr);
			else $P->delete_addons();
			
			/*if($res) warning("edit", "success");
			else warning("edit", "fail");*/
			redirect($this->curpage);
			exit();
		}
		$data['row'] = $P->row;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'manage_add_on_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
}

/* End of file products.php */
/* Location: ./application/controllers/admin/products.php */