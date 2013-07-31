<?php

class OProductReview
{
	var $CI;
	var $db;
	var $row;
	var $id;
	
	public function __construct($id)
	{
		$CI = & get_instance();
		$this->CI = $CI;
		$this->db = $CI->db;
		if(intval($id) == 0)
		{
			$this->id = false;
			$this->row = false;
		}
		else
		{
			$q = "SELECT * FROM product_reviews WHERE id = ?";
			$res = $this->db->query($q,array($id));
			if(emptyres($res)) 
			{
				$this->id = false;
				$this->row = false;
			}
			else
			{
				$this->id = $id;
				$this->row = $res->row();
			}
		}		
	}
	
	public static function add($arr)
	{
		$CI =& get_instance();
		$CI->db->insert('product_reviews',$arr);
		return $CI->db->insert_id();
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
		return $this->db->update('product_reviews',$arr,array('id' => $this->id));		
	}
	
	public function delete()
	{
		return $this->db->query("DELETE FROM product_reviews WHERE id = ?", array($this->id));		
	}	
	
	/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="id DESC", $product_id, $status="", $user_id, $order_id, $size_id, $order_detail_id)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		if($status != "")
		{
			$sql_stats[] = " status = ? ";
			$sql_arrs[] = $status;
		}
		if($product_id != "")
		{
			$sql_stats[] = " product_id = ? ";
			$sql_arrs[] = $product_id;
		}
		if($user_id != "")
		{
			$sql_stats[] = " user_id = ? ";
			$sql_arrs[] = $user_id;
		}
		if($order_id != "")
		{
			$sql_stats[] = " order_id = ? ";
			$sql_arrs[] = $order_id;
		}
		
		if($size_id != "")
		{
			$sql_stats[] = " size_id = ? ";
			$sql_arrs[] = $size_id;
		}
		if($order_detail_id != "")
		{
			$sql_stats[] = " order_detail_id = ? ";
			$sql_arrs[] = $order_detail_id;
		}
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " WHERE ";
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM product_reviews {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function search($keyword)
    {
    	$CI =& get_instance();
        $q = "SELECT * 
        		FROM product_reviews pr LEFT JOIN products p ON pr.product_id = p.id
                WHERE pr.id = ?";
        $arr = array();
        $arr[] = intval($keyword);
                        $arr[] = "{$keyword}%";
                            $q .= " OR (p.name LIKE ?)";
                    $res = $CI->db->query($q,$arr); 
        
        if(emptyres($res)) return FALSE;
        else return $res->result();                
    }
	
	
}
?>