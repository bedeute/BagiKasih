<?php

class OBanner
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
				$q = "SELECT * FROM banners WHERE id = ?";
			}
			else 
			{
				$q = "SELECT * FROM banners WHERE url_title = ?";
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
		$CI->db->insert('banners',$params);
		return $CI->db->insert_id();
	}
	
	public function edit($params)
	{
		return $this->db->update('banners',$params,array('id' => $this->id));		
	}
	
	public function delete()
	{
		$old_photo = $this->row->photo_url;
		$del = $this->db->query("DELETE FROM banners WHERE id = ?", array($this->id));
		if(!$del) return false;
		else
		{
			OBanner::delete_photo($old_photo);
			return true;
		}	
	}
	
	public function featured()
	{
		$this->db->update('banners',array('featured' => 1), array('id' => $this->id));
		return TRUE;
	}
	public function unfeatured()
	{
		$this->db->update('banners',array('featured' => 0), array('id' => $this->id));
		return TRUE;
	}
	
	public function get_category()
	{
		$q = "SELECT * FROM categories WHERE id = ?";
		$res = $this->db->query($q,array($this->row->category_id));
		if(emptyres($res)) return FALSE;
		else return $res->row();
	}
	public function get_location()
	{
		$q = "SELECT * FROM locations WHERE id = ?";
		$res = $this->db->query($q,array($this->row->location_id));
		if(emptyres($res)) return FALSE;
		else return $res->row();
	}
	/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="id DESC"/*, $active=""*/)
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM banners {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function checkbox_select($name,$selval=array(),$optional = "",$separator = "<br />")
	{
		$lists = OBanner::get_list(0, 0, "title ASC");
		
		$arr = array();
		$oo = new OBanner();
		foreach($lists as $r)
		{			
			$oo->setup($r);
			$arr[$r->id] = "<img src='".$oo->get_photo()."' width='100' height='100' title='".$r->title." (".$r->id.")' />";
		}
		return str_replace("<br />","",checkboxes($name,$arr,$selval,$optional,$separator));		
	}
	
	
	
	public static function save_photo($filename)
	{
		return save_photo($filename,"banners",array("600", "400", "200", "100", "200xcrops","590x200"), array("cropped_590x200"));
	}
	
	public function get_photo($dim="200xcrops",$no_default_img=FALSE)
	{
		return get_photo_url($this->row->photo_url, "banners", $dim, $no_default_img);		
	}
	
	public function delete_current_photo()
	{
		return delete_photo($this->row->photo_url, "banners",array("600", "400", "200", "100", "200xcrops", "590x200"),$this->photo_crop_arr);		
	}
	
	public static function delete_photo($image)
	{
		return delete_photo($image, "banners",array("originals", "600", "400", "200", "100", "200xcrops","590x200"));
	}	
	
	public static function search($keyword)
    {
    	$CI =& get_instance();
        $q = "SELECT * 
        		FROM banners
                WHERE id = ?";
        $arr = array();
        $arr[] = intval($keyword);
                        $arr[] = "{$keyword}%";
                            $q .= " OR (title LIKE ?)";
                    $res = $CI->db->query($q,$arr); 
        
        if(emptyres($res)) return FALSE;
        else return $res->result();                
    }
}
?>