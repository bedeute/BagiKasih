<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Confirm_payment extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//error_reporting(E_ALL);
	}
	
	public function index()
	{
		//$this->send_confirm_payment();
	}
	
	/*public function send_confirm_payment($order_id)
	{
		$data['cu'] = $cu = get_logged_in_user();
		if(!cu) redirect('/');
		
		$O = new OOrder($order_id);
		if($O->row == "") redirect('user/confirm_payment_list');
		
		$data['order_id'] = $order_id;
		$data['O'] = $O;
		
		$this->form_validation->set_rules('order_id', 'Order ID', 'trim|required');
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('payment_method', 'Payment Method', 'trim|required');
		$this->form_validation->set_rules('account', 'Account', 'trim|required');
		$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
		$this->form_validation->set_error_delimiters('', '<br />');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST); 
			
			$to = 'fajar@smart-it.co.id';
			$subject = "Hapikado.com - Confirmation Payment";
			
			$content = $this->load->view('tpl_email_confirm_payment', array("cu" => $cu, "order_id" => $order_id, "date" => $date, "payment_method" => $payment_method, "account" => $account, "amount" => $amount, "keterangan" => $keterangan), TRUE);				
			noreply_mail($to,$subject,$content);
			
			$this->session->set_flashdata('confirm_msg', 'Your Confirmation Payment for Order # '.$order_id.' has been sent. Our customer support team will contact you shortly regarding this. Thank you.');
			
			redirect('user/confirm_payment_list');
		}
		$this->load->view('header', $data);
		$this->load->view('confirm_payment_form', $data);
		$this->load->view('footer', $data);
	}*/
	
	
	public function send_confirm_payment()
	{
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		$this->form_validation->set_rules('order_id', 'Order ID', 'trim|required');
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('payment_method', 'Payment Method', 'trim|required');
		$this->form_validation->set_rules('bank_account', 'Bank Account', 'trim|required');
		$this->form_validation->set_rules('account_number', 'Account  Number', 'trim|required');
		$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
		$this->form_validation->set_error_delimiters('', '<br />');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST); 
		
			$O = new OOrder($order_id);
			if($O->row == "") $this->session->set_flashdata('no_order_id', 'Order ID #'.$order_id.' not found.');
			
			$data['order_id'] = $order_id;
			$data['O'] = $O;
			
			//email ke admin
			$admin_emails = OAdminEmail::get_list(0, 0);
			
			foreach($admin_emails as $ae)
			{
				$to = $ae->email;
				$subject = "Hapikado.com - Confirmation Payment";
				
				$content = $this->load->view('tpl_email_confirm_payment_2', array("name" => $name, "email" => $email, "order_id" => $order_id, "date" => $date, "payment_method" => $payment_method, "bank_account" => $bank_account, "account_number" => $account_number, "amount" => $amount, "keterangan" => $keterangan), TRUE);				
				noreply_mail($to,$subject,$content);
			}
			
			$data['confirm_msg'] = 'Your Confirmation Payment for Order # '.$order_id.' has been sent. Our customer support team will contact you shortly regarding this. Thank you.';
			$this->session->set_flashdata('confirm_msg', 'Your Confirmation Payment for Order # '.$order_id.' has been sent. Our customer support team will contact you shortly regarding this. Thank you.');
		}
		$this->load->view('header', $data);
		$this->load->view('confirm_payment_form_2', $data);
		$this->load->view('footer', $data);
	}
	
	public function select_confirm_payment()
	{
		$data['cu'] = $cu = get_logged_in_user();
		if(!cu) redirect('/');
		
		if(sizeof($_POST) <= 0) 
		{
			$this->session->set_flashdata('msg_pay', 'Please choose Your Order!');
			redirect('user/confirm_payment_list');
		}
		
		$i = 1;
		$amount = 0;
		foreach($_POST as $key => $val)
		{
			foreach($val as $r)
			{
				$order_id = $r;
				
				if(intval($i) < sizeof($val)) $data['order_id'] .= "#".$order_id." , ";
				else $data['order_id'] .= "#".$order_id;
				
				if(intval($i) < sizeof($val)) $id .= "#".$order_id." , ";
				else $id .= "#".$order_id;
				
				$O = new OOrder($order_id);
				$amount = intval($amount) + doubleval($O->row->grand_total);
				$data['total'] = $amount;
								
				$i++;
				unset($O);
			}
		}
		
		$this->load->view('header', $data);
		$this->load->view('confirm_payment_form', $data);
		$this->load->view('footer', $data);
	}
	
	public function send_select_confirm_payment()
	{
		$data['cu'] = $cu = get_logged_in_user();
		if(!cu) redirect('/');
		
		$this->form_validation->set_rules('order_id', 'Order ID', 'trim|required');
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('payment_method', 'Payment Method', 'trim|required');
		$this->form_validation->set_rules('account', 'Account', 'trim|required');
		$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
		//$this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
		$this->form_validation->set_error_delimiters('', '<br />');	
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			
			$order_id_remove_coma = explode(' , ', $order_id);
			
			foreach($order_id_remove_coma as $r)
			{
				$new_order_id = str_replace("#", " ", $r);
				
				$O = new OOrder($new_order_id);
				$arr['is_confirm_payment'] = '1';
				$O->edit($arr);
				unset($O);
			}
			
			//email ke admin
			$admin_emails = OAdminEmail::get_list(0, 0);
			
			foreach($admin_emails as $ae)
			{
				$to = $ae->email;
				$subject = "Hapikado.com - Confirmation Payment";			
				$content = $this->load->view('tpl_email_confirm_payment', array("cu" => $cu, "order_id" => $order_id, "date" => $date, "payment_method" => $payment_method, "account" => $account, "amount" => $amount, "keterangan" => $keterangan), TRUE);				
				noreply_mail($to,$subject,$content);
			}
			
			$to_user = $cu->email;
			$subject_user = "Hapikado.com - Confirmation Payment";			
			$content_user = $this->load->view('tpl_email_confirm_payment_user', array("cu" => $cu, "order_id" => $order_id, "date" => $date, "payment_method" => $payment_method, "account" => $account, "amount" => $amount, "keterangan" => $keterangan), TRUE);				
			noreply_mail($to_user,$subject_user,$content_user);
			
			$this->session->set_flashdata('confirm_msg', 'Your Confirmation Payment for Order '.$order_id.' has been sent. Our customer support team will contact you shortly regarding this. Thank you.');
			
			redirect('user/confirm_payment_list');
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function confirm_payment_from_email($order_id)
	{
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('payment_method', 'Payment Method', 'trim|required');
		$this->form_validation->set_rules('account', 'Account', 'trim|required');
		$this->form_validation->set_rules('amount', 'Amount', 'trim|required');	
		$this->form_validation->set_error_delimiters('', '<br />');	
		
		$O = new OOrder($order_id);
		$data['row'] = $O->row;
		
		$U = new OUser($O->row->user_id);
		
		$data['order_id'] = $order_id;
		$data['O'] = $O;
			
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST); 
		
			
			
			//email ke admin
			$admin_emails = OAdminEmail::get_list(0, 0);
			
			foreach($admin_emails as $ae)
			{
				$to = $ae->email;
				$subject = "Hapikado.com - Confirmation Payment";
				
				$content = $this->load->view('tpl_email_confirm_payment_from_email', array("name" => $name, "email" => $email, "order_id" => $order_id, "date" => $date, "payment_method" => $payment_method, "account" => $account, "amount" => $amount, "keterangan" => $keterangan), TRUE);
				noreply_mail($to,$subject,$content);
			}
			
			$to_user = $U->row->email;
			$subject_user = "Hapikado.com - Confirmation Payment";			
			$content_user = $this->load->view('tpl_email_confirm_payment_from_email_for_user', array("name" => $name, "email" => $email, "order_id" => $order_id, "date" => $date, "payment_method" => $payment_method, "account" => $account, "amount" => $amount, "keterangan" => $keterangan), TRUE);						
			noreply_mail($to_user,$subject_user,$content_user);
			
			$data['confirm_msg'] = 'Your Confirmation Payment for Order # '.$order_id.' has been sent. Our customer support team will contact you shortly regarding this. Thank you.';
			$this->session->set_flashdata('confirm_msg', 'Your Confirmation Payment for Order # '.$order_id.' has been sent. Our customer support team will contact you shortly regarding this. Thank you.');
			
			redirect('confirm_payment/confirm_payment_from_email/'.$order_id);
		}
		$this->load->view('header', $data);
		$this->load->view('confirm_payment_form_from_email', $data);
		$this->load->view('footer', $data);		
	}
	
}

/* End of file confirm_payment.php */
/* Location: ./application/controllers/confirm_payment.php */