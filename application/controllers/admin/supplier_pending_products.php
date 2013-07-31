<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier_pending_products extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/supplier_pending_products";
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
		
		if($_GET['keyword'] != "") $data['list'] = OSupplierPendingProduct::search($_GET['keyword']);
		else $data['list'] = OSupplierPendingProduct::get_list($start, $perpage, "id DESC", "");
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= "{$this->curpage}/page?perpage=$perpage";
 		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'supplier_pending_product_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function details($id/*, $start=0*/)
	{ 
		$data['SPP'] = $SPP = new OSupplierPendingProduct($id);
		
		if(!empty($id))
		{			
			if($SPP->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
				exit();
			}
		}
		
		/*$start 				= $_GET['page'];
		$perpage 			= $_GET['perpage'];
		if(empty($start))	$start = 0;
		if(empty($perpage))	$perpage = 10;
		$data['list'] 		= $SPP->get_details($start, $perpage);
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= "{$this->curpage}/details/{$id}?";
 		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);*/
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'supplier_pending_product_details_list', $data);
		$this->load->view($this->ffoot, $data);
		unset($SPP);
	}
	
	
	public function approve($id)
	{
		$data['SPP'] = $SPP = new OSupplierPendingProduct($id);
		
		if(!empty($id))
		{			
			if($SPP->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
				exit();
			}
		}
		
		$this->form_validation->set_rules('supplier_id', 'Supplier ID', 'trim|required');
		/*$this->form_validation->set_rules('product_unique_key', 'Product ID', 'trim|required');*/
		$this->form_validation->set_rules('product_name', 'Product Name', 'trim|required');
		$this->form_validation->set_rules('category_id', 'Category ID', 'trim|required');
		/*$this->form_validation->set_rules('type_id', 'Type ID', 'trim|required');
		$this->form_validation->set_rules('theme_id', 'Theme ID', 'trim|required');
		$this->form_validation->set_rules('brand_id', 'Brand ID', 'trim|required');*/
		/*$this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
		$this->form_validation->set_rules('size_name', 'Size Name', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');*/
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
		
			if($SPP->row != "")
			{
				//cek BRAND, if name is not exist, SAVE as new brand...if exist, get the ID
				/*$cek_brand_name = $this->db->query("SELECT * FROM brands WHERE name = ?", array(strtolower($brand_name)));
				$res_cek_brand = $cek_brand_name->row();
				
				if(strtolower($res_cek_brand->id) == "")
				{
					$arr_brand = array(	'category_id'	=> $category_id,
										'name'			=> $brand_name
									   );
					
					$brand_id = OBrand::add($arr_brand);
				}
				else
				{
					$B = new OBrand($res_cek_brand->id);
					$brand_id = $B->row->id;
				}*/
				
				$multi_text = explode("\n", $multiple_text_fields);				
			
				$arr_product = array(	/*'product_unique_key'	=> $product_unique_key,*/
									 	'name'				 	=> $product_name,
										'name_en'				 	=> $product_name,
										'description'			=> '<p>'.nl2br($description).'</p>',
										'description_en'		=> '<p>'.nl2br($description).'</p>',
										'category_id'		 	=> $category_id,
										/*'brand_id'			=> $brand_id,
										'type_id'				=> $type_id,
										'theme_id'				=> $theme_id,
										'seo_keyword'			=> $seo_keyword	*/									
									 );
				
				if($product_unique_key) $arr_product['product_unique_key'] = $product_unique_key;
				if($brand_id) $arr_product['brand_id'] = $brand_id;
				if($type_id) $arr_product['type_id'] = $type_id;
				if($theme_id) $arr_product['theme_id'] = $theme_id;
				/*if($seo_keyword) $arr_product['seo_keyword'] = $seo_keyword;*/
				
				if($multiple_text_fields) 
				{
					$arr_product['multiple_text_fields'] = '<p>'.implode("\n", $multi_text).'</p>';
					$arr_product['multiple_text_fields_en'] = '<p>'.implode("\n", $multi_text).'</p>';
				}
				$arr_product['estimate_delivery'] = $estimate_delivery;
				
				$new_product = OProduct::add($arr_product);
				
				//add to SIZES, PRODUCT SIZES, SUPPLIER PRODUCT SIZES
				if($multiple_sizes)
				{
					$sizes =  explode("\n", $multiple_sizes);
					
					foreach($sizes as $r)
					{
						$prices = explode(":", $r);
						
						//cek SIZE, if name is not exist, SAVE as new size...if exist, get the ID
						$cek_size_name = $this->db->query("SELECT * FROM sizes WHERE name LIKE ?", array(strtolower($prices[0])));
						$res_cek_size = $cek_size_name->row();
						
						if($res_cek_size->id == "")
						{
							$arr_size = array(	'category_id'	=> $category_id,
												'name'			=> $prices[0],
												'name_en'			=> $prices[0]
											   );
							$size_id = OSize::add($arr_size);
						}
						else
						{
							$S = new OSize($res_cek_size->id);
							$size_id = $S->row->id;
						}
						
						$arr_product_size = array(	'product_id'	=> $new_product,
													'size_id'		=> $size_id,
													'price'			=> $prices[1],
													'final_price'  	=> $prices[1]
												);
						
						$new_product_size = OProductSize::add($arr_product_size);
						
						$arr_supplier_product_size = array(	'supplier_id'	=> $SPP->row->supplier_id,
															'product_id'	=> $new_product,
															'size_id'		=> $size_id,
															'price'			=> $prices[1],
															'final_price'	=> $prices[1]
														);
					
						$new_supplier_product_size = OSupplierProductSize::add($arr_supplier_product_size);
					}
				}
				if($type_id) { $this->db->insert('product_type_categories',array('product_id' => $new_product, 'type_category_id' => $type_id)); }
				if($theme_id) { $this->db->insert('product_theme_categories',array('product_id' => $new_product, 'theme_category_id' => $theme_id)); }
				//cek SIZE, if name is not exist, SAVE as new size...if exist, get the ID
				/*$cek_size_name = $this->db->query("SELECT * FROM sizes WHERE name = ?", array(strtolower($size_name)));
				$res_cek_size = $cek_size_name->row();
				
				if(strtolower($res_cek_size->id) == "")
				{
					$arr_size = array(	'category_id'	=> $category_id,
										'name'			=> $size_name
									   );
					
					$size_id = OSize::add($arr_size);
				}
				else
				{
					$S = new OSize($res_cek_size->id);
					$size_id = $S->row->id;
				}*/
				
				//add to PRODUCT
				/*$arr_product = array(	'name'			=> $product_name,
										'category_id'	=> $category_id,
										'brand_id'		=> $brand_id,
										'description'	=> $description
									 );
				
				$new_product = OProduct::add($arr_product);*/
				
				//add to PRODUCT SIZE
				/*$arr_product_size = array(	'product_id'	=> $new_product,
											'size_id'		=> $size_id
										);
				
				$new_product_size = OProductSize::add($arr_product_size);*/
				
				//add to PRODUCT PHOTO
				$photo = OSupplierPendingProductPhoto::get_list(0, 0, "id DESC", $SPP->row->id);
				
				foreach($photo as $r)
				{
					$arr_product_photo = array(	'product_id'	=> $new_product,
												'image'			=> $r->image
											);
					
					
					$new_product_photo = OProductPhoto::add($arr_product_photo);
				}
				//-->MASIH KURANG: import photo dari folder supplier_pending_product_photo ke folder product 
				
				
				//add to SUPPLIER PRODUCT SIZE
				/*$arr_supplier_product_size = array(	'supplier_id'	=> $SPP->row->supplier_id,
													'product_id'	=> $new_product,
													'size_id'		=> $size_id
												);
				
				$new_supplier_product_size = OSupplierProductSize::add($arr_supplier_product_size);*/
				
				
				//UPDATE SUPPLIER PENDING PRODUCT
				$arr['status'] = "approved";
				$res = $SPP->edit($arr);				
				
				if($res) warning("edit", "success");
				else warning("edit", "fail");				
				redirect($this->curpage);
				exit();			
			}	
		}
		$data['row'] = $SPP->row;
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'supplier_pending_product_approve', $data);
		$this->load->view($this->ffoot, $data);
		unset($SPP);		
	}
	/*
	
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
	
	
	
	public function act($act="", $id)
	{
		$SPP = new OSupplierPendingProduct($id);	
		
		if(!empty($id))
		{			
			if($SPP->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		if($act == "published") $arr['status'] = "published";
		if($act == "pending") $arr['status'] = "pending";
		
		$res = $SPP->edit($arr);
			
		if($res) warning("edit", "success");
		else warning("edit", "fail");				
		redirect($this->curpage);
		exit();
	}*/	
	
	
	public function delete($id)
	{
		$SPP = new OSupplierPendingProduct($id);
		
		if(!empty($id))
		{			
			if($SPP->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		$res = $SPP->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
		unset($SPP);
	}
}

/* End of file supplier_pending_products.php */
/* Location: ./application/controllers/admin/supplier_pending_products.php */