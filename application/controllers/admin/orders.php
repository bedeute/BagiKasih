<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/orders";
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
		
		if($_GET['keyword'] != "") $data['list'] = OOrder::search($_GET['keyword']);
		else $data['list'] 		= OOrder::get_list($start, $perpage);		
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= "{$this->curpage}/page?perpage=$perpage";
 		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'order_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	/*public function edit($id)
	{ 
		$O = new DLSOrder($id);
		
		if(!empty($id))
		{			
			if($O->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
				exit();
			}
		}
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('address_1', 'Address 1', 'trim|required');
		$this->form_validation->set_rules('address_2', 'Address 2', 'trim|required');
		$this->form_validation->set_rules('city', 'City', 'trim|required');
		$this->form_validation->set_rules('state', 'State', 'trim|required');
		$this->form_validation->set_rules('country', 'Country', 'trim|required');
		$this->form_validation->set_rules('zip_code', 'Zip Code', 'trim|required');
		$this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');		
		
		$this->form_validation->set_rules('ship_name', 'Ship Name', 'trim|required');
		$this->form_validation->set_rules('ship_address1', 'Ship Address 1', 'trim|required');
		$this->form_validation->set_rules('ship_address2', 'Ship Address 2', 'trim|required');
		$this->form_validation->set_rules('ship_city', 'Ship City', 'trim|required');
		$this->form_validation->set_rules('ship_state', 'Ship State', 'trim|required');
		$this->form_validation->set_rules('ship_country', 'Ship Country', 'trim|required');
		$this->form_validation->set_rules('ship_zipcode', 'Ship Zip Code', 'trim|required');
		$this->form_validation->set_rules('ship_phone_number', 'Ship Phone Number', 'trim|required');
		$this->form_validation->set_rules('ship_email', 'Ship Email', 'trim|required|valid_email');
		
		$this->form_validation->set_rules('total', 'Total', 'trim|required');
		$this->form_validation->set_rules('tax', 'Tax', 'trim|required');
		$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>
		');
		
		if($this->form_validation->run() == TRUE)
		{
			$arr = array();
			if(sizeof($_POST) > 0)
			{		
				foreach($_POST as $key => $val)
				{
					$arr[$key] = $val;	
				}
				
				$res = $O->edit($arr);
				if($res) warning("edit", "success");
				else warning("edit", "fail");
				redirect($this->curpage);
				exit();
			}			
		}
		$data['cu'] = $this->cu;
		$data['row'] = $O->row;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'order_form', $data);
		$this->load->view($this->ffoot, $data);
	}*/
	
	/*public function mark_as_paid($id)
	{
		$O = new DLSOrder($id);
		
		if(sizeof($_POST) > 0)
		{
			extract($_POST);
			$arr['status'] = 'paid';
			$arr['paid_note'] = $paid_note;
			$arr['paid_date'] = $paid_date;
			$res = $O->edit($arr);	
			redirect('admin/orders');
		}		
	}
	
	public function mark_as_shipped($id)
	{
		$O = new DLSOrder($id);
		
		if(sizeof($_POST) > 0)
		{
			extract($_POST);
			$arr['status'] = 'shipped';
			$arr['shipped_note'] = $shipped_note;
			$arr['shipped_date'] = $shipped_date;
			$res = $O->edit($arr);	
			redirect('admin/orders');
		}	
	}
	*/
	
	public function assign_shipping_fee($id)
	{
		$O = new OOrder($id);
				
		if(sizeof($_POST) > 0)
		{
			extract($_POST);
			
			$arr = array(	'shipping_cost' 	=> $shipping_fee,
						 	'grand_total'		=> intval($O->row->subtotal) + intval($O->row->tax_cost) + intval($shipping_fee)
						 );
			
			$res = $O->edit($arr);	
			redirect('admin/orders');
		}	
	}
	
	
	public function ajax($action, $param1)
	{
		extract($_POST);
				
		/*if($action == "assign_shipping_fee")
		{
			$id = $param1;
			if(empty($id)) die();
			echo $this->load->view("admin/shipping_fee_form", array("id" => $id), TRUE);
			die();
		}*/	
		
		if($action == "shipped")
		{
			$id = $param1;
			if(empty($id)) die();
			echo $this->load->view("admin/tpl_shipped_form", array("id" => $id), TRUE);
			die();
		}
		
		if($action == "confirm_payment")
		{
			$id = $param1;
			if(empty($id)) die();
			echo $this->load->view("admin/tpl_confirm_payment_form", array("id" => $id), TRUE);
			die();
		}		
	}
	
	/*public function delete($id)
	{
		$pborder = new DLSOrder($id);
		
		$res = $pborder->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
	}*/
	
	public function notify_buyer($order_id)
	{ 
		$data['O'] = $O = new OOrder($order_id);
		
		if(!empty($order_id))
		{			
			if($O->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
				exit();
			}
		}
		
		extract($_POST);
		
		$arr['is_notify_buyer'] = "1";
		$res = $O->edit($arr);		
		
		$list = $O->get_details();
		
		$U = new OUser($O->row->user_id); 
		
		$to = $U->row->email;
		$subject = "Hapikado.com Confirmation Order And Shipping Cost";
		$body = $this->load->view('admin/tpl_notify_buyer', array('O' => $O, 'list' => $list), TRUE);
		noreply_mail($to,$subject,$body);
		
		$this->session->set_flashdata('notify_msg', 'Your Notification was successfully sent to buyer.');
		
		$data['cu'] = $this->cu;
		redirect($this->curpage);
		unset($O);
	}
	
	public function mark_as_paid($order_id)
	{ 
		$data['O'] = $O = new OOrder($order_id);
		
		if(!empty($order_id))
		{			
			if($O->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
				exit();
			}
		}
		
		extract($_POST);
		
		$arr['status'] = "paid";
		$res = $O->edit($arr);		
		
		$list = $O->get_details();
		
		$U = new OUser($O->row->user_id); 
		
		$to = $U->row->email;
		$subject = "Hapikado.com Confirmation Delivery";
		$body = $this->load->view('admin/tpl_mark_as_paid', array('O' => $O, 'list' => $list), TRUE);
		noreply_mail($to,$subject,$body);
		
		foreach($list as $r)
		{
			$S = new OSupplier($r->supplier_id);
			
			$list_supplier = $O->get_details(0, 0, "", $r->supplier_id);
			
			$to_sup = $S->row->email;
			$subject_sup = "Hapikado.com Confirmation Delivery to Buyer";
			$body_sup = $this->load->view('admin/tpl_supplier_delivery', array('O' => $O, 'list_supplier' => $list_supplier), TRUE);
			noreply_mail($to_sup,$subject_sup,$body_sup);
			unset($S);
		}
		
		$this->session->set_flashdata('paid_msg', 'Your Confirmation was successfully sent to Supplier.');
		
		$data['cu'] = $this->cu;
		redirect($this->curpage);
		unset($O);
	}
	
	public function details($order_id, $start=0)
	{ 
		$data['O'] = $O = new OOrder($order_id);
		
		if(!empty($order_id))
		{			
			if($O->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
				exit();
			}
		}
		
		$start 				= $_GET['page'];
		$perpage 			= $_GET['perpage'];
		if(empty($start))	$start = 0;
		if(empty($perpage))	$perpage = 10;
		$data['list'] 		= $O->get_details($start, $perpage);
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= "{$this->curpage}/details/{$order_id}?";
 		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'order_detail_list', $data);
		$this->load->view($this->ffoot, $data);
		unset($O);
	}
	
	/*public function edit_details($order_id, $id)
	{  
		$O = new DLSOrder($order_id);
		$det = $O->get_details($id);
		
		if(!empty($order_id))
		{			
			if($O->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage.'/details');
				exit();
			}
		}		
		
		$this->form_validation->set_rules('product_id', 'Product ID', 'required|integer');
		$this->form_validation->set_rules('product_name', 'Product Name', 'required');
		$this->form_validation->set_rules('product_qty', 'Product Quantity', 'required|integer');
		$this->form_validation->set_rules('product_size', 'Product Size', 'required');
		$this->form_validation->set_rules('price_per_unit', 'Price Per Unit', 'required|decimal');
		$this->form_validation->set_rules('subtotal', 'Sub Total', 'required|decimal');
		
		if($this->form_validation->run() == TRUE)
		{
			$arr = array(); 
			if(sizeof($_POST) > 0)
			{
				$arr = array( 'id' => $id,
							  'order_id' => $order_id,
							  'product_id' => $this->input->post('product_id'),
							  'name' => $this->input->post('product_name'), 
							  'qty' => $this->input->post('product_qty'),
							  'price_per_unit' => $this->input->post('price_per_unit'),
							  'subtotal' => $this->input->post('subtotal')
							 );
				
				$res = $detail->edit_details($id, $arr); 
				if($res) $this->session->set_flashdata('msg', 'Your data has been updated.');
				redirect('admin/orders/details/'.$order_id);
			}			
		}
		
		$data['cu'] = $this->cu;
		$data['row'] = $det;
		$data['ordersNav'] = "active";
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'order_detail_form', $data);
		$this->load->view($this->ffoot, $data);
	}*/
	
	
	public function assign_suppliers($order_id)
	{ 
		$data['O'] = $O = new OOrder($order_id);
		
		if(!empty($order_id))
		{			
			if($O->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
				exit();
			}
		}
		
		$data['list'] = $O->get_details(0, 0, "id DESC");
		$data['histories'] = OOrder_History::get_list(0, 0, "id DESC",$O->row->id);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'assign_supplier_form', $data);
		$this->load->view($this->ffoot, $data);
		unset($O);
	}
	
	public function add_assign_suppliers($order_id)
	{ 	
		// when admin submit
		if(sizeof($_POST) > 0)
		{
			extract($_POST);
			$orderid = $order_id;
			// check if the total number of quantities match?
				// get each order detail quantity into array
				$OO = new OOrder($orderid);
				$ods = $OO->get_details(0,0);
				$odsarr = array();
				foreach($ods as $od)
				{
					$odsarr[$od->id] = intval($od->qty);
				}
				// get total from submitted
				$supplier_ids = $_POST['supplier_id'];
				$qtys = $_POST['qty'];
				$postqtys = array();
				foreach($_POST['qty'] as $key => $qtys)
				{
					$postqtys[$key] = 0;
					foreach($qtys as $supcount => $tmpqty)
					{
						// only get quantity that supplier id has been checked
						if(isset($_POST['supplier_id'][$key][$supcount]))
						{
							$postqtys[$key] += $tmpqty;
						}
					}
				}
				// check if both are the same
				$thesame = TRUE;
			  	foreach($odsarr as $odid => $val)
				{
					if($postqtys[$odid] > $val) { $thesame = FALSE; break; }
				}
				// if not the same, redirect user to the page with error
				if(!$thesame)
				{
					$this->session->set_flashdata('posts_data',json_encode($_POST));
					$this->session->set_flashdata('msg_qty','Jumlah yang diassign tidak boleh lebih besar daripada pesanan.');
					redirect('admin/orders/assign_suppliers/'.$orderid);
					exit;
				}
				else
				{
					
					// quantities match, let's process...
					// delete all that are pending
					
					foreach($postqtys as $odid => $qty)
					{
						$this->db->delete('order_detail_assign_suppliers',array('order_detail_id' => $odid));
						$suppliers = $supplier_ids[$odid];
						$status = $_POST['status'][$odid];
						$tmpqtys = $_POST['qty'][$odid];
						// var_dump($tmpqtys);
						foreach($suppliers as $key => $sup)
						{
							$arr = array(	'order_detail_id'	=> $odid,
											'supplier_id'		=> $suppliers[$key],
											'qty'				=> $tmpqtys[$key],
											'status' 			=> $status[$key]
										);				
							// var_dump($arr);
							$new = OSupplier::add_order_assign_supplier($arr);
						}
					}
					
				}
				// done
				// redirect user
				warning("add", "success");		
				redirect("admin/orders/assign_suppliers/{$orderid}");
				exit();
		}
		else
		{
			redirect("admin/orders/assign_suppliers/{$orderid}");
			exit();
		}
	}
	
	
	public function send_adjustment($id)
	{
		$assign_supplier = OSupplier::get_total_order_detail_assign_supplier($id);
		//var_dump($this->db->last_query());
		
		/*foreach($assign_supplier as $r)
		{
			$arr = array('order_detail_id' 	=> $r->order_detail_id,
						 'qty'				=> $r->total_qty
						 );
		}*/
		
		$O = new OOrder($id);
		$U = new OUser($O->row->user_id);
		
		$to = $U->row->email;
		$subject = "Hapikado.com Adjustment Order";
		$body = $this->load->view('admin/tpl_adjustment_order', array('O' => $O, 'assign_supplier' => $assign_supplier), TRUE);
		noreply_mail($to,$subject,$body);
		
		$this->session->set_flashdata('email_adjustment', 'Your email has been send.');
		redirect($this->curpage);
		exit();
	}
	
	public function cancel_order($id)
	{
		if($id == "") redirect($this->curpage);
		
		$O = new OOrder($id);
		if($O->row == "") redirect($this->curpage);
		
		// update status to cancel
		$arr['status_new'] = 'cancel'; 
		$res = $O->edit($arr);
		
		// delete all data from order_detail_assign_suppliers based on this order
		$details = $O->get_details();
		
		foreach($details as $dtl)
		{
			
			$assign_supplier = OOrderDetailAssignSupplier::get_list(0, 0, "id DESC", $dtl->id);
			
			foreach($assign_supplier as $as)
			{
				//echo '<pre>'; print_r($as); echo '</pre>'; die();
				$ODAS = new OOrderDetailAssignSupplier($as->id);
				$ODAS->delete();
				unset($ODAS);
			}
		}
		
		// do a full refund
			// get the order.amount_paid
			$amount_paid = doubleval($O->row->paid_amount);
			// update order.refund = order.amount_paid
			$arr_order['refund'] = $amount_paid;
			$O->refresh();
			$res = $O->edit($arr_order);
			// add into refund table as follow: 
				// order_id = $this->id
				// total_order = order.amount_paid
				// new_total = 0
				// refund = order.amount_paid - 0
				$arr_refund = array('order_id'		=> $O->row->id,
									'total_order'	=> $amount_paid,
									'new_total'		=> 0,
									'refund'		=> doubleval($amount_paid) - 0
									);
				$new_refund = ORefund::add($arr_refund); 
				
		// return any used points
			// update points so that total_points = total_points + order.points_used
			$used_points = $this->db->query("UPDATE points SET total_point = total_point + ? WHERE user_id = ?", array($O->row->points_used, $O->row->user_id));
			
		// get back received points
			// update points so that total_points = total_points - order.points_received
		$received_points = $this->db->query("UPDATE points SET total_point = total_point - ? WHERE user_id = ?", array($O->row->points_received, $O->row->user_id));		
		
		if($res && $new_refund && $used_points && $received_points) warning("edit", "success");
		else warning("edit", "fail");
		redirect($this->curpage);
		exit();
		unset($O);
	}
	
	public function mark_as_wait_payment($id)
	{
		if($id == "") redirect($this->curpage);
		
		$O = new OOrder($id);
		if($O->row == "") redirect($this->curpage);
		
		$order_arr['status'] = 'wait_payment';
		
		$assign_supplier = OSupplier::get_total_order_detail_assign_supplier($id);
				
		$final_price = 0;		
		foreach($assign_supplier as $r)
		{
			//edit order detail
			$OD = new OOrderDetail($r->order_detail_id);
			
			$next_price = intval($OD->row->price) * intval($r->total_qty);
			
			$detail_arr = array('qty'			=> intval($r->total_qty),
								'final_price'	=> $next_price
								);
			
			
			$detail_res = $OD->edit($detail_arr);
			$final_price = $final_price + $next_price; 
			unset($OD);
		}
		
		//var_dump($detail_arr);
		
		$O->refresh($id);
		$new_total = $O->get_total_order_after_edited();
		//edit order
		//var_dump($new_total);
		$order_arr = array(	'subtotal' 		=> $final_price,//$new_total->final_price_edited,
					 		'shipping_cost'	=> $new_total->shipping_fee_edited,
							'grand_total'	=> intval($final_price) + intval($new_total->shipping_fee_edited),
							//intval($new_total->final_price_edited) + intval($new_total->shipping_fee_edited),
							'status'		=> 'wait_payment'	
					 		);
		
		//var_dump($order_arr);
		$order_res = $O->edit($order_arr);
		$O->refresh($id);
		
		if($order_res) 
		{
			$U = new OUser($O->row->user_id);
		
			$to = $U->row->email;
			$subject = "Hapikado.com Order AND Shipping Fee Confirmation";
			$body = $this->load->view('admin/tpl_shipping_fee_confirmation', array('O' => $O), TRUE);
			noreply_mail($to,$subject,$body);
			
			warning("edit", "success");
		}
		else warning("edit", "fail");
		redirect($this->curpage);
		exit();
		unset($O);
	}
	
	public function mark_as_shipped($id)
	{
		if($id == "") redirect($this->curpage);
		
		$O = new OOrder($id);
		if($O->row == "") redirect($this->curpage);
		
		$arr['status_new'] = 'shipped';
		$arr['dt_shipped'] = date("Y-m-d");
		$res = $O->edit($arr);
		
		if($res) warning("edit", "success");
		else warning("edit", "fail");
		redirect($this->curpage);
		/*if(sizeof($_POST) > 0)
		{
			extract($_POST);
			
			$this->form_validation->set_rules('dt_shipped', 'Date Shipped', 'trim|required');
			$this->form_validation->set_rules('service_delivery', 'Service Delivery', 'trim|required');
			$this->form_validation->set_rules('tracking', 'Tracking', 'trim|required');
			$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if($this->form_validation->run() == TRUE)
			{				
				$arr = array('status_new' 		=> 'shipped',
							 'dt_shipped'		=> $dt_shipped,
							 'service_delivery'	=> $service_delivery,
							 'tracking'			=> $tracking
							 );
				
				$res = $O->edit($arr);				
				
				if($res)
				{
					$U = new OUser($O->row->user_id);
					$to = $U->row->email;
					$subject = "Hapikado.com Shipment Confirmation";
					$body = $this->load->view('admin/tpl_shipped_mail', array('O' => $O, 'dt_shipped' => $dt_shipped, 'service_delivery' => $service_delivery, 'tracking' => $tracking), TRUE);
					noreply_mail($to,$subject,$body);
					
					warning("edit", "success");
				}
				else warning("edit", "fail");
				redirect($this->curpage);
			}
		}
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'tpl_shipped', $data);
		$this->load->view($this->ffoot, $data);*/
	}
	
	public function confirm_payment($id)
	{
		if($id == "") redirect($this->curpage);
		
		$O = new OOrder($id);
		if($O->row == "") redirect($this->curpage);
		
		extract($_POST);
		$arr['status_new'] = 'confirm_payment';
		$arr['confirm_payment_note'] = $confirm_payment_note;
		$arr['paid_amount'] = $O->row->grand_total;
		
		$res = $O->edit($arr);
		
		if($res)
		{
			$suppliers = OSupplier::get_list(0, 0, "id DESC", $O->row->location_id);
			/*
			foreach($suppliers as $r)
			{
				$to = $r->email;
				$subject = "Hapikado.com - Review Order To All Suppliers";
				$content = $this->load->view('admin/tpl_email_review_order_supplier', array("O" => $O), TRUE);				
				noreply_mail($to,$subject,$content);
			}
			*/
			warning("edit", "success");
		}
		else warning("edit", "fail");
		
		unset($O);
		redirect($this->curpage);
	}
	
	public function send_email_to_suppliers($id)
	{
		$O = new OOrder($id);		
		if($id == "" || $O->row == "") redirect($this->curpage);		
		
		$order_details = $O->get_order_details_assign_supplier_by_order_id(0, 0);
				
		$sup_ids = $order_details[0]->supplier_id;
		//$cur_sup = TRUE;
		foreach($order_details as $r)
		{
			/*if($sup_ids == $r->supplier_id)
			{
				$arr_sup[$r->supplier_id][] = array('order_detail_id'	=> $r->order_detail_id,
								   'qty'				=> $r->qty,
								   'supplier_id'		=> $r->supplier_id,
								   'order_id'			=> $O->row->id);
				$cur_sup =udah PAk TRUE;
			}
			else
			{*/
				$arr_sup[$r->supplier_id][] = array('order_detail_id'	=> $r->order_detail_id,
								   'qty'				=> $r->qty,
								   'supplier_id'		=> $r->supplier_id,
								   'order_id'			=> $O->row->id);
				$cur_sup = FALSE;
				$sup_ids = $r->supplier_id;
			//}
		}	
		
		if(count($arr_sup) > 0)
		{	
			if($cur_sup == FALSE)
			{
				foreach($arr_sup as $key => $val)
				{
					$SUP = new OSupplier($key);
					
					$arr_sup = $val;
					
					$to = $SUP->row->email;
					$subject = "Hapikado.com Assign Order to Supplier";
					$body = $this->load->view('admin/tpl_order_assign_supplier', array("arr_sup" => $arr_sup), TRUE);
					noreply_mail($to,$subject,$body);
				}
			}
		}
			
		$this->session->set_flashdata('send_email_msg', 'Your email has been sent');
		redirect($this->curpage.'/assign_suppliers/'.$id);
	}
	
	public function edit($id)
	{ 
		$O = new OOrder($id);
		
		if(!empty($id))
		{			
			if($O->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
				exit();
			}
		}
		
		// get the order
		$data['O'] = $O;
		// get the details
		$data['list'] = $list = $O->get_details(0, 0);
		
		if(sizeof($_POST) > 0)
		{
			extract($_POST);
			
			$subtotal = 0;
			foreach($order_detail_id as $r)
			{
				//edit old_qty
				$ODD = new OOrderDetail($r);
				$cur_qty = $ODD->row->qty;
				$arr_old['old_qty'] = $cur_qty;
				$ODD->edit($arr_old);
				unset($ODD);
				
				// edit detail				
				$q = "SELECT SUM(qty) AS total FROM order_detail_assign_suppliers WHERE order_detail_id = ? AND status = 'approved'";
				$tmpres = $this->db->query($q,array($r));
				if(!emptyres($tmpres))
				{
					$tmprow = $tmpres->row();
					$total = intval($tmprow->total);
				}
				// jika Jumlah yang dirubah lebih besar daripada yang FULFILLED, edit detail
				if(intval($qty[$r]) >= $total)
				{
					$OD = new OOrderDetail($r);
					$od_final_price = intval($qty[$r]) * doubleval($OD->row->price);
					$arr = array(	'id'			=> $r,
									'qty'			=> $qty[$r],
									'final_price'	=> $od_final_price
								);
									
					$res_detail = $OD->edit($arr);
					$subtotal += $od_final_price; 
					$OD->refresh();
					unset($OD);
				}
				else
				{
					$this->session->set_flashdata('edit_error', 'Jumlah yang dirubah TIDAK BOLEH lebih kecil daripada yang FULFILLED.');
					redirect($this->curpage.'/edit/'.$id);
					break;
				}
			}
			
			// edit order
			$grand_total = doubleval($subtotal) + doubleval($O->row->shipping_cost) - ($O->row->discount);
			// old grand total - new grand total
			$refund = doubleval($O->row->paid_amount) - $grand_total;
			if($refund <= 0) $refund = 0;
			$arr_order = array(	'subtotal'		=> doubleval($subtotal),
							   	'grand_total'	=> $grand_total,
								'refund'		=> $refund
							   );
			
			$res_order = $O->edit($arr_order);
			
			// add refund
			$this->db->delete('refunds',array('order_id' => $id));
			$total_refund = $refund;
			$arr_refund = array( 'order_id' 	=> $id,
								 'total_order'	=> doubleval($O->row->paid_amount),
								 'new_total' => doubleval($grand_total),
								 'refund'		=> $total_refund
								);
			
			if(intval($total_refund) > 0) $refund = Orefund::add($arr_refund);
			
			$O->refresh();
			
			// get new the details
			$ONEW = new OOrder($O->row->id);
			$new_list = $ONEW->get_details(0, 0);			
			$U = new OUser($ONEW->row->user_id);
			
			// send new adjusment to buyer
			$to = $U->row->email;
			$subject = "Hapikado.com New Adjustment";
			$body = $this->load->view('admin/tpl_adjustment_order', array('O' => $ONEW, 'list' => $new_list, 'total_refund' => $total_refund), TRUE);
			noreply_mail($to,$subject,$body);
			
			$this->session->set_flashdata('adjustment_success', 'New adjustment to send to buyers.');
			redirect($this->curpage);
		}
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'order_detail_list_edit', $data);
		$this->load->view($this->ffoot, $data);
		unset($O, $U);
	}
	
	// Finalize order
	function finalize_order($orderid)
	{
		// check apakah jumlah order qty sudah matched dengan assigned qty yang APPROVED.
		$OO = new OOrder($orderid);
		$ods = $OO->get_details();
		$matched = TRUE;
		foreach($ods as $od)
		{
			$q = "SELECT SUM(qty) AS total FROM order_detail_assign_suppliers WHERE order_detail_id = ?";
			$tmpres = $this->db->query($q,array($od->id));
			if(emptyres($tmpres)) $matched = FALSE;
			else
			{
				$tmprow = $tmpres->row();
				if(intval($tmprow->total) != $od->qty) $matched = FALSE;
			}
			
			if(!$matched) break;
		}
		// jika belum, redirect ke halaman assign supplier dan tunjukkan error. EXIT
		if(!$matched) { $this->session->set_flashdata('msg_qty','Maaf, jumlah qty order tidak sesuai dengan jumlah yang diassign ke supplier yang sudah confirm.'); redirect('admin/orders/assign_suppliers/'.$orderid); exit; }
		
		// untuk setiap order_detail_assign_suppliers yang bersangkutan
		
			// kirimkan email ke setiap supplier bahwa pesanan yang bersangkutan dengan supplier tersebut telah OK
			// mohon disiapkan dan dikirim ke alamat yang tercantum			
			$order_details = $OO->get_order_details_assign_supplier_by_order_id(0, 0, "odas.supplier_id DESC");
					
			$sup_ids = $order_details[0]->supplier_id;
			//$cur_sup = TRUE;
			foreach($order_details as $r)
			{
				/*var_dump($sup_ids);
				echo '<pre>';
				print_r($r);
				echo '</pre>'; die();*/
				
				// update status order_detail_assign_suppliers ke "confirmed"
				$OA = new OOrderDetailAssignSupplier();
				$OA->setup($r);
				$arr_oa['status'] = 'confirmed';
				$OA->edit($arr_oa);
				unset($OA);
				
				//if($)
				/*if($sup_ids == $r->supplier_id)
				{
					$arr_sup[$r->supplier_id][] = array(   'order_detail_id'	=> $r->order_detail_id,
														   'qty'				=> $r->qty,
														   'supplier_id'		=> $r->supplier_id,
														   'order_id'			=> $OO->row->id);
					//$cur_sup = TRUE;
				}
				else
				{*/
					$arr_sup[$r->supplier_id][] = array(   'order_detail_id'	=> $r->order_detail_id,
														   'qty'				=> $r->qty,
														   'supplier_id'		=> $r->supplier_id,
														   'order_id'			=> $OO->row->id);
					/*//$cur_sup = FALSE;
					$sup_ids = $r->supplier_id;
				}
				//var_dump($cur_sup);
				//die("testing");*/
			}
			if(count($arr_sup) > 0)
			{	
				
				/*echo '<pre>';
				print_r($arr_sup);
				echo '</pre>'; die();*/
				
				foreach($arr_sup as $key => $val)
				{
					//var_dump($key); 
					$SUP = new OSupplier($key);
					
					$arr_sup = $val;
					
					$to = $SUP->row->email;
					$subject = "Hapikado.com - Finalize Assign Order to Supplier";
					$body = $this->load->view('admin/tpl_finalize_order_assign_supplier', array("arr_sup" => $arr_sup), TRUE);
					noreply_mail($to,$subject,$body);
					unset($SUP);
				}
			}				
			//die("testing");
			
		// update status order ke 'assign'
		$arr_order['status_new'] = 'assign';
		$arr_order['points_received'] = $received_point = floor($OO->row->grand_total / doubleval(get_setting('convert_to_point')));
		$OO->edit($arr_order);		
		
		// kirim email ke user bahwa pesanan telah siap dan akan dikirim sesuai jadwal
		$OU = new OUser($OO->row->user_id);
		$to_user = $OU->row->email;
		$subject_user = "Hapikado.com - Delivery Order";
		$body_user = $this->load->view('admin/tpl_delivery_order', array("OO" => $OO, "OU" => $OU), TRUE);
		noreply_mail($to_user,$subject_user,$body_user);
		// update user point
		$OU->update_point($OU->get_total_point() + $received_point);
		unset($OU);		
		
		// update flash data bahwa order sudah difinalized dan balikkan ke halaman order listing
		$this->session->set_flashdata('finalize_msg', 'The Order process has been completed !');
		unset($OO);
		redirect($this->curpage);
	}
	
	
	public function shipped($id)
	{
		if($id == "") redirect($this->curpage);
		
		$O = new OOrder($id);
		if($O->row == "") redirect($this->curpage);
		
		extract($_POST);		
		$arr['shipped_note'] = $shipped_note;		
		$res = $O->edit($arr);
		
		if($res) warning("edit", "success");
		else warning("edit", "fail");		
		unset($O);
		redirect($this->curpage);
	}
	
	
	
	
	public function approve_order_manually($order_id, $order_detail_id, $order_assign_supplier_id)
	{
		$O = new OOrder($order_id);
		
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
		//$arr['qty'] = $_POST['qty'];
		$arr['qty'] = intval($ODAS->row->qty);
		
		/*if(intval($arr['qty']) == 0)
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
			$S = new OSupplier($cu->id);*/
			$OD = new OOrderDetail($order_detail_id);
			$order_id = $OD->get_order_id();
			$arr_history['note'] = "Admin(MANUALLY) CONFIRM DETAIL #".$order_detail_id." dengan jumlah: ".intval($ODAS->row->qty);
			$arr_history['order_id'] = $order_id->order_id;
		
			$res_history = OOrder_History::add($arr_history);
			
			$res = $ODAS->edit($arr);
			
			if($res && $res_history) $this->session->set_flashdata('approve_new_order_success', 'Konfirmasi penyanggupan pesanan telah diterima.');
			unset(/*$S,*/ $OD, $ODAS);
			redirect($this->curpage.'/assign_suppliers/'.$O->row->id);
			exit();
		//}
	}
}

/* End of file orders.php */
/* Location: ./application/controllers/admin/orders.php */