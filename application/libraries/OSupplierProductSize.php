<?php

class OSupplierProductSize
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
				$q = "SELECT * FROM supplier_product_sizes WHERE id = ?";
			}
			else 
			{
				$q = "SELECT * FROM supplier_product_sizes WHERE url_title = ?";
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
		$CI->db->insert('supplier_product_sizes',$params);
		return $CI->db->insert_id();
	}
	
	public function edit($params)
	{
		return $this->db->update('supplier_product_sizes',$params,array('id' => $this->id));		
	}
	
	public function delete()
	{
		// $old_photo = $this->row->photo_url;
		return $this->db->query("DELETE FROM supplier_product_sizes WHERE id = ?", array($this->id));
		/*		
		if(!$del) return false;
		else
		{
			OSupplier::delete_photo($old_photo);
			return true;
		}
		*/
		
	}
		/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="id DESC", $product_id = 0, $supplier_id = 0)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;		
		
	
		if(intval($product_id) != 0)
		{
			$sql_stats[] = " product_id = ? ";
			$sql_arrs[] = $product_id;
		}
		if(intval($supplier_id) != 0)
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM supplier_product_sizes {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function get_price_by_supplier_product_size($supplier_id="", $product_id="", $size_id="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;		
		
	
		if(intval($supplier_id) != 0)
		{
			$sql_stats[] = " supplier_id = ? ";
			$sql_arrs[] = $supplier_id;
		}
		if(intval($product_id) != 0)
		{
			$sql_stats[] = " product_id = ? ";
			$sql_arrs[] = $product_id;
		}
		if(intval($size_id) != 0)
		{
			$sql_stats[] = " size_id = ? ";
			$sql_arrs[] = $size_id;
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM supplier_product_sizes {$add_sql_stats} LIMIT 1", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->row();
	}
	
	public static function checkbox_select($name,$selval=array(),$optional = "",$separator = "<br />", $location_id, $product_id/*, $price, $discount_value, $final_price*/)
	{
		
		$lists = OSupplierProductSize::get_supplierproductsize_by_location(0, 0, "s.id DESC", $location_id, $product_id);
		
		$arr = array();
		foreach($lists as $r)
		{    
			$arr[$r->id] = $r->name.' | '.$r->size_name.'&nbsp;&nbsp;&nbsp;<input type="text" name="price['.$r->id.']" value="'.$price[$r->id].'" placeholder="Price" size="20px" /> | <input type="radio" name="primary_supplier" value="1"> Primary Supplier';
		}
		
		return checkboxes($name,$arr,$selval,$optional,$separator);
	}
	
	
	public static function get_supplierproductsize_by_location($page=0, $limit=0, $orderby="s.id DESC", $location_id="", $product_id="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;		
		
	
		if(intval($location_id) != "")
		{
			$sql_stats[] = " s.location_id = ? ";
			$sql_arrs[] = $location_id;
		}
		if(intval($product_id) != "")
		{
			$sql_stats[] = " sps.product_id = ? ";
			$sql_arrs[] = $product_id;
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
		$res = $CI->db->query("SELECT s.*, sps.*, sz.name as size_name 
								FROM suppliers s, locations l, supplier_product_sizes sps, sizes sz
									WHERE l.id = s.location_id										
										AND s.id = sps.supplier_id
										AND sz.id = sps.size_id
										{$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function drop_down_select($name,$selval,$optional="", $location_id="", $product_id="")
	{
		$list = OSupplierProductSize::get_supplierproductsize_by_location(0, 0, "s.id DESC", $location_id, $product_id);
		
		foreach($list as $r)
		{
			$S = new OSize($r->size_id);
			
			$arr[$r->id] = $S->row->name." &#124; ".rupiah_format($r->price);
			unset($S);			
		}
		return dropdown($name,$arr,$selval,$optional);
	}
	
	public static function radio_select_size_price($name,$selval=array(),$optional = "",$separator = "<br />", $location_id, $product_id)
	{
		$lists = OSupplierProductSize::get_supplierproductsize_by_location(0, 0, "s.id DESC", $location_id, $product_id);
		
		$arr = array();
		foreach($lists as $r)
		{    
			$S = new OSize($r->size_id);
			
			$arr[$r->id] = $S->row->name." &#124; ".rupiah_format($r->price);
			unset($S);
		}
		
		return radios($name,$arr,$selval,"",$separator="<br />");
	}
	
	public static function get_list_distinct_product($page=0, $limit=0, $orderby="id DESC", $product_id = 0, $supplier_id = 0)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;		
		
	
		if(intval($product_id) != 0)
		{
			$sql_stats[] = " product_id = ? ";
			$sql_arrs[] = $product_id;
		}
		if(intval($supplier_id) != 0)
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS DISTINCT(product_id) FROM supplier_product_sizes {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public function delete_by_product_id($product_id)
	{
		return $this->db->query("DELETE FROM supplier_product_sizes WHERE product_id = ?", array($product_id,$location_id));
	}
	
	public function delete_by_product_id_and_location($product_id,$location_id)
	{
		return $this->db->query("DELETE FROM supplier_product_sizes WHERE product_id = ? AND supplier_id IN (SELECT id FROM suppliers WHERE location_id = ?)", array($product_id,$location_id));
	}
}
?>