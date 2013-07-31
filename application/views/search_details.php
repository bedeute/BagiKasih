<?=$this->load->view('tpl_sidebar',array('nofilter' => TRUE),TRUE)?>

<div class="main-content">
      <h1>Hasil Pencarian</h1><br />
      <?php
	  $found = false; 
	  $product_res = OProduct::get_filtered_list_keyword($_GET['keyword'],get_location(),"",intval($_GET['cat']),"","","","p.id DESC",0,0);		
	  	  
	  if(!empty($product_res))
	  {
		  $found = true;
		  $total = get_db_total_rows();
		  ?>
          <div class="special-cakes">
          	<?php $C = new OCategory(intval($_GET['cat'])); ?>
          	<h3 class="rib-head rib-yhead"><?=strtoupper($C->row->name)?> (<?=$total?> items)</h3>
            <ul class="product-list">
            <?php
                echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
            ?>
            </ul>
          </div>
          <?php
	  }
	  ?>
      
      <?php
	  if(!$found)
	  {
		  ?>
          <p>Maaf, hasil tidak bisa ditemukan untuk keyword ini.</p>
          <?php
	  }
	  ?>
    </div><!--.main-content-->
  </div><!--.main-container-->