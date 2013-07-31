<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Locations extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
	}
	
	public function clear()
	{
		clear_location();
		header("location: {$_SERVER['HTTP_REFERER']}");
		exit;
	}
	public function details($location)
	{
		// print_r($_SERVER);
		$q = "SELECT * FROM locations WHERE url_title = ?";
		$res = $this->db->query($q,array($this->uri->segment(3)));
		if(emptyres($res))
		{
		}
		else
		{
			$r = $res->row();
			set_location($r->id);
		}
		
		
		header("location: {$_SERVER['HTTP_REFERER']}");
		exit;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */