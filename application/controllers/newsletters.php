<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newsletters extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//error_reporting(E_ALL);
	}
	
	public function add()
	{ 
		$this->form_validation->set_rules('newsletter_email', 'Email', 'trim|required|valid_email');		
		$this->form_validation->set_error_delimiters('', '<br />');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			
			$cek_email = $this->db->query('SELECT email FROM newsletters WHERE email = ?', array($newsletter_email));
			
			if(!emptyres($cek_email))
			{
				$row = $cek_email->row();
				$email_res = $row->email;
			}
			
			if(!empty($newsletter_email) && strtolower($newsletter_email) == strtolower($email_res))
			{
				$this->session->set_flashdata('newsletter_warning', 'The Email has been registered.');
				redirect($_SERVER['HTTP_REFERER']);				
			}
			else
			{
				$arr = array('email' => $newsletter_email, 'is_subscribe' => '1');
			
				$new = ONewsletter::add($arr);
				if($new)
				{
					$this->session->set_flashdata('newsletter_success', 'Your email has been sent.');
					redirect($_SERVER['HTTP_REFERER']);	
				}
				else
				{
					$this->session->set_flashdata('newsletter_warning', 'Your email has not been sent.');
					redirect($_SERVER['HTTP_REFERER']);	
				}	
			}			
		}
	}
	
	public function active()
	{
		$cu = get_logged_in_user();
		
		$arr['is_subscribe'] = "1";
		
		$res = $this->db->update('newsletters',$arr,array('email' => $cu->email));		
		redirect('user/profile');
	}
	
	public function non_active()
	{
		$cu = get_logged_in_user();
		
		$arr['is_subscribe'] = "0";
		
		$res = $this->db->update('newsletters',$arr,array('email' => $cu->email));
		redirect('user/profile');
	}
}

/* End of file newsletters.php */
/* Location: ./application/controllers/newsletters.php */