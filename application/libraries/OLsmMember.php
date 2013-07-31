<?php

class OLsmMember
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
			$q = "SELECT * FROM lsm_member WHERE id_member = ?";
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
	
	public function setup($row)
	{
		if($row->id != "")
		{
			$this->row = $row;
			$this->id = $row->id;
		}
		else return false;
	}
	
	public static function add($arr)
	{
		$CI =& get_instance();
		$CI->db->insert('lsm_member',$arr);
		return $CI->db->insert_id();
	}
	
	public function edit($arr)
	{
		return $this->db->update('lsm_member',$arr,array('id_member' => $this->id));		
	}
	
	public function delete()
	{
		return $this->db->query("DELETE FROM lsm_member WHERE id_member = ?", array($this->id));
	}	
	
	public static function get_list($page=0, $limit=0, $orderby="id_member DESC")
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM lsm_member {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function drop_down_select($name, $selval, $optional="")
	{
		$list = OLsmMember::get_list(0, 0, "name ASC");
		
		foreach($list as $r)
		{
			$arr[$r->id_member] = $r->name;			
		}
		return dropdown($name,$arr,$selval,$optional);
	}
	
	public function get_filtered_list($location_id,$brand_id = 0,$category_id = 0, $subcat_id = 0, $size_id = 0, $topic_id = 0,$orderby = "p.name ASC",$start = 0, $limit = 20, $featured = 0, $type_id = 0, $theme_id = 0, $price_range_id = 0)
	{
		// get all suppliers
		$q = "SELECT * FROM suppliers WHERE location_id = ?";
		$res = $this->db->query($q,array($location_id));
		if(emptyres($res)) return FALSE;
		$suppliers = array();
		foreach($res->result() as $row) $suppliers[] = $row->id;
		$q = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(p.id) 
				FROM lsm_member p
				INNER JOIN supplier_product_sizes sps ON sps.product_id = p.id
				AND sps.supplier_id IN (".@implode(",",$suppliers).")";
		
		if(intval($brand_id) != 0)
		{
			$where[] = "p.brand_id = ?";
			$val[] = intval($brand_id);
		}
		if(intval($category_id != 0))
		{
			$where[] = "p.category_id = ?";
			$val[] = intval($category_id);
		}
		if(intval($subcat_id != 0))
		{
			$where[] = "p.id IN (SELECT product_id FROM product_subcategories WHERE subcategory_id = ?)";
			$val[] = intval($subcat_id);
		}
		if(intval($size_id != 0))
		{
			$where[] = "p.id IN (SELECT product_id FROM product_sizes WHERE size_id = ?)";
			$val[] = intval($size_id);
		}
		if(intval($topic_id != 0))
		{
			$where[] = "p.id IN (SELECT product_id FROM product_topics WHERE topic_id = ?)";
			$val[] = intval($topic_id);
		}
		if(intval($featured) != 0)
		{
			$where[] = "p.featured = ?";
			$val[] = intval($featured);
		}
		/*tambah*/
		if(intval($type_id) != 0)
		{
			$where[] = "p.id IN (SELECT product_id FROM product_type_categories WHERE type_category_id = ?)";
			$val[] = intval($type_id);
		}
		if(intval($theme_id) != 0)
		{
			$where[] = "p.id IN (SELECT product_id FROM product_theme_categories WHERE theme_category_id = ?)";
			$val[] = intval($theme_id);
		}
		if(intval($price_range_id) != 0)
		{
			$where[] = "p.id IN (SELECT product_id FROM product_price_ranges WHERE price_range_id = ?)";
			$val[] = intval($price_range_id);
		}
		/*end*/
		if(sizeof($where) > 0)
		{
			$q .= " AND ".@implode($where," AND ");
		}
		$q .= " ORDER BY ".$orderby;
		if(intval($limit) > 0)
		{
			$q .= " LIMIT ?,?";
			$val[] = intval($start);
			$val[] = intval($limit);
		}
		$res = $this->db->query($q,$val);
		$GLOBALS['total'] = get_db_total_rows();
		if(emptyres($res)) return FALSE;
		else
		{
			$lsm_member = array();
			foreach($res->result() as $row)
			{
				
				$op = new OLsmMember($row->id);
				$lsm_member[] = $op->row;
				unset($op);
			}
			
			return $lsm_member;
		}	
	}
	
	public function get_filtered_list_keyword($keyword,$location_id,$brand_id = 0,$category_id = 0, $subcat_id = 0, $size_id = 0, $topic_id = 0,$orderby = "p.name ASC",$start = 0, $limit = 20, $featured = 0)
	{
		// get all suppliers
		$q = "SELECT * FROM suppliers WHERE location_id = ?";
		$res = $this->db->query($q,array($location_id));
		if(emptyres($res)) return FALSE;
		$suppliers = array();
		foreach($res->result() as $row) $suppliers[] = $row->id;
		$q = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(p.id) 
				FROM lsm_member p
				INNER JOIN supplier_product_sizes sps ON sps.product_id = p.id
				AND sps.supplier_id IN (".@implode(",",$suppliers).")
				AND p.name LIKE ?";
		$val[] = $keyword."%";
		if(intval($brand_id) != 0)
		{
			$where[] = "p.brand_id = ?";
			$val[] = intval($brand_id);
		}
		if(intval($category_id != 0))
		{
			$where[] = "p.category_id = ?";
			$val[] = intval($category_id);
		}
		if(intval($subcat_id != 0))
		{
			$where[] = "p.id IN (SELECT product_id FROM product_subcategories WHERE subcategory_id = ?)";
			$val[] = intval($subcat_id);
		}
		if(intval($size_id != 0))
		{
			$where[] = "p.id IN (SELECT product_id FROM product_sizes WHERE size_id = ?)";
			$val[] = intval($size_id);
		}
		if(intval($topic_id != 0))
		{
			$where[] = "p.id IN (SELECT product_id FROM product_topics WHERE topic_id = ?)";
			$val[] = intval($topic_id);
		}
		if(intval($featured) != 0)
		{
			$where[] = "p.featured = ?";
			$val[] = intval($featured);
		}
		if(sizeof($where) > 0)
		{
			$q .= " AND ".@implode($where," AND ");
		}
		
		if($order_by != "")
		{
			$q .= " ORDER BY ".$orderby;
		}
		
		if(intval($limit) > 0)
		{
			$q .= " LIMIT ?,?";
			$val[] = intval($start);
			$val[] = intval($limit);
		}
		$res = $this->db->query($q,$val);
		if(emptyres($res)) return FALSE;
		else
		{
			$lsm_member = array();
			foreach($res->result() as $row)
			{
				$op = new OLsmMember($row->id);
				$lsm_member[] = $op->row;
				unset($op);
			}
			
			return $lsm_member;
		}	
	}
	
	public static function search($keyword)
    {
    	$CI =& get_instance();
        $q = "SELECT * 
        		FROM lsm_member
                WHERE id_member = ?";
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