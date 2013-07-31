<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coupons extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->fhead 	= "admin/header";
        $this->ffoot 	= "admin/footer";
        $this->fpath 	= "admin/";
        $this->curpage 	= "admin/coupons";
        
        $this->cu = $cu = get_current_admin();
        if(!$cu) admin_logout();
		$this->load->library('OCoupon');
    }
    
    public function index()
    {
        $this->listing(0);
    }
    
    public function listing($start=0)
    {
        $start 				= intval($_GET['page']);
        $perpage 			= 10;
        if($_GET['keyword'] != "") $data['list'] = OCoupon::search($_GET['keyword']);
        else $data['list'] 	= OCoupon::get_list($start, $perpage, "id DESC");
        $data['uri'] 		= intval($start);
        $total 				= get_db_total_rows();
        $url 				= $this->curpage."/listing?";
        
        $data['pagination'] = getPagination($total, $perpage, $url, 5);
        
        $data['cu'] = $this->cu;
        $this->load->view($this->fhead, $data);
        $this->load->view($this->fpath.'coupons_list', $data);
        $this->load->view($this->ffoot, $data);
    }
    
    public function add()
    {
		$this->form_validation->set_rules('code', 'Code', 'trim|required');
		$this->form_validation->set_rules('discount', 'Discount', 'trim|required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
		$this->form_validation->set_rules('expired_date', 'Expired Date', 'trim|required');
		$this->form_validation->set_error_delimiters('<br /><span class="red">', '</span>');
	
		if($this->form_validation->run() == TRUE)
        {
			extract($_POST);
            
            $arr = NULL;
            $exclude_arr = array("submit","url_title","password");
            
            foreach($_POST as $key => $val)
            {
                if(!in_array($key, $exclude_arr))
                {	
                    if(trim($val) != "") $arr[$key] = trim($val);	
                }
            }
            
            $new = OCoupon::add($arr);
            if($new) warning("add", "success"); else warning("add", "fail");
			unset($O);
            redirect($this->curpage);
            exit();
        }
        $data['cu'] = $this->cu;
        $this->load->view($this->fhead, $data);
        $this->load->view($this->fpath.'coupons_form', $data);
        $this->load->view($this->ffoot, $data);	
    }
    
    public function edit($id)
    {
        $O = new OCoupon($id);
        
        if(!empty($id))
        {			
            if($O->row == FALSE)
            {
                $this->session->set_flashdata('warning', 'ID does not exist.');
                redirect($this->curpage);
                exit();
            }
        }
        
		$this->form_validation->set_rules('code', 'Code', 'trim|required');
		$this->form_validation->set_rules('discount', 'Discount', 'trim|required');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required');
		$this->form_validation->set_rules('expired_date', 'Expired Date', 'trim|required');
		$this->form_validation->set_error_delimiters('<br /><span class="red">', '</span>');
	
		if($this->form_validation->run() == TRUE)
      	//if(sizeof($_POST) > 0)
        {
			extract($_POST);
            
            $arr = NULL;
            $exclude_arr = array("submit","url_title","password","image");
            
            foreach($_POST as $key => $val)
            {
                if(!in_array($key, $exclude_arr))
                {	
                    $arr[$key] = trim($val);	
                }
            }
            
            $res = $O->edit($arr);
			
            if($res) warning("edit", "success");
            else warning("edit", "fail");
			unset($O);
            redirect($this->curpage);
            exit();
        }
        $data['cu'] = $this->cu;
        $data['row'] = $O->row;
        $this->load->view($this->fhead, $data);
        $this->load->view($this->fpath.'coupons_form', $data);
        $this->load->view($this->ffoot, $data);	
    }
	
	public function email_check($email, $id_exception)
	{
		if(OUser::check_email_exists($email, $id_exception)) {
			$this->form_validation->set_message('email_check', 'This email has been taken, please use another email');
			return FALSE;
		}else return true;
	}
    
    public function delete()
    {
		$id = $this->uri->segment(4);
        $res = $this->db->query("DELETE FROM coupon_code WHERE id = '".$id."'");
		
		if($res) warning("delete", "success"); else warning("delete", "fail");
        redirect($this->curpage);
    }
}

/* End of file coupons.php */
/* Location: ./application/controllers/admin/coupons.php */