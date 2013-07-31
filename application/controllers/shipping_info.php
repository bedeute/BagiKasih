<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shipping_info extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//error_reporting(E_ALL);	
	}
	
	public function index()
	{
		$this->load->view("header", $data);
		$this->load->view("shipping_info", $data);
		$this->load->view("footer", $data);
	}
}

/* End of file shipping_info.php */
/* Location: ./application/controllers/shipping_info.php */