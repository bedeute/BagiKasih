<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Refunds extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/refunds";
		//error_reporting(E_ALL);
		
		$this->cu = $cu = get_current_admin();
		if(!$cu) admin_logout();
	}
	
	public function index()
	{
		$this->listing(0);
	}
	
	public function listing($start=0)
	{
		$start 				= $_GET['page'];
		$perpage 			= $_GET['perpage'];
		if(empty($start))	$start = 0;
		if(empty($perpage))	$perpage = 100;
		
		/*if($_GET['keyword'] != "") $data['list'] = OBanner::search($_GET['keyword']);
		else */
		$data['list'] = ORefund::get_list($start, $perpage, "id ASC");
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= $this->curpage."/listing?perpage=$perpage";
		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'refund_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function ajax($action, $param1)
	{
		extract($_POST);		
		
		if($action == "set_done")
		{
			$id = $param1;
			if(empty($id)) die();
			echo $this->load->view("admin/tpl_refunds", array("id" => $id), TRUE);
			die();
		}
	}
	
	public function set_done($id)
	{
		if($id == "") redirect($this->curpage);
		
		$O = new ORefund($id);
		if($O->row == "") redirect($this->curpage);
		
		extract($_POST);		
		$arr = array('status' => 'done', 'note' => $note);		
		$res = $O->edit($arr);		
		
		if($new) warning("edit", "success");
		else warning("edit", "fail");		
		unset($O);
		redirect($this->curpage);
	}
}

/* End of file banners.php */
/* Location: ./application/controllers/admin/banners.php */