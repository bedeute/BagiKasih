<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supplier extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->cu = $cu = get_logged_in_supplier();		
		if(!$cu) supplier_logout();
		//error_reporting(E_ALL);
	}
	
	public function index()
	{
		$this->profile();
	}
	
	public function home()
	{
		$this->profile();
	}
	
	public function profile()
	{
		$data['cu'] = $cu = $this->cu;
		
		$this->load->view("header", $data);
		$this->load->view("supplier_profile", $data);
		$this->load->view("footer", $data);
	}
		
	public function edit_profile()
	{
		$data['cu'] = $cu = $this->cu;
		
		if(sizeof($_POST) > 0)
		{
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('address', 'Address', 'trim|required');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
			/*$this->form_validation->set_rules('fax', 'Fax', 'trim|required');
			$this->form_validation->set_rules('website', 'Website', 'trim|required');
			$this->form_validation->set_rules('description', 'Description', 'trim|required');*/
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check');			
			$this->form_validation->set_error_delimiters('', '<br />');
			
			extract($_POST);
			
			if(trim($_POST['old_password']) != "" || trim($_POST['password']) != "" || trim($_POST['retype_password']) != "") 
			{
				$this->form_validation->set_rules('old_password', 'Current Password', 'trim|required||min_length[6]|callback_old_password_check');
				$this->form_validation->set_rules('password', 'Password', 'trim|required||min_length[6]|matches[retype_password]|md5');
				$this->form_validation->set_rules('retype_password', 'Retype Password', 'trim|required|min_length[6]');
			}
			
			if ($this->form_validation->run() == TRUE)
			{
				$inc_arr = array("name", "address", "phone", /*"fax", "website", "description",*/ "email");
				$arr = NULL;
				foreach($_POST as $key => $val)
				{
					if(in_array($key, $inc_arr))
					{
						$arr[$key] = $val;
					}
				}
				if($location_id) $arr['location_id'] = $location_id;
				
				if(trim($_POST['password']) != "") 
				{
					$arr['password'] = md5($_POST['password']);
				}
				
				if($fax) $arr['fax'] = $fax;
				if($website) $arr['website'] = $website;
				if($description) $arr['description'] = $description;
				
				$U = new OSupplier();
				$U->setup($cu);
				
				/*if($userfile != NULL)
				{
					$image_file = TDUser::save_image($userfile);
					$arr['image'] = $image_file;
					$U->delete_current_image();
				}	*/			
				
				$exec = $U->edit($arr);
				if($exec)
				{
					if($_POST['email'] != $cu->email) 
					{
						set_login_session($_POST['email'], $cu->password, $type="supplier");
					}
					if($_POST['password'] != "")
					{
						set_login_session($cu->email, md5($_POST['password']), $type="supplier");
					}
					if($_POST['email'] != $cu->email && $_POST['password'] != "") 
					{
						set_login_session($_POST['email'], md5($_POST['password']), $type="supplier");
					}
							
					$this->session->set_flashdata("success_profile", "Your profile has been updated.");
					redirect('supplier/profile');
				}
				else {
					$data['error_string'] = "There is an error in the system. Please contact the website administrator.";					
				}				
			}
		}
		$this->load->view("header", $data);
		$this->load->view("supplier_edit_profile", $data);
		$this->load->view("footer", $data);
	}
	
	public function email_check($str)
	{
		extract($_POST);
		$email = $this->session->userdata("sname");
		$res = $this->db->query("SELECT * FROM suppliers WHERE email = ? AND email NOT IN(?)", array($str, $email));
		if (!emptyres($res))
		{
			$this->form_validation->set_message('email_check', 'Please choose another email!');
			
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function old_password_check($str)
	{
		$email = $this->session->userdata("sname");
		$res = $this->db->query("SELECT * FROM suppliers WHERE password = ? AND email = ?", array(md5(md5($str)), $email));
		if (emptyres($res))
		{
			$this->form_validation->set_message('old_password_check', 'Invalid your current password!');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	/*public function post_items()
	{
		$data['cu'] = $cu = $this->cu;
		
		$this->form_validation->set_rules('product_name', 'Product Name', 'trim|required');
		$this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
		$this->form_validation->set_rules('size_name', 'Size Name', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$include_arr = array("product_name", "category_id", "brand_name", "size_name", "description");
			
			$arr = NULL;			
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					if(trim($val) != "") $arr[$key] = trim($val);
					$arr['supplier_id'] = $cu->id;
					$arr['status'] = "pending";
				}
			}
			
			$new = OSupplierPendingProduct::add($arr);	
				
			if($new) 
			{
				$image_arr = NULL;
				if($_POST['image'] != NULL)
				{
					foreach($_POST['image'] as $image)
					{
						$image_file = OSupplierPendingProductPhoto::save_photo($image);
						$image_arr[] = array("supplier_pending_product_id" => $new, "image" => $image_file);
					}
					$photos = OSupplierPendingProductPhoto::add_batch($image_arr);
				}
				
				$this->session->set_flashdata('post_item_success', 'Items that you enter is sent. We will immediately confirm the item. Thank you.');
			}
			redirect('supplier/profile');
			exit();
		}
		$this->load->view("header", $data);
		$this->load->view("supplier_post_item", $data);
		$this->load->view("footer", $data);	
	}*/
	
	public function new_orders()
	{
		$data['cu'] = $cu = $this->cu;
		
		$this->load->view("header", $data);
		$this->load->view("supplier_new_order", $data);
		$this->load->view("footer", $data);
	}
	
	public function approve_new_orders($order_detail_id, $order_assign_supplier_id)
	{
		$ODAS = new OOrderDetailAssignSupplier($order_assign_supplier_id);
		
		if(!empty($order_assign_supplier_id))
        {			
            if($ODAS->row == FALSE)
            {
                $this->session->set_flashdata('warning', 'Assign Supplier does not exist.');
				unset($ODAS);
                redirect('supplier/new_orders');
                exit();
            }
        }
		
		$data['cu'] = $cu = $this->cu;
		
		$arr['status'] = "approved";
		$arr['qty'] = $_POST['qty'];
		if(intval($arr['qty']) == 0)
		{
			$this->reject_new_orders($order_detail_id);
		}
		else if(intval($arr['qty']) > intval($ODAS->row->qty))
		{
			$this->session->set_flashdata('amount_less', 'Jumlah yang di-Appove tidak boleh lebih dari Qty.');	
			redirect('supplier/new_orders');
			exit();
		}
		else
		{
		
			$S = new OSupplier($cu->id);
			$OD = new OOrderDetail($order_detail_id);
			$order_id = $OD->get_order_id();
			$arr_history['note'] = "Supplier ".$S->row->name." (ID : ".$S->row->id.") CONFIRM DETAIL #".$order_detail_id." dengan jumlah: ".$_POST['qty'];
			$arr_history['order_id'] = $order_id->order_id;
		
			$res_history = OOrder_History::add($arr_history);
			
			//$res = $this->db->update('order_detail_assign_suppliers', $arr, array('order_detail_id' => $order_detail_id, 'supplier_id' => $cu->id, 'status' => 'pending'));
			
			$res = $ODAS->edit($arr);
			
			if($res && $res_history) $this->session->set_flashdata('approve_new_order_success', 'Konfirmasi penyanggupan pesanan telah diterima.');
			unset($S, $OD, $ODAS);
			redirect('supplier/new_orders');
			exit();
		}
	}
	
	public function reject_new_orders($order_detail_id)
	{
		$data['cu'] = $cu = $this->cu;
				
		$arr['status'] = "rejected";
		
		$S = new OSupplier($cu->id);
		$OD = new OOrderDetail($order_detail_id);
		$order_id = $OD->get_order_id();
		$arr_history['note'] = "Supplier ".$S->row->name." (ID : ".$S->row->id.") REJECT order id #".$order_detail_id."";
		$arr_history['order_id'] = $order_id->order_id;
	
		$res_history = OOrder_History::add($arr_history);
		
		$this->db->delete('order_detail_assign_suppliers',array('order_detail_id' => $order_detail_id, 'supplier_id' => $S->row->id));		
		//$res = $this->db->update('order_detail_assign_suppliers', $arr, array('order_detail_id' => $order_detail_id, 'supplier_id' => $cu->id, 'status' => 'rejected'));	
		
		if($res && $res_history) $this->session->set_flashdata('approve_new_order_success', 'New Orders has been rejected.');
		unset($S, $OD);
		redirect('supplier/new_orders');
		exit();		
	}
	
	public function supplier_handle_products()
	{
		$data['cu'] = $cu = $this->cu;
		
		//$data['list'] = OSupplierProductSize::get_list(0, 0, "id DESC", "", $cu->id);
		$data['list'] = OSupplierProductSize::get_list_distinct_product(0, 0, "id DESC", "", $cu->id);
		//$data['list'] = OSupplierPendingProduct::get_list(0, 0, "id DESC", "approved");
		
		$this->load->view("header", $data);
		$this->load->view("supplier_handle_product_list", $data);
		$this->load->view("footer", $data);
	}
	
	/*public function active_handle_products($id)
	{
		$data['cu'] = $cu = $this->cu;
		
		if(sizeof($_POST) > 0)
		{
			extract($_POST);
			
			$this->form_validation->set_rules('price', 'Price', 'trim|required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
			if($this->form_validation->run() == TRUE)
			{
				$arr = array('status' 	=> 'active',
							 'price' 	=> $price
							 );
				
				$res = $this->db->update('supplier_product_sizes', $arr, array('id' => $id, 'supplier_id' => $cu->id));
				
				if($res) warning("edit", "success");
				else warning("edit", "fail");				
				redirect('supplier/supplier_handle_products');
				exit();
			}
		}		
		$this->load->view("header", $data);
		$this->load->view("supplier_handle_product_form", $data);
		$this->load->view("footer", $data);		
	}
	
	public function non_active_handle_products($id)
	{
		$data['cu'] = $cu = $this->cu;
			
		$arr = array('status' 	=> 'nonactive');
		
		$res = $this->db->update('supplier_product_sizes', $arr, array('id' => $id, 'supplier_id' => $cu->id));
		
		if($res) warning("edit", "success");
		else warning("edit", "fail");				
		redirect('supplier/supplier_handle_products');
		exit();		
	}*/
	
	public function add_products()
	{
		$data['cu'] = $cu = $this->cu;
		if(!$cu) redirect('/');
		
		/*$this->form_validation->set_rules('product_unique_key', 'Product ID', 'trim|required');*/
		$this->form_validation->set_rules('product_name', 'Product Name', 'trim|required');
		$this->form_validation->set_rules('category_id', 'Category', 'trim|required');
		/*$this->form_validation->set_rules('type_id', 'Type', 'trim|required');
		$this->form_validation->set_rules('theme_id', 'Theme', 'trim|required');
		$this->form_validation->set_rules('brand_id', 'Brand', 'trim|required');*/
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
	
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			
			$include_arr = array("product_name", "category_id"/*, "type_id", "theme_id", "brand_id", "seo_keyword"*/);
			
			$arr = NULL;			
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					if(trim($val) != "") $arr[$key] = trim($val);
				}
			}
			//if($product_unique_key) $arr['product_unique_key'] = $product_unique_key;
			
			$arr['supplier_id'] = $cu->id;
			$arr['status'] = "pending";
			if($type_id) $arr['type_id'] = $type_id;
			if($theme_id) $arr['theme_id'] = $theme_id;
			if($brand_id) $arr['brand_id'] = $brand_id;
			
			if($estimate_delivery) $arr['estimate_delivery'] = $estimate_delivery;
			if($description) $arr['description'] = $description;
			
			$cek_product = OProduct::get_list(0, 0, "id DESC", $product_unique_key);
			$cek_supplier_pending_product = OSupplierPendingProduct::get_list(0, 0, "id DESC", "", $product_unique_key);
			
			if(sizeof($cek_product) <= 0 && sizeof($cek_supplier_pending_product) <= 0) 
			{
				$arr['product_unique_key'] = $product_unique_key;
			}
			else $this->session->set_flashdata('cek_product_unique_key', 'This Product ID already exists');
			
			$multi_text = explode("\n", $multiple_text_fields);
			$multi_sizes =  explode("\n", $multiple_sizes);
			
			if($multiple_text_fields) $arr['multiple_text_fields'] = implode("\n", $multi_text);
			if(sizeof($size) > 0 && sizeof($price) > 0) 
			{
				$multi_sizes = array();
				foreach($size as $key => $sz)
				{
					$multi_sizes[] = "{$sz}: ".$price[$key];
				}
				$arr['multiple_sizes'] = implode("\n", $multi_sizes);
			}
			else
			{
				$arr['multiple_sizes'] = "";
			}
			$new = OSupplierPendingProduct::add($arr);
				
			if($new) 
			{
				$O = new OSupplierPendingProduct($new);
				$O->edit(array('product_unique_key' => $new));
				unset($O);
				
				$image_arr = NULL;
				if($_POST['image'] != NULL)
				{
					foreach($_POST['image'] as $image)
					{
						$image_file = OSupplierPendingProductPhoto::save_photo($image);
						$image_arr[] = array("supplier_pending_product_id" => $new, "image" => $image_file);
					}
					$photos = OSupplierPendingProductPhoto::add_batch($image_arr);
				}
				
				$this->session->set_flashdata('post_item_success', 'Items that you enter is sent. We will immediately confirm the item. Thank you.');
			}
			redirect('supplier/supplier_handle_products');
			exit();
		}
				
		$this->load->view("header", $data);
		$this->load->view("supplier_post_item", $data);
		$this->load->view("footer", $data);	
	}
	
	public function ajax($action, $param1)
	{
		extract($_POST);
				
		if($action == "shipped")
		{
			$id = $param1;
			if(empty($id)) die();
			echo $this->load->view("tpl_supplier_ship_note", array("id" => $id), TRUE);
			die();
		}
	}
	
	public function shipped($id)
	{
		$data['cu'] = $cu = $this->cu;
		if(!$cu) redirect('/');
		
		if($id == "") redirect($this->curpage);
		
		$OD = new OOrderDetail($id);
		if($OD->row == "") redirect($this->curpage);
		
		// update order_detail_assign_suppliers, status = shipped dan edit shipping note
		extract($_POST);
		$arr['status'] = 'shipped';
		$arr['shipping_date'] = $shipping_date;
		$arr['shipping_note'] = $shipping_note;		
		
		$res = $this->db->update('order_detail_assign_suppliers', $arr, array('order_detail_id' => $OD->row->id, 'supplier_id' => $cu->id, 'status' => 'confirmed'));	
		
		if($res)
		{
			// kirim email ke user kalo barang sudah shipped
			$get_order_id = $OD->get_order_id();
			$order_id = $get_order_id->order_id;
			
			$O = new OOrder($order_id);
			$U = new OUser($O->row->user_id);
						
			$to = $U->row->email;
			$subject = "Hapikado.com - Items Shipped";
			$content = $this->load->view('tpl_email_items_shipped', array("order_id" => $O->row->id, "order_detail_id" => $OD->row->id, "user_id" => $U->row->id, "shipping_note" => $shipping_note, "shipping_date" => $shipping_date), TRUE);
			noreply_mail($to,$subject,$content);			
			
			$O->refresh();
			$cek_details = $O->get_details();
			// cek apakah order_detail_assign_suppliers, sudah shipped semua
			foreach($cek_details as $r)
			{
				$cek_assign_suppliers = OOrderDetailAssignSupplier::get_list(0, 0, "id DESC", $r->id, "", "confirmed");
			}
			
			// jika sudah shipped semua, kirim email ke user kalo sudah dikirm semua
			if(sizeof($cek_assign_suppliers) <= 0)
			{
				$list = OOrderDetail::get_list(0, 0, "id DESC", $O->row->id);
				
				$to_all = $U->row->email;
				$subject_all = "Hapikado.com - ALL Items Shipped";
				$content_all = $this->load->view('tpl_all_email_items_shipped', array('O' => $O, 'list' => $list), TRUE);
				noreply_mail($to_all,$subject_all,$content_all);
				
				$arr_order['status_new'] = 'shipped';
				$arr_order['dt_shipped'] = date("Y-m-d");
				$O->edit($arr_order);
			}
			warning("edit", "success");
		}
		else warning("edit", "fail");
		
		unset($OD, $O, $U);
		$this->session->set_flashdata('shipped_msg', 'Email has been sent.');
		redirect('supplier/new_orders');
	}
	
	public function supplier_brand_list()
	{
		$data['cu'] = $cu = $this->cu;
		
		$data['list'] = OBrand::get_list(0, 0, "id DESC", "", $cu->id);
		
		$this->load->view("header", $data);
		$this->load->view("supplier_brand_list", $data);
		$this->load->view("footer", $data);
	}
	
	public function brand_form($id)
	{
		$cus = get_logged_in_supplier();
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
								'supplier_id' 	=> $cus->id,
								'shipping_fee' 	=> $shipping_fee,
								'url_title' 	=> strtolower(str_replace(' ', '_', $name))
							);
				$res = $OBrand->edit($arr);
				
				if($res) warning("edit", "success");
				else warning("edit", "fail");				
				redirect('supplier/supplier_brand_list');
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
								'supplier_id' 	=> $cus->id,
								'shipping_fee' 	=> $shipping_fee,
								'url_title' 	=> strtolower(str_replace(' ', '_', $name))
							  // 'parent_id'	=> $parent_id
							);
				$new = OBrand::add($arr);				
				
				if($new) warning("add", "success");
				else warning("add", "fail");
				redirect('supplier/supplier_brand_list');
				exit();										
			}			
		}
		
		$data['cu'] = $this->cu;
		$this->load->view('header', $data);
		$this->load->view('supplier_brand_form', $data);
		$this->load->view('footer', $data);
	}
	
	public function delete_brand($id)
	{
		$OBrand = new OBrand($id);
		
		$res = $OBrand->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect('supplier/supplier_brand_list');
		exit();
	}
}

/* End of file supplier.php */
/* Location: ./application/controllers/supplier.php */