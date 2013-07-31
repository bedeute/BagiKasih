<?php

class OUser
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
				$q = "SELECT * FROM buyer WHERE id_buyer = ?";
			}
			else 
			{
				$q = "SELECT * FROM buyer WHERE url_title = ?";
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
		$CI->db->insert('buyer',$params);
		return $CI->db->insert_id();
	}
	
	public function edit($params, $id)
	{
		return $this->db->update('buyer',$params,array('id_buyer' => $id));
	}
	
	public function delete($id)
	{
		return $this->db->query("DELETE FROM buyer WHERE id_buyer = ?", array($id));		
	}
	/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="id_buyer DESC")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;		
		
		/*
		if($active != "")
		{
			$sql_stats[] = " active = ? ";
			$sql_arrs[] = $active;
		}
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " WHERE ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		*/
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM buyer {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function drop_down_select($name, $selval, $optional="")
	{
		$list = OUser::get_list(0, 0, "name ASC");
		
		foreach($list as $r)
		{
			$arr[$r->id] = $r->name." (".$r->email.")";			
		}
		return dropdown($name,$arr,$selval,$optional);
	}
	
	public function get_bank_accounts($page=0, $limit=0, $orderby="uba.id DESC"/*, $active=""*/)
	{
		$sql_stats = $sql_arrs = NULL;		
		
		$sql_stats[] = " uba.user_id = ? ";
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
		$res = $this->db->query("SELECT SQL_CALC_FOUND_ROWS uba.* FROM users u, user_bank_accounts uba WHERE u.id = uba.user_id {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public function get_points($page=0, $limit=1)
	{
		$sql_stats = $sql_arrs = NULL;		
		
		$sql_stats[] = " user_id = ? ";
		$sql_arrs[] = $this->id;
		
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " WHERE ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		
		//if(trim($orderby) != "") $add_sql_stats .= " ORDER BY ".$orderby." ";
		
		if(intval($limit) > 0)
		{
			$add_sql_stats .= " LIMIT ?, ? ";
			$sql_arrs[] = intval($page);
			$sql_arrs[] = intval($limit);
		}
		
		$res = $this->db->query("SELECT * FROM points {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public function get_total_point()
	{
		$q = "SELECT * FROM points WHERE user_id = ?";
		$res = $this->db->query($q,array($this->id));
		if(emptyres($res)) return 0;
		else
		{
			$r = $res->row();
			return intval($r->total_point);
		}
	}
	
	public function update_point($newpoint)
	{
		$q = "SELECT * FROM points WHERE user_id = ?";
		$res = $this->db->query($q,array($this->id));
		if(emptyres($res)) 
		{
			$this->db->insert('points',array('user_id' => $this->id, 'total_point' => $newpoint));
		}
		else
		{
			$this->db->update('points',array('total_point' => $newpoint),array('user_id' => $this->id));
		}
	}
	
	public static function check_email_exists($email, $id_exception=0)
	{
		$ci =& get_instance();
		$q = "SELECT * FROM buyer WHERE email = ? AND id_buyer != ? LIMIT 1";
		$res = $ci->db->query($q, array($email, $id_exception));
		if(!emptyres($res)) return $res->row();
		else return false;
	}
	
	public static function search($keyword)
    {
    	$CI =& get_instance();
        $q = "SELECT * 
        		FROM buyer
                WHERE id_buyer = ?";
        $arr = array();
        $arr[] = intval($keyword);
                        $arr[] = "{$keyword}%";
                            $q .= " OR (email LIKE ?)";
                    $res = $CI->db->query($q,$arr); 
        
        if(emptyres($res)) return FALSE;
        else return $res->result();                
    }
}
?>