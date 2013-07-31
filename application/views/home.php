<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="sidebar">
				<?=$this->load->view('tpl_sidebar')?>
			</div>
			<div class="span9">
				<div class="content">
					<h4 style="margin-left:13px;">Our Special Gift for You</h4>
					<div class="row-fluid">
						<?php
							$product_res = OProduct::get_filtered_list(get_location(),$brand_id,"","","","","p.id DESC",0,3,1);					
							echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
						?>
					</div>
					<hr style="margin-top:5px; margin-bottom:0px;">
					<h4 style="margin-left:13px;">Latest Special Cake</h4>
					<div class="row-fluid">
						<?php
							$product_res = OProduct::get_filtered_list(get_location(),$brand_id,'1',"","","","p.id DESC",0,8);            
							echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
				
							$C = new OCategory(1);
							echo (sizeof($product_res) > 8 ? '<div id="column-right">'.anchor($C->get_link(), '.....More special cakes').'</div>' : '');
							unset($C);
						?>
					</div>
					<hr style="margin-top:5px; margin-bottom:0px;">
					<h4 style="margin-left:13px;">Latest Beautiful Flower</h4>
					<div class="row-fluid">
						<?php
							$product_res = OProduct::get_filtered_list(get_location(),$brand_id,'2',"","","","p.id DESC",0,8);
							echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
						
							$C = new OCategory(2);
							echo (sizeof($product_res) > 8 ? '<div id="column-right">'.anchor($C->get_link(), '.....More special flowers').'</div>' : '');
							unset($C);
						?>
					</div>
					<hr style="margin-top:5px; margin-bottom:0px;">
					<h4 style="margin-left:13px;">Latest Gift</h4>
					<div class="row-fluid">
						<?php
							//$product_res = OProduct::get_filtered_list(get_location(),$brand_id,'4',"","","","p.id DESC",0,8);
							$product_res = OProduct::get_filtered_list_for_gift($brand_id,'4',"","","","p.id DESC",0,8);
							echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
						
							$C = new OCategory(3);
							echo (sizeof($product_res) > 8 ? '<div id="column-right">'.anchor($C->get_link(), '.....More special dolls').'</div>' : '');
							unset($C);
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->