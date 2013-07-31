<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lsm_list extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fhead 	= "admin/header";
		$this->ffoot 	= "admin/footer";
		$this->fpath 	= "admin/";
		$this->curpage 	= "admin/lsm_list";
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
		$start 				= intval($_GET['page']);
		$perpage 			= 10;
		
		if($_GET['keyword'] != "") $data['list'] = OLsmList::search($_GET['keyword']);
		else $data['list'] 		= OLsmList::get_list($start, $perpage);
		
		$data['uri'] 		= intval($start);
		$total 				= get_db_total_rows();
		$url 				= $this->curpage."/listing?";
		
		$data['pagination'] = getPagination($total, $perpage, $url, 5);
		
		$data['cu'] = $this->cu;
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'lsm_list', $data);
		$this->load->view($this->ffoot, $data);
	}
	
	public function add()
	{
		extract($_POST); 
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('deskripsi', 'Description', 'trim|required');
		$this->form_validation->set_rules('id_kota', 'Location', 'trim|required');
		$this->form_validation->set_rules('id_lsm_category', 'Category', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(trim($val) != "") $arr[$key] = trim($val);
			}
			
			$this->load->library('upload');

			$config['upload_path'] = './assets/images';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = '1024';
			$config['encrypt_name'] = TRUE;
			
			$this->upload->initialize($config);
			
			if( ! $this->upload->do_upload("userfile")){
				echo $this->upload->display_errors();
			}

			$file_name = $this->upload->file_name;
			$arr = array_merge($arr, array('image' => $file_name));
				
			$new_id = OLsmList::add($arr);
			
			if($new_id) warning("add", "success");
			else warning("add", "fail");			
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;
		$data['act'] = "add";
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'lsm_form', $data);
		$this->load->view($this->ffoot, $data);	
	}	
	
	
	public function edit($id)
	{
		$dlsproduct = new OLsmList($id);
		
		if(!empty($id))
		{			
			if($dlsproduct->row == FALSE)
			{
				$this->session->set_flashdata('warning', 'ID does not exist.');
				redirect($this->curpage);
			}
		}
		
		extract($_POST);
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('deskripsi', 'Description', 'trim|required');
		$this->form_validation->set_rules('id_kota', 'Supplier', 'trim|required');
		$this->form_validation->set_rules('id_lsm_category', 'Category', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{	
			$arr = NULL;
			foreach($_POST as $key => $val)
			{
				if(trim($val) != "") $arr[$key] = trim($val);
			}
			
			$this->load->library('upload');

			$config['upload_path'] = './assets/images';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = '1024';
			$config['encrypt_name'] = TRUE;
			
			$this->upload->initialize($config);
			
			if( ! $this->upload->do_upload("userfile")){
				echo $this->upload->display_errors();
			}

			$file_name = $this->upload->file_name;
			$arr = array_merge($arr, array('image' => $file_name));
			
			$res = $dlsproduct->edit($arr);
			if($res) warning("edit", "success");
			else warning("edit", "fail");				
			redirect($this->curpage);
			exit();
		}
		$data['cu'] = $this->cu;
		$data['row'] = $dlsproduct->row;
		$data['act'] = "edit";
		$this->load->view($this->fhead, $data);
		$this->load->view($this->fpath.'lsm_form', $data);
		$this->load->view($this->ffoot, $data);	
	}
	
	public function delete($id)
	{
		$dlsproduct = new OLsmList($id);		
		
		$res = $dlsproduct->delete();
		if($res) warning("delete", "success");
		else warning("delete", "fail");
		redirect($this->curpage);
		exit();
	}
}

/* End of file lsm_list.php */
/* Location: ./application/controllers/admin/lsm_list.php */