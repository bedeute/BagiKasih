	<?php $img_def = '<img src="'.base_url('assets/img/no_image.png').'" alt=""  />'; ?>
    
    <div class="span3 content-item" style="margin-left:10px; min-height:220px;">
	<a href="<?=base_url($p->get_link())?>">
		<img class="img-polaroid" src="<?=base_url($p->get_photo())?>" width="142" height="142" alt="<?=$p->row->name?>">
		
		<span style="padding:6px;"><?= (strlen($p->row->name) > 15) ? substr($p->row->name, 0, 15).'...' : $p->row->name ; ?></span>
		
		<?php
		if(doubleval($p->get_highest_price()) > doubleval($p->get_lowest_price()))
		{
			?><p style="text-decoration:line-through;"><small>RP <?=format_number($p->get_highest_price())?></small></p><?	
		}
		?>                
		<p class="text-error" style="font-weight:bold;">RP <?=format_number($p->get_lowest_price())?></p>
	</a>
</div>