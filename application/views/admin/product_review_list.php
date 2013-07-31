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
							<a href="#">Products</a> <span class="divider">/</span>	
						</li>
						<li class="active">Product Testimonial List</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Product Testimonial List</div>
					</div>
					<div class="block-content collapse in">
						<p><?=anchor($this->curpage."/add", "<i class='icon-plus'></i> Create new",array("class" => "btn"))?></p>
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						<?php if(sizeof($list) <= 0) { ?>
							<p class='text_red'>Product Tetimonial List is empty.</p>
						<? } else { ?>
							<form>
								<div class="input-append">
									<input class="span9" id="appendedInputButtons" type="text" name="keyword" value="<?=$_GET["keyword"]?>" placeholder="Type Keywords..." />
									<button class="btn" type="submit">Search</button>
									<?=anchor('admin/product_reviews','Refresh', array('class' => 'btn'))?>
								</div>
							</form>
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>No.</th>        	
										<th>Date</th>
										<th>Product Name</th>
										<th>User Info</th>
										<th>Rating</th>
										<th>Description</th>
										<th>Status</th>
										<th>Action</th>                
									</tr>
								</thead>
								<tbody>
								<?php
									$i=1 + $uri; 
									
									foreach($list as $row) :
									
										$U = new OUser($row->user_id);
										$user = $U->row;
										
										$P = new OProduct($row->product_id);
								?>
									<tr>
										<td><?=$i?></td>            
										<td><?=parse_date($row->dt)?></td>
										<td><?=$P->row->name?></td>
										<td>
											<?=$user->name?><br />
											<?=$user->city?><br />
											<?=$user->email?><br />
										</td>
										<td><?=$row->rating?></td>
										<td><?=trimmer($row->description, 50)?></td>
										<td>
											<?php
												if($row->status == "pending") echo ucfirst($row->status)." | ".anchor($this->curpage.'/act/published/'.$row->id, 'set to Published');	
												else echo ucfirst($row->status)." | ".anchor($this->curpage.'/act/pending/'.$row->id, 'set to Pending');
											?>
										</td>
										<td>
											<?php 
												echo anchor($this->curpage."/delete/".$row->id, "Delete", array("onclick" => "return confirm('Are you sure?');"));
												echo " | ".anchor($this->curpage."/edit/".$row->id, "Edit");
											?>
										</td>
									</tr>
								<?php 
									$i++;
									unset($U, $P);
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