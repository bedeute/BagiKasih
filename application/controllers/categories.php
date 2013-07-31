<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends CI_Controller 
{
	
	public function index()
	{
		
	}
	
	public function clear()
	{
		clear_location();
		header("location: {$_SERVER['HTTP_REFERER']}");
		exit;
	}
	
	public function details($caturl, $page="")
	{
		/*$this->output->enable_profiler(TRUE);*/

		$oc = new OCategory($caturl,"urltitle");
		if($oc->row->id == "") { redirect(""); exit; }
		$data['oc'] = $oc;
		
		$start 				= $page;
		$perpage 			= 20;
		
		if($oc->row->id == "1" || $oc->row->id == "2" || $oc->row->id == "3")
		{
			$data['allproducts'] = OProduct::get_filtered_list(get_location(),$_GET['brandid'],$oc->row->id,$_GET['subcatid'],$_GET['sizeid'],$_GET['topicid'],"p.views DESC",intval($_GET['page']),20, "", $_GET['typeid'], $_GET['themeid'],$_GET['pricerangeid']);
			$data['fullproducts'] = OProduct::get_filtered_list(get_location(),$_GET['brandid'],$oc->row->id,$_GET['subcatid'],$_GET['sizeid'],$_GET['topicid'],"p.views DESC",0,0, "", $_GET['typeid'], $_GET['themeid'],$_GET['pricerangeid']);
		}
		else
		{
			$data['allproducts'] = OProduct::get_filtered_list_for_gift($_GET['brandid'],$oc->row->id,$_GET['subcatid'],$_GET['sizeid'],$_GET['topicid'],"p.views DESC",intval($_GET['page']),20, "", $_GET['typeid'], $_GET['themeid'],$_GET['pricerangeid']);
			$data['fullproducts'] = OProduct::get_filtered_list_for_gift($_GET['brandid'],$oc->row->id,$_GET['subcatid'],$_GET['sizeid'],$_GET['topicid'],"p.views DESC",0,0, "", $_GET['typeid'], $_GET['themeid'],$_GET['pricerangeid']);	
		}

		$data['uri'] 		= intval($start);
		$total 				= $GLOBALS['total']; //get_db_total_rows();
		$url 				= "categories/details/$catur1/$page";
		$data['total'] 		= intval($total);
		$data['pagination'] = genPagination($total, $perpage, $url, 5, 5, TRUE);
		
		$data['catid'] = $oc->row->id;
		
		$this->load->view('header', $data);
		$this->load->view('categories_details', $data);
		$this->load->view('footer', $data);		
	}
}

/* End of file categories.php */
/* Location: ./application/controllers/categories.php */