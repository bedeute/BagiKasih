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
						<li>
							<a href="#">Suppliers</a> <span class="divider">/</span>	
						</li>
						<li class="active">Supplier Pending Products</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Supplier Pending Product List</div>
					</div>
					<div class="block-content collapse in">
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						<?php if(sizeof($list) <= 0) { ?>
							<p class='text_red'>There is no data found.</p>
						<? } else { ?>
							<form>
								<div class="input-append">
									<input class="span9" id="appendedInputButtons" type="text" name="keyword" value="<?=$_GET["keyword"]?>" placeholder="Type Keywords..." />
									<button class="btn" type="submit">Search</button>
									<?=anchor('admin/supplier_pending_products','Refresh', array('class' => 'btn'))?>
								</div>
							</form>
							<table class="table table-bordered table-striped tbl_list">
								<thead>
									<tr>
										<th>No.</th>        	
										<th>Date</th>
										<th>Supplier Name</th>
										<th>Product Info</th>
										<th>Category</th>
										<th>Other</th>
										<th>Size</th>
										<?php /*?>
										<th>Brand</th>
										<th>Size</th>
										<th>Description</th><?php */?>
										<th>Status</th>
										<th>Action</th>                
									</tr>
								</thead>
								<tbody>
								<?php 
									$i=1 + $uri;
									
									foreach($list as $row) : 
										$S = new OSupplier($row->supplier_id);
										$C = new OCategory($row->category_id);
										$TP = new OTypecategory($row->type_id);
										$TH = new OThemecategory($row->theme_id);
										$B = new OBrand($row->brand_id);
								?>
									<tr>
										<td><?=$i?></td>            
										<td><?=parse_date($row->dt)?></td>                
										<td><?=$S->row->name?></td>
										<td>
											ID: <?=$row->product_unique_key?><br />
											Name: <?=$row->product_name?><br />
											Type: <?=$TP->row->name?><br />
											Theme: <?=$TH->row->name?><br />
											Brand: <?=$B->row->name?>
										</td>
										<td><?=$C->row->name?></td>
										<td><?=nl2br($row->multiple_text_fields)?></td>
										<td><?=nl2br($row->multiple_sizes)?></td>
										<?php /*?>
										<td><?=$row->brand_name?></td>
										<td><?=$row->size_name?></td>
										<td><?=trimmer($row->description, 50)?></td><?php */?>
										<td><?=$row->status?></td>
										<td>
										<?php 
											if($row->status == "approved") echo '<span style="color:#0F0; font-weight:bold;">Approved</span>';
											else
											{
												/*echo anchor($this->curpage."/details/".$row->id, "Details");*/
												echo ($row->status == "pending" ? /*" | ".*/anchor($this->curpage."/approve/".$row->id, "Approve") : "");
												echo " | ".anchor($this->curpage."/delete/".$row->id, "Delete", array("onclick" => "return confirm('Are you sure?');"));
											}
										?>
										</td>
									</tr>
								<?php 
									$i++;
									unset($S, $C, $TP, $TH, $B);
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