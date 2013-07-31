<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_review extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->cu = $cu = get_logged_in_user();		
		if(!$cu) user_logout();
		//error_reporting(E_ALL);
	}
	
	public function index()
	{
		$this->product_reviews();
	}
	
	public function product_reviews()
	{
		$data['cu'] = $cu = $this->cu;
		
		$data['product_reviews'] = OProductReview::get_list(0, 0, "id DESC", "", "", $cu->id);
		
		$this->load->view("header", $data);
		$this->load->view("user_product_review", $data);
		$this->load->view("footer", $data);
	}
	
	public function add_product_reviews($o_id, $p_id)
	{
		$data['cu'] = $cu = $this->cu;
		
		/*$this->form_validation->set_rules('topic', 'Topic', 'trim|required'); 
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');*/
		
		if($_POST['description'] != NULL && $_POST['entity_score'] != NULL)
		{
		/*if($this->form_validation->run() == TRUE)
		{*/
			//var_dump($_POST); die();
			extract($_POST);
			
			$arr = array('order_id'		=> $order_id,
						 'product_id'	=> $product_id,
						 'size_id'		=> $size_id,
						 'user_id'		=> $cu->id,
						 'rating'		=> $entity_score,
						 /*'topic'		=> $topic,*/
						 'description'	=> $description,
						 'order_detail_id'	=> $order_detail_id,
						 'status'		=> 'pending'
						 );
			
			$new = OProductReview::add($arr);
			
			if($new) $this->session->set_flashdata('testimonial_msg', 'Your Product Review has been sent.'); 
			//redirect('user_review/product_reviews');
			redirect('user_review');
		//}
		}
		else
		{
			$this->session->set_flashdata('error_msg', 'Please insert Description and Rating.');
			redirect('user_review');	
		}
		
		/*$this->load->view("header", $data);
		$this->load->view("user_add_product_review", $data);
		$this->load->view("footer", $data);*/
	}
	
	public function order_reviews()
	{
		$data['cu'] = $cu = $this->cu;
		
		$data['order_reviews'] = OOrderReview::get_list(0, 0, "id DESC", $cu->id);
		
		$this->load->view("header", $data);
		$this->load->view("order_review", $data);
		$this->load->view("footer", $data);
	}
	
	public function add_order_reviews()
	{
		$data['cu'] = $cu = $this->cu;
		
		/*$this->form_validation->set_rules('topic', 'Topic', 'trim|required'); 
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');*/
		
		if($_POST['description'] != NULL && $_POST['entity_score'] != NULL)
		{
		/*if($this->form_validation->run() == TRUE)
		{*/
			extract($_POST);
			
			$arr = array('order_id'		=> $order_id,
						 'user_id'		=> $cu->id,
						 'rating'		=> $entity_score,
						 /*'topic'		=> $topic,*/
						 'description'	=> $description,
						 'status'		=> 'pending'
						 );
			
			$new = OOrderReview::add($arr);
			
			if($new) $this->session->set_flashdata('testimonial_msg', 'Your Order Review has been sent.'); 
			//redirect('user_review/order_reviews');
			redirect('user_review');
		//}	
		}
		else 
		{
			$this->session->set_flashdata('error_msg', 'Please insert Description and Rating.');
			redirect('user_review');	
		}
		
		/*$this->load->view("header", $data);
		$this->load->view("user_add_order_review", $data);
		$this->load->view("footer", $data);*/
	}
}

/* End of file user_review.php */
/* Location: ./application/controllers/user_review.php */