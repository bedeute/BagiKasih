<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->cu = $cu = get_logged_in_user();
		//error_reporting(E_ALL);
	}
	
	public function add_to_cart()
	{	
		if(sizeof($_POST) > 0)
		{
			extract($_POST); 
			
			$PS = new OProductSize($product_size_id);
			$P = new OProduct($PS->row->product_id);
			
			$cur_qty = 0;
			
			$itemexist = false;
			
			// go through every content of the cart to see if there is a similar size and addons.
			foreach ($this->cart->contents() as $items) 
			{ 
				// if the item id equals to the product size id
				if($items['id'] == $product_size_id)
				{
					// get the cart item's quantity
					$cur_qty = $items['qty'];
					
					// if this item has options
					if($this->cart->has_options($items['rowid']) == TRUE)
					{
						// get the product option "addon"
						$options = $this->cart->product_options($items['rowid']);

						$c_addonqty = ($options['addon_qty']);
						$c_addonid = ($options['addon_id']);
						$tmpaoid = array();
						$tmpaoqty = array();
						foreach($addon_qty as $aoid => $aoqty)
						{
							$tmpaoid[] = intval($aoid);
							$tmpaoqty[] = intval($aoqty);
						}
						$jsonaoid = json_encode($tmpaoid);
						$jsonaioqty = json_encode($tmpaoqty);
						if($jsonaoid == $c_addonid && $jsonaioqty == $c_addonqty)
						{
							$itemexist = true;
							$itemexistqty = $cur_qty;
							$itemexistrowid = $items['rowid'];
							break;
						}
						
						
					}
				}
			}
			
			
			if(!$itemexist)
			{
				// setup base to include add on pricing
				
				$data = array( 'id'		=> $product_size_id,
							   'qty'	=> intval($_POST['quantity']),
							   'price'	=> $PS->row->final_price,
							   'name'	=> $P->row->name,							   
							   'options' => array(	'product_id' 	=> $PS->row->product_id, 
													'size_id' 		=> $PS->row->size_id
												 )
						  );
				$totaladdonprice = 0;
				// setup parameter	
				if(sizeof($addon_qty) != 0)
				{
					$data['options']['addon'] = $addon_qty; // not used	
					$data['options']['addon_id'] = $data['options']['addon_qty'] = NULL;
					foreach($addon_qty as $key => $val)
					{
						$data['options']['addon_id'][] = intval($key);
						$data['options']['addon_qty'][] = intval($val);
						$AO = new OAddOn($key);
						$res = $AO->get_price($PS->row->product_id); 
						$totaladdonprice += floatval($res->price) * intval($val);
						unset($AO);
					}
					$data['options']['addon_id'] = json_encode($data['options']['addon_id']);
					$data['options']['addon_qty'] = json_encode($data['options']['addon_qty']);
				}
				$data['price'] = $PS->row->final_price + $totaladdonprice;
				$res = $this->cart->insert($data);
			}
			else
			{
				$this->cart->update(array('rowid' => $itemexistrowid, 'qty' => $itemexistqty + intval($_POST['quantity'])));
			}
			
			if($res) $this->session->set_flashdata('msg', $P->row->name.' has been added into the shopping cart.');
			unset($PS, $P);
			redirect('cart/user_cart');				

		}
	}
	
	public function user_cart()
	{	
		$last_page = $_SERVER['HTTP_REFERER'];
		if(stristr($last_page, "products/details")) $this->session->set_userdata("last_page", $last_page);
		
		$data['last_page'] = $this->session->userdata("last_page");
		
		
		// jika total harga < diskon, adjust diskon dan total point yang digunakan
		$price_before_discount = intval($this->cart->total()) + intval($this->session->userdata('total_all_addon'));
		//$discount = intval($this->session->userdata('point_used')) * intval(get_setting('convert_to_discount'));
		$old_discount = $this->session->userdata('old_discount');
		
		//var_dump($price_before_discount, $old_discount);
		if(intval($this->session->userdata('point_used')) > 0 && intval($price_before_discount) < intval($old_discount))
		{
			$adjust_point_used = floor($price_before_discount / doubleval(get_setting('convert_to_discount')));			
			$this->session->set_userdata('point_used', $adjust_point_used);
		}
		// end
		
		
		/*echo '<pre>';
		print_r($this->cart->contents());
		echo '</pre>';*/
			
		//tambah point
		if(sizeof($_POST) > 0)
		{
			extract($_POST);
			/*
			$arr_point['point_used'] = $point_used;
			
			$this->session->set_userdata('point', $arr_point);
			$this->session->set_userdata('point_used',intval($point_used));
			*/
			redirect('cart/member_area');
		}
				
		$this->load->view('header', $data);
		$this->load->view('shopping_cart', $data);
		$this->load->view('footer', $data);
	}
	
	public function update_cart()
	{	
		$this->cart->update($_POST); 
		
		$this->session->set_flashdata('msg', 'This Product has been updated');
		redirect('cart/user_cart');
	}
	
	public function delete_item($row_id)
	{
		$data = array( 'rowid'		=> $row_id,
						'qty'		=> 0
				 	  );
		$this->cart->update($data);
		$this->session->set_flashdata('msg', 'This Product has been deleted');
		
		redirect('cart/user_cart');
	}
	
	public function member_area()
	{
		if($this->cu) redirect('cart/check_out'); 
		
		if(sizeof($_POST) > 0)
		{
			extract($_POST);
			
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
			$this->form_validation->set_error_delimiters('', '<br />');
			
			if ($this->form_validation->run() == TRUE)
			{
				$user = get_user($email,md5(md5($password)));
				
				if(!$user) 
				{
					$data['error_string'] = "Invalid Email and Password Combination.";
				}
				else 
				{
					set_login_session($email, md5(md5($password)), $type="user");
					redirect('cart/check_out');
				}
			}
		}
		
		$data['initcss'] = array("additional_other");
		
		$this->load->view('header', $data);
		$this->load->view('member_area', $data);
		$this->load->view('footer', $data);
	}
	
	public function check_out()
	{	
		//untuk mencari ESTIMATE DELIVERY
		foreach($this->cart->contents() as $items)
		{
			if($this->cart->has_options($items['rowid']) == TRUE)
			{ 
				foreach($this->cart->product_options($items['rowid']) as $option_name => $option_value)
				{
					$arr[$option_name] = $option_value;
				}
			}
				
			$P = new OProduct($arr['product_id']);
			
			$min_estimate_delivery = 0;
			
			if(intval($P->row->estimate_delivery) > intval($min_estimate_delivery))
			{
				$min_estimate_delivery = intval($min_estimate_delivery) + intval($P->row->estimate_delivery);
			}
			unset($P);
		}
		$data['estimate_delivery'] = $min_estimate_delivery;
		
		$data['cu'] = $cu = $this->cu;		
		
		if(!$cu)
		{
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('address', 'Address', 'trim|required');
			$this->form_validation->set_rules('city', 'City', 'trim|required');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check');
			//$this->form_validation->set_rules('fax', 'Fax', 'trim|required');
			//$this->form_validation->set_rules('location_id', 'Location', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|matches[confirm_password]');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[6]');
		}
		
		$this->form_validation->set_rules('shipping_name', 'Shipping Name', 'trim|required');
		$this->form_validation->set_rules('shipping_address', 'Shipping Address', 'trim|required');
		$this->form_validation->set_rules('shipping_city', 'Shipping City', 'trim|required');
		//$this->form_validation->set_rules('shipping_phone', 'Shipping Phone', 'trim|required');
		//$this->form_validation->set_rules('shipping_fax', 'Shipping Fax', 'trim|required');
		
		$this->form_validation->set_rules('shipping_location_id', 'Shipping Location', 'trim|required');
		$this->form_validation->set_rules('shipping_date', 'Shipping Date', 'trim|required');
		$this->form_validation->set_rules('shipping_location_details', 'Shipping Location Details', 'trim|required');
		$this->form_validation->set_rules('shipping_fee', 'Shipping Fee', 'trim|required');
		$this->form_validation->set_rules('shipping_time', 'Shipping Time', 'trim|required');
		$this->form_validation->set_rules('shipping_no_address', 'Shipping No Address', 'trim|required');
		
		if($_POST['shipping_email'])
		{
			$this->form_validation->set_rules('shipping_email', 'Shipping Email', 'trim|valid_email');	
		}
		//$this->form_validation->set_rules('shipping_note', 'Shipping Note', 'trim|required');
		//$this->form_validation->set_rules('shipping_cards', 'Shipping Card', 'trim|required');
		
		$this->form_validation->set_error_delimiters('', '<br />');
		
		if(sizeof($_POST) > 0)
		{
			extract($_POST); 			
			$arr_shipping_details = array( 'shipping_name'				=> $shipping_name,
										   'shipping_address'			=> $shipping_address,
										   'shipping_city'				=> $shipping_city,										   
										   'shipping_location_id'		=> $shipping_location_id,
										   'shipping_date'				=> $shipping_date,
										   'shipping_time'				=> $shipping_time,
										   'shipping_location_details'	=> $shipping_location_details,
										   'shipping_fee'				=> $shipping_fee,
										   'shipping_no_address'		=> $shipping_no_address,
										   );
			
			if($shipping_phone) $arr_shipping_details['shipping_phone'] = $shipping_phone; 
			if($shipping_email) $arr_shipping_details['shipping_email'] = $shipping_email; 
			if($shipping_fax) $arr_shipping_details['shipping_fax'] = $shipping_fax;
			if($shipping_note) $arr_shipping_details['shipping_note'] = $shipping_note;
			if($shipping_cards) $arr_shipping_details['shipping_cards'] = $shipping_cards;
			$this->session->set_userdata('shipping_details', $arr_shipping_details);			
			
			//tambah baru
			if(!$cu)
			{
				$arr_register_new = array('name'		=> $name,
										  'address'		=> $address,
										  'city'		=> $city,
										  'phone'		=> $phone,
										  'fax'			=> $fax,
										  //'location_id'	=> $location_id,
										  'email'		=> $email,
										  'password'	=> $password
										  );
				
				$this->session->set_userdata('register_new', $arr_register_new);
			}
			//end
			if($this->form_validation->run() == TRUE)
			{
				
				
				
				//if($this->session->userdata('shipping_details') != "") redirect('cart/order_review');
				
				//ganti baru
				if($this->session->userdata('shipping_details') != "") 
				{
					if($cu || $this->session->userdata('register_new') != "")
					{
						redirect('cart/order_review'); 			
					}
				}
				//end
			}
			else
			{
				$this->session->set_flashdata('error',validation_errors());
				redirect('cart/check_out');
			}
		}
		
		$this->load->view('header', $data);
		$this->load->view('user_check_out', $data);
		$this->load->view('footer', $data);
	}
	
	public function order_review()
	{
		$data['cu'] = $cu = $this->cu;		
		//if(!$cu) redirect('cart/user_cart');
			
		
		//tambah ini(2)
		//cari total qty
		$shipping_details = $this->session->userdata('shipping_details');
		$data['point'] = $data['point_used'] = $point = $this->session->userdata('point_used');
		$data['credit_point'] = $credit_point = intval($point) * doubleval(get_setting('convert_to_discount'));
		
		$total_qty = 0;
		foreach($this->cart->contents() as $q)
		{
			$total_qty = intval($total_qty) + $q['qty'];
		}
		
		if($shipping_details['shipping_location_details'] == "inside")
		{
			$data['shipping_cost'] = $shipping_cost = intval($total_qty) * $shipping_details['shipping_fee'];
		}
		else
		{
			$data['shipping_cost'] = $shipping_cost = $shipping_details['shipping_fee'];
		}
		//end
		
		
		//tambah ini
		
		if(!$cu)
		{
			$data['arr_register_new'] = $register_new = $this->session->userdata('register_new');
			//var_dump($register_new);
			$arr = array(	'name'			=> $register_new['name'],
							'address'		=> $register_new['address'],
							'city'			=> $register_new['city'],
							'phone'			=> $register_new['phone'],
							'fax'			=> $register_new['fax'],
							'email'			=> $register_new['email'],
							'password'		=> md5(md5($register_new['password']))/*,
							'location_id'	=> $register_new['location_id']*/
							);
			
			$exec = OUser::add($arr);
			
			if($exec)
			{			
				set_login_session($register_new['email'], md5(md5($register_new['password'])), $type="user");
				
				/*$cek_newsletter = ONewsletter::get_list(0, 0, "", $register_new['email']);
				
				if(sizeof($cek_newsletter) <= 0) 
				{
					$arr_newsletter = array(	'email'	=> $register_new['email'],
												'is_subscribe'	=> '1',
												'type'			=> 'public'
											);
					
					$new_newsletter = ONewsletter::add($arr_newsletter);
				}*/
			}
		}
		//end
		
		$this->load->view('header', $data);
		$this->load->view('user_order_review', $data);
		$this->load->view('footer', $data);	
	}
	
	public function end_process()
	{
		
		$data['cu'] = $cu = $this->cu;		
		if(!$cu) redirect('cart/user_cart');
		
		$data['shipping_details'] = $shipping_details = $this->session->userdata('shipping_details');
		$U = new OUser();		
		$U->setup($cu);
		$total_point = $U->get_total_point();
		// clalculate point
		$data['point'] = $data['point_used'] = $point = $this->session->userdata('point_used');
		$data['credit_point'] = $credit_point = intval($point) * doubleval(get_setting('convert_to_discount'));
		// check if the used point is enough with the existing total_point
		if($point > 0)
		{
			if($total_point < intval($point))
			{
				$this->session->set_flashdata('error','You do not have sufficient points. Please update your points.');
				redirect('cart/user_cart');
				exit;
			}
		}
		
		
		
		
		
		$sisa_point = intval($total_point) - intval($point);
		
		$U->update_point($sisa_point);
		

		
		//tambah
		$total_all_addon = 0;
		foreach($this->cart->contents() as $items)
		{
			$PS = new OProductSize($items['id']);
			$OP = new OProduct($PS->row->product_id);
			$B = new OBrand($OP->row->brand_id);
			$a1[] = $B->row->id;
			$a2[] = $B->row->shipping_fee;
			$aaa = array_combine($a1, $a2);
			
			if($this->cart->has_options($items['rowid']) == TRUE)
			{ 
				foreach($this->cart->product_options($items['rowid']) as $option_name => $option_value)
				{
					$arr_opt[$option_name] = $option_value;												
					if($option_name == "addon")
					{
						$total_price_addon = 0;
						foreach($option_value as $id => $qty)
						{
							if($qty)
							{
								if(sizeof($id) > 0) 
								{
									$AO = new OAddOn($id);
									$res = $AO->get_price($P->row->id); 
																								
									$total_price_addon = $total_price_addon + (doubleval($qty) * doubleval($res->price));
									
									unset($AO);
								}
							}
						}										
					}
				}
			}
			$total_all_addon = $total_all_addon + $total_price_addon;
		}
		$points_received = $this->session->userdata("points_received");
		$coupn = ($this->cart->total() * $this->session->userdata('coupdisc')) / 100;
		$subtotal = doubleval($this->cart->total());
		// foreach($aaa as $id => $fee) { $fee2	= $fee2 + $fee; }
		$grand_total = doubleval($this->cart->total()) - $coupn;
		//end
		
		$arr_order = array(	'user_id' 		=> $cu->id,
							'name'			=> $shipping_details['shipping_name'],
							'address'		=> $shipping_details['shipping_address'],
							'city'			=> $shipping_details['shipping_city'],
							'location_id'	=> $shipping_details['shipping_location_id'],
							/*'phone'			=> $shipping_details['shipping_phone'],
							'email'			=> $shipping_details['shipping_email'],*/
							'shipping_date'	=> $shipping_details['shipping_date'],
							'shipping_time'	=> $shipping_details['shipping_time'],
							/*'shipping_note'	=> $shipping_details['shipping_note'],*/
							'subtotal'		=> $subtotal,//intval($this->cart->total()),
							'discount'		=> $credit_point,
							'points_used'   => $point,
							'points_received'   => $points_received,
							//'grand_total'	=> $grand_total,//intval($this->cart->total()),
							'status'		=> 'pending',
							'shipping_no_address'	=> $shipping_details['shipping_no_address'],
							'shipping_location_details'	=>	$shipping_details['shipping_location_details'],
							'coup_disc'		=>	$coupn
							/*'shipping_cards'		=> $shipping_details['shipping_cards']*/
						   );
		
		if($shipping_details['shipping_note']) $arr_order['shipping_note'] = $shipping_details['shipping_note'];
		if($shipping_details['shipping_cards']) $arr_order['shipping_cards'] = $shipping_details['shipping_cards'];
		if($shipping_details['shipping_email']) $arr_order['email'] = $shipping_details['shipping_email'];
		if($shipping_details['shipping_phone']) $arr_order['phone'] = $shipping_details['shipping_phone'];
		
		//cari total qty
		$total_qty = 0;
		foreach($this->cart->contents() as $q)
		{
			$total_qty = intval($total_qty) + $q['qty'];
		}
		
		if($shipping_details['shipping_location_details'] == "inside")
		{
			$arr_order['shipping_cost'] = $shipping_cost = intval($total_qty) * $shipping_details['shipping_fee'];
		}
		else
		{
			$arr_order['shipping_cost'] = $shipping_cost = $shipping_details['shipping_fee'];
		}
		/*$arr_order['grand_total'] = $total_all = intval($grand_total) + intval($shipping_cost);*/
		$arr_order['grand_total'] = $total_all = intval($grand_total) + intval($shipping_cost) - $credit_point;
		//end
		
		
		
		$new_order = OOrder::add($arr_order);				
				
		//ganti ini
		$total_all_addon = 0;
		foreach($this->cart->contents() as $r)
		{
			$P = new OProduct($r['options']['product_id']);
			$S = new OSize($r['options']['size_id']);
			
			if($this->cart->has_options($r['rowid']) == TRUE)
			{ 
				foreach($this->cart->product_options($r['rowid']) as $option_name => $option_value)
				{
					$arr_opt[$option_name] = $option_value;												
					if($option_name == "addon")
					{
						$total_price_addon = 0;
						foreach($option_value as $id => $qty)
						{
							if($qty != "")
							{
								//$option = array($option_value);
								$j_option = json_encode($option_value);
								
								$AO = new OAddOn($id);
								$res = $AO->get_price($P->row->id);																							
								
								$total_price_addon = $total_price_addon + (doubleval($qty) * doubleval($res->price));								
							}
						}
						
					}
				}
				
				//var_dump($j_option); die();
				$arr_order_detail = array(    'order_id'			=> $new_order,
											  'qty'					=> intval($r['qty']),
											  'product_id'			=> intval($r['options']['product_id']),
											  'size_id'				=> intval($r['options']['size_id']),
											  'product_name'		=> $P->row->name,
											  'size_name'			=> $S->row->name,
											  'price'				=> intval($r['price']),
											  'final_price'			=> intval($r['price']) * intval($r['qty'])
										  );
				
				if($j_option != "") $arr_order_detail['addons'] = $j_option;
			
				$new_order_detail = OOrderDetail::add($arr_order_detail);
				
				unset($P, $S);
			}
			// $total_all_addon = $total_all_addon + $total_price_addon;
		}
		//end
		
		
		//foreach($this->cart->contents() as $r)
//		{
//			$P = new OProduct($r['options']['product_id']);
//			$S = new OSize($r['options']['size_id']);
//			//$SPS = new OSupplierProductSize($r->id);			
//			
//			$arr_order_detail = array(    'order_id'			=> $new_order,
//										  'qty'					=> intval($r['qty']),
//										  'product_id'			=> intval($r['options']['product_id']),
//										  'size_id'				=> intval($r['options']['size_id']),
//										  'product_name'		=> $P->row->name,
//										  'size_name'			=> $S->row->name,
//										  'price'				=> intval($r['price']),
//										  'final_price'			=> intval($r['price'])/*,
//										  'supplier_id'			=> intval($r['options']['supplier_id']),
//										  'supplier_cost'		=> intval($SPS->row->price),	
//										  'supplier_final_cost'	=> intval($SPS->row->price)*/
//									  );
//			
//			$new_order_detail = OOrderDetail::add($arr_order_detail);
//			
//			unset($P, $S/*, $SPS*/);
//		}
		
	
		
		$data['konten'] = $this->cart->contents();
		$data['new'] = $new_order;
		$data['sub_total'] = $this->cart->total();
		$data['total']  = intval($total);
		
		$data['shipping_fee'] = intval($shipping_cost);
		$data['gran_total'] = intval($total_all);
		
		//email ke user
		$to_user = $cu->email;
		$subject_user = "Hapikado.com Order Detail #{$new_order}";
		$body_user = $this->load->view('tpl_email_cart_user', $data, TRUE);
		noreply_mail($to_user,$subject_user,$body_user);
		
		//email ke admin
		$admin_emails = OAdminEmail::get_list(0, 0);
		
		foreach($admin_emails as $ae)
		{
			$to = $ae->email;
			$subject = "Hapikado.com Order Detail #{$new_order}";
			$body = $this->load->view('tpl_email_cart_admin', $data, TRUE);
			noreply_mail($to,$subject,$body);
		}
		
		$this->delete_cart();
		$this->session->unset_userdata('shipping_details');		
		redirect('cart/success');		
	}
	
	public function delete_cart()
	{
		$this->cart->destroy();
	}
	
	public function success()
	{
		/*if(sizeof($_POST) > 0)
		{
			extract($_POST);
			
			$register_new = $this->session->userdata('register_new');
			
			if($receive_newsletter == "no") 
			{
				$cek_newsletter = ONewsletter::get_list(0, 1, "", $register_new['email']);
			
				if(sizeof($cek_newsletter) > 0) 
				{
					foreach($cek_newsletter as $r)
					{
						$N = new ONewsletter($r->id);
						
						$arr['is_subscribe'] = '0';
						
						$res = $N->edit($arr);
						
						if($res) $this->session->set_flashdata('newsletter_msg', 'Anda Tidak Akan Menerima Newsletter Kami.');
						unset($N);
					}
				}
			}
			else $this->session->set_flashdata('newsletter_msg', 'Anda Akan Menerima Newsletter Kami.');
			
			$this->session->unset_userdata('register_new');
			unset_login_session($type="user");
		}*/
		$this->load->view('header', $data);
		$this->load->view('success_page', $data);
		$this->load->view('footer', $data);
	}
	
	public function email_check($str)
	{
		$res = $this->db->query("SELECT * FROM users WHERE email = ?", array($str));
		
		if (!emptyres($res))
		{
			$this->form_validation->set_message('email_check', 
												'This email has been used. If you are the owner of this email, please <a id="login-link-2">login</a>.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function coupon()
	{
		$coup = get_coupdisc($_POST['coupon'], date('Y-m-d'));
		if(!$coup) {
			$this->session->set_flashdata('msg_coup', 'Coupon Code yang Anda Masukkan Salah');
		} else {
			$this->session->set_flashdata('msg_coup', 'Anda Mendapat Diskon '.$coup->discount.'%');
			$this->session->set_userdata('coupdisc', $coup->discount);
		}
		redirect('cart/user_cart');
	}
	
	public function update_point()
	{
		$cu = $this->cu;
		if($cu && intval($_POST['point_used']) > 0)
		{
			$points = OPoint::get_list(0, 1, "id DESC", $cu->id);
			
			foreach($points as $r)
			{
				$total_point = $r->total_point;	
			}
			
			$price_before_discount = intval($this->cart->total()) + intval($this->session->userdata('total_all_addon'));
			$discount = intval($_POST['point_used']) * doubleval(get_setting('convert_to_discount'));
			$this->session->set_userdata('old_discount', $discount);
			
			if(intval($total_point) < intval($_POST['point_used'])) 
			{
				$this->session->set_flashdata('point_error', 'Maaf. Point yang anda gunakan melebihi dari jumlah point anda.');
				redirect('cart/user_cart');
			}
			else 
			{
				$this->session->set_flashdata('msg', 'Anda telah menggunakan point Anda sebesar '.$_POST['point_used']. ' point');
				$this->session->set_userdata('point_used',intval($_POST['point_used']));
				redirect('cart/user_cart');
			}
		}
		else if($cu && intval($_POST['point_used']) == 0)
		{
			$this->session->set_flashdata('no_point', 'Anda tidak menggunakan Point.');
			$this->session->set_userdata('point_used',intval($_POST['point_used']));
			redirect('cart/user_cart');
		}
	}
	
	public function update_buyer()
	{
		$data['cu'] = $cu = $this->cu;
		
		if(sizeof($_POST) > 0)
		{
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check['.$cu->id.']');
			$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
			$this->form_validation->set_error_delimiters('', '<br />');
			
			extract($_POST);
			
			if ($this->form_validation->run() == TRUE)
			{
				$inc_arr = array("name", "email", "phone");
				$arr = NULL;
				foreach($_POST as $key => $val)
				{
					if(in_array($key, $inc_arr))
					{
						$arr[$key] = $val;
					}
				}
				
				$url_title = url_title($name, 'dash', TRUE);
				$arr['url_title'] = $url_title;
				
				$U = new OUser();
				$U->setup($cu);
				
				if($userfile != NULL)
				{
					$image_file = OUser::save_image($userfile);
					$arr['image'] = $image_file;
					$U->delete_current_image();
				}				
				
				$exec = $U->edit($arr);
				if($exec)
				{
					if($_POST['email'] != $cu->email) 
					{
						set_login_session($_POST['email'], $cu->password, $type="user");
					}
					if($_POST['password'] != "")
					{
						set_login_session($cu->email, md5($_POST['password']), $type="user");
					}
					if($_POST['email'] != $cu->email && $_POST['password'] != "") 
					{
						set_login_session($_POST['email'], md5($_POST['password']), $type="user");
					}
					
					$cek = $this->db->query("SELECT * FROM users WHERE url_title = ? AND id <> ?", array($url_title, $U->id));
					$res_cek = $cek->row();
					
					if(sizeof($res_cek) > 0)
					{
						$url_title = $url_title."-".$U->id;						
						$U->edit(array("url_title" => $url_title));
					}
												
					$this->session->set_flashdata("success_profile", "Your profile has been updated.");
					redirect('cart/check_out');
				}
				else {
					$data['error_string'] = "There is an error in the system. Please contact the website administrator.";					
				}				
			}
		}
		
		$data['initcss'] = array("additional_user");
		
		$this->load->view("header", $data);
		$this->load->view("user_check_out", $data);
		$this->load->view("footer", $data);
	}
}

/* End of file cart.php */
/* Location: ./application/controllers/cart.php */