<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="sidebar">
				<?=$this->load->view('user_right_sidebar')?>
			</div>
			<div class="span9">
				<div class="content thumbnail">
					<ul class="breadcrumb">
						<li><?=anchor('/', 'Home')?> <span class="divider">/</span></li>
						<li class="active"><a><?=anchor('user/user_review', 'User Review')?></a></li>
					</ul>
					<h3 class="text-left">Review</h3>
					<div class="row-fluid">
						<div class="span12">
							<?=print_error($this->session->flashdata('error_msg'))?>
							<br>
							<?php
								$orders = OOrder::get_list(0, 0, "id DESC", $cu->id);
								
								if(sizeof($orders) <= 0) echo '<p>Orders not found.</p>';
								else
								{
									foreach($orders as $r)
									{
										$cek_order_review = OOrderReview::get_list(0, 0, "id DESC", $cu->id, $r->id);
										
										foreach($cek_order_review as $r_cek_order_review)
										{
											$description = $r_cek_order_review->description;
											$rating = $r_cek_order_review->rating;
										}
										?>
										<?=form_open('user_review/add_order_reviews/'.$r->id, array('class' => 'order_review_form_'.$r->id.''))?>
										<table class="table table-bordered">
											<tr>
												<td>
													<div>
														<input type="hidden" name="order_id" value="<?=$r->id?>" readonly="readonly" />
														<b>Order #<?=$r->id?></b>
														<br />
														<?=parse_date($r->dt)?>
													</div>
												</td>
												<td>
													<?
														if(sizeof($cek_order_review) > 0)
														{
															echo $description;
															?><div class="fixed" data-value="<?=$rating?>"></div><?
														}
														else 
														{
															?>
															<textarea name="description" rows="1" placeholder="Description"></textarea>
															<div class="custom" data-value="<?=$rating?>"></div>        
															<?	
														}
													?>
												</td>
												<td>
													<?
														if(sizeof($cek_order_review) <= 0)
														{
															?>
															<a href="javascript:;" onclick="$('.order_review_form_<?=$r->id?>').submit();" class="btn btn-info">Review</a> 
															<?		
														}
													?>
												</td>
											</tr>
										</table>
										<?=form_close()?>
										
										<fieldset>
											<legend>Product</legend>
											<?
											$order_details = OOrderDetail::get_list(0, 0, "id DESC", $r->id);
											if(sizeof($orders) > 0 && sizeof($order_details) > 0)
											{
												foreach($order_details as $row)
												{
													$cek_product_review = OProductReview::get_list(0, 0, "id DESC", $row->product_id, "", $cu->id, $r->id, $row->size_id, $row->id);
													//var_dump($this->db->last_query());
													foreach($cek_product_review as $r_cek_product_review)
													{										
														$description_pro = $r_cek_product_review->description;
														$rating_pro = $r_cek_product_review->rating;
													}
												?>
												<?=form_open('user_review/add_product_reviews/'.$r->id.'/'.$row->product_id, array('class' => 'product_review_form_'.$row->id.''))?>                      
													<table class="table table-bordered">
														<tr>
															<td>
																<?php 
																	$P = new OProduct($row->product_id);														
																	$img = '<img src="'.$P->get_photo().'" alt="" width="50px" height="50px" />';
																?>
																<?=$img?>
																<div>
																	<input type="hidden" name="order_id" class="order_id" value="<?=$r->id?>" readonly="readonly" />
																	<input type="hidden" name="product_id" value="<?=$row->product_id?>" readonly="readonly" />
																	<input type="hidden" name="size_id" value="<?=$row->size_id?>" readonly="readonly" />
																	<input type="hidden" name="order_detail_id" value="<?=$row->id?>" readonly="readonly" />
																</div>
															</td>
															<td>
																<b><?=$P->row->name?></b>
															</td>
															<td>
																<?
																	if(sizeof($cek_product_review) > 0)
																	{
																		echo $description_pro;
																		?><div class="fixed_pro" data-value="<?=$rating_pro?>"></div><?
																	}
																	else 
																	{
																		?>
																		<textarea name="description" rows="1" placeholder="Description"></textarea>
																		<div class="custom_pro" data-value="<?=$rating?>"></div>
																		<?	
																	}
																?>
																
															</td>
															<td>
																<?
																	if(sizeof($cek_product_review) <= 0)
																	{
																		?>
																		<a href="javascript:;" onclick="$('.product_review_form_<?=$row->id?>').submit();" class="btn btn-info">Review</a>
																		<?		
																	}
																?>
															</td>
														</tr>
													</table>                                
													<?=form_close()?>
													<?	
												}
											}
										?>
										</fieldset>
										<?
									}
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->

<script type="tet/javascript">
	$('.custom').raty({
		scoreName: 'entity.score',
		path: '<?=base_url('assets/img')?>',
		number: 5,
		start: 1
	});
	
	$('.custom_pro').raty({
		scoreName: 'entity.score',
		path: '<?=base_url('assets/img')?>',
		number: 5,
		start: 1
	});
	
	var val = $('.fixed').data('value');
	$('.fixed').raty({
	  readOnly:  true,
	  path: '<?=base_url('assets/img')?>',
	  start:    val
	});
	
	var val_pro = $('.fixed_pro').data('value');
	$('.fixed_pro').raty({
	  readOnly:  true,
	  path: '<?=base_url('assets/img')?>',
	  start:    val_pro
	});
</script>

<style>
	#custom img, #fixed img {		
		width:15px;
		height:15px;
		display:inline-block;
		margin:0px;
	}
</style>