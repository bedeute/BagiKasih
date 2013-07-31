<?php

class OThemecategory
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
				$q = "SELECT * FROM theme_categories WHERE id = ?";
			}
			else 
			{
				$q = "SELECT * FROM theme_categories WHERE url_title = ?";
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
	
	public function get_category()
	{
		$q = "SELECT * FROM categories WHERE id = ?";
		$res = $this->db->query($q,array($this->row->category_id));
		if(emptyres($res)) return FALSE;
		else return $res->row();
	}
	
	public static function add($params)
	{
		$CI =& get_instance();
		$CI->db->insert('theme_categories',$params);
		$new_id = $CI->db->insert_id();
		if($new_id)
		{
			if(trim($params['name']) != "")
			{
				$url_title = url_title($params['name'], 'dash', TRUE);
				$check_duplicate = $CI->db->query("SELECT id FROM theme_categories WHERE url_title = ?", array($url_title));
				if(!emptyres($check_duplicate))
				{
					$url_title = $url_title."_".$new_id;
				}
				$lvcategory = new OTypecategory($new_id);
				$lvcategory->edit(array("url_title" => $url_title));
			}
		}
		return $new_id;
	}
	
	public function edit($params)
	{
		if(trim($params['name']) != "")
		{
			$url_title =  url_title($params['name'], 'underscore', TRUE);
			$check_duplicate = $this->db->query("SELECT id FROM theme_categories WHERE url_title = ? AND id <> ?", array($url_title, $this->id));
			if(!emptyres($check_duplicate))
			{
				$url_title = $url_title."_".$this->id;
			}
			$params['url_title'] = $url_title;
		}
		return $this->db->update('theme_categories',$params,array('id' => $this->id));		
	}
	
	public function delete()
	{
		return $this->db->delete('theme_categories',array('id' => $this->id));		
	}	
	
	/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="id DESC",$category_id = 0)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		
		if($category_id != 0)
		{
			$sql_stats[] = " category_id = ? ";
			$sql_arrs[] = $category_id;
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM theme_categories {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function drop_down_select($name,$selval,$optional = "",$default_val="",$id_exception="",$parent_id="")
	{
		$lists = OThemecategory::get_list(0, 0, "name ASC", "0");
		$arr = array();
		
		if($default_val != "") $arr[''] = $default_val;
		foreach($lists as $r)
		{
			if($id_exception != $r->id) $arr[$r->id] = $r->name;
			if($parent_id == "")
			{
				$childs = OSubcategory::get_list(0, 0, "name ASC", $r->id);
				foreach($childs as $rc)
				{
					$arr[$rc->id] = $r->name." > ".$rc->name;
				}
			}
		}
		return dropdown($name,$arr,$selval,$optional);
	}
	
	public static function get_cat_all_link($catid,$filters)
	{
		$CAT = new OCategory($catid);
		if($CAT->row->id == "") return FALSE;
		unset($filters['page']);
		unset($filters['subcatid']);
		return $CAT->get_link()."?".http_build_query($filters);
	}
	
	public function get_link()
	{
		return "shop/v/{$this->row->url_title}";
	}
	
	public function get_cat_link($catid,$filters)
	{
		$CAT = new OCategory($catid);
		if($CAT->row->id == "") return FALSE;
		unset($filters['page']);
		/*unset($filters['subcatid']);
		$filters['subcatid'] = $this->row->id;*/
		unset($filters['themeid']);
		$filters['themeid'] = $this->row->id;
		return $CAT->get_link()."?".http_build_query($filters);
	}
	
	public static function checkbox_select($name,$selval=array(),$optional = "",$separator = "<br />", $category_id)
	{
		$lists = OThemecategory::get_list(0, 0, "name ASC", $category_id);
		
		$arr = array();
		foreach($lists as $r)
		{
			$arr[$r->id] = $r->name;
		}
		return checkboxes($name,$arr,$selval,$optional,$separator);		
	}
	
	public static function get_theme_ddl($name, $selval, $optional, $category_id, $default_value = '')
	{
		$list = OThemecategory::get_list(0, 0, "id ASC", $category_id);
		
		foreach($list as $r)
		{	
			$arr[$r->id] = $r->name;
		}
		return dropdown($name,$arr,$selval,$optional, $default_value);
	}
	
	public static function search($keyword)
    {
    	$CI =& get_instance();
        $q = "SELECT * 
        		FROM theme_categories
                WHERE id = ?";
        $arr = array();
        $arr[] = intval($keyword);
                        $arr[] = "{$keyword}%";
                            $q .= " OR (name LIKE ?)";
                    $res = $CI->db->query($q,$arr); 
        
        if(emptyres($res)) return FALSE;
        else return $res->result();                
    }
}
?>