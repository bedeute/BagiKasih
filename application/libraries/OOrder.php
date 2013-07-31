<?php

class OOrder
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
			$q = "SELECT * FROM orders WHERE id = ?";
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
		$CI->db->insert('orders',$arr);
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
		return $this->db->update('orders',$arr,array('id' => $this->id));		
	}
	
	public function delete()
	{
		$this->db->query("DELETE FROM order_details WHERE order_id = ?", array($this->id));		
		return $this->db->query("DELETE FROM orders WHERE id = ?", array($this->id));		
	}	
	
	 public function refresh()
    {
        $res = $this->db->get_where('orders',array("id" => $this->id));
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
	
	/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="id DESC", $user_id="", $status="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		if($user_id != "")
		{
			$sql_stats[] = " user_id = ? ";
			$sql_arrs[] = $user_id;
		}
		if($status != "")
		{
			$sql_stats[] = " status_new = ? ";
			$sql_arrs[] = $status;
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM orders {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public function get_details($page=0, $limit=0, $orderby="id DESC", $supplier_id="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		$sql_stats[] = " order_id = ? ";
		$sql_arrs[] = $this->id;
		
		if($supplier_id != "")
		{
			$sql_stats[] = " supplier_id = ? ";
			$sql_arrs[] = $supplier_id;
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM order_details {$add_sql_stats} ", $sql_arrs);		
		if(emptyres($res)) return array(); 
		else return $res->result();
	}
	
	
	public static function drop_down_select($name, $selval, $optional="" ,$location_id, $product_id, $size_id)
	{
		$list = OSupplier::get_supplier_by_location_product_size(0, 0, "s.name ASC", $location_id, $product_id, $size_id);
		
		foreach($list as $r)
		{
			$arr[$r->id] = $r->name;			
		}
		return dropdown($name,$arr,$selval,$optional);
	}
	
	
	public static function get_product_list_by_userid($page=0, $limit=0, $orderby="od.id DESC", $user_id="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		if($user_id != "")
		{
			$sql_stats[] = " o.user_id = ? ";
			$sql_arrs[] = $user_id;
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS DISTINCT(od.product_id) 
							  	FROM orders o, order_details od
								WHERE o.id = od.order_id 
								{$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}	
	
	public static function get_order_reviews_ddl($name, $selval, $optional, $user_id)
	{
		$list = OOrder::get_list(0, 0, "id DESC", $user_id);
		
		foreach($list as $r)
		{	
			$arr[$r->id] = "#".$r->id;			
		}
		return dropdown($name,$arr,$selval,$optional, $default_value);
	}
	
	public function get_total_order_after_edited($page=0, $limit=0/*, $orderby="o.id DESC", $active=""*/)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		$sql_stats[] = " o.id = ? ";
		$sql_arrs[] = $this->id;
		
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " AND ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		// order by
		//if(trim($orderby) != "") $add_sql_stats .= " ORDER BY ".$orderby." ";
		// limit
		if(intval($limit) > 0)
		{
			$add_sql_stats .= " LIMIT ?, ? ";
			$sql_arrs[] = intval($page);
			$sql_arrs[] = intval($limit);
		}
		// setup
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS SUM(od.final_price) as final_price_edited, SUM(odas.shipping_fee) as shipping_fee_edited
							  	FROM orders o, order_details od, order_detail_assign_suppliers odas 
								WHERE o.id = od.order_id
									AND od.id = odas.order_detail_id {$add_sql_stats} ", $sql_arrs);		
		if(emptyres($res)) return array(); 
		else return $res->row();
	}
	
	/*public function refresh($id)
    {
        $res = $this->db->get_where('orders',array("id" => $this->id));
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
    }*/
	
	public static function search($keyword)
    {
    	$CI =& get_instance();
        $q = "SELECT * 
        		FROM orders
                WHERE id = ?";
        $arr = array();
        $arr[] = intval($keyword);
                        $arr[] = "{$keyword}%";
                            $q .= " OR (name LIKE ?)";
                    $res = $CI->db->query($q,$arr); 
        
        if(emptyres($res)) return FALSE;
        else return $res->result();                
    }
	
	public function get_order_details_assign_supplier_by_order_id($page=0, $limit=0, $orderby="odas.supplier_id DESC")
	{
		$sql_stats = $sql_arrs = NULL;
		
		$sql_stats[] = " o.id = ? ";
		$sql_arrs[] = $this->id;
		
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
		$res = $this->db->query("SELECT SQL_CALC_FOUND_ROWS odas.order_detail_id, odas.supplier_id, odas.qty, odas.id
							  	FROM orders o, order_details od, order_detail_assign_suppliers odas 
								WHERE o.id = od.order_id
									AND od.id = odas.order_detail_id
								{$add_sql_stats} ", $sql_arrs);		
		if(emptyres($res)) return array(); 
		else return $res->result();
	}
	
	public static function get_delivery_status($page=0, $limit=0, $orderby="id DESC", $user_id="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		$sql_stats[] = " (status_new = 'assign' OR status_new = 'shipped') ";
		
		
		if($user_id != "")
		{
			$sql_stats[] = " user_id = ? ";
			$sql_arrs[] = $user_id;
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM orders {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public function get_order_detail_by_userid($page=0, $limit=0, $orderby="od.id DESC", $user_id="")
	{
		$sql_stats = $sql_arrs = NULL;
		
		$sql_stats[] = " o.id = ? ";
		$sql_arrs[] = $this->id;		
		
		if($user_id != "")
		{
			$sql_stats[] = " o.user_id = ? ";
			$sql_arrs[] = $user_id;
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
		$res = $this->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM orders o, order_details od WHERE o.id = od.order_id {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
}
?>