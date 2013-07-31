<?php

class OAddOn
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
				$q = "SELECT * FROM addons WHERE id = ?";
			}
			else 
			{
				$q = "SELECT * FROM addons WHERE url_title = ?";
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
		$CI->db->insert('addons',$params);
		return $CI->db->insert_id();
	}
	
	public function edit($params)
	{
		return $this->db->update('addons',$params,array('id' => $this->id));		
	}
	
	public function delete()
	{
		$old_photo = $this->row->image;
		$del = $this->db->query("DELETE FROM addons WHERE id = ?", array($this->id));
		if(!$del) return false;
		else
		{
			OAddOn::delete_photo($old_photo);
			return true;
		}	
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM addons {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function save_photo($filename)
	{
		return save_photo($filename,"addons",array("600", "400", "200", "100", "200xcrops"));
	}
	
	public function get_photo($dim="200xcrops",$no_default_img=FALSE)
	{
		return get_photo_url($this->row->image, "addons", $dim, $no_default_img);		
	}
	
	public function delete_current_photo()
	{
		return delete_photo($this->row->image, "addons",array("originals", "600", "400", "200", "100", "200xcrops"));		
	}
	
	public static function delete_photo($image)
	{
		return delete_photo($image, "addons",array("originals", "600", "400", "200", "100", "200xcrops"));
	}	
	
	
	public static function checkbox_select($name,$selval=array(),$optional = "",$separator = "<br />", $price)
	{
		$list = OAddOn::get_list(0, 0, "name ASC");
		//<td><input type="hidden" name="supplier_id[]" value="'.$r->id.'" readonly="readonly" /></td>		
		$arr = array();		
		foreach($list as $r)
		{
			$display = '<td width="150px">'.$r->name.'</td>';
			$additional_display = '<td><input type="text" name="price['.$r->id.']" value="'.$price[$r->id].'" class="price" placeholder="Price" size="20px" /></td>';
							
			if(in_array($r->id, $selval)) $checked = 'checked="checked"'; 
			else $checked = '';
			
			$ret[] = "	<span>							
							<table>
								<tr>
									<label for='addon_".$r->id."'>
										<td>
											<input type='checkbox' value='{$r->id}' name='addon_id[".$r->id."]' id='addon_id[".$r->id."]' $checked> $display
										</td>
									</label>
									&nbsp;&nbsp;
									".$additional_display."
								</tr>
							</table>
						</span>";
		}
		return implode("", $ret);		
	}
	
	public function get_price($product_id)
	{
		$sql_stats = $sql_arrs = NULL;		
		
		if($product_id != "")
		{
			$sql_stats[] = " product_id = ? ";
			$sql_arrs[] = $product_id;
		}
		
		$sql_stats[] = " addon_id = ? ";
		$sql_arrs[] = $this->id;
		
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " WHERE ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		
		// setup
		$res = $this->db->query("SELECT SQL_CALC_FOUND_ROWS price FROM product_addons {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->row();
	}
	
	public static function search($keyword)
    {
    	$CI =& get_instance();
        $q = "SELECT * 
        		FROM addons
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