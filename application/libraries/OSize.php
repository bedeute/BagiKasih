<?php

class OSize
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
				$q = "SELECT * FROM sizes WHERE id = ?";
			}
			else 
			{
				$q = "SELECT * FROM sizes WHERE url_title = ?";
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
		$CI->db->insert('sizes',$params);
		$new_id = $CI->db->insert_id();
		if($new_id)
		{
			if(trim($params['name']) != "")
			{
				$url_title = url_title($params['name'], 'dash', TRUE);
				$check_duplicate = $CI->db->query("SELECT id FROM sizes WHERE url_title = ?", array($url_title));
				if(!emptyres($check_duplicate))
				{
					$url_title = $url_title."_".$new_id;
				}
				$lvcategory = new OSize($new_id);
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
			$check_duplicate = $this->db->query("SELECT id FROM sizes WHERE url_title = ? AND id <> ?", array($url_title, $this->id));
			if(!emptyres($check_duplicate))
			{
				$url_title = $url_title."_".$this->id;
			}
			$params['url_title'] = $url_title;
		}
		return $this->db->update('sizes',$params,array('id' => $this->id));		
	}
	
	public function delete()
	{
		$this->db->delete('product_sizes', array('size_id'	=> $this->id));
		$this->db->delete('supplier_product_sizes', array('size_id'	=> $this->id));
		return $this->db->delete('sizes',array('id' => $this->id));		
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM sizes {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function get_tree_list_array($parent_id = 0, $prefix = "")
	{
		$CI =& get_instance();
		$arr = array();
		// see if there are children
		$q = "SELECT * FROM sizes WHERE parent_id = ? ORDER BY name ASC";
		$res = $CI->db->query($q,array($parent_id));
		if(emptyres($res))
		{
			// if there is no children, then return the current node
			$q = "SELECT * FROM sizes WHERE id = ? ORDER BY name ASC";
			$res = $CI->db->query($q,array($parent_id));
			if(emptyres($res)) return FALSE;
			$r = $res->row();
			if($prefix == "") return array($r->id => $r->name);
			else return array($r->id => $prefix." > ".$r->name);
		}
		else
		{
			// if there are children
			// get the current node
			$q = "SELECT * FROM sizes WHERE id = ? ORDER BY name ASC";
			$tmpres = $CI->db->query($q,array($parent_id));
			$r = $tmpres->row();
			if($r->name != "") 
			{
				if($prefix == "") $arr[$r->id] = $r->name;
				else $arr[$r->id] = $prefix." > ".$r->name;
				$nextprefix = $arr[$r->id];
			}
			
			foreach($res->result() as $row)
			{						
				$arr = array_merge_special($arr,OSize::get_tree_list_array($row->id,$nextprefix));
			}
			return $arr;
		}
	}
	
	public static function get_parents($children_id = 0, $prefix = "")
	{
		if(empty($children_id)) return FALSE;
		
		$CI =& get_instance();
		$arr = array();
		// see if there are parent
		$q = "SELECT * FROM sizes WHERE id = ? ORDER BY name ASC";
		$res = $CI->db->query($q,array($children_id));
		if(!emptyres($res))
		{
			// if there are parent
			// get the current node
			$row = $res->row();
			
			$q = "SELECT * FROM sizes WHERE id = ? ORDER BY name ASC";
			$tmpres = $CI->db->query($q,array($row->parent_id));
			$r = $tmpres->row();
			if(emptyres($tmpres)) return FALSE;
			if($r->name != "") 
			{
				$arr = array($r->id => $r->name);
				//if($prefix == "") $arr = array($r->id => $r->name);
				//else $arr = array($r->id => $prefix." > ".$r->name);
				$nextprefix = $arr[$r->id];
				$arr = array_merge_special($arr,OSize::get_parents($r->id,$nextprefix));
			}
			return $arr;
		} 
		else return FALSE;
	}

	public static function get_childrens($id = 0, $prefix = "")
	{
		if(empty($id)) return FALSE;
		
		$CI =& get_instance();
		$arr = array();
		// see if there are parent
		//*
		$q = "SELECT * FROM sizes WHERE parent_id = ? ORDER BY name ASC";
		$res = $CI->db->query($q,array($id));
		if(emptyres($res))
		{
			// if there is no children, then return the current node
			$q = "SELECT * FROM sizes WHERE id = ? ORDER BY name ASC";
			$res = $CI->db->query($q,array($id));
			if(emptyres($res)) return FALSE;
			$r = $res->row();
			return array($r->id => $r->name);
			//if($prefix == "") return array($r->id => $r->name);
			//else return array($r->id => $prefix." > ".$r->name);
		}
		else
		{
			// if there are parent
			// get the current node
			//*/
			$q = "SELECT * FROM sizes WHERE id = ? ORDER BY name ASC";
			$tmpres = $CI->db->query($q,array($id));
			$r = $tmpres->row();
			if(emptyres($tmpres)) return FALSE;
			if($r->name != "") 
			{
				$arr = array($r->id => $r->name);
				//if($prefix == "") $arr = array($r->id => $r->name);
				//else $arr = array($r->id => $prefix." > ".$r->name);
				$nextprefix = $arr[$r->id];
			}
			
			foreach($res->result() as $row)
			{						
				$arr = array_merge_special($arr,OSize::get_childrens($row->id,$nextprefix));
			}
			
			return $arr;
		//*
		}
		//*/
	}

	public static function drop_down_select($name,$selval,$optional = "",$default_val="",$id_exception="",$parent_id="")
	{
		$lists = OSize::get_list(0, 0, "name ASC", "0");
		$arr = array();
		
		if($default_val != "") $arr[''] = $default_val;
		foreach($lists as $r)
		{
			if($id_exception != $r->id) $arr[$r->id] = $r->name;
			if($parent_id == "")
			{
				$childs = OSize::get_list(0, 0, "name ASC", $r->id);
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
		unset($filters['sizeid']);
		return $CAT->get_link()."?".http_build_query($filters);
	}
	
	public function get_link()
	{
		//return "shop/v/{$this->row->url_title}";
		if($this->id == "") return "";
		return "sizes/details/".$this->row->url_title;
	}
	public function get_cat_link($catid,$filters)
	{
		$CAT = new OCategory($catid);
		if($CAT->row->id == "") return FALSE;
		unset($filters['page']);
		unset($filters['sizeid']);
		$filters['sizeid'] = $this->row->id;
		return $CAT->get_link()."?".http_build_query($filters);
	}
	
	public static function get_product_size_by_category($page=0, $limit=0, $orderby="s.id DESC",$category_id = 0)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		
		
		if($category_id != 0)
		{
			$sql_stats[] = " s.category_id = ? ";
			$sql_arrs[] = $category_id;
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS ps.*, s.* FROM product_sizes ps, sizes s WHERE ps.size_id = s.id {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function checkbox_select($name,$selval=array(),$optional = "",$separator = "", $category_id, $price, $discount_value, $final_price)
	{
		$lists = OSize::get_list(0, 0, "name ASC", $category_id);
		
		$ret = array();
		
		if(sizeof($lists) > 0)
		{
			$ret[] = "<table>
						<tr>
							<th width='120px' style='text-align: center'>Size</th>
							<th width='150px' style='text-align: center'>Price</th>
							<th width='150px' style='text-align: center'>Discount</th>
							<th width='150px' style='text-align: center'>Final Price</th>
						</tr>
					</table>";
		} 
		else $ret[] = '<strong>Please Insert Sizes First !!!</strong>';
		
		$i = 1;
		foreach($lists as $r)
		{    
			$display = '<td width="100px">'.$r->name.'</td>';
			$additional_display = '
			<td><input type="text" name="price['.$r->id.']" value="'.$price[$r->id].'" class="price" placeholder="Price" size="20px" /></td>
			<td><input type="text" name="discount_value['.$r->id.']" value="'.$discount_value[$r->id].'" class="discount" placeholder="Discount" size="20px" /></td>
			<td><input type="text" name="final_price['.$r->id.']" value="'.$final_price[$r->id].'" class="final_price" placeholder="Final Price" size="20px" /></td>
							';
						
			if(in_array($r->id,$selval)) { $checked = 'checked="checked"'; } else $checked = '';
			$id = str_replace("[]","",$name);
			$ret[] = "<span><label for='{$id}_".url_title($r->id)."_".$i."'><table><tr><td><input type='checkbox' value='{$r->id}' name='{$name}' id='{$id}_".url_title($r->id)."' $checked> $display</td></label>&nbsp;&nbsp;".$additional_display."</tr></table></span>";
			
			$i++;
		}
		
		return implode("", $ret);
	}
	
	public static function search($keyword)
    {
    	$CI =& get_instance();
        $q = "SELECT * 
        		FROM sizes
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