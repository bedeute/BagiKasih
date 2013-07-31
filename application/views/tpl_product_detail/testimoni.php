<?=print_success($this->session->flashdata('testimonial_msg'))?>

<?php 
	$cu = get_logged_in_user(); 
	$cek_review = OProductReview::get_list(0, 0, "id DESC", $P->row->id, "", $cu->id);
	if($cu && sizeof($cek_review) <= 0)
	{
?>
<?=form_open('products/send_testimonials/'.$P->row->id)?>            
	<table width="70%">
		<tr>
			<td>Topik</td>
			<td><input type="text" name="topic" id="topic" value="<?=set_value('topic')?>" required="required"></td>
		</tr>
		<tr>
			<td valign="top">Testimonial</td>
			<td><textarea name="description" id="description" style="width:95%;" rows="5" required><?=set_value('description')?></textarea></td>
		</tr>
		<tr>
			<td>Rating</td>
			<td><span id="custom" data-value="<?=$rating?>"></span></td>
		</tr>
		<tr>
			<td align="right" colspan="2"><input type="submit" class="btn btn-success" value="Kirim Testimoni" /></td>
		</tr>
	</table>
<?=form_close()?>
<hr>
<? } ?>

<ul class="unstyled">
	<?php
		$reviews = OProductReview::get_list(0, 0, "id DESC", $P->row->id , "published");
		
		if(sizeof($reviews) > 0)
		{						
			foreach($reviews as $r)
			{
				?>
					<li>
						<div style="border-bottom: 1px solid #D3D3D3; min-height: 50px; padding-bottom:5px; margin-top:5px;" class="comment-list row-fluid">
							<div class="span2">
								<img src="<?=base_url('assets/img/cake_3.jpg')?>" width="80" height="80"class="icoment img-polaroid">
							</div>
							<?php $U = new OUser($r->user_id); ?>
							<div class="span8">
								<div class="comment-head"><b><?=$U->row->name?></b></div>
								<div class="comment">"<?=$r->description?>"</div>
							</div>
							<div class="span2">
								<small class="comment-date muted"> <?=parse_date_time($r->dt)?></small><br><br>
								<div id="star-rating" class="pull-left">
									<div class="fixed comment-star" data-value="<?=$r->rating?>"></div>
								</div>
							</div>
						</div>
					</li>
				<?php
				unset($U);
			}
		}
		else '<p class="text-red">Testimonials not found.</p>';
	?>
</ul>