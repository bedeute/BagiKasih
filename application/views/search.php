<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div class="content">
					<h3>Hasil Pencarian</h3>
					<?php
						$found = false;
						$product_res = OProduct::get_filtered_list_keyword($_GET['s'],get_location(),$brand_id,'1',"","","","p.id DESC",'0');		

						if(!empty($product_res))
						{
							$found = true;
							$total = get_db_total_rows();
					?>
					<h4>Cake (<?=$total;?> items)</h4>
					<div class="row-fluid">
						<?php          
							echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
							
							echo (intval($total) > 8 ? '<div id="column-right">'.anchor('search/details?cat=1&keyword='.$_GET['s'], 'Click to View More Results &#8230').'</div>' : '');
						?>
					</div>
					<hr>
					<? } ?>
					<?php
						$product_res = OProduct::get_filtered_list_keyword($_GET['s'],get_location(),$brand_id,'2',"","","","p.id DESC",0,8);		
						if(!empty($product_res))
						{
							$found = true;
							$total = get_db_total_rows();
					?>
					<h4>Flower (<?=$total;?> items)</h4>
					<div class="row-fluid">
						<?php
							echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
							
							echo (intval($total) > 8 ? '<div id="column-right">'.anchor('search/details?cat=2&keyword='.$_GET['s'], 'Click to View More Results &#8230').'</div>' : '');
						?>
					</div>
					<hr>
					<? } ?>
					<?php
						$product_res = OProduct::get_filtered_list_keyword($_GET['s'],get_location(),$brand_id,'3',"","","","p.id DESC",0,8);		
						if(!empty($product_res))
						{
							$found = true;
							$total = get_db_total_rows();
					?>
					<h4>Gift (<?=$total;?> items)</h4>
					<div class="row-fluid">
						<?php
							echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
						
							echo (intval($total) > 8 ? '<div id="column-right">'.anchor('search/details?cat=3&keyword='.$_GET['s'], 'Click to View More Results &#8230').'</div>' : '');
						?>
					</div>
					<? } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->