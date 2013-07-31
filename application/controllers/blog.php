<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//error_reporting(E_ALL);	
	}
	
	public function index()
	{
		$data['initcss'] = array("additional_other");
		
		$this->load->view("header", $data);
		$this->load->view("blog", $data);
		$this->load->view("footer", $data);
	}
}

/* End of file blog.php */
/* Location: ./application/controllers/blog.php */