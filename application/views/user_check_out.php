<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div class="content">
					<ul class="breadcrumb">
						<li><?=anchor('/', 'Home')?> <span class="divider">/</span></li>
						<li><?=anchor('cart/user_cart', langtext('shopping_cart'))?> <span class="divider">/</span></li>
						<li class="active"><a><?=anchor('cart/member_area', 'Checkout')?></a></li>
					</ul>
					<h3 class="text-left">Checkout</h3>
					<div class="row-fluid">
						<div class="span12">
							<div class="alert alert-info"><?=langtext('ddelivery')?></div>
							<?=print_error($this->session->flashdata('error'))?>
							<?=form_open('cart/check_out', array('id' => 'check_out_form'))?> 
							<div class="row-fluid">
								<div class="span12">
									<fieldset>
										<legend><?=langtext('buyer_info')?></legend>
										<?php 
											$cu = get_logged_in_user(); 
											$new_reg = $this->session->userdata('register_new');
											
											if($cu)
											{
										?>
										<div class="row-fluid">
											<div class="span6">
												<table width="100%">
													<tbody>
														<tr>
															<td><?=langtext('fullname')?></td>
															<td> : <input name="name" value="<?=$cu->name?>" class="large-field" type="text"></td>
														</tr>
														<tr>
															<td>Email</td>
															<td> : <input name="email" value="<?=$cu->email?>" class="large-field" type="text"></td>
														</tr>
														<tr>
															<td><?=langtext('phone')?></td>
															<td> : <input name="phone" value="<?=$cu->phone?>" class="large-field" type="text"></td>
														</tr>
														<tr>
															<td>
																<button class="btn btn-info" onclick="$('#check_out_form').attr('action','<?=base_url('cart/update_buyer')?>').submit();">Update Info</button>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<? } else { ?>
										<table>
											<tbody>
												<tr>
													<td><span class="required"></span><?=langtext('name')?></td>
													<td><input type="text" name="name" value="<?=($new_reg == "" ? set_value('name') : $new_reg['name'])?>" required></td>
												</tr>
												<tr>
													<td><span class="required"></span><?=langtext('address')?></td>
													<td><textarea name="address" rows="2" style="width: 95%;" required><?=($new_reg == "" ? set_value('address') : $new_reg['address'])?></textarea></td>
												</tr>
												<tr>
													<td><span class="required"></span><?=langtext('city')?></td>
													<td><input type="text" name="city" value="<?=($new_reg == "" ? set_value('city') : $new_reg['city'])?>" required></td>
												</tr>
												<tr>
													<td><span class="required"></span><?=langtext('phone')?></td>
													<td><input type="text" name="phone" value="<?=($new_reg == "" ? set_value('phone') : $new_reg['phone'])?>" required></td>
												</tr>
												<tr>
													<td><span class="required"></span>Fax</td>
													<td><input type="text" name="fax" value="<?=($new_reg == "" ? set_value('fax') : $new_reg['fax'])?>"></td>
												</tr>       
												<tr>
													<td><span class="required"></span>Email</td>
													<td><input type="email" class="text" name="email" value="<?=($new_reg == "" ? set_value('email') : $new_reg['email'])?>" required></td>
												</tr>
												<tr>
													<td><span class="required"></span>Password</td>
													<td><input type="password" name="password" required></td>
												</tr>
												<tr>
													<td><span class="required"></span>Confirm Password</td>                    
													<td><input type="password" name="confirm_password" required></td>
												</tr>
											</tbody>
										</table>
										<? } ?>
									</fieldset>
								</div>
							</div>
							<hr>
							<div class="row-fluid">
								<div class="span6">
									<?php $shipping_details = $this->session->userdata('shipping_details'); ?>
									<fieldset>
										<legend><?=langtext('ddelivery')?></legend>
										<table>
											<tbody>
												<tr>
													<td><?=langtext('nearest_city')?></td>
													<td>
														<input type="hidden" name="shipping_location_id" id="shipping_location_id" value="<?=get_location()?>" readonly="readonly" />
														<?php $L = new OLocation(get_location()); ?>
														<input type="text" name="location_name" value="<?=$L->row->name?>" readonly="readonly" />
													</td>								
												</tr>
												<tr>
													<td valign="top"><span class="required">*</span> Area</td>
													<td>
														<?=gen_ddl_set("shipping_location_details", array("" => "- Select Area -", "inside" => "Dalam Kota", "outside" => "Luar Kota"), ($shipping_details == "" ? set_value('shipping_location_details') : $shipping_details['shipping_location_details']), 'onchange="pilih_location_details()"', FALSE); ?>
														<br>
														<input type="text" name="location2" id="location2" style="<? if($shipping_details == '') echo "display:none;"; ?>" value="<?=($shipping_details == "" ? set_value('location2') : $shipping_details['location2'])?>" />
													</td>
												</tr>
												<tr>
													<td><span class="required">*</span> Shipping Fee</td>
													<td><input type="text" name="shipping_fee" id="shipping_fee" value="<?=($shipping_details == "" ? set_value('shipping_fee') : $shipping_details['shipping_fee'])?>" readonly="readonly" /></td>
												</tr>
												<tr>
													<td><span class="required">*</span> <?=langtext('date_deliver')?>:</td>
													<td>
														<input type="text" name="shipping_date" id="datepicker" value="<?=($shipping_details == "" ? set_value('shipping_date') : $shipping_details['shipping_date'])?>" required /><br />
														<strong>Delivery of a minimum of <?=$estimate_delivery?> days</strong>    
													</td>                                
												</tr>
												<tr>
													<td><span class="required">*</span> <?=langtext('time_deliver')?>:</td>
													<td>
														<?=gen_ddl_set("shipping_time", array("08.00 AM - 02.00 PM" => "08.00 AM - 02.00 PM", "02.00 PM - 08.00 PM" => "02.00 PM - 08.00 PM"), ($shipping_details == "" ? set_value('shipping_time') : $shipping_details['shipping_time']))?>
													</td>
												</tr>
												<tr>
													<td><span class="required">*</span> <?=langtext('addr_unknown')?></td>
													<td>
														<?=gen_ddl_set("shipping_no_address", array("telepon ke pembeli" => "Telepon Ke Pembeli", "telepon ke penerima" => "Telepon Ke Penerima"), ($shipping_details == "" ? set_value('shipping_no_address') : $shipping_details['shipping_no_address']))?>
													</td>
												</tr>
												<tr>
													<td><?=langtext('special_request')?></td>
													<td><textarea name="shipping_note" rows="2"><?=($shipping_details == "" ? set_value('shipping_note') : $shipping_details['shipping_note'])?></textarea></td>
												</tr>
												<tr>
													<td><?=langtext('card_content')?></td>
													<td><textarea name="shipping_cards" rows="2"><?=($shipping_details == "" ? set_value('shipping_cards') : $shipping_details['shipping_cards'])?></textarea></td>
												</tr>
											</tbody>
										</table>
									</fieldset>
								</div>
								<div class="span6">
									<fieldset>
										<legend><?=langtext('drecipient')?></legend>
										<table>
											<tbody>
												<tr>
													<td>
														<span class="required">*</span> <?=langtext('fullname')?>
													</td>
													<td>
														<input type="text" name="shipping_name" value="<?=($shipping_details == "" ? set_value('shipping_name') : $shipping_details['shipping_name'])?>" required />
													</td>
												</tr>
												<tr>
													<td>
														<span class="required">*</span> <?=langtext('fulladdress')?>
													</td>
													<td>
														<textarea name="shipping_address" rows="2" style="width: 95%;" required><?=($shipping_details == "" ? set_value('shipping_address') : $shipping_details['shipping_address'])?></textarea>
													</td>
												</tr>
												<tr>
													<td><?=langtext('city')?></td>
													<td>
														<input type="text" name="shipping_city" value="<?=($shipping_details == "" ? $L->row->name : $shipping_details['shipping_city'])?>" readonly="readonly" required />
													</td>
												</tr>
												<tr>
													<td><?=langtext('phone')?></td>
													<td>
														<input type="text" name="shipping_phone" id="shipping_phone" value="<?=($shipping_details == "" ? set_value('shipping_phone') : $shipping_details['shipping_phone'])?>" />
													</td>
												</tr>
												<!--<tr>
													<td>Fax</td>
													<td>
														<input type="text" name="shipping_fax" value="<?=($shipping_details == "" ? set_value('shipping_fax') : $shipping_details['shipping_fax'])?>" />
													</td>
												</tr>
												<tr>
													<td>Email</td>
														<td>
															<input type="text" name="shipping_email" value="<?=($shipping_details == "" ? set_value('shipping_email') : $shipping_details['shipping_email'])?>" />
														</td>
												</tr>-->
											</tbody>
										</table>
									</fieldset>
								</div>
							</div>
							<hr>
							<div class="row-fluid">
								<div class="span6">
									<table>
										<tbody>
											<!--<tr>
												<td><?=langtext('pay_method')?> : </td>
												<td><input type="checkbox" checked disabled /> TRANSFER BANK</td>
											</tr>-->
											<tr>
												<td>
													<?=anchor('cart/user_cart', 'Back', array('class' => 'btn'))?>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="span6">
									<table>
										<tbody>
											<tr>
												<td align="right">
													<?=langtext('agreement').' '.anchor('terms_and_conditions', '<b>'.langtext('term_and_cond').'</b>')?> <input type="checkbox" name="agree" value="1" checked="checked" disabled="disabled">
												</td>
											</tr>
											<tr>
												<td align="right">
													<button id="button-payment" class="btn btn-success">Continue</button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<?= form_close(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->