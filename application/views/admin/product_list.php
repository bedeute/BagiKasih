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
						<li class="active">Product List</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Product List</div>
					</div>
					<div class="block-content collapse in">
						<p><?=anchor($this->curpage."/add", "<i class='icon-plus'></i> Create new",array("class" => "btn"))?></p>
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						<?php if(sizeof($list) <= 0) { ?>
							<p class='text_red'>Product List is empty.</p>
						<? } else { ?>
							<form>
								<div class="input-append">
									<input class="span9" id="appendedInputButtons" type="text" name="keyword" value="<?=$_GET["keyword"]?>" placeholder="Type Keywords..." />
									<button class="btn" type="submit">Search</button>
									<?=anchor('admin/products','Refresh', array('class' => 'btn'))?>
								</div>
							</form>
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>ID</th>  	
										<th>Name</th>
										<th>Category</th>
										<th>Supplier</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>                
								<?php 
									$i=1 + $uri;
									foreach($list as $row):				
										extract(get_object_vars($row));
										$C = new OCategory($id_category);
										$S = new OSupplier($id_supplier);
								?>		        
									<tr>
										<td><?=$id_product?></td>
										<td><?=$name?></td>
										<td><?=$C->row->category?></td>
										<td><?=$S->row->name?></td>
										<td>
											<?= anchor($this->curpage."/delete/".$id_product, "Delete", array("onclick" => "return confirm('Are you sure?');")); ?>
											<?= " | ".anchor($this->curpage."/edit/".$id_product, "Edit"); ?>
										</td>       
									</tr>
								<?php 
										unset($C, $B);
										$i++;
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