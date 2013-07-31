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
						<li class="active">Supplier List</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Supplier List</div>
					</div>
					<div class="block-content collapse in">
						<p><?=anchor($this->curpage."/add", "<i class='icon-plus'></i> Create new",array("class" => "btn"))?></p>
						<p>
							<strong>Filter by City : </strong><?=anchor("admin/suppliers/","All")?> | 
							<?php
							$loclist = OLocation::get_list(0,0);
							foreach($loclist as $loc)
							{
								echo anchor("admin/suppliers/?location_id={$loc->id_kota}",$loc->name)." | ";
							}
							?>
						</p>
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						<?php if(sizeof($list) <= 0) { ?>
							<p class='text_red'>There is no data found.</p>
						<? } else { ?>
							<form>
								<div class="input-append">
									<input class="span9" id="appendedInputButtons" type="text" name="keyword" value="<?=$_GET["keyword"]?>" placeholder="Type Keywords..." />
									<button class="btn" type="submit">Search</button>
									<?=anchor('admin/suppliers','Refresh', array('class' => 'btn'))?>
								</div>
							</form>
							<table class="table table-bordered table-striped tbl_list">
								<thead>
									<tr>
										<th>ID</th>        	
										<th>Location</th>                    
										<th>Name</th>                    
										<th>Info</th>                   
										<th>Action</th>
									</tr>
								</thead>
								<tbody>                
								<?php 
									$i=1 + $uri;
									
									foreach($list as $row):
										$os = new OSupplier();
										$os->setup($row);
												
									extract(get_object_vars($row));
								?>		        
									<tr>
										<td><?=$id_supplier?></td>            
										<td><?=get_city($id_city)->name?></td>
										<td><?=$name?></td>
										<td><?=nl2br($address)?><br /><?=$phone?> / <?=$fax?><br /><?=auto_link($email)?></td>
										<td>
											<?php
												echo anchor($this->curpage."/delete/".$id_supplier, "Delete", array("onclick" => "return confirm('Are you sure?');"));
												echo " | ".anchor($this->curpage."/edit/".$id_supplier, "Edit");
											?> 
										</td>       
									</tr>
								<?php				
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