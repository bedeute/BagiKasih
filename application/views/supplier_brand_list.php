<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="sidebar">
				<?=$this->load->view('supplier_right_sidebar')?>
			</div>
			<div class="span9">
				<div class="content">
					<ul class="breadcrumb">
						<li><?=anchor('/', 'Home')?> <span class="divider">/</span></li>
						<li class="active"><a><?=anchor('supplier', 'Supplier')?></a></li>
					</ul>
					<?=print_success($this->session->flashdata('warning'))?>
					<?=print_success($this->session->flashdata('success'))?>
					<p><?=anchor('supplier/brand_form', 'Add New Brand', array('class' => 'btn btn-success'))?><p>
					<div>

						<div class="thumbnail">
							<div class="tab-content" id="myTabContent" style="padding:5px;">
								<h4>Your Brand List</h4>
								<div class="row-fluid" style="overflow:hidden;">
									<table class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>No</th>
												<th>Brand Name</th>
												<th>Category</th>
												<th>Shipping Fee</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php 
												$i = 1;
												$ob = new OBrand();
												foreach($list as $row):
													$ob->setup($row);
											?>
												<tr>
													<td><?=$i?></td>            
													<td><?=$row->name?></td>
													<td><?=$ob->get_category()->name?></td>
													<td>Rp <?=format_number($row->shipping_fee)?></td>
													<td>
														<?=anchor('supplier/delete_brand/'.$row->id, 'delete', array("onclick" => "return confirm('Are you sure?');"))?> |
														<?=anchor('supplier/brand_form/'.$row->id, 'edit')?>
													</td>
												</tr>
											<?php 
												$i++;
												endforeach; 
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