<?php

class OOrderDetailAssignSupplier
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
			$q = "SELECT * FROM order_detail_assign_suppliers WHERE id = ?";
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
		$CI->db->insert('order_detail_assign_suppliers',$arr);
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
		return $this->db->update('order_detail_assign_suppliers',$arr,array('id' => $this->id));		
	}
	
	public function delete()
	{
		return $this->db->query("DELETE FROM order_detail_assign_suppliers WHERE id = ?", array($this->id));		
	}	
	
	public function refresh($id)
	{
		$CI = & get_instance();
		
		if(intval($id) == 0)
		{
			$this->id = false;
			$this->row = false;
		}
		else
		{
			$q = "SELECT * FROM order_detail_assign_suppliers WHERE id = ?";
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
	
	/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="id DESC", $order_detail_id="", $supplier_id="", $status="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		if($order_detail_id != "")
		{
			$sql_stats[] = " order_detail_id = ? ";
			$sql_arrs[] = $order_detail_id;
		}
		if($supplier_id != "")
		{
			$sql_stats[] = " supplier_id = ? ";
			$sql_arrs[] = $supplier_id;
		}
		if($status != "")
		{
			$sql_stats[] = " status = ? ";
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM order_detail_assign_suppliers {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
}
?>