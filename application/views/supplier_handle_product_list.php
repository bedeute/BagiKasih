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
					<?=print_success($this->session->flashdata('approve_new_order_success'))?>
					<?=print_success($this->session->flashdata('post_item_success'))?>
					<p><?=anchor('supplier/add_products', 'Add Product', array('class' => 'btn btn-success'))?><p>
					<div>
						<ul class="nav nav-tabs" id="myTab" style="margin-bottom:-2px;">
							<li class="active"><a data-toggle="tab" href="#deskripsi">Pending Products</a></li>
							<li><a data-toggle="tab" href="#foto-produk">Active Products</a></li>
						</ul>

						<div class="thumbnail">
							<div class="tab-content" id="myTabContent" style="padding:5px;">
								<div class="tab-pane fade-in active" id="deskripsi">
									<h4>Pending Products</h4>
									<table class="table table-bordered table-striped">
										<thead>
											<tr>
												<td class="image">Image</td>
												<td class="remove">Product Name</td>
												<td class="remove">Category</td>
											</tr>
										</thead>
										<tbody>
											<?php
											$this->load->library('OSupplierPendingProduct');
											if(!emptyres($pending_list))
											{
												foreach($pending_list->result() as $r)
												{
													$P = new OSupplierPendingProduct($r->id);
													?>
													<tr>
														<td class="image"><img src="<?=base_url($P->get_photo())?>" alt="" width="30px" height="30px" /></td>
														<td class="remove"><?=$P->row->product_name?></td>
														<td class="remove"><?=$P->get_category()->name?></td>
														
													</tr>      
											<?php 
													unset($P);
												}
											}
											?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="foto-produk">
									<h4>Active Products</h4>
									<div class="row-fluid" style="overflow:hidden;">
										<table class="table table-bordered table-striped">
											<thead>
												<tr>
													<td class="image">Image</td>
													<td class="remove">Product ID</td>
													<td class="remove">Product Name</td>
													<td class="remove">Category</td>
												</tr>
											</thead>
											<tbody>
												<?php
													foreach($list as $r)
													{
														$P = new OProduct($r->product_id);
														$S = new OSize($r->size_id);
														
														$C = new OCategory($P->row->category_id);
														?>
														<tr>
															<td class="image"><img src="<?=base_url($P->get_photo())?>" alt="" width="30px" height="30px" /></td>
															<td class="remove"><b></b></td>
															<td class="remove"><?=$P->row->name?></td>
															<td class="remove"><?=$C->row->name?></td>
															
														</tr>      
												<?php 
														unset($P, $S, $C);
													} 					
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
</div>
<!-- end mid-content -->