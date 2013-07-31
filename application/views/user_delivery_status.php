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
						<li><?=anchor('user/home', langtext('account'))?> <span class="divider">/</span></li>
						<li class="active"><a><?=anchor('user/delivery_status', langtext('delivery_status'))?></a></li>
					</ul>
					<h3 class="text-left"><?=langtext('delivery_status')?></h3>
					<div class="row-fluid">
						<?=$this->session->flashdata("status_update")?>
						<div class="span12">
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<td class="left">Order #</td>
										<td class="left" width="250px">Status</td>
										<td class="left">Completed Date</td>
										<td class="left">Notes</td>
									</tr>
								</thead>
								<tbody>
									<?php
										foreach($list as $r)
										{							
											?>
											<tr>
												<td class="left"><?=anchor('user/view_order_details/'.$r->id, $r->id)?></td>
												<td class="left">
													<?
													if($r->status_new == "assign") 
													{
														echo "In Preparation and Shipment <br />";
														echo "<strong>Shipping Date :</strong> ".parse_date($r->shipping_date)."<br />";
														echo "<strong>Shipping Time :</strong> ".$r->shipping_time;
													}
													if($r->status_new == "shipped") echo "Completed";
													?>
												</td>
												<td class="left">
													<?
													if($r->status_new == "assign") echo "-";
													if($r->status_new == "shipped") echo parse_date($r->dt_shipped);
													?>
												</td>
												<td>
													<?
													$order_details = OOrderDetail::get_list(0, 0, "id DESC", $r->id);
													
													foreach($order_details as $r)
													{
														$assign_suppliers = OOrderDetailAssignSupplier::get_list(0, 0, "id DESC", $r->id);
														
														$i=1;
														foreach($assign_suppliers as $as)
														{
															$OD = new OOrderDetail($as->order_detail_id);
															$P = new OProduct($OD->row->product_id);
															$S = new OSize($OD->row->size_id);
															
															echo "<strong>Item :</strong> ".$P->row->name."<br />";
															echo "<strong>Size :</strong> ".$S->row->name."<br />";
															echo "<strong>Qty :</strong> ".$as->qty."<br />";
															echo "<strong>Add On :</strong><br />";
															
															$add_on = json_decode($OD->row->addons, true); 
															
															if(sizeof($add_on) <= 0) echo "-";
															else
															{
																foreach($add_on as $k => $v)
																{
																	if($v != 0)
																	{
																		$AO = new OAddOn($k);
																		$price = $AO->get_price($row->product_id);														
																		echo $AO->row->name." (Qty : ".$v.") <br />";
																		unset($AO);
																	}
																}
															}											
															
															echo "<strong>Shipping Date :</strong> ".parse_date($as->shipping_date)."<br />";
															echo "<strong>Shipping Note :</strong> ".$as->shipping_note."<br /><br />";
															
															unset($OD, $P, $S);
															}	
														}
													?>
												</td>
											</tr>
									<? } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->