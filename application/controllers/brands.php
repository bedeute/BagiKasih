<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Brands extends CI_Controller 
{
	
	public function __construct()
	{
		parent::__construct();		
		//error_reporting(E_ALL);	
	}
	
	public function index()
	{
		
	}
	
	public function details($caturl)
	{
		$B = new OBrand($caturl, "urltitle");
		if($B->row->id == "") { redirect(""); exit; }
		$data['B'] = $B;
		
		$data['brand_id'] = $B->row->id;
		
		$this->load->view('header', $data);
		$this->load->view('home', $data);
		$this->load->view('footer', $data);		
	}
}

/* End of file brands.php */
/* Location: ./application/controllers/brands.php */