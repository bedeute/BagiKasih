<?php
	if(!empty($product_res))
	{
		foreach($product_res as $r)
		{
			$P = new OProduct();
			$P->setup($r);			
			$B = new Obrand($r->brand_id);			
			$img_def = '<img src="'.base_url('assets/img/no_image.png').'" alt=""  />';
			?>
            <div class="span3 content-item" style="margin-left:10px; min-height:220px;">
		<a href="<?=$P->get_link()?>">
			<img class="img-polaroid" src="<?=$P->get_photo()?>" width="142" height="142" alt="<?=$P->row->name?>">
			<span style="padding:6px;"><?= (strlen($r->name) > 14) ? substr($r->name, 0, 14).'...' : $r->name ; ?></span>
			
			<?php
			if(doubleval($P->get_highest_price()) > doubleval($P->get_lowest_price()))
			{
				?><p style="text-decoration:line-through;"><small>RP <?=format_number($P->get_highest_price())?></small></p><?	
			}
			?>
			<p class="text-error" style="font-weight:bold;">RP <?=format_number($P->get_lowest_price())?></p>
		</a>
            </div>
			<?
			unset($P, $B);
		}					
	}
	else echo '<p class="text-red">No Items found.</p><br />';
?>