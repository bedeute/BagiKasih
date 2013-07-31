<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div class="content">
					<ul class="breadcrumb">
						<li><?=anchor('/', 'Home')?> <span class="divider">/</span></li>
						<li><?=anchor('cart/user_cart', langtext('shopping_cart'))?> <span class="divider">/</span></li>
						<li class="active"><?=anchor('cart/order_review', 'Checkout')?></li>
					</ul>
					<h3 class="text-left">Checkout</h3>
					<div class="row-fluid">
						<div class="span12">
							<div class="alert alert-info"><?=langtext('confirm_order')?></div>
								<table class="table table-bordered table-striped">
								  <thead>
									<tr>
									  <th class="model">Foto</th>
												<th class="model"><?=langtext('product_name')?></th>
												<th class="quantity"><?=langtext('quantity')?></th>
												<th class="total"><?=langtext('unit_price')?></th>
												<th class="total">Total</th>
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
											<td class="model" width="90">
												<?=anchor($OP->get_link(), '<img src="'.base_url($OP->get_photo()).'" alt="" width="80px" height="80px" class="img-polaroid" />')?>
											</td>
											<td class="name">
												<?=anchor($OP->get_link(), $OP->row->name)?><br>
												<strong>Brand :</strong> <?=$B->row->name?>
												<?php 
													if ($this->cart->has_options($items['rowid']) == TRUE)
													{ 
														echo "<p>";
															
															foreach($this->cart->product_options($items['rowid']) as $option_name => $option_value)
															{
																$arr_opt[$option_name] = $option_value;												
																if($option_name == "addon")
																{
																	$total_price_addon = 0;
																	foreach($option_value as $id => $qty)
																	{
																		if($qty && intval($qty) != 0)
																		{
																			$AO = new OAddOn($id);
																			$res = $AO->get_price($P->row->id);
																			
																			if($AO->row != "")
																			{
																				echo "<span style='font-size:12px;'><strong>".$AO->row->name." ( Qty : ".$qty.", Price : ".currency_format($res->price, 'IDR').", Total : ".currency_format(doubleval($qty) * doubleval($res->price), 'IDR').") </strong></span><br />";
																			}
																			$total_price_addon = $total_price_addon + (doubleval($qty) * doubleval($res->price));
																			
																			unset($AO);
																		}
																	}			
																}
															} 
															//echo "<br />";
															
															$SZ = new OSize($arr_opt['size_id']);
															?><strong>Size:</strong> <?=$SZ->row->name?><br /><?php
														echo "</p>";                
													}
												?>
											</td>
											<td class="quantity">
												<?=$items['qty']?>
											</td>
											<td class="total">Rp <?=format_number($items['price'])?></td>
											<td class="total">Rp <?=format_number($items['subtotal'])?></td>
										</tr>
									<?php
										$i++;
										$total_all_addon = $total_all_addon + $total_price_addon;
									}
								?>
									
										<tr>
											<td colspan="4" style="text-align:right"><b>Sub-Total:</b></td>
											<td class="right">Rp <?=format_number($this->cart->total())?></td>
										</tr>
										<tr>
											<td colspan="4" style="text-align:right"><b><?=langtext('ship_fee')?> :</b></td>
											<td class="right">Rp <?=format_number($shipping_cost)?></td>
										</tr>

										<?php
										$coupn = (($this->cart->total() * $this->session->userdata('coupdisc')) / 100);
										$total = $this->cart->total() + doubleval($shipping_cost);
										$totil = $this->cart->total() + doubleval($shipping_cost) - doubleval($credit_point) - $coupn;
										?>
										<tr>
											<td colspan="4" style="text-align:right"><b>Total:</b></td>
											<td class="right">Rp <?=format_number($total)?></td>
										</tr>
										<?php if(intval($point_used) > 0) { ?>
											<tr>
												<td colspan="4" style="text-align:right"><b>Discount Point:</b></td>
												<td class="right">Rp <?=format_number($credit_point)?> (<?php echo $point_used; ?> Points)</td>
											</tr>
										<? } ?>
										<?php if($this->session->userdata('coupdisc') != "") { ?>
										<tr>
											<td colspan="4" style="text-align:right"><b>Discount Coupon <?=$this->session->userdata('coupdisc');?>%:</b></td>
											<td class="right">
												Rp <?=format_number($coupn)?>
											</td>
										</tr>
										<tr>
											<td colspan="4" style="text-align:right"><b>Grand-Total:</b></td>
											<td class="right">
												Rp <?=format_number($totil)?>
											</td>
										</tr>
										<? } ?>
										<tr>
											<td colspan="4" style="text-align:right"><b><?=langtext('preceived')?>:</b></td>
											<td class="right"><?php echo floor($total / doubleval(get_setting('convert_to_point'))); ?></td>
										</tr>
									</tbody>
								</table>
								<p>
								<?php
									$cu = get_logged_in_user();
									$ocu = new OUser($cu->id);
									$points = $ocu->get_points();
									unset($ocu);
									// tampilkan jika user sudah logged in, mempunyai point lebih dari 0, dan point_used = 0
									if($cu->id != "" && $points > 0 && $point_used == 0)
									{
									?>
									<span style="font-weight:normal; color:#333;"><?=langtext('notif_point').anchor("cart/user_cart",langtext('notif_point2'))?>.</span>
								<? } ?>
								<?=anchor('cart/end_process', '<span>Send Order</span>', array('class' => 'btn btn-success'))?>
								</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->