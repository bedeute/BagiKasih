<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="sidebar">
				<?=$this->load->view('supplier_right_sidebar')?>
			</div>
			<div class="span9">
				<div class="content thumbnail">
					<h3 class="text-left">Order History</h3>
					<div class="row-fluid">
						<div class="span12">
							<div class="row-fluid">
								<?=print_success($this->session->flashdata('shipped_msg'))?>
								<?=print_error($this->session->flashdata('warning'))?>
								<?=print_error($this->session->flashdata('amount_less'))?>
								
								<div class="thumbnail">
									<table class="table table-bordered table-striped">
										<thead>
											<tr>
												<td class="left" width="40px">Order #</td>
												<td class="left" width="150px">Product Info</td>
												<td class="left">Tgl Masuk</td>
												<td class="left" width="150px">Shipping Info</td>
												<td class="center">Status</td>
												<td class="center">Konfirmasi</td>
											</tr>
										</thead>
										<tbody>
											<?php
												$S = new OSupplier($cu->id);			
												$new_orders = $S->get_new_orders();
												
												foreach($new_orders as $r)
												{
													$OD = new OOrderDetail($r->order_detail_id);
													$P = new OProduct($OD->row->product_id);
													$O = new OOrder($OD->row->order_id);
													$L = new OLocation($O->row->location_id);
													$SZ = new OSize($OD->row->size_id);
													?>
													<tr>
														<td class="left"><a href="#"><?=$OD->row->order_id?></a></td>
														<td class="left">
															<strong>Product Name : </strong><br /><?=$P->row->name?><br /><br />
															<strong>Size : </strong><br /><?=$SZ->row->name?><br /><br />
															<strong>Qty : </strong><br /><?=$r->qty?><br /><br />
															<strong>Add On : </strong><br />
															<?php
															// show addons
															$json_addons = json_decode($OD->row->addons);
															foreach($json_addons as $addon_id => $qty)
															{
																if(intval($qty) == 0) continue;
																$oaddon = new OAddOn($addon_id);
																echo intval($qty)."x ".$oaddon->row->name."<br />";
																unset($oaddon);
															}
															?>
														</td>
														<td class="left"><?=parse_date_time($O->row->dt)?></td>
														<td class="left">
															<strong>Tgl. Kirim : </strong><br /><?=parse_date_time($O->row->shipping_date)?><br /><br />
															<strong>Jam Kirim : </strong><br /><?=$O->row->shipping_time?><br /><br />
															<strong>Note : </strong><br /> <?=$r->shipping_note?><br /><br />
														</td>	
														<td class="left"><b><?
														if($r->status == "approved") echo "Request Accepted. Pending Approval from Admin";
														if($r->status == "rejected") echo "Request Rejected.";
														if($r->status == "confirmed") 
														{
															echo "Request Confirmed. <br /> Please prepare and Ship to: <br />";
															echo "Name : ".$O->row->name."<br />";
															echo "Address : ".$O->row->address.", ".$L->row->name."<br />";
															echo "Phone : ".$O->row->phone."<br />";
														}
														if($r->status == "shipped") echo "Item Shipped.";
														?></b></td>
														<td class="center">
															<?php
																if($r->status == "pending")
																{
																	?>
																	<?=form_open('supplier/approve_new_orders/'.$r->order_detail_id.'/'.$r->id, array('id' => 'approve_form_'.$r->order_detail_id.''))?>
																		<input class="span12" type="text" name="qty"placeholder="qty" />                                            
																	<?=form_close()?>	
																		
																		<a href="javascript:;" onclick="$('#approve_form_<?=$r->order_detail_id?>').submit();" class="btn btn-success"><span>Confirm</span></a>
															<?php
																}
																
																if($r->status == "confirmed")
																{
																	echo anchor('supplier/ajax/shipped/'.$r->order_detail_id, '<span>Ship</span>', array("class" => "btn btn-info ajax_popup_dimension_link button", "title" => "Ship Note", "dimension" => "500x400"));
																}
															?>
														</td>			
													</tr>
													<?php 
													unset($OD, $P, $O, $L, $SZ);
												}
												unset($S);
											?>
										</tbody>
									</table>
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