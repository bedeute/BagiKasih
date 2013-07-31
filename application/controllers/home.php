<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		$cu = get_logged_in_user();
		
		$path = base_url('assets');
		$filename = "fb_friend_{$cu->id}.txt";
		$filename_path = $path.$filename;
		
		if(!$this->session->userdata("fb_friends") && intval($cu->id) > 0):
		
			$this->load->library("curl");
			$fb_userdata = $this->curl->simple_get("https://graph.facebook.com/".$cu->fb_id."/friends?fields=id,name,birthday&access_token=".$cu->fb_access_token);
			
			$fb = json_decode($fb_userdata);
			if($fb != "")
			{
				$fb_json = json_encode($fb);
				
				if (!$handle = fopen($filename_path, 'w')) {
					 echo "Cannot open file ($filename_path)";
					 exit;
				}
			
				if (fwrite($handle, $fb_json) === FALSE) {
					echo "Cannot write to file ($filename_path)";
					exit;
				}
			
				fclose($handle);
				
				$this->session->set_userdata("fb_friends", $filename);
			}	 

		endif;
		
		$this->load->view('header', $data);
		$this->load->view('home', $data);
		$this->load->view('footer', $data);
	}
	
	public function set_lang()
	{
		$this->session->set_userdata('lang', $this->uri->segment(3));
		redirect($_SERVER['HTTP_REFERER']);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */