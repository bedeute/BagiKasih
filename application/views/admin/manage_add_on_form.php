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
						<li class="active">Manage Add Ons</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Manage Add Ons</div>
					</div>
					<div class="block-content collapse in">

						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>

						<?php
							if($row)
							{
								$P = new OProduct();
								$P->setup($row);
								
								$addon_res = $P->get_addons();
								
								$addon_id_arr = $price = NULL;
								foreach($addon_res as $r)
								{ 
									$addon_id_arr[] = $r->addon_id;
									$price[$r->addon_id] = $r->price;
								}
								unset($P);
							}
						?>
						
						<strong><p>Select Add Ons & Enter The Prices</p></strong>
						<?=form_open()?>
							<table class="table table-bordered table-striped"> 
								<tr>
									<td><?=OAddOn::checkbox_select("addon_id", $addon_id_arr, "", "<br />", $price)?></td>
								</tr>
								<tr>            	
									<td>                	
										<button type="submit" class="btn btn-success">Save</button>                
									</td>
								</tr>
						   </table>
					   <?=form_close()?>
					</div>
				</div>
				<!-- /block -->
			</div>
		</div>
	</div>
</div>