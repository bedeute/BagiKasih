<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller 
{
	
	public function __construct()
	{
		parent::__construct();		
		//error_reporting(E_ALL);	
	}
	
	public function index()
	{
		
	}
	
	public function price()
	{
		$this->load->view('header', $data);
		$this->load->view('price_list', $data);
		$this->load->view('footer', $data);		
	}
	
	public function details($id)
	{
		$P = new OProduct($id);
		$data['P'] = $P;
		
		$this->load->view('header', $data);
		$this->load->view('product_details', $data);
		//$this->load->view('product_details_2', $data);
		$this->load->view('footer', $data);		
	}
	
	public function send_testimonials($id)
	{
		$cu = get_logged_in_user();
		
		$this->form_validation->set_rules('topic', 'Topic', 'trim|required'); 
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if($this->form_validation->run() == TRUE)
		{
			extract($_POST);
			
			$arr = array('product_id'	=> $id,
						 'user_id'		=> $cu->id,
						 'rating'		=> $entity_score,
						 'topic'		=> $topic,
						 'description'	=> $description,
						 'status'		=> 'pending'
						 );
			
			$new = OProductReview::add($arr);
			
			//-------
			$U = new OUser($cu->id);
		
			$cek_point = $U->get_points();
			
			if(sizeof($cek_point) <= 0)
			{
				$arr_point = array(	'user_id'		=> $cu->id,
									'total_point'	=> '3'
								   );	
				
				$new_point = OPoint::add($arr_point);
			}
			else 
			{
				foreach($cek_point as $r)
				{
					$point_id = $r->id; 	
				}
				
				$PT = new OPoint($point_id);
				
				$res_point =  $this->db->query("UPDATE points SET total_point = total_point + 3 WHERE id = ?", array($PT->row->id));
			}
			//-------			
			
			if($new) $this->session->set_flashdata('testimonial_msg', 'Your testimonial has been sent.'); 
			redirect('products/details/'.$id);
		}
	}
	
	public function jejaring($id)
	{
		$P = new OProduct($id);
		$data['P'] = $P;
		
		$this->load->view('header', $data);
		$this->load->view('product_detail_jejaring', $data);
		$this->load->view('footer', $data);	
	}
}

/* End of file products.php */
/* Location: ./application/controllers/products.php */