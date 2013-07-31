<?php

class OSupplierPendingProduct
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
				$q = "SELECT * FROM supplier_pending_products WHERE id = ?";
			}
			else 
			{
				$q = "SELECT * FROM supplier_pending_products WHERE url_title = ?";
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
		$CI->db->insert('supplier_pending_products',$params);
		return $CI->db->insert_id();
	}
	
	public function edit($params)
	{
		return $this->db->update('supplier_pending_products',$params,array('id' => $this->id));		
	}
	
	public function delete()
	{
		// $old_photo = $this->row->photo_url;
		$del = $this->db->query("DELETE FROM supplier_pending_products WHERE id = ?", array($this->id));
		
		if(!$del) return FALSE;
		else
		{	
			$list = OSupplierPendingProductPhotos::get_list(0, 0, "id DESC", $this->id);
			
			foreach($list as $r)
			{
				$old_image = $r->image;
				
				$OSupplierPendingProductPhoto::delete_photo($old_image);
			}
			
			$this->db->query("DELETE FROM supplier_pending_product_photos WHERE supplier_pending_product_id = ?", array($this->id));
			return TRUE;
		}
	}
		/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="id DESC", $status="", $product_unique_key="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;		
		
	
		if($status != "")
		{
			$sql_stats[] = " status = ? ";
			$sql_arrs[] = $status;
		}
		if($product_unique_key != "")
		{
			$sql_stats[] = " product_unique_key = ? ";
			$sql_arrs[] = $product_unique_key;
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM supplier_pending_products {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public function get_details($page=0, $limit=0, $orderby="spp.id DESC"/*, $supplier_id=""*/)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		$sql_stats[] = " sppp.supplier_pending_product_id = ? ";
		$sql_arrs[] = $this->id;
		
		/*if($supplier_id != "")
		{
			$sql_stats[] = " supplier_id = ? ";
			$sql_arrs[] = $supplier_id;
		}*/
		
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS spp.*, sppp.* 
							  	FROM supplier_pending_products spp, supplier_pending_product_photos sppp
								WHERE spp.id = sppp.supplier_pending_product_id
								{$add_sql_stats} ", $sql_arrs);		
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function search($keyword)
    {
    	$CI =& get_instance();
        $q = "SELECT * 
        		FROM supplier_pending_products sps LEFT JOIN suppliers s ON sps.supplier_id = s.id
                WHERE sps.id = ?";
        $arr = array();
        $arr[] = intval($keyword);
                        $arr[] = "{$keyword}%";
                            $q .= " OR (s.name LIKE ?)";
                    $res = $CI->db->query($q,$arr); 
        
        if(emptyres($res)) return FALSE;
        else return $res->result();                
    }
	
	public function get_photo($limit="1", $size="200xcrops")
	{
		$photo = OSupplierPendingProductPhoto::get_list(0, intval($limit), "id DESC", $this->id);

					
		$img_url = NULL;
		foreach($photo as $row)
		{
			$PP = new OSupplierPendingProductPhoto();
			$PP->setup($row);
	//		var_dump($PP->row);
			
			$img_url = $PP->get_photo($size);
//			var_dump($img_url);
			//$img = '<img src="'.$img_url.'" alt="" />';
			unset($PP);
		}
		//return "";
		return $img_url;
	}
	
	public function get_category()
	{
		$q = "SELECT * FROM categories WHERE id = ?";
		$res = $this->db->query($q,array($this->row->category_id));
		if(emptyres($res)) return FALSE;
		else return $res->row();
	}
}
?>