<?php
// http://php.net/manual/en/function.sort.php
function array_sort($array, $on, $order=SORT_ASC)
{
	$new_array = array();
	$sortable_array = array();
  
	if (count($array) > 0) {
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				foreach ($v as $k2 => $v2) {
					if ($k2 == $on) {
						$sortable_array[$k] = $v2;
					}
				}
			} else {
				$sortable_array[$k] = $v;
			}
		}
		
		switch ($order) {
			case SORT_ASC:
				asort($sortable_array);
			break;
			case SORT_DESC:
				arsort($sortable_array);
			break;
		}
  
		foreach ($sortable_array as $k => $v) {
			$new_array[$k] = $array[$k];
		}
	}
  
	return $new_array;
}

$access_token = $this->session->userdata("fb_access_token");
$fb_friends = json_decode(file_get_contents("https://graph.facebook.com/me/friends?access_token=".$access_token."&fields=id,name,picture,birthday"),true);

$f_today = $f_tomorrow = $f_this_week = $f_arr = $f_all = NULL;
foreach($fb_friends['data'] as $r)
{
	$b = $r['birthday'];

	$b_arr = explode("/", $b);
	if(count($b_arr) == 3)
	{
		if( date("m/d") == date("m/d", strtotime($b)) )
		{
			$f_today[] = $r;
			$f_all[] = $r;
		}
		else if( date("m/d", strtotime("+1 day")) == date("m/d", strtotime($b)) )
		{
			$f_tomorrow[] = $r;
			$f_all[] = $r;
		}
		else if( $b_arr[0] == date("m", strtotime("+1 week")) && intval($b_arr[1]) <= date("j", strtotime("+1 week")) && intval($b_arr[1]) >= date("j") )
		{
			$f_this_week[] = $r;
			$f_all[] = $r;
		}
		else if( $b_arr[0] == date("m", strtotime("+1 month")) )
		{
			$f_this_week[] = $r;
			$f_all[] = $r;
		}
		$f_arr[] = $r;
	}
	if(count($b_arr) == 2)
	{
		if( date("m/d") == $b )
		{
			$f_today[] = $r;
			$f_all[] = $r;
		}
		else if( date("m/d", strtotime("+1 day")) == $b )
		{
			$f_tomorrow[] = $r;
			$f_all[] = $r;
		}
		else if( $b_arr[0] == date("m", strtotime("+1 week")) && intval($b_arr[1]) <= date("j", strtotime("+1 week")) && intval($b_arr[1]) >= date("j") )
		{
			$f_this_week[] = $r;
			$f_all[] = $r;
		}
		else if( $b_arr[0] == date("m", strtotime("+1 month")) )
		{
			$f_this_week[] = $r;
			$f_all[] = $r;
		}
		$f_arr[] = $r;
	}
}

if(count($f_all) > 0)
{
	$arr = array(
		"f_title"		=> "",
		"f_obj"			=> $f_all,
		"f_sort_by"		=> "birthday",
		"f_sort_type"	=> SORT_ASC,
		"f_limit"		=> 10
	);
	echo $this->load->view("tpl_fb_connect_list", $arr, TRUE);
}

	if(count($f_today) <= 0 && count($f_tomorrow) <= 0 && count($f_this_week) <= 0)
	{
		?>
		<div class="sidebar-title">
			<h4 class="text-center"><?=langtext('social_network')?></h4>
		</div>
		<div class="facebook-connect text-center">
			<a href="https://graph.facebook.com/oauth/authorize?client_id=472690359469850&redirect_uri=<?=site_url("account/facebook")?>&scope=email,user_birthday,user_location,friends_birthday">
				<img src="<?=base_url('assets/img/facebook_connect_button.png')?>">
			</a>
		</div>
		<h5 class="text-center">Facebook Connect</h5>
		<p class="text-center">Lihat Teman Anda Yang Akan dan Lihat Teman Anda Sedang ULANG TAHUN</p>
		<?php
	}
?>
<?php
	/*
	$access_token = $this->session->userdata("fb_access_token");
	$friendsList = json_decode(file_get_contents("https://graph.facebook.com/me/friends?access_token=".$access_token."&fields=id,name,picture,birthday"),true) ;
	$data = $friendsList['data'] ;
	foreach ($data as $nr => $friends)
	{
	 echo $friends['name'];			
	}
	*/
?>