<?php

class OProductSubCategory
{
	var $CI;
	var $db;
	var $row;
	var $id;
	var $photo_crop_arr;
	
	public function __construct($id)
	{
		$CI = & get_instance();
		$this->CI = $CI;
		$this->db = $CI->db;
		$this->photo_crop_arr = NULL;
		if(empty($id))
		{
			$this->id = false;
			$this->row = false;
		}
		else
		{
			$q = "SELECT * FROM product_subcategories WHERE id = ?";
			$res = $this->db->query($q,array($id));
			if(emptyres($res)) 
			{
				$this->id = false;
				$this->row = false;
			}
			else
			{
				$this->row = $res->row();
				$this->id = $this->row->id;
			}
		}		
	}
	
	public static function add($arr)
	{
		$CI =& get_instance();
		$CI->db->insert('product_subcategories',$arr);
		return $CI->db->insert_id();
	}
	
	public static function add_batch($arr)
	{
		$CI =& get_instance();
		$CI->db->insert_batch('product_subcategories',$arr);
		return TRUE;
	}
	
	public function setup($row)
	{
		if($row->id != "")
		{
			$this->row = $row;
			$this->id = $row->id;
		}
		else return false;
	}
	
	public function edit($arr)
	{
		return $this->db->update('product_subcategories',$arr,array('id' => $this->id));		
	}
	
	public function delete()
	{
		return $this->db->delete('product_subcategories',array('id' => $this->id));
	}	
	
	
	
	/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="ps.product_id DESC", $sub_category="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
	
		if($sub_category != "")
		{
			$sql_stats[] = " ps.subcategory_id = ? ";
			$sql_arrs[] = $sub_category;	
		}
		
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " AND ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		
		// order by
		if(trim($orderby) != "") $add_sql_stats .= " ORDER BY ".$orderby." ";
		// limit
		if(intval($limit) > 0)
		{
			$add_sql_stats .= " LIMIT ?, ? ";
			$sql_arrs[] = intval($page);
			$sql_arrs[] = intval($limit);
		}
		// setup
		//$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM product_subcategories {$add_sql_stats} ", $sql_arrs);
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS DISTINCT(ps.product_id)
							  	FROM product_subcategories ps, supplier_product_sizes sps 
								WHERE ps.product_id = sps.product_id {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
}
?>