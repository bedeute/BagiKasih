<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="sidebar">
				<?=$this->load->view('tpl_sidebar_categories')?>
			</div>
			<div class="span9">
				<div class="content">
					<h4 style="margin-left:13px;"><?php echo $total; ?> <?=ucfirst($oc->get_name())?> FOUND</h4>
					<div class="row-fluid">
						<?php			
							if(empty($allproducts))
							{
								?><br /><p class="text-red">No items found.</p><br /><?php
							}
							else
							{
								foreach($allproducts as $prod)
								{
									$P = new OProduct();
									$P->setup($prod);
									echo $this->load->view('tpl_product_list',array('p' => $P),TRUE);
									unset($P);	
								}
							}
							$total = get_db_total_rows();
						?>                  
						<br /><br /><br />
						<?=$pagination?>
					</div>
					<hr>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->