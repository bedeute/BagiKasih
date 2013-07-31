<?php

class DLSProductPhoto
{
	var $CI;
	var $db;
	var $row;
	var $id;
	var $photo_crop_arr;
	
	public function __construct($id)
	{
		$CI = & get_instance();
		$this->CI = $CI;
		$this->db = $CI->db;
		//$this->photo_crop_arr = array("cropped_110x75", "cropped_245x140", "cropped_300x200", "cropped_580x220", "cropped_630x390", "cropped_960x360");
		$this->photo_crop_arr = NULL;
		if(empty($id))
		{
			$this->id = false;
			$this->row = false;
		}
		else
		{
			$q = "SELECT * FROM product_photos WHERE id = ?";
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
	
	public static function add($arr)
	{
		$CI =& get_instance();
		$CI->db->insert('product_photos',$arr);
		return $CI->db->insert_id();
	}
	
	public static function add_batch($arr)
	{
		$CI =& get_instance();
		$CI->db->insert_batch('product_photos',$arr);
		return TRUE;
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
		return $this->db->update('product_photos',$arr,array('id' => $this->id));		
	}
	
	public function delete()
	{
		$old_photo = $this->row->image;
		$del = $this->db->delete('product_photos',array('id' => $this->id));		
		
		if(!$del) return false;
		else
		{
			DLSProductPhoto::delete_photo($old_photo);
			return true;
		}
	}	
	
	/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="id DESC", $product_id="", $default_photo=""/*, $active=""*/)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		if($default_photo != "")
		{
			$sql_stats[] = " default_photo = ? ";
			$sql_arrs[] = $default_photo;	
		}
		if($product_id != "")
		{
			$sql_stats[] = " product_id = ? ";
			$sql_arrs[] = $product_id;	
		}
		/*
		if($active != "")
		{
			$sql_stats[] = " active = ? ";
			$sql_arrs[] = $active;
		}*/
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM product_photos {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function save_photo($filename)
	{
		return save_photo($filename,"products",array("600", "400", "200", "100", "200xcrops"));
	}
	
	public function get_photo($dim="200xcrops",$no_default_img=FALSE)
	{
		return get_photo_url($this->row->image, "products", $dim, $no_default_img);		
	}
	
	public function delete_current_photo()
	{
		return delete_photo($this->row->image, "products",array("600", "400", "200", "100", "200xcrops"),$this->photo_crop_arr);		
	}
	
	public static function delete_photo($image)
	{
		return delete_photo($image, "products",array("originals", "600", "400", "200", "100", "200xcrops"));
	}	
}
?>