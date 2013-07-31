<?php
	$cu = get_logged_in_user();
	$cko = (!$cu) ? "#modal" : "cart/member_area" ;
	
	$point_used = intval($this->session->userdata('point_used'));
	$discount = $point_used * doubleval(get_setting('convert_to_discount'));
	$coupn = (($this->cart->total() * $this->session->userdata('coupdisc')) / 100) + doubleval($total_add_on);
	$total = $this->cart->total() + doubleval($total_all_addon);
	$totil = $this->cart->total() + doubleval($total_all_addon) - $discount - $coupn;
?>
<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div class="content">
					<ul class="breadcrumb">
						<li><?=anchor('/', 'Home')?> <span class="divider">/</span></li>
						<li class="active"><a><?=anchor('cart/user_cart', langtext('shopping_cart'))?></a></li>
					</ul>
					<h3 class="text-left"><?=langtext('shopping_cart')?></h3>
					<div class="row-fluid">
						<div class="span12">
						<?php echo "<p><strong>".print_error($this->session->flashdata('error'))."</strong></p>"; ?>
						
						<?php echo "<p><strong>".print_error($this->session->flashdata('no_point'))."</strong></p>"; ?>
						<?php echo "<p><strong>".print_error($this->session->flashdata('point_error'))."</strong></p>"; ?>
						<?php echo "<p><strong>".print_error($this->session->flashdata('discount_error'))."</strong></p>"; ?>
						<?php echo "<p><strong>".print_success($this->session->flashdata('msg'))."</strong></p>"; ?>
						<?php echo "<p><strong>".print_success($this->session->flashdata('msg_coup'))."</strong></p>"; ?>
						<?php
							if(sizeof($this->cart->contents()) == 0) echo '<p class="error">Shopping Cart is Empty</p>';
							else
							{
							?>
							<div class="row-fluid">
								<div class="span12">
									<?=form_open('cart/update_cart', array('id' => 'cart_form'))?>
									<table class="table table-bordered table-hover">
										<thead>
											<tr>
												<th class="model">Image</th>
												<th class="model"><?=langtext('product_name')?></th>
												<th class="remove"><?=langtext('remove')?></th>
												<th class="total"><?=langtext('unit_price')?></th>
												<th class="total">Total</th>
												<th class="quantity"><?=langtext('quantity')?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$i = 1;
											$total_all_addon = 0;
											foreach($this->cart->contents() as $items)
											{
												echo form_hidden($i.'[rowid]', $items['rowid']);
											?>
											<tr>
												<?php
													$PS = new OProductSize($items['id']);
													$OP = new OProduct($PS->row->product_id);
													$B = new OBrand($OP->row->brand_id);
													$a1[] = $B->row->id;
													$a2[] = $B->row->shipping_fee;
													$aaa = array_combine($a1, $a2);
												?>
												<td class="model">
													<?=anchor($OP->get_link(), '<img src="'.base_url($OP->get_photo()).'" alt="" width="80px" height="80px" class="img-polaroid" />')?>
												</td>
												<td class="name">
													<b><?=anchor($OP->get_link(), $OP->row->name)?></b><br>
													<?php $SZ = new OSize($items['options']['size_id']); ?>
													<strong>Size:</strong> <?=$SZ->row->name?>
													<?php
														if ($this->cart->has_options($items['rowid']) == TRUE)
														{ 
															echo "<p>";
																echo "<strong>ADD ON <span style='font-size:11px;'>( Optional )</span></strong><br />";
																
																foreach($this->cart->product_options($items['rowid']) as $option_name => $option_value)
																{
																	$arr_opt[$option_name] = $option_value;		
																	
																	if($option_name == "addon")
																	{
																		$total_price_addon = 0;
																		foreach($option_value as $id => $qty)
																		{
																			if($qty)
																			{
																				if(sizeof($id) <= 0) echo '<p class="text-red">No Add On.</p>';
																				else
																				{
																					$AO = new OAddOn($id);
																					$res = $AO->get_price($P->row->id); 
																																				
																					if($AO->row != "")
																					{
																						echo "<span style='font-size:12px;'>
																									<strong>"
																									.$AO->row->name.
																									" ( Qty : ".$qty.", Price : ".currency_format($res->price, 'IDR').", 
																									  Total : ".currency_format(doubleval($qty) * doubleval($res->price), 'IDR').")
																									</strong>
																							  </span><br />";
																					}
																				   
																					
																					unset($AO);
																				}
																			}
																		}
																		
																	}
																}
																echo "<br />";
																
																$SZ = new OSize($arr_opt['size_id']);
																?><!--<strong>Size:</strong> <?=$SZ->row->name?><br />--><?php
															echo "</p>";                
														}
													?>
												</td>
												<?php 
												
												?>
												<td class="remove">
													<a href="<?=site_url('cart/delete_item/'.$items['rowid'])?>" class="btn btn-mini btn-danger"><i class="icon-remove icon-white"></i></a>
												</td>
												<td class="total">Rp <?=format_number($items['price'])?></td>
												<td class="total">Rp <?=format_number($items['subtotal'])?></td>
												<td class="quantity" valign="middle">
													<div class="input-append">
														<input type="text" id="appendedInputButton" name="<?=$i.'[qty]'?>" value="<?=$items['qty']?>" id="<?=$i.'[qty]'?>" size="20" class="span9" />
														<a href="javascript:;" onclick="$('#cart_form').submit();" class="btn" title="Update"><i class="icon-edit"></i></a>
													</div>
												</td>
											</tr>
											<?php
												$i++;
												$total_all_addon = $total_all_addon + $total_price_addon;
												$this->session->set_userdata('total_all_addon', $total_all_addon);
											}
											/*echo "<pre>";
											print_r($aaa);
											echo "</pre>";*/
										?>
										</tbody>
										<tfoot>
										<?php if($this->session->userdata('coupdisc') == "") { ?>
											<tr>
												<th colspan="5" style="text-align:right; vertical-align:middle;"><?=langtext('coupcode')?></th>
												<td style="vertical-align:middle;"">
													<div class="input-append">
														<input type="text" id="appendedInputButton" name="coupon" placeholder="Enter Coupon" id="coupon" size="20" class="span9" />
														<a href="javascript:;" onclick="$('#cart_form').attr('action','<?=base_url('cart/coupon')?>').submit();" class="btn" title="validate"><i class="icon-ok"></i></a>
													</div>
												</td>
											</tr>
										<? } ?>
											<tr>
												<td colspan="6">
													<div class="row-fluid">
														<div class="span12">
															<div class="row-fluid">
																<div class="span5">
																	<?php
																	if($cu)
																	{
																		$points = OPoint::get_list(0, 0, "id DESC", $cu->id);
																		
																		foreach($points as $r)
																		{
																			$total_point = $r->total_point;	
																		}
																	?>
																	<div class="row-fluid">
																		<div class="span7 right" style="margin-top:-5px;"><?=langtext('cpoint');?></div>
																		<div class="span5 right" style="margin-top:-5px;"><b><?=intval($total_point)?> Point</b></div>
																	</div>
																	<div class="row-fluid">
																		<div class="span7 right" style="margin-top:-5px;"><?=langtext('usepoint');?></div>
																		<div class="span5 right" style="margin-top:-5px;"><? $pp = $this->session->userdata('point'); ?>
																		<input type="text" name="point_used" size="20" value="<?=intval($this->session->userdata('point_used'))?>" class="span3" /><b> POINT</b></div>
																	</div>
																	<div class="row-fluid">
																		<div class="span12 right" style="margin-top:-5px;"><span style="font-size:11px; font-weight:bold;">(1 Point = Rp. <?php echo number_format(doubleval(get_setting('convert_to_discount'))); ?>)</span></div>
																	</div>
																	<div class="row-fluid">
																		<div class="span12 right" style="margin-top:-5px;"><a href="javascript:;" onclick="$('#cart_form').attr('action','<?=base_url('cart/update_point')?>').submit();" class="btn btn-info"><span>Update Point</span></a></div>
																	</div>
																	<? } else { ?>
																	<div class="row-fluid">
																		<div class="span9 left" style="margin-top:-5px;"><?=langtext('wusepoint')?> ? <?= anchor($cko, langtext('plogin').'.', array('data-toggle' => 'modal')); ?></div>
																	</div>
																	<? } ?>
																</div>
																<div class="span2"></div>
																<div class="span5">
																	<div class="row-fluid">
																		<div class="span7 right" style="margin-top:-5px;"><b>Sub-Total:</b></div>
																		<div class="span5 right" style="margin-top:-5px;">Rp <?=format_number($this->cart->total() + doubleval($total_all_addon))?></div>
																	</div>
																	<div class="row-fluid">
																		<div class="span7 right" style="margin-top:-5px;"><b>Total:</b></div>
																		<div class="span5 right" style="margin-top:-5px;">Rp <?=format_number($total)?></div>
																	</div>
																	<?php if(intval($this->session->userdata('point_used')) > 0) { ?>
																	<div class="row-fluid">
																		<div class="span7 right" style="margin-top:-5px;"><b>Discount Point:</b></div>
																		<div class="span5 right" style="margin-top:-5px;">Rp <?=format_number($discount)?></div>
																	</div>
																	<? } ?>
																	<?php if($this->session->userdata('coupdisc') != '') { ?>
																	<div class="row-fluid">
																		<div class="span7 right" style="margin-top:-5px;"><b>Discount Coupon <?=$this->session->userdata('coupdisc');?>%:</b></div>
																		<div class="span5 right" style="margin-top:-5px;">Rp <?=format_number($coupn)?></div>
																	</div>
																	<? } ?>
																	<div class="row-fluid">
																		<div class="span7 right" style="margin-top:-5px;"><b>Grand-Total:</b></div>
																		<div class="span5 right" style="margin-top:-5px;">Rp <?=format_number($totil)?></div>
																	</div>
																	<?php
																		$cu = get_logged_in_user();
																		if($cu)
																		{
																	?>
																	<div class="row-fluid">
																		<div class="span7 right" style="margin-top:-5px;"><b><?=langtext('preceived')?>:</b></div>
																		<div class="span5 right" style="margin-top:-5px;"><b><?=floor($total / doubleval(get_setting('convert_to_point')))?></b></div>
																	</div>
																	<? } ?>
																	<?php $this->session->set_userdata("points_received", floor($total / doubleval(get_setting('convert_to_point')))); ?>
																	<div class="row-fluid">
																		<div class="span12 right" style="margin-top:-5px;"><?=langtext('noincludeship')?>.</div>
																	</div>
																	<div class="row-fluid">
																		<div class="span12 right" style="margin-top:-5px;">
																			<a href="<?=$last_page?>" class="btn"><span>Continue Shopping</span></a>
																			<?= anchor($cko, '<span>Check Out</span>', array('class' => 'btn btn-info', 'data-toggle' => 'modal')); ?>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</td>
											</tr>
										</tfoot>
									</table>
									<?=form_close()?>
								</div>
							</div>
							<? } ?>
						</div>

						<div style="width:400px;" id="modal" class="modal hide fade text-center">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4>Login</h4>
							</div>	
							<div class="modal-body">
								<div class="text-center">
									<a href="https://graph.facebook.com/oauth/authorize?client_id=472690359469850&redirect_uri=<?=site_url("account/facebook")?>&scope=email,user_birthday,user_location,friends_birthday">
										<img src="http://satutempat.s3.amazonaws.com/assets/facebook-sign-in-ff6fd902f37a7c0e7357ae59b4fe9b33.png">
									</a>
								</div>
								<h5>OR</h5>
								<?=form_open('cart/member_area', array('id'	=> 'member_area_form'))?>
									<?=print_error($error_string)?>
									<?=print_error(validation_errors())?>
									&nbsp;
										<label>Your Email Address </label>
										<input type="text" name="email" placeholder="Email" value="<?=set_value('email')?>"/>
										<label>Password</label>
										<input type="password" name="password" placeholder="Password" />
									<br />
									<p><?=anchor('account/forgot_password', 'Forgot Your Password ?')?></p>
									<div class="text-center">
										<a class="btn btn-warning" href="javascript:;" onclick="$('#member_area_form').submit();">login</a> or <?=anchor('cart/check_out', 'First Time Buyer', array('class' => 'btn btn-info'))?>
									</div>
								<?=form_close()?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->