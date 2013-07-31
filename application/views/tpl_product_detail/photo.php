<?php
	$photo = OProductPhoto::get_list(0,0,"id DESC",intval($P->row->id));
	
	foreach($photo as $r)
	{
		$PP = new OProductPhoto();
		$PP->setup($r);
		
		$img_url = $PP->get_photo($r->image,"products","originals");
		$img = '<img src="'.$img_url.'" alt="" />';
		?>
			<div class="span11">
				<img src="<?=base_url($img_url)?>" class="img-polaroid">
			</div>
		<?
		unset($PP);
	}
?>