<!-- mid-content -->
<style>
	.row-fluid .span11 {
	  width: 95.48936170212765%;
	}
</style>
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<?php 
				$C = new OCategory($P->row->category_id);
				$B = new OBrand($P->row->brand_id);
			?>
			<div class="span12">
				<div class="content">
					<ul style="margin-top:-15px; margin-bottom:5px;" class="breadcrumb">
						<li><?=anchor('/', 'Home')?> <span class="divider">/</span></li>
						<li><?=$C->row->name?></a> <span class="divider">/</span></li>
						<li class="active"><a><?=$P->row->name?></a></li>
					</ul>
					<div class="cart">
						<div class="row-fluid">
							<div class="span9 thumbnail">
								<?=form_open('cart/add_to_cart', array('id'	=> 'add_to_cart'))?>
									<div class="">
										<div class="span4">
											<div style="margin-top:5px; margin-left:5px;">
												<img class="img-polaroid" src="<?=base_url($P->get_photo('1','200xcrops'))?>" style="height:230px;" />
											</div>
											<br>
											<!-- ShareThis Button BEGIN -->
											<div style="margin-left:5px;">
												<span class='st_sharethis_large' displayText='ShareThis'></span>
												<span class='st_facebook_large' displayText='Facebook'></span>
												<span class='st_twitter_large' displayText='Tweet'></span>
												<span class='st_googleplus_large' displayText='Google +'></span>
												<span class='st_email_large' displayText='Email'></span>
											</div>
											<!-- ShareThis Button END -->
										</div>
										<div class="span8">
											<div class="row-fluid" style="margin-left:10px;">
												<div class="span12">
													<table style=" margin-top:-13px;">
														<thead>
															<tr>
																<td colspan="2">
																	<h4 class="text-left" style="margin-top:20px; margin-bottom:5px;"><?=strtoupper($P->row->name)?></h4>
																</td>
															</tr>
															<tr>
																<td width="183">Kategori</td>
																<td>: <?=$C->row->name?></td>
															</tr>
															<tr>
																<td width="183">Brand</td>
																<td>: <?=$B->row->name?></td>
															</tr>
															<tr>
																<td width="183">Estimate Delivery</td>
																<td>: <?=$P->row->estimate_delivery?> days</td>
															</tr>
															<tr>
																<td width="183">Area Pengiriman</td>   
																<td>:
																	<?php
																		if(intval($P->row->category_id) == 1 || intval($P->row->category_id) == 2)
																		{
																			$locations = $P->get_cities(0, 0, "l.name DESC");
																			
																			$i = 1;
																			foreach($locations as $r)
																			{
																				$S = new OSupplier($r->id);
																				$L = new OLocation($S->row->location_id);
																				echo ($i < sizeof($locations) ? $L->row->name.", " : $L->row->name);
																				$i++;
																				unset($S);
																			}
																		}
																	?>
																</td>
															</tr>
															<tr>
																<td width="183">Tema</td>
																<td>:                            	
																	<?php
																		$themes = $P->get_theme_categories();
																		
																		$i = 1;
																		foreach($themes as $rth)
																		{
																			echo (intval($i) == sizeof($themes) ? $rth->name : $rth->name.", ");
																			$i++;
																		}
																	?>
																</td>
															</tr>
															<tr>
																<td width="183">Jenis</td>
																<td>:                            	
																	<?php
																		$types = $P->get_type_categories();
																		
																		$i = 1;
																		foreach($types as $rty)
																		{
																			echo (intval($i) == sizeof($types) ? $rty->name : $rty->name.", ");
																			$i++;
																		}
																	?>
																</td>
															</tr>
														</thead>
													</table>
												</div>
											</div>
											<hr style="margin-top:5px; margin-bottom:5px;">
											<div class="row-fluid" style="margin-left:10px;">
												<div class="span12">
													<table style="">
														<thead>
															<tr>
																<td valign="middle">Qty</td>
																<td valign="middle"> : 
																	<select name="quantity" class="span4 new_qty">
																		<?php
																			for($i = 1; $i <= 10; $i++) {
																		?>
																			<option value="<?=$i?>"><?=$i?></option>
																		<? } ?>
																	</select>
																	<input name="product_id" size="2" value="40" type="hidden">
																</td>
															</tr>
															<tr>
																<td valign="middle">Ukuran</td>
																<td valign="middle"> : 
																	<select class="prosizid span11" name="product_size_id">
																		<option value="">-Pilih Ukuran-</option>
																		<?php
																			$lists = OProductSize::get_list(0, 0, "id DESC", $P->row->id);
																			
																			foreach($lists as $r)
																			{    
																				$S = new OSize($r->size_id);
																				echo "<option value=".$r->id.">".$S->row->name." | Rp <span>".format_number($r->final_price)."</span></option>";
																			}
																		?>
																	</select>
																</td>
															</tr>
															<tr>
																<td width="183" rowspan="2" align="left">
																	<button class="btn btn-large btn-warning text-center"><i class="icon-shopping-cart icon-white"></i> Buy Now</button>
																</td>
															</tr>
															<tr>
																<td>
																	<table>
																		<tr>
																			<td>
																				<strong>Total Point</strong>
																			</td>
																			<td>
																				: <span><span id='current_total_point'>0</span></span>
																			</td>
																		</tr>
																		<tr>
																			<td>
																				<strong>Sub-Total</strong>
																			</td>
																			<td>
																				: Rp <span><span id='sub_total'>0</span></span>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</thead>
														<input type="hidden" name="point_setting" id="point_setting" value="<?=doubleval(get_setting('convert_to_point'))?>" />
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							<div class="thumbnail span3" style="min-height:319px;">
								<div class="sidebar-title">
									<h4 class="text-center"><?=langtext('create_your_own')?></h4>
								</div>

								<div class="tab-content" id="myTabContent" style="height: 252px; overflow: auto;">
									<?php
									$add_on_list = OProduct::get_addon_by_productid($P->row->id);
									
									if(sizeof($add_on_list) > 0)
									{
									?>
									<div class="row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<div class="create-own rank-content">
													<table>
														<tbody>
															<?php
																$i = 1;
																foreach($add_on_list as $r)
																{
																$AO = new OAddOn($r->addon_id);
																$img_url = $AO->get_photo('200xcrops');
															?>
															<tr>
																<td class="span5">
																	<img src="<?=base_url($img_url)?>" class="img-polaroid" width="60" height="60">
																</td>
																<td class="man span6">
																	<input type="hidden" name="addon_id[<?=$r->addon_id?>]" id="addon_id[<?=$r->addon_id?>]" value="<?=$r->addon_id?>" readonly="readonly" />
																	
																	<?=gen_ddl_set("addon_qty[".$r->addon_id."]", array("0" => "0", "1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5","6" => "6"), $addon_qty)?>
																	<p>Rp. <span><?=format_number($r->price)?></span></p>
																</td>
															</tr>
															<tr><td><p></p></td></tr>
															<?php $i++; unset($AO); } ?>
														</tbody>
													</table>
												</div>	 
											</div>	 
										</div>	 
									</div>	 
									<? } ?>
								</div>
							</div>
						</div>
						<br>
						<?=form_close()?>
						<div class="row-fluid">
							<div class="span9">
								<ul class="nav nav-tabs" id="myTab" style="margin-bottom:-2px;">
									<li class="active"><a data-toggle="tab" href="#deskripsi"><?=langtext('description')?></a></li>
									<li><a data-toggle="tab" href="#foto-produk"><?=langtext('product_photo')?></a></li>
									<li><a data-toggle="tab" href="#testimoni"><?=langtext('testimonial')?></a></li>
									<li><a data-toggle="tab" href="#jejaring"><?=langtext('network')?></a></li>
									<li><a data-toggle="tab" href="#alasan-memilih"><?=langtext('reason_for_choosing')?></a></li>
									<li><a data-toggle="tab" href="#faq">F.A.Q</a></li>
								</ul>

								<div class="thumbnail">
									<div class="tab-content" id="myTabContent" style="padding:5px;">
										<div class="tab-pane fade-in active" id="deskripsi">
											<h4><?=langtext('description')?></h4>
											<p><?=nl2br($P->row->description)?></p>
											<hr>
											<p>
												<h4><?=langtext('product_photo')?></h4>
												<div class="row-fluid" style="overflow:hidden;">
													<?=$this->load->view('tpl_product_detail/photo')?>
												</div>
											</p>
											<hr>
											<p>
												<h4><?=langtext('testimonial')?></h4>
												<?=$this->load->view('tpl_product_detail/testimoni')?>
											</p>
											<hr>
											<p>
												<h4><?=langtext('social_network')?></h4>
												<div class="fb-comments" data-href="<?=base_url().uri_string()?>" data-width="680" data-num-posts="10"></div>	
											</p>
											<hr>
											<p>
												<h4><?=langtext('reason_for_choosing')?></h4>
												<?=$this->load->view('tpl_product_detail/alasan')?>
											</p>
											<hr>
											<p>
												<h4>F.A.Q</h4>
												<?=$this->load->view('tpl_product_detail/faq')?>
											</p>
										</div>
										<div class="tab-pane fade" id="foto-produk">
											<h4><?=langtext('product_photo')?></h4>
											<div class="row-fluid" style="overflow:hidden;">
												<?=$this->load->view('tpl_product_detail/photo')?>
											</div>
										</div>
										<div class="tab-pane fade" id="testimoni">
											<h4><?=langtext('testimonial')?></h4>
											<?=$this->load->view('tpl_product_detail/testimoni')?>
										</div>
										<div class="tab-pane fade" id="jejaring">
											<h4><?=langtext('social_network')?></h4>
											<div class="fb-comments" data-href="<?=base_url().uri_string()?>" data-width="680" data-num-posts="10"></div>		
										</div>
										<div class="tab-pane fade" id="alasan-memilih">
											<h4><?=langtext('reason_for_choosing')?></h4>
											<?=$this->load->view('tpl_product_detail/alasan')?>
										</div>
										<div class="tab-pane fade" id="faq">
											<h4>F.A.Q</h4>
											<?=$this->load->view('tpl_product_detail/faq')?>
										</div>
									</div>
								</div>
							</div>
							<div class="thumbnail span3">
								<div class="sidebar-title">
									<h4 class="text-center">
									<?php if($this->session->userdata("lang") == 'en') { ?>
										<?=langtext('other').' '.$C->row->name ?>
									<? } else if(!$this->session->userdata("lang") || $this->session->userdata("lang") == 'id') { ?>
										<?=$C->row->name.' '.langtext('other')?>
									<? } ?>
									</h4>
								</div>

								<div class="tab-content" id="myTabContent">
									<?php
										$others = OProduct::get_filtered_list(get_location(), "", $P->row->category_id, "", "", "",$orderby = "p.id DESC",0 , "");
										
										if(empty($others)) echo '<br><div class="span8"><p>No Other '.$C->row->name.'.</p></div>';
										else 
										{
											foreach($others as $tv)
											{
												$P = new OProduct();
												$P->setup($tv);
												
												$img_def = '<img src="'.base_url('assets/img/no_image.png').'" width="50px" height="50px" alt="default_image"  />';
												?>
													<div class="row-fluid rank-content">
														<a href="<?=base_url($P->get_link())?>">
														<div class="span4">
															<? if($P->get_photo()) { ?>
															<img class="img-polaroid" src="<?=base_url($P->get_photo())?>" width="60" height="60" alt="<?=$tv->name?>" />
															<? } else echo $img_def; ?>
														</div>
													
														<div class="span8">
															<p style="margin-top:6px; margin-left:13px;"><?=(strlen($tv->name) > 12) ? substr($tv->name, 0, 12).'...' : $tv->name ;?></p>
															&emsp;<span>Rp. <?=format_number($P->get_lowest_price())?></span>
														</div>
														</a>
													</div><br>
												<?
												unset($P);
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
	</div>
</div>
<!-- end mid-content -->