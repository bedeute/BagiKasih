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
						<li class="active"><a><?=anchor('user/view_orders', langtext('order_history'))?></a></li>
					</ul>
					<h3 class="text-left"><?=langtext('order_information')?></h3>
					<div class="row-fluid">
						<div class="span12">
							<?php
								if(sizeof($list) <= 0) echo '<p class="text-red">Orders not found.</p>';
									else
									{
										foreach($list as $r)
										{	
											?>
											<table class="table table-bordered table-striped">
												<tbody>
													<tr>
														<td colspan="3"><b>Order ID:</b> #<?=$r->id?></td>
													</tr>
													<tr>
														<td>
															<b><?=langtext('date_added')?>:</b> <?=parse_date($r->dt)?><br>
														</td>
														<td>
															<b><?=langtext('customer')?>:</b> <?=$r->name?><br>
															<b>Total:</b> Rp <?=format_number($r->grand_total)?>
														</td>
														<td>
															<?=anchor('user/view_order_details/'.$r->id, '<span>View detail</span>', array('class' => 'btn btn-info'))?>
														</td>
													</tr>
												</tbody>
											</table>
											<?
										}
									}
								?>
							<div class="pagination">
								<?=$pagination?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->