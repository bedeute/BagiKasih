<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_status extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//error_reporting(E_ALL);	
	}
	
	public function index()
	{
		$this->load->view("header", $data);
		$this->load->view("order_status", $data);
		$this->load->view("footer", $data);
	}
	
	public function invoice()
	{
		if(sizeof($_POST) > 0)
		{
			$referal = $_SERVER['HTTP_REFERER'];
			
			extract($_POST);
			$user = get_user_bymail($email);
			$data['O'] = $O = new OOrder($order_id);
			$data['list'] = $list = $O->get_order_detail_by_userid(0, 0, "o.id DESC", $user->id);
			
			if(count($list) > 0)
			{
				$this->load->view("header", $data);
				$this->load->view("view_invoice", $data);
				$this->load->view("footer", $data);
			}
			else
			{
				$this->session->set_flashdata('no_rec', "No Record Found.");	
				redirect($referal);
			}
		}
	}
}

/* End of file order_status.php */
/* Location: ./application/controllers/order_status.php */