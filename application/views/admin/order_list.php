<div class="row-fluid">
	<!--/span-->
	<div class="span12" id="content">
		<div class="row-fluid">
			<div class="navbar">
				<div class="navbar-inner">
					<ul class="breadcrumb">
						<li>
							<a href="#">Home</a> <span class="divider">/</span>	
						</li>
						<li class="active">Orders</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Order List</div>
					</div>
					<div class="block-content collapse in">
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>

						<?=print_success($this->session->flashdata('notify_msg'))?>
						<?=print_success($this->session->flashdata('paid_msg'))?>
						<?=print_success($this->session->flashdata('email_adjustment'))?>
						<?=print_success($this->session->flashdata('adjustment_success'))?>
						<?=print_success($this->session->flashdata('finalize_msg'))?>
						<?php if(sizeof($list) <= 0) { ?>
							<p class='text_red'>There is no data found.</p>
						<? } else { ?>
							<form>
								<div class="input-append">
									<input class="span9" id="appendedInputButtons" type="text" name="keyword" value="<?=$_GET["keyword"]?>" placeholder="Type Keywords..." />
									<button class="btn" type="submit">Search</button>
									<?=anchor('admin/orders','Refresh', array('class' => 'btn'))?>
								</div>
							</form>
							<table class="table table-bordered table-striped tbl_list">
								<thead>
									<tr>       	
										<th width="30px">Order No #</th>
										<th width="250px">Info</th>
										<th width="150px">Cost</th>
										<th width="250px">Products Assignment</th>
										<th width="100px">Status</th>
										<th width="150px">Action</th>                
									</tr>
								</thead>
								<tbody>
								<?php
									$i=1 + $uri;
									$O = new OOrder();
										foreach($list as $row) :
											$O->setup($row);
											$UN = new OUser($row->user_id);
											$user = $UN->row;
											
											$LN = new OLocation($row->location_id);
								?>
									<tr>            
										<td><?=$row->id?></td>
										<td style="font-size:11px;">
											<strong>== SENDER INFO ==</strong><br />
											Name : <strong><?=$user->name?></strong><br />
											Address : <strong><?=$user->address?>, <?=$user->city?></strong><br />
											Email : <strong><?=$user->email?></strong><br />
											Phone : <strong><?=$user->phone?></strong><br />
											Fax : <strong><?=$user->fax?></strong><br />
											<br />
											<strong>== Shipping Info ==</strong><br />
											Name : <strong><?=$row->name?></strong><br />
											Address : <strong><?=$row->address?>, <?=$LN->row->name?></strong><br />
											Phone : <strong><?=$row->phone?></strong><br />
											Email : <strong><?=$row->email?></strong>
										</td>
										<td style="text-align:right">
											<div style="font-size:11px;">
												<strong>Cost:</strong> Rp <?=format_number($row->subtotal)?><br />
												<strong>Shipping Cost:</strong> Rp <?=format_number($row->shipping_cost)?><br />
												<?php
												if(doubleval($row->discount) > 0)
												{
													?>
													<strong>Discount: </strong>Rp <?=format_number($row->discount)?><br />
													<?php
												}
												?>
												<strong>Grand Total: </strong> Rp <?=format_number($row->grand_total)?>
											</div>
										</td>
										<td>
											<?php
												$ods = $O->get_details();
												$total_actuals = array();
												$total_assigneds = array();
												foreach($ods as $od)
												{
													$top = new OProduct($od->product_id);
													?>
													<strong><?php echo $od->qty."x ".$top->row->name; ?></strong><br />
													<?php
													$total_actuals[$od->id] = $od->qty;
													// show addons
													$json_addons = json_decode($od->addons);
													$addons = array();
													foreach($json_addons as $addonid => $addonqty)
													{
														if(intval($addonqty) == 0) continue;
														$oao = new OAddOn($addonid);
														$addons[] = $addonqty."x ".$oao->row->name;
														unset($oao);
													}
													if(sizeof($addons) > 0) echo "<strong>ADDON: </strong>".@implode(", ",$addons);
													// show assigned suppliers
													
													?>
													<table width="100%" cellpadding="2">
													<?php
													$tmptotal = 0;
													$q = "SELECT * FROM order_detail_assign_suppliers WHERE order_detail_id = ?";
													$tmpres = $this->db->query($q,array($od->id));
													$this->load->library('OSupplier');
													foreach($tmpres->result() as $tmprow)
													{
														$os = new OSupplier($tmprow->supplier_id);
														$supplier_name = $os->row->name;
														?>
														<tr>
															<td><?php echo $tmprow->qty."x"; ?></td>
															<td><?php echo $supplier_name; ?></td>
															<td><?php 
																if($tmprow->status == "pending") echo "P";
																if($tmprow->status == "approved") { echo "OK"; $tmptotal += $tmprow->qty; }
																if($tmprow->status == "rejected") { echo "R"; }
																if($tmprow->status == "confirmed") { echo "C"; }
																if($tmprow->status == "shipped") { echo "S"; }
																?>
														   </td>
														</tr>
														<?php
														unset($os);
													}
													$total_assigneds[$od->id] = $tmptotal;
													?>
													</table>
													<hr />
													<?php
													unset($top);
												}
												$matched = TRUE;
												foreach($total_actuals as $odid => $qty)
												{
													if(intval($qty) > intval($total_assigneds[$odid]))
													{
														$matched = FALSE;
														break;
													}
												}
												?><?php 
												if($matched)
												{
													?>
													<span style="font-weight:bold; color:green;">Semua barang telah diassigned. Silahkan finalize pengiriman ke supplier!</span>
													<?php
												}
												else
												{
													?>
													Barang masih belum selesai diassign. Silahkan mengecek supplier assignment.
													<?php
												}
												
											?>
										</td>
										<td>
											<?
											if($row->status_new == "pending") echo "Waiting for Payment";
											if($row->status_new == "confirm_payment") echo "Please assign suppliers.";
											if($row->status_new == "assign") echo "Items have been assigned to suppliers. Waiting to be shipped.";
											if($row->status_new == "shipped") 
											{
												echo anchor('admin/orders/ajax/shipped/'.$row->id, 'Items have been shipped', array("class" => "ajax_popup_dimension_link", "title" => "Shipped Note", "dimension" => "500x400"))."<br /><br />";
												echo "<strong>Shipping Date :</strong><br />".parse_date($row->shipping_date)."<br />";
												echo "<strong>Note :</strong><br />".$row->shipped_note;
											}
											if($row->status_new == "cancel") echo "Items was Canceled."
											?>
										</td>
										<td>
											<?=anchor($this->curpage.'/details/'.$row->id, 'details')?>
											
											<?php
											if($row->status_new != "cancel")
											{
											?>
											<br />
											<?php
												// jika status PENDING, maka link yang muncul adalah CONFIRM PAYMENT
												if($row->status_new == "pending") 
												{							
													echo anchor('admin/orders/ajax/confirm_payment/'.$row->id, 'Confirm Payment', array("class" => "ajax_popup_dimension_link", "title" => "Confirm Payment Note", "dimension" => "500x400"))."<br />";
												}
												
												// jika sudah confirm payment, muncul assign supplier dan send adjustment
												if($row->status_new == "confirm_payment") 
												{
													echo anchor($this->curpage.'/assign_suppliers/'.$row->id, 'Assign Suppliers')."<br />";
													echo anchor($this->curpage.'/edit/'.$row->id, 'Edit &amp; Send Adjustment to User')."<br /><br />";
												}						
												
												// jika status TIDAK shipped
												if($row->status_new != "shipped") 
												{
													echo anchor($this->curpage."/mark_as_shipped/".$row->id, "Mark as SHIPPED", array("onclick" => "return confirm('Anda akan membuat pesanan ini sebagai sudah terkirim (Shipped). TOLONG PASTIKAN bahwa semua items telah selesai dikirim oleh supplier. Untuk melanjutkan klik OK');"))."<br />";
													echo anchor($this->curpage.'/cancel_order/'.$row->id, 'Cancel Order', array("onclick" => "return confirm('Anda yakin??');"));
													echo '<br /><br />';
												}
											}
											?>	
										</td>
									</tr>
								<?php 
									$i++;
									unset($UN, $LN);
									endforeach;
								?>	
								</tbody>
							</table>
							<div class="pagination">
								<?=$pagination?>
							</div>
						<? } ?>
					</div>
				</div>
				<!-- /block -->
			</div>
		</div>
	</div>
</div>