<?php

class OSupplier
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
				$q = "SELECT * FROM suppliers WHERE id_supplier = ?";
			}
			else 
			{
				$q = "SELECT * FROM suppliers WHERE url_title = ?";
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
		$CI->db->insert('suppliers',$params);
		return $CI->db->insert_id();
	}
	
	public function edit($params, $id)
	{
		return $this->db->update('suppliers',$params,array('id_supplier' => $id));		
	}
	
	public function delete($id)
	{
		// $old_photo = $this->row->photo_url;
		return $this->db->query("DELETE FROM suppliers WHERE id_supplier = ?", array($id));
		/*		
		if(!$del) return false;
		else
		{
			OSupplier::delete_photo($old_photo);
			return true;
		}
		*/
		
	}
	
	public function show()
	{
		$this->db->update('suppliers',array('shown' => 1), array('id' => $this->id));
		return TRUE;
	}
	public function hide()
	{
		$this->db->update('suppliers',array('shown' => 0), array('id' => $this->id));
		return TRUE;
	}
	
	
	public function get_location()
	{
		$q = "SELECT * FROM kota WHERE id_supplier = ?";
		$res = $this->db->query($q,array($this->row->location_id));
		if(emptyres($res)) return FALSE;
		else return $res->row();
	}
	/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="id DESC", $location_id = 0, $supplier_id = 0)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;		
		
	
		if(intval($location_id) != 0)
		{
			$sql_stats[] = " id_city = ? ";
			$sql_arrs[] = $location_id;
		}
		
		if(intval($supplier_id) != '')
		{
			$sql_stats[] = " id_supplier = ? ";
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM suppliers {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function checkbox_select($name,$selval=array(),$optional = "",$separator = "<br />")
	{
		$lists = OSupplier::get_list(0, 0, "title ASC");
		
		$arr = array();
		$oo = new OSupplier();
		foreach($lists as $r)
		{			
			$oo->setup($r);
			$arr[$r->id] = "<img src='".$oo->get_photo()."' width='100' height='100' title='".$r->title." (".$r->id.")' />";
		}
		return str_replace("<br />","",checkboxes($name,$arr,$selval,$optional,$separator));		
	}
	
	/*public static function get_supplier_by_location_product_size($page=0, $limit=0, $orderby="s.id DESC", $location_id="", $product_id="", $size_id="", $group_by = "", $status = "")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;		
		
	
		if(intval($location_id) != "")
		{
			$sql_stats[] = " o.location_id = ? ";
			$sql_arrs[] = $location_id;
		}
		if(intval($product_id) != "")
		{
			$sql_stats[] = " od.product_id = ? ";
			$sql_arrs[] = $product_id;
		}
		if(intval($size_id) != "")
		{
			$sql_stats[] = " od.size_id = ? ";
			$sql_arrs[] = $size_id;
		}
		
		if(intval($status) != "")
		{
			$sql_stats[] = " sps.status = ? ";
			$sql_arrs[] = $status;
		}
				
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " AND ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		
		if(trim($group_by) != "") $add_sql_stats .= " GROUP BY ".$group_by." ";

		if(trim($orderby) != "") $add_sql_stats .= " ORDER BY ".$orderby." ";

		if(intval($limit) > 0)
		{
			$add_sql_stats .= " LIMIT ?, ? ";
			$sql_arrs[] = intval($page);
			$sql_arrs[] = intval($limit);
		}

		$res = $CI->db->query("SELECT s.* 
								FROM suppliers s, orders o, locations l, order_details od, supplier_product_sizes sps
									WHERE l.id = s.location_id
										AND l.id = o.location_id											
										AND o.id = od.order_id
										AND od.product_id = sps.product_id
										AND od.size_id = sps.size_id
										AND s.id = sps.supplier_id
										{$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}*/
	
	
	public static function checkbox_select_by_location($name,$supplier_sizes=array(),$optional = "",$separator = "<br />", $location_id, $category_id, $price, $discount_value, $final_price)
	{
		$suppliers = OSupplier::get_list(0, 0, "name ASC", $location_id);
				
		$arr = array();
		
		if(sizeof($suppliers) > 0)
		{
			$ret[] = '<table>
						<tr>
							<th width="150px" style="text-align: center">Supplier Name</th>
							<th width="150px" style="text-align: center">Size</th>
							<th width="150px" style="text-align: center">Price</th>
							<!--<th width="150px" style="text-align: center">Primary Supplier</th>-->
						</tr>
					</table>';
		}
		
		
		foreach($suppliers as $r)
		{   
			$sizes = OSize::get_list(0, 0, "name ASC", $category_id);
			
			$i = 1;
			foreach($sizes as $row)
			{
				$display = '<td width="150px">'.$r->name.'</td>';
								
				$additional_display = '
				<td><input type="hidden" name="supplier_id['.$r->id.']" value="'.$r->id.'" readonly="readonly" /></td>
				<td width="120px">
					Size : '.$row->name.'
					<input type="hidden" name="size_name['.$r->id.'][]" value="'.$row->name.'" readonly="readonly" />
					<input type="hidden" name="size_id['.$r->id.'][]" value="'.$row->id.'" readonly="readonly" />
				</td>								
				<td><input type="text" name="price['.$r->id.'][]" value="'.$price[$r->id."|".$row->id].'" class="price" size="20px" /></td>
				<td><input type="hidden" name="discount_value['.$r->id.']" value="0" class="discount" placeholder="Discount" size="20px" readonly="readonly" /></td>
				<!--<td><input type="radio" name="primary_supplier['.$r->id.']" value="1" > Primary Supplier</td>-->
								';
							
				if(in_array($r->id."|".$row->id,$supplier_sizes)) { $checked = 'checked="checked"'; } else $checked = '';
				$id = str_replace("[]","",$name);
				$ret[] = "<span supplier_id='".$r->id."'><label for='{$id}_".url_title($r->id)."_".$i."'><table><tr><td><input type='checkbox' value='{$r->id}' name='{$name}' id='{$id}_".url_title($r->id)."' $checked> $display</td></label>".$additional_display."</tr></table></span>";
				$i++;
			}
			
		}
		return implode("", $ret);
	}
	
	/*public static function checkbox_select_order_assign_supplier($name,$selval=array(),$optional = "",$separator = "<br />", $qty, $product_id, $size_id, $order_detail_id)
	{
		$lists = OSupplier::get_supplier_by_location_product_size(0, 0, "s.id DESC", "", $product_id, $size_id, "sps.supplier_id", "active");		
		
		$arr = array();
		foreach($lists as $r)
		{			
			$display = '<td width="150px">'.$r->name.'</td>';
			$additional_display = '<td><input type="text" name="qty['.$r->id.']" value="'.$qty[$r->id].'" class="qty" placeholder="Qty" size="20px" /></td>
									<td><input type="hidden" name="order_detail_id['.$r->id.']" value="'.$order_detail_id.'" class="order_detail_id" /></td>';
						
			if(in_array($r->id,$selval)) $checked = 'checked="checked"';
			else $checked = '';
			
			$ret[] = "	<span>							
							<table class='sup_list'>
								<tr>
									<label for='supplier_".$r->id."'>
										<td>
											<input type='checkbox' value='{$r->id}' name='supplier_id[".$r->id."]' id='supplier_id[".$r->id."]' $checked> $display
										</td>
									</label>
									&nbsp;&nbsp;
									".$additional_display."
								</tr>
							</table>
						</span>";
		}
		return implode("", $ret);
	}*/
	
	public function get_order_assign_supplier($order_id, $page=0, $limit=0/*, $orderby="id DESC", $active=""*/)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
				
		$sql_stats[] = " o.id = ? ";
		$sql_arrs[] = $order_id;
				
		
		/*if($order_detail_id != "")
		{
			$sql_stats[] = " odas.order_detail_id = ? ";
			$sql_arrs[] = $order_detail_id;
		}*/
		
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " AND ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		
		// order by
		//if(trim($orderby) != "") $add_sql_stats .= " ORDER BY ".$orderby." ";
		// limit
		if(intval($limit) > 0)
		{
			$add_sql_stats .= " LIMIT ?, ? ";
			$sql_arrs[] = intval($page);
			$sql_arrs[] = intval($limit);
		}
		// setup
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS odas.* 
							  		FROM order_detail_assign_suppliers odas, orders o, order_details od 
									WHERE o.id = od.order_id 
										AND od.id = odas.order_detail_id {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public function delete_order_assign_supplier($order_detail_id)
	{
		return $this->db->delete('order_detail_assign_suppliers', array("order_detail_id" => $order_detail_id));		
	}
	
	public function set_order_assign_supplier($arr)
	{
		//$this->delete_order_assign_supplier();
		return $this->db->insert_batch('order_detail_assign_suppliers',$arr);		
	}
	
	public static function add_order_assign_supplier($params)
	{
		$CI =& get_instance();
		$CI->db->insert('order_detail_assign_suppliers',$params);
		return $CI->db->insert_id();
	}
	
	
	public function get_new_orders($page=0, $limit=0, $orderby="odas.order_detail_id DESC", $status=0)
	{
		$sql_stats = $sql_arrs = NULL;
		
		$sql_stats[] = " odas.supplier_id = ? ";
		$sql_arrs[] = $this->id;
		
		if($status != 0)
		{
			$sql_stats[] = " odas.status = ? ";
			$sql_arrs[] = $status;	
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
		$res = $this->db->query("SELECT SQL_CALC_FOUND_ROWS odas.* 
							  		FROM order_detail_assign_suppliers odas, suppliers s
									WHERE s.id = odas.supplier_id {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public function get_total_order_detail_assign_supplier($order_id, $page=0, $limit=0/*, $orderby="od.id DESC", $active=""*/)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
				
		$sql_stats[] = " o.id = ? ";
		$sql_arrs[] = $order_id;
				
		
		/*if($order_detail_id != "")
		{
			$sql_stats[] = " odas.order_detail_id = ? ";
			$sql_arrs[] = $order_detail_id;
		}*/
		
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " AND ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		
		// order by
		//if(trim($orderby) != "") $add_sql_stats .= " ORDER BY ".$orderby." ";
		
		$add_sql_stats .= " GROUP BY odas.order_detail_id ";
		// limit
		if(intval($limit) > 0)
		{
			$add_sql_stats .= " LIMIT ?, ? ";
			$sql_arrs[] = intval($page);
			$sql_arrs[] = intval($limit);
		}
		// setup
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS odas.*, SUM(odas.available_stock) as total_qty 
							  		FROM order_detail_assign_suppliers odas, orders o, order_details od 
									WHERE o.id = od.order_id 
										AND od.id = odas.order_detail_id {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	
	
	public function get_data_order($status = 0, $page=0, $limit=0, $orderby="o.dt DESC")
	{
		//$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
	
		if(intval($status) != 0)
		{
			$sql_stats[] = " odas.status = ? ";
			$sql_arrs[] = $status;
		}
		
		$sql_stats[] = " odas.supplier_id = ? ";
			$sql_arrs[] = $this->id;
		
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " AND ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		$add_sql_stats .= " GROUP BY year(o.dt), month(o.dt) ";
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
		$res = $this->db->query("SELECT SQL_CALC_FOUND_ROWS year(o.dt) as per_year, month(o.dt) as per_month, SUM(odas.available_stock) as total_order, 
										SUM(odas.available_stock * od.price) as total_price
									FROM order_detail_assign_suppliers odas, order_details od, orders o
									WHERE od.id = odas.order_detail_id
										AND o.id = od.order_id
									{$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	
	/*public static function get_supplier_by_location_product_size_new($page=0, $limit=0, $orderby="s.id DESC", $location_id="", $product_id="", $size_id="", $group_by = "", $status = "", $order_detail_id = "")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;		
		
	
		if(intval($location_id) != "")
		{
			$sql_stats[] = " o.location_id = ? ";
			$sql_arrs[] = $location_id;
		}
		if(intval($product_id) != "")
		{
			$sql_stats[] = " od.product_id = ? ";
			$sql_arrs[] = $product_id;
		}
		if(intval($size_id) != "")
		{
			$sql_stats[] = " od.size_id = ? ";
			$sql_arrs[] = $size_id;
		}
		
		if(intval($order_detail_id) != "")
		{
			$sql_stats[] = " od.id = ? ";
			$sql_arrs[] = $order_detail_id;
		}
		
		if(intval($status) != "")
		{
			$sql_stats[] = " sps.status = ? ";
			$sql_arrs[] = $status;
		}
				
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " AND ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		
		if(trim($group_by) != "") $add_sql_stats .= " GROUP BY ".$group_by." ";
		
		if(trim($orderby) != "") $add_sql_stats .= " ORDER BY ".$orderby." ";
		
		if(intval($limit) > 0)
		{
			$add_sql_stats .= " LIMIT ?, ? ";
			$sql_arrs[] = intval($page);
			$sql_arrs[] = intval($limit);
		}
		
		$res = $CI->db->query("SELECT s.*, odas.*
								FROM suppliers s, orders o, locations l, order_details od, supplier_product_sizes sps, order_detail_assign_suppliers odas
									WHERE l.id = s.location_id
										AND l.id = o.location_id											
										AND o.id = od.order_id
										AND od.product_id = sps.product_id
										AND od.size_id = sps.size_id
										AND s.id = sps.supplier_id
										AND s.id = odas.supplier_id
										{$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}*/
	
	public static function drop_down_select($name,$selval,$optional = "",$default_val="",$id_exception="",$parent_id="")
	{
		$lists = OSupplier::get_list(0, 0, "name ASC", "0");
		$arr = array();
		
		if($default_val != "") $arr[''] = $default_val;
		foreach($lists as $r)
		{
			$arr[$r->id_supplier] = $r->name;
			
		}
		return dropdown($name,$arr,$selval,$optional);
	}
	
	public static function get_supplier_by_location_product_size($page=0, $limit=0, $orderby="s.id DESC", $location_id="", $product_id="", $size_id="", $group_by = "s.id", $status = "")
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
		if(intval($size_id) != "")
		{
			$sql_stats[] = " sps.size_id = ? ";
			$sql_arrs[] = $size_id;
		}
		
		if(intval($status) != "")
		{
			$sql_stats[] = " sps.status = ? ";
			$sql_arrs[] = $status;
		}
				
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " AND ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		
		if(trim($group_by) != "") $add_sql_stats .= " GROUP BY ".$group_by." ";

		if(trim($orderby) != "") $add_sql_stats .= " ORDER BY ".$orderby." ";

		if(intval($limit) > 0)
		{
			$add_sql_stats .= " LIMIT ?, ? ";
			$sql_arrs[] = intval($page);
			$sql_arrs[] = intval($limit);
		}

		$res = $CI->db->query("SELECT sps.supplier_id, s.name, sps.primary
								FROM suppliers s, supplier_product_sizes sps
									WHERE s.id = sps.supplier_id
    									{$add_sql_stats} ", $sql_arrs);
		
		/*$res = $CI->db->query("SELECT s.id, s.name, odas.supplier_id
								FROM suppliers s, orders o, locations l, order_details od, supplier_product_sizes sps, order_detail_assign_suppliers odas
									WHERE l.id = s.location_id
										AND l.id = o.location_id											
										AND o.id = od.order_id
										AND od.product_id = sps.product_id
										AND od.size_id = sps.size_id
										AND s.id = sps.supplier_id
										AND s.id = odas.supplier_id
    									{$add_sql_stats} ", $sql_arrs);*/
										
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function checkbox_select_order_assign_supplier($name,$selval=array(),$optional = "",$separator = "<br />", $qty, $product_id, $size_id, $order_detail_id)
	{
		$lists = OSupplier::get_supplier_by_location_product_size(0, 0, "s.id DESC", "", $product_id, $size_id, "sps.supplier_id", "active");		
		
		$arr = array();
		foreach($lists as $r)
		{	
			$assign_suppliers = OOrderDetailAssignSupplier::get_list(0, 0, "id DESC", $order_detail_id, $r->supplier_id);
			
			foreach($assign_suppliers as $as)
			{
				$sup_id	= $as->supplier_id;
				$status_assign = $as->status;
			}
			
			
			$display = '<td width="150px">'.$r->name.'</td>';
			$additional_display = '<td><input type="text" name="qty['.$r->supplier_id.']" value="'.$qty[$r->supplier_id].'" class="qty" placeholder="Qty" size="3px" /></td>
									<td><input type="hidden" name="order_detail_id['.$r->supplier_id.']" value="'.$order_detail_id.'" class="order_detail_id" /></td>
									<td>Status : <strong>'.$status_assign.'</strong></td>
									';
						
			//if(in_array($r->id,$selval)) $checked = 'checked="checked"';
			//else $checked = '';
			
			if(in_array($r->supplier_id,$selval) && $r->supplier_id == $sup_id) $checked = 'checked="checked"';	
			else $checked = '';
			
			$ret[] = "	<span>							
							<table class='sup_list'>
								<tr>
									<label for='supplier_".$r->supplier_id."'>
										<td>
											<input type='checkbox' value='{$r->supplier_id}' name='supplier_id[".$r->supplier_id."]' id='supplier_id[".$r->supplier_id."]' $checked> $display
										</td>
									</label>
									
									".$additional_display."
								</tr>
							</table>
						</span>";
		}
		return implode("", $ret);
	}
	
	public static function search($keyword)
    {
    	$CI =& get_instance();
        $q = "SELECT * 
        		FROM suppliers
                WHERE id_supplier = ?";
        $arr = array();
        $arr[] = intval($keyword);
                        $arr[] = "{$keyword}%";
                            $q .= " OR (name LIKE ?)";
                    $res = $CI->db->query($q,$arr); 
        
        if(emptyres($res)) return FALSE;
        else return $res->result();                
    }
	
	public static function check_email_exists($email, $id_exception=0)
	{
		$ci =& get_instance();
		$q = "SELECT * FROM suppliers WHERE email = ? AND id_supplier != ? LIMIT 1";
		$res = $ci->db->query($q, array($email, $id_exception));
		if(!emptyres($res)) return $res->row();
		else return false;
	}
}
?>