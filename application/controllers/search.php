<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

	public function __construct()
	{
		parent::__construct();		
		//error_reporting(E_ALL);	
	}	
	
	public function index()
	{
		//echo "Hi!";
		$this->load->view('header', $data);
		$this->load->view('search', $data);
		$this->load->view('footer', $data);
	}
	
	public function details()
	{
		$this->load->view('header', $data);
		$this->load->view('search_details', $data);
		$this->load->view('footer', $data);
	}
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */