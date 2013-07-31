<?=nl2br($P->row->description)?>
<br /><br />

<h2>Foto Produk</h2>
<?php
	$photo = OProductPhoto::get_list(0,0,"id DESC",intval($P->row->id));
	
	foreach($photo as $r)
	{
		$PP = new OProductPhoto();
		$PP->setup($r);
		
		$img_url = $PP->get_photo("200xcrops");
		$img = '<img src="'.$img_url.'" alt="" />';
		
		?><div class="image-detail"><?=$img?></div><?
		unset($PP);
	}
?>
<br /><br />

<h2>Testimoni</h2>
<?=print_success($this->session->flashdata('testimonial_msg'))?>

<?php 
	$cu = get_logged_in_user(); 
	
	$cek_review = OProductReview::get_list(0, 0, "id DESC", $P->row->id, "", $cu->id);
	
	if($cu && sizeof($cek_review) <= 0)
	{
?>
<?=form_open('products/send_testimonials/'.$P->row->id)?>            
  <fieldset>
	<div class="input-wrap">
	  <label for="testi_topic">Topik</label>
	  <div class="input-content">
		<input type="text" name="topic" id="topic" value="<?=set_value('topic')?>" required="required">
	  </div>
	</div>
	<div class="input-wrap">
	  <label for="testi_message">Testimoni</label>
	  <div class="input-content">
		<textarea name="description" id="description" cols="30" rows="10" required="required"><?=set_value('description')?></textarea>
	  </div>
	</div>
	<div class="input-wrap">
		<label for="testi_message">Rating</label>
		<div class="input-content">
			<div id="custom" data-value="<?=$rating?>"></div>
		</div>
	</div>
	<div class="input-wrap submit-wrap">
	  <input type="submit" value="Kirim Testimoni" />
	</div>
  </fieldset>
<?=form_close()?>
<? 	} ?>


<ul class="padd-testi-list">
	<?php
		$reviews = OProductReview::get_list(0, 0, "id DESC", $P->row->id , "published");
		
		if(sizeof($reviews) > 0)
		{						
			foreach($reviews as $r)
			{
	?>
    			<div class="comment-list" style="border-bottom: 1px solid #D3D3D3; min-height: 50px">
                    <td><img class ="icoment" src="<?=base_url('_assets/nw_img/product2.jpg')?>"></td>
                    <td>
                        <div class="comment-head"><b>Order# <?=$r->order_id?></b></div>
                        <div class="comment-date"> <?=parse_date_time($r->dt)?></div>
                        <?php $U = new OUser($r->user_id); ?>
                        <div class="comment"><?=$U->row->name?>: "<?=$r->description?>"</div>
                        <div class="fixed comment-star" data-value="<?=$r->rating?>"></div>
                    </td>
                </div>
                <br />
	<?php
				unset($U);
			}
		}
		else '<p class="text-red">Testimonials not found.</p>';
	?>				
</ul>

<style type="text/css">
	.fixed { width: 150px !important; }
</style>
<br /><br />

<h2>Jejaring</h2>
<br />
<div style="margin-left:20px;">
    <fb:comments href="<?=current_url()?>" num_posts="2" width="700"></fb:comments>
</div>
