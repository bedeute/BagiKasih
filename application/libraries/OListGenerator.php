<?php

class OListGenerator
{
	var $CI;
	var $db;
	var $row;
	var $id;
	
	public function __construct($category_id)
	{
	
		$CI = & get_instance();
		$this->CI = $CI;
		$this->db = $CI->db;				
		
	
		$this->category_id = $category_id;
		$this->subcats = array();
		$this->brands = array();
		$this->typecats = array();
		$this->themecats = array();
		$this->price_ranges = array();
		
		$this->subcats2 = array();
		$this->brands2 = array();
		$this->typecats2 = array();
		$this->themecats2 = array();
		$this->price_ranges2 = array();
	}
	
	public function process_products($arr)
	{
		$this->products = $arr;
		$this->process();
	}
	
	public function process()
	{
		$productids = array();
		foreach($this->products as $prod) $productids[] = $prod->id;
		if(sizeof($productids) == 0) return;
		$this->CI->load->library('OSubcategory');
		// setup subcategories
		$arr = array();
		$lists = OSubcategory::get_list(0, 0, "ordering ASC, id DESC",$this->category_id);
		$rsubcats = array();
		foreach($lists as $list) 
		{
			$q = "SELECT COUNT(*) AS total FROM product_subcategories WHERE subcategory_id = ? AND product_id IN (".@implode(",",$productids).");";
			$tmpres = $this->db->query($q,array($list->id));
			if(emptyres($tmpres)) continue;
			else
			{
				$tmprow = $tmpres->row();
				$total = intval($tmprow->total);
				if($total == 0) continue;
			}
			$rsubcats[] = array('name' => $list->name, 'total' => $total, 'id' => $list->id);
		}
		// setup brands
		$lists = OBrand::get_list(0, 0, "ordering ASC, id DESC", $this->category_id);
		foreach($lists as $list) 
		{
			$q = "SELECT COUNT(*) AS total FROM products WHERE brand_id = ? AND id IN (".@implode(",",$productids).");";
			$tmpres = $this->db->query($q,array($list->id));
			if(emptyres($tmpres)) continue;
			else
			{
				$tmprow = $tmpres->row();
				$total = intval($tmprow->total);
				if($total == 0) continue;
			}
			$rbrands[] = array('name' => $list->name, 'total' => $total, 'id' => $list->id);
		}
		
		// setup types
		$lists = OTypecategory::get_list(0, 0, "ordering ASC, id DESC", $this->category_id);
		foreach($lists as $list) 
		{
			$q = "SELECT COUNT(*) AS total FROM product_type_categories WHERE type_category_id = ? AND product_id IN (".@implode(",",$productids).");";
			$tmpres = $this->db->query($q,array($list->id));
			if(emptyres($tmpres)) continue;
			else
			{
				$tmprow = $tmpres->row();
				$total = intval($tmprow->total);
				if($total == 0) continue;
			}
			$rtypecats[] = array('name' => $list->name, 'total' => $total, 'id' => $list->id);
		}
		
		// setup themes
		$lists = OThemecategory::get_list(0, 0, "ordering ASC, id DESC", $this->category_id);
		foreach($lists as $list) 
		{
			$q = "SELECT COUNT(*) AS total FROM product_theme_categories WHERE theme_category_id = ? AND product_id IN (".@implode(",",$productids).");";
			$tmpres = $this->db->query($q,array($list->id));
			if(emptyres($tmpres)) continue;
			else
			{
				$tmprow = $tmpres->row();
				$total = intval($tmprow->total);
				if($total == 0) continue;
			}
			$rthemecats[] = array('name' => $list->name, 'total' => $total, 'id' => $list->id);
		}
		
		// setup price ranges
		
		$lists = OPriceRange::get_list(0, 0, "ordering ASC, id DESC", $this->category_id);
		foreach($lists as $list) 
		{
			$q = "SELECT COUNT(*) AS total FROM product_price_ranges WHERE price_range_id = ? AND product_id IN (".@implode(",",$productids).");";
			$tmpres = $this->db->query($q,array($list->id));
			if(emptyres($tmpres)) continue;
			else
			{
				$tmprow = $tmpres->row();
				$total = intval($tmprow->total);
				if($total == 0) continue;
			}
			$rpriceranges[] = array('name' => $list->name, 'total' => $total, 'id' => $list->id);
		}
		
		
		
		$this->subcats = $rsubcats;
		$this->brands = $rbrands;
		$this->typecats = $rtypecats;
		$this->themecats = $rthemecats;
		$this->price_ranges = $rpriceranges;
	}
	
	public function get_subcategories() { return $this->subcats; }
	public function get_brands() { return $this->brands; }
	public function get_types() { return $this->typecats; }
	public function get_themes() { return $this->themecats; }
	public function get_price_ranges() { return $this->price_ranges; }
	
	
	public function process_products2()
	{
		$this->process2();
	}
	
	public function process2()
	{
		$this->CI->load->library('OSubcategory');
		// setup subcategories
		$lists = OSubcategory::get_list(0, 0, "ordering ASC, id DESC",$this->category_id);
		$rsubcats = array();
		foreach($lists as $list) 
		{
			$rsubcats2[] = array('name' => $list->name, 'id' => $list->id);
		}
		
		// setup brands
		$lists = OBrand::get_list(0, 0, "ordering ASC, id DESC", $this->category_id);
		foreach($lists as $list) 
		{
			$rbrands2[] = array('name' => $list->name, 'id' => $list->id);
		}
		
		// setup types
		$lists = OTypecategory::get_list(0, 0, "ordering ASC, id DESC", $this->category_id);
		foreach($lists as $list) 
		{
			$rtypecats2[] = array('name' => $list->name, 'id' => $list->id);
		}
		
		// setup themes
		$lists = OThemecategory::get_list(0, 0, "ordering ASC, id DESC", $this->category_id);
		foreach($lists as $list) 
		{
			$rthemecats2[] = array('name' => $list->name, 'id' => $list->id);
		}
		
		// setup price ranges
		$lists = OPriceRange::get_list(0, 0, "ordering ASC, id DESC", $this->category_id);
		foreach($lists as $list) 
		{
			$rpriceranges2[] = array('name' => $list->name, 'id' => $list->id);
		}
		
		$this->subcats2 = $rsubcats2;
		$this->brands2 = $rbrands2;
		$this->typecats2 = $rtypecats2;
		$this->themecats2 = $rthemecats2;
		$this->price_ranges2 = $rpriceranges2;
	}
	
	public function get_subcategories2() { return $this->subcats2; }
	public function get_brands2() { return $this->brands2; }
	public function get_types2() { return $this->typecats2; }
	public function get_themes2() { return $this->themecats2; }
	public function get_price_ranges2() { return $this->price_ranges2; }
	
	
}
?>