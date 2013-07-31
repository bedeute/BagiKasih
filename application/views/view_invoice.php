<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div class="content thumbnail">
					<ul class="breadcrumb">
						<li><?=anchor('/', 'Home')?> <span class="divider">/</span></li>
						<li><?=anchor('order_status', langtext('check_order_status'))?> <span class="divider">/</span></li>
						<li class="active"><?=langtext('invoice_detail')?></li>
					</ul>
					<h3 class="text-left"><?=langtext('invoice_detail')?></h3>
					<div class="row-fluid">
						<div class="span12">
							<table class="table table-bordered table-striped">
								<tbody>
									<tr>
										<td class="left" style="width: 50%;">
											<b><?=langtext('payment_status')?> :</b> <?=ucwords($O->row->status)?><br>
											<b><?=langtext('delivery_status')?> :</b> 
											<?
												if($O->row->status_new == "assign") 
												{
													echo "In Preparation and Shipment <br />";
													echo "<strong>Shipping Date :</strong> ".parse_date($O->row->shipping_date)."<br />";
													echo "<strong>Shipping Time :</strong> ".$O->row->shipping_time;
												}
												else if($O->row->status_new == "shipped")
												{
													echo "Completed";
												}
												else
												{
													echo "Pending";
												}
											?>
										</td>
									</tr>
								</tbody>
							</table>
						
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th class="left" colspan="2"><?=langtext('order_detail')?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="left" style="width: 50%;">
											<b>Order ID:</b> #<?=$O->row->id?><br>
											<b><?=langtext('date_added')?>:</b> <?=parse_date_time($O->row->dt)?>
										</td>
										<td class="left">
											<b><?=langtext('pay_method')?>:</b> Bank Transfer<br>
											<b><?=langtext('date_deliver')?>:</b> <?=parse_date($O->row->shipping_date)?> <br />
											<b><?=langtext('time_deliver')?>:</b> <?=$O->row->shipping_time?> <br />
											<b><?=langtext('card_content')?>:</b> <?=$O->row->shipping_cards?> <br />
											<b><?=langtext('special_request')?>:</b> <?=$O->row->shipping_note?>
										</td>
									</tr>
								</tbody>
							</table>
							
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th class="left"><?=langtext('payment_address')?></th>
										<th class="left"><?=langtext('shipping_address')?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<?php 
											$U = new OUser($O->row->user_id); 
											$L = new OLocation($O->row->location_id);
										?>
										<td class="left"><?=$U->row->name?><br><?=$U->row->address?><br><?=$U->row->city?> <?=$U->row->zip_code?><br><?=$U->row->state?><br>Indonesia</td>
										<td class="left"><?=$O->row->name?><br><?=$O->row->address?><br><?=$O->row->city?><br>(<?=$L->row->name?>)<br>Indonesia</td>
									</tr>
								</tbody>
							</table>
							
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<td style="text-align: center;" width="1"></td>
										<td class="left" colspan="2"><?=langtext('product_name')?></td>
										<td class="right"><?=langtext('quantity')?></td>
										<td class="right"><?=langtext('unit_price')?></td>
										<td class="right">Total</td>
									</tr>
								</thead>
								<tbody>
								<?php
									$i = 1;
									foreach($list as $r)
									{
									$P = new OProduct($r->product_id);
									$S = new Osize($r->size_id);
									
									$B = new OBrand($P->row->brand_id);
									$a1[] = $B->row->id;
									$a2[] = $B->row->shipping_fee;
									$aaa = array_combine($a1, $a2);
								?>
								<tr>
									<td style="text-align: center; vertical-align: middle;" width="15%">
										<img src="<?=base_url($P->get_photo("1", "200xcrops"))?>"  width="50px" height="50px" class="img-polaroid" />
									</td>
									<td class="left" colspan="2">
										<?=$P->row->name?><br />
										<strong>Size:</strong> <?=$S->row->name?><br />
										
										<?php 
											$add_on = json_decode($r->addons, true); 
											
											foreach($add_on as $k => $v)
											{
												$AO = new OAddOn($k);
												$price = $AO->get_price($row->product_id);
												
												if($v != 0)
												{
													echo $AO->row->name." ( <strong>Qty:</strong> ".$v." | <strong>Price:</strong> Rp ".format_number($price->price)." | <strong>Total:</strong> Rp ".format_number(doubleval($v) * doubleval($price->price)).") <br />";
												}
												unset($AO);
											}
										?>
									</td>
									<td class="right"><?=$r->qty?></td>
									<td class="right">Rp. <?=format_number($r->price)?></td>
									<td class="right">Rp. <?=format_number($r->final_price)?></td>
								</tr>        
								<?php unset($P, $S); } ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="5" style="text-align:right;"><b>Sub-Total:</b></td>
										<td class="right">Rp. <?=format_number($O->row->subtotal)?></td>
									</tr>
									<tr>
										<td colspan="5" style="text-align:right;"">
											<b>
											Shipping Rate : 
											[<?=$L->row->name?> - 
											 <?php
												if($O->row->shipping_location_details == "inside") echo "Dalam Kota";
												if($O->row->shipping_location_details == "outside") echo "Luar Kota";
												if($O->row->shipping_location_details == "outside") echo "(".$O->row->location2.")";
											 ?>]
											</b>
										</td>
										<td class="right">Rp. <?=format_number($O->row->shipping_cost)?></td>
									</tr>
									<?php if(doubleval($O->row->discount) > 0) { ?>
									<tr>
										<td colspan="5" style="text-align:right;"><b>Discount Point:</b></td>
										<td class="right">Rp. <?=format_number($O->row->discount)?></td>
									</tr>
									<? } ?>
									<?php if(doubleval($O->row->discount) > 0) { ?>
									<tr>
										<td colspan="5" style="text-align:right;"><b>Discount Coupon:</b></td>
										<td class="right">Rp. <?=format_number($O->row->coup_disc)?></td>
									</tr>
									<? } ?>
									
									<tr>
										<td colspan="5" style="text-align:right;"><b>Grand-Total:</b></td>
										<td class="right">Rp. <?=format_number($O->row->grand_total)?></td>
									</tr>
									<tr>
										<td colspan="5" style="text-align:right;"><b><?=langtext('preceived')?>:</b></td>
										<td class="right"><?=$O->row->points_received?> Point</td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->