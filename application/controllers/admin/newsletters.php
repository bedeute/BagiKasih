<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newsletters extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/newsletters";
		//error_reporting(E_ALL);
		
		$this->cu = $cu = get_current_admin();
		if(!$cu) admin_logout();
	}
	
	public function index()
	{
		$this->page();
	}
	
	public function page($start=0)
	{
		$start 				= intval($_GET['page']);
		$perpage 			= 10;
		
		if($_GET['keyword'] != "") $data['list'] = ONewsletter::search($_GET['keyword']);
		else $data['list'] = ONewsletter::get_list($start, $perpage);
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= "{$this->curpage}/page?";
 		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'newsletter_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function add()
	{
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_email_check[""]');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			
			$include_arr = array("email", "type");
			
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					if(trim($val) != "") $arr[$key] = trim($val);	
				}
			}
			
			$new = ONewsletter::add($arr);	
				
			if($new) warning("add", "success");
			else warning("add", "fail");			
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;		
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'newsletter_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	
	public function edit($id)
	{
		$N = new ONewsletter($id);
		
		if(!empty($id))
		{			
			if($N->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_email_check['.$N->id.']');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);		
			
			$include_arr = array("email", "type");
			
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(in_array($key, $include_arr))
				{	
					if(trim($val) != "") $arr[$key] = trim($val);	
				}				
			}
			
			$res = $N->edit($arr);
			
			if($res) warning("edit", "success");
			else warning("edit", "fail");				
			redirect($this->curpage);
			exit();			
		}
		
		$data['cu'] = $this->cu;
		$data['row'] = $N->row;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'newsletter_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	
	public function delete($id)
	{
		$N = new ONewsletter($id);
		
		if(!empty($id))
		{			
			if($N->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		$res = $N->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
	}
	
	public function email_check($email, $id_exception)
	{
		if(ONewsletter::check_email_exists($email, $id_exception)) 
		{
			$this->form_validation->set_message('email_check', 'This email has been taken, please use another email');
			return FALSE;
		}
		else return TRUE;
	}
	
	public function newsletter_queues($a = "", $param1, $param2)
	{
		$curpage = $this->curpage."/newsletter_queues";
		if($a == "")
		{
			/*$start 				= intval($_GET['page']);
			$perpage 			= 10;
			$this->db->limit($perpage, $start);
			$this->db->order_by("id ASC");
			$res = $this->db->get("newsletter_queues");
			if(emptyres($res)) $list = FALSE;
			else $list = $res->result();
			$data['list'] 		= $list;
			$data['uri'] 		= intval($start);
			$total 				= get_db_total_rows();
			$url 				= "{$this->curpage}/page?";
			
			$data['pagination'] = getPagination($total, $perpage, $url, 5);*/
			
			
			$start 				= $_GET['page'];
			$perpage 			= $_GET['perpage'];
			if(empty($start))	$start = 0;
			if(empty($perpage))	$perpage = 10;
			
			if($_GET['keyword'] != "") $data['list'] = ONewsletter::search_queue($_GET['keyword']);
			else $data['list'] = ONewsletter::get_newsletter_queue_list($start, $perpage, "id ASC");
			
			$data['uri'] 		= intval($start);
			$total 				= get_db_total_rows();
			$url 				= $this->curpage."/newsletter_queues?perpage=$perpage";
			
			$data['pagination'] = getPagination($total, $perpage, $url, 5);
			
			$data['cu'] = $this->cu;
			$this->load->view($this->fhead, $data);
			$this->load->view($this->fpath.'newsletter_queue_list', $data);
			$this->load->view($this->ffoot, $data);
		}
		if($a == "delete")
		{
			$id = $param1;
			if(empty($id))
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($curpage);
				exit();
			}
			
			$res = $this->db->delete("newsletter_queues", array("id" => $id));
			if($res) warning("delete", "success");
			else warning("delete", "fail");
			redirect($curpage);
			exit();
		}
	}
	
	public function create_newsletter()
	{
		$this->form_validation->set_rules('title', 'Subject', 'trim|required');
		$this->form_validation->set_rules('body', 'Content', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			// add to "newsletter_contents" first
			$this->db->insert("newsletter_contents", $_POST);
			$newid = $this->db->insert_id();
			
			if(intval($newid) > 0)
			{
				// than generate "newsletter_queues"
				//$list = ONewsletter::get_list(0, 0, "id ASC");
				
				//$list = ONewsletter::get_list(0, 0, "id ASC", "", "1");
				if($type == "member") $list = ONewsletter::get_list(0, 0, "id ASC", "", "1", "member");
				else $list = ONewsletter::get_list(0, 0, "id ASC", "", "1");
				
				if($list):
					foreach($list as $r)
					{
						$arr = array(
									"email" => $r->email, 
									"newsletter_content_id" => $newid,
									"status" => "pending"
									);
						$this->db->insert("newsletter_queues", $arr);
					}
				endif;
			}
			
			/*$list = ONewsletter::get_list(0, 0, "id ASC");
			
			foreach($list as $r)
			{
				$body 		= $this->load->view('admin/tpl_send_newsletter',$_POST, true);
				$to			= $r->email;
				$subject	= "Newsletter Subcription | Hapikado.com";
				
				noreply_mail($to,$subject,$body);				
			}*/
			$this->session->set_flashdata('newsletter_success', 'Newsletter has been created.');
			redirect($this->curpage);
			exit();
		}
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'send_newsletter_form', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function send()
	{
		$this->db->limit(100);
		$this->db->where("status", "pending");
		$this->db->order_by("id ASC");
		$list = $this->db->get("newsletter_queues");
		if(!emptyres($list))
		{
			foreach($list->result() as $r)
			{
				$nc_res = $this->db->get_where("newsletter_contents", array("id" => $r->newsletter_content_id));
				if(!emptyres($nc_res))
				{
					$rc 		= $nc_res->row();
					$body 		= $this->load->view('admin/tpl_send_newsletter',array("body" => $rc->body), true);
					$to			= $r->email;
					$subject	= $rc->title." | ".DOMAIN_NAME;
					// send email
					noreply_mail($to,$subject,$body);
					// set status to "sent", update sent_date
					$res = $this->db->query("UPDATE newsletter_queues SET status = 'sent', sent_date = NOW() WHERE id = ? ;", array($r->id));
					if($res) echo $r->id." Sent.<br />";
				}
			}
			die("DONE");
		}
		else {
			die("ERROR");
		}
	}
	
}

/* End of file newsletters.php */
/* Location: ./application/controllers/admin/newsletters.php */