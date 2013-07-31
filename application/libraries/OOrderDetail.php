<?php

class OOrderDetail
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
			$q = "SELECT * FROM order_details WHERE id = ?";
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
		$CI->db->insert('order_details',$arr);
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
		return $this->db->update('order_details',$arr,array('id' => $this->id));		
	}
	
	public function delete()
	{
		$this->db->query("DELETE FROM order_details WHERE order_id = ?", array($this->id));		
		return $this->db->query("DELETE FROM orders WHERE id = ?", array($this->id));		
	}	
	
	public function refresh()
    {
        $res = $this->db->get_where('order_details',array("id" => $this->id));
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

	public static function get_list($page=0, $limit=0, $orderby="id DESC", $order_id="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		if($order_id != "")
		{
			$sql_stats[] = " order_id = ? ";
			$sql_arrs[] = $order_id;
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
	
	public function get_details($page=0, $limit=0, $orderby="id DESC"/*, $active=""*/)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		$sql_stats[] = " order_id = ? ";
		$sql_arrs[] = $this->id;
		
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
	
	public function get_detail_assign_suppliers($page=0, $limit=0, $orderby="id DESC"/*, $active=""*/)
	{
		$sql_stats = $sql_arrs = NULL;
		
		$sql_stats[] = " order_detail_id = ? ";
		$sql_arrs[] = $this->id;
		
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
		$res = $this->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM order_detail_assign_suppliers {$add_sql_stats} ", $sql_arrs);		
		if(emptyres($res)) return array(); 
		else return $res->result();
	}
	
	public function get_order_id($page=0, $limit=1/*, $orderby="id DESC", $active=""*/)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		$sql_stats[] = " id = ? ";
		$sql_arrs[] = $this->id;
		
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " WHERE ";
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS order_id FROM order_details {$add_sql_stats} ", $sql_arrs);		
		if(emptyres($res)) return array(); 
		else return $res->row();
	}
	
	public function get_qty($page=0, $limit=1/*, $orderby="id DESC", $active=""*/)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		$sql_stats[] = " id = ? ";
		$sql_arrs[] = $this->id;
		
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " WHERE ";
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS qty FROM order_details {$add_sql_stats} ", $sql_arrs);		
		if(emptyres($res)) return array(); 
		else return $res->row();
	}	
	
	public function get_list_groupby_supplier($page=0, $limit=0, $orderby="odas.supplier_id DESC"/*, $groupby="odas.supplier_id"*/)
	{
		$sql_stats = $sql_arrs = NULL;
		
		$sql_stats[] = " od.id = ? ";
		$sql_arrs[] = $this->id;
		
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " AND ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		//group by
		//if(trim($groupby) != "") $add_sql_stats .= " GROUP BY ".$groupby." ";		
		//order by
		if(trim($orderby) != "") $add_sql_stats .= " ORDER BY ".$orderby." ";
		 
		//limit
		if(intval($limit) > 0)
		{
			$add_sql_stats .= " LIMIT ?, ? ";
			$sql_arrs[] = intval($page);
			$sql_arrs[] = intval($limit);
		}
		//setup
		$res = $this->db->query("SELECT SQL_CALC_FOUND_ROWS odas.supplier_id, od.id, odas.qty 
							  	FROM order_details od, order_detail_assign_suppliers odas
								WHERE od.id = odas.order_detail_id {$add_sql_stats} ", $sql_arrs);		
		if(emptyres($res)) return array(); 
		else return $res->result();
	}
}
?>