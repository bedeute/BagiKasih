<div class="sidebar-title">
	<h4 class="text-center">Upcoming Birthdays</h4>
</div>
<div  class="tab-content" id="myTabContent" style="height: 250px; overflow: auto;">
	<?php
		$i=1;
		$f_arr = NULL;		  
		foreach($f_obj as $r):
			$b_day_arr = explode("/", $r['birthday']);
			$bday = date("Y")."-".$b_day_arr[0]."-".$b_day_arr[1];
			$bday_time = strtotime($bday);
			$f_arr[$r['id']] = array(
				"id"		=>	$r['id'], 
				"name"		=>	$r['name'], 
				"birthday"	=>	$bday_time
			);
			if($i == 50) break;
			$i++;
		endforeach;
		//sorting
		$new_f_arr = array_sort($f_arr, $f_sort_by, $f_sort_type);
		
		$i=1;
		foreach($new_f_arr as $k => $v):
	?>
	<div class="row-fluid rank-content" style="text-shadow:none;">
		<a href="http://facebook.com/<?=$v['id']?>" title="<?=$v['name']?>">
			<div class="span4">
				<img class="img-polaroid" src="https://graph.facebook.com/<?=$v['id']?>/picture?type=square " alt="" width="50" />
			</div>
			<div class="span8">
				<p style="margin-left:13px;">
					<?=$v['name'];?><br>
					(<?=date("F d", $v['birthday'])?>)
				</p>
			</div>
		</a>
	</div>
	<br>
	<?php if($i == $f_limit) break; $i++; endforeach; ?>
</div>
