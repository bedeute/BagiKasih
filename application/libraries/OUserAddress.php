<?php

class OUserAddress
{
	var $CI;
	var $db;
	var $row;
	var $id;
	
	public function __construct($id, $type="id")
	{
		$CI = & get_instance();
		$this->CI = $CI;
		$this->db = $CI->db;
		if(empty($id))
		{
			$this->id = false;
			$this->row = false;
		}
		else
		{
			if($type == "id")
			{
				$q = "SELECT * FROM user_addresses WHERE id = ?";
			}
			else 
			{
				$q = "SELECT * FROM user_addresses WHERE url_title = ?";
			}
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
	
	public function setup($row)
	{
		if($row->id != "")
		{
			$this->row = $row;
			$this->id = $row->id;
		}
		else return false;
	}
	
	public static function add($params)
	{
		$CI =& get_instance();
		$CI->db->insert('user_addresses',$params);
		return $CI->db->insert_id();
	}
	
	public function edit($params)
	{
		return $this->db->update('user_addresses',$params,array('id' => $this->id));		
	}
	
	public function delete()
	{
		return $this->db->query("DELETE FROM user_addresses WHERE id = ?", array($this->id));		
	}
	/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="id DESC", $user_id="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;		
				
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM user_addresses {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function drop_down_select($name,$selval,$optional = "", $user_id="")
	{
		$lists = OUserAddress::get_list(0, 0, "address DESC", $user_id);
		$arr = array();
		
		foreach($lists as $r)
		{
			$arr[$r->id] = $r->address;
		}
		
		return dropdown($name,$arr,$selval,$optional);
	}
}
?>