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
						<li class="active">Refunds</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Refund List</div>
					</div>
					<div class="block-content collapse in">
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						<?php if(sizeof($list) <= 0) { ?>
							<p class='text_red'>There is no data found.</p>
						<? } else { ?>
							<table class="table table-bordered table-striped tbl_list">
								<thead>
									<tr>
										<th>ID</th>        	
										<th>Date</th>                    
										<th>Info</th>                    
										<th>Paid Amount</th>
										<th>New Amount</th>
										<th>Refund</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>                
								<?php 
									$i=1 + $uri;
									
									foreach($list as $row):
									if(intval($row->refund) > 0):
								?>		
											
									<tr>
										<td><?=$row->id?></td>            
										<td><?=$row->dt?></td>
										<td>
											#<?=$row->order_id?><br />
											<?
											$O = new OOrder($row->order_id);
											$U = new OUser($O->row->user_id);
											?>
											Name : <?=$U->row->name?>
										</td>
										<td style="text-align: right;">Rp <?=format_number($row->total_order)?></td>
										<td style="text-align: right;">Rp <?=format_number($row->new_total)?></td>
										<td style="text-align: right;">Rp <?=format_number($row->refund)?></td>
										<td>
											<?
											if($row->status == "pending") 
											{
												echo '<span class="text-red">'.ucfirst($row->status)."</span> | ";
												echo anchor('admin/refunds/ajax/set_done/'.$row->id, 'set to DONE', array("class" => "ajax_popup_dimension_link", "title" => "Refund Note", "dimension" => "500x400"));	
											}
											else 
											{
												echo '<span class="text-green">'.strtoupper($row->status)."</span><br />";
												echo $row->note;
											}
											?>
										</td>
									</tr>
								<?php				
									$i++;
									endif;
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