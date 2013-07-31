<?php

class DLSProduct
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
				$q = "SELECT * FROM products WHERE id = ?";
			}
			else 
			{
				$q = "SELECT * FROM products WHERE url_title = ?";
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
		$CI->db->insert('products',$params);
		$new_id = $CI->db->insert_id();
		if($new_id)
		{
			if(trim($params['name']) != "")
			{
				$url_title = url_title($params['name'], 'underscore', TRUE);
				$check_duplicate = $CI->db->query("SELECT id FROM products WHERE url_title = ?", array($url_title));
				if(!emptyres($check_duplicate))
				{
					$url_title = $url_title."_".$new_id;
				}
				$lvproduct = new DLSProduct($new_id);
				$lvproduct->edit(array("url_title" => $url_title));
				unset($lvproduct, $check_duplicate);
			}
		}
		return $new_id;
	}
	
	public function edit($params)
	{
		if(trim($params['name']) != "")
		{
			$url_title =  url_title($params['name'], 'underscore', TRUE);
			$check_duplicate = $this->db->query("SELECT id FROM products WHERE url_title = ? AND id <> ?", array($url_title, $this->id));
			if(!emptyres($check_duplicate))
			{
				$url_title = $url_title."_".$this->id;
			}
			$params['url_title'] = $url_title;
			unset($check_duplicate);
		}
		return $this->db->update('products',$params,array('id' => $this->id));		
	}
	
	public function delete()
	{
		$id = $this->id;
		$del =  $this->db->delete('products',array('id' => $this->id));
		
		if($del)
		{
			$photo_res = DLSProductPhoto::get_list(0,0,"",$id);
			
			//delete product download
			$del_pd = $this->db->delete('product_downloads',array('product_id' => $this->id));
			//end
			
			//delete product overview options
			$del_poo = $this->db->delete('product_overview_options',array('product_id' => $this->id));
			//end
			
			$lvproductphoto = new DLSProductPhoto();
			foreach($photo_res as $r)
			{
				$lvproductphoto->setup($r);
				$lvproductphoto->delete();
			}
			unset($lvproductphoto);
			return TRUE;
		}
		else return FALSE;
	}	
	
	/*
	 * EXTERNAL FUNCTIONS
	 */

	public static function get_list($page=0, $limit=0, $orderby="ordering ASC", $status="", $featured="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		//*
		if($featured != "")
		{
			$sql_stats[] = " featured = ? ";
			$sql_arrs[] = $featured;
		}
		if($status != "")
		{
			$sql_stats[] = " status = ? ";
			$sql_arrs[] = $status;
		}
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " WHERE ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		//*/
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM products {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	public static function get_list_by_category($category_id = 0, $page=0, $limit=0, $orderby="ordering ASC", $status="", $featured="")
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
		//*
		if($category_id != 0)
		{
			$sql_stats[] = " category_id = ? ";
			$sql_arrs[] = $category_id;
		}
		if($featured != "")
		{
			$sql_stats[] = " featured = ? ";
			$sql_arrs[] = $featured;
		}
		if($status != "")
		{
			$sql_stats[] = " status = ? ";
			$sql_arrs[] = $status;
		}
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " WHERE ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		//*/
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS * FROM products {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function search($keyword, $page=0, $limit=0, $orderby="score ASC", $status="", $featured="")
	{
		$CI =& get_instance();
		if(trim($keyword) == "") return FALSE;
		
		$sql_stats = $sql_arrs = NULL;
		//*
		$sql_arrs[] = $keyword;
		$sql_arrs[] = $keyword;
		$sql_arrs[] = '%'.$keyword.'%';
		if($featured != "")
		{
			$sql_stats[] = " featured = ? ";
			$sql_arrs[] = $featured;
		}
		if($status != "")
		{
			$sql_stats[] = " status = ? ";
			$sql_arrs[] = $status;
		}
		$add_sql_stats = "";
		if(count($sql_stats) > 0)
		{
			$add_sql_stats .= " AND ";
			$add_sql_stats .= implode(" AND ", $sql_stats);
		}
		//*/
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS *, MATCH(description) AGAINST (? IN BOOLEAN MODE) as score 
								FROM products
								WHERE MATCH(description) AGAINST (? IN BOOLEAN MODE) OR name LIKE ?
								{$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public static function drop_down_select($name,$selval,$optional = "")
	{
		$lists = DLSProduct::get_list(0, 0, "");
		
		$arr = array();
		foreach($lists as $r)
		{
			$arr[$r->id] = $r->name;
		}
		return dropdown($name,$arr,$selval,$optional);		
	}
	
	public function get_link()
	{
		return "shop/product/".$this->row->url_title;
	}
	
	// get all photos
	public function get_photos($start=0, $limit=0, $order_by="ordering ASC, id ASC", $default_photo="")
	{
		$photos = DLSProductPhoto::get_list($start,$limit,$order_by,$this->id,$default_photo);
		return $photos;
	}
	
	// get default photo
	public function get_photo($dim="200xcrops",$no_default_img=FALSE)
	{
		$photo = $this->get_photos(0,1,"default_photo DESC, id ASC");
		$lvproductphoto = new DLSProductPhoto();
		$lvproductphoto->setup($photo[0]);
		$photo_url = $lvproductphoto->get_photo($dim,$no_default_img);
		unset($lvproductphoto);
		return $photo_url;
	}
	
	public function adjust_inventory($params=array())
	{
		if(count($params) <= 0) return FALSE;
		if(trim($params['product_id']) == "")
		{
			$params['product_id'] = $this->id;
		}
		return $this->db->insert("product_histories", $params);
	}
	
	
	public function get_downloads($page=0, $limit=0/*, $orderby="id DESC", $active=""*/)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
				
		$sql_stats[] = " pd.product_id = ? ";
		$sql_arrs[] = $this->id;
				
		/*
		if($active != "")
		{
			$sql_stats[] = " active = ? ";
			$sql_arrs[] = $active;
		}
		*/
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS d.*, pd.* FROM product_downloads pd, downloads d WHERE pd.download_id = d.id {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public function delete_downloads()
	{
		$this->db->delete('product_downloads', array("product_id" => $this->id));
		return true;
	}
	
	public function set_downloads($arr)
	{
		$this->delete_downloads();
		$this->db->insert_batch('product_downloads',$arr);
		return true;
	}
	
	
	
	public function get_overview_options($page=0, $limit=0/*, $orderby="id DESC", $active=""*/)
	{
		$CI =& get_instance();
		$sql_stats = $sql_arrs = NULL;
				
		$sql_stats[] = " poo.product_id = ? ";
		$sql_arrs[] = $this->id;
				
		/*
		if($active != "")
		{
			$sql_stats[] = " active = ? ";
			$sql_arrs[] = $active;
		}
		*/
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
		$res = $CI->db->query("SELECT SQL_CALC_FOUND_ROWS oo.*, poo.* 
							  	FROM product_overview_options poo, overview_options oo 
								WHERE poo.overview_option_id = oo.id {$add_sql_stats} ", $sql_arrs);
		if(emptyres($res)) return array();
		else return $res->result();
	}
	
	public function delete_overview_options()
	{
		$this->db->delete('product_overview_options', array("product_id" => $this->id));
		return true;
	}
	
	public function set_overview_options($arr)
	{
		$this->delete_overview_options();
		$this->db->insert_batch('product_overview_options',$arr);
		return true;
	}
	
}
?>