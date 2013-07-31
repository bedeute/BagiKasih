<?php

class ONewsletter
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
				$q = "SELECT * FROM newsletters WHERE id = ?";
			}
			else 
			{
				$q = "SELECT * FROM newsletters WHERE url_title = ?";
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
		$CI->db->insert('newsletters',$params);
		return $CI->db->insert_id();
	}
	
	public function edit($params)
	{
		return $this->db->update('newsletters',$params,array('id' => $this->id));		
	}
	
	public function delete()
	{
		return $this->db->query("DELETE FROM newsletters WHERE id = ?", array($this->id));
	}
	/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="id DESC", $email="", $is_subscribe="", $type="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;		
		
		if($email != "")
		{
			$sql_stats[] = " email = ? ";
			$sql_arrs[] = $email;
		}
		
		if($is_subscribe != "")
		{
			$sql_stats[] = " is_subscribe = ? ";
			$sql_arrs[] = $is_subscribe;
		}
		
		if($type != "")
		{
			$sql_stats[] = " type = ? ";
			$sql_arrs[] = $type;
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM newsletters {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function check_email_exists($email, $id_exception=0)
	{
		$ci =& get_instance();
		$q = "SELECT * FROM newsletters WHERE email = ? AND id != ? LIMIT 1";
		$res = $ci->db->query($q, array($email, $id_exception));
		if(!emptyres($res)) return $res->row();
		else return false;
	}
	
	public static function get_newsletter($email="", $is_subscribe="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;		
		
		if($email != "")
		{
			$sql_stats[] = " email = ? ";
			$sql_arrs[] = $email;
		}
		if($is_subscribe != "")
		{
			$sql_stats[] = " is_subscribe = ? ";
			$sql_arrs[] = $is_subscribe;
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM newsletters {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->row();
	}
	
	public static function search($keyword)
    {
    	$CI =& get_instance();
        $q = "SELECT * 
        		FROM newsletters
                WHERE id = ?";
        $arr = array();
        $arr[] = intval($keyword);
                        $arr[] = "{$keyword}%";
                            $q .= " OR (email LIKE ?)";
                    $res = $CI->db->query($q,$arr); 
        
        if(emptyres($res)) return FALSE;
        else return $res->result();                
    }
	
	
	public static function get_newsletter_queue_list($page=0, $limit=0, $orderby="id DESC")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM newsletter_queues {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function search_queue($keyword)
    {
    	$CI =& get_instance();
        $q = "SELECT * 
        		FROM newsletter_queues
                WHERE id = ?";
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