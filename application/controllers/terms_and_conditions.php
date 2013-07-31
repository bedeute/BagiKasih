<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Terms_and_conditions extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//error_reporting(E_ALL);	
	}
	
	public function index()
	{
		$this->load->view("header", $data);
		$this->load->view("terms_and_conditions", $data);
		$this->load->view("footer", $data);
	}
}

/* End of file terms_and_conditions.php */
/* Location: ./application/controllers/terms_and_conditions.php */