<?php /*?><?=$this->load->view('tpl_sidebar')?>

<div class="main-content">
      <div class="home-banners">
        <ul class="hb-slides">
        	<?php
				$banners = OBanner::get_list(0, 5, "ordering ASC, id DESC");
						
				if(!empty($banners))
				{
					foreach($banners as $r)
					{
						$B = new OBanner();
						$B->setup($r);
						
						$img_url = $B->get_photo('cropped_738x243');
						$img = '<img src="'.$img_url.'" alt="'.$r->url.'" />';
						?><li><?=anchor($r->url, $img, array('class' => 'ftimg'))?></li><?php						
						unset($B);
					}
				}
			?>
        </ul>
      </div>
      <div class="home-socials clearfix">
        <div class="special-from-us">
          <h3 class="rib-head rib-yhead">Persembahan Spesial Kami</h3>
          <ul class="product-list">
          	<?php
				$product_res = OProduct::get_filtered_list_by_price(get_location(),"","","","","","p.id DESC",0,3,1, $_GET['start_price'], $_GET['end_price']);
				 
				echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
			?>
          </ul>
        </div>
        <div class="social-banner">
          <h3 class="rib-head">Jejaring Sosial</h3>
          <a href="#" class="ftimg"><img src="<?=site_url('_assets/img/media/banner-fb.png')?>" alt=""></a>
        </div>
      </div>
      <div class="special-cakes">
        <h3 class="rib-head rib-yhead">KUE</h3>
        <ul class="product-list">
		<?php
            $product_res = OProduct::get_filtered_list_by_price(get_location(),"",'1',"","","","p.id DESC",0,8,"", $_GET['start_price'], $_GET['end_price']);
            
			echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
        ?>
        </ul>
        <?php
			$C = new OCategory(1);
			
			echo (sizeof($product_res) > 8 ? '<p class="all-items">'.anchor($C->get_link(), 'Lihat Koleksi yang Lain &#8230').'</p>' : '');
		?>
      </div>
      
      
      <div class="special-flowers">
        <h3 class="rib-head rib-yhead">BUNGA</h3>
        <ul class="product-list">
       	<?php
            
			$product_res = OProduct::get_filtered_list_by_price(get_location(),"",'2',"","","","p.id DESC",0,8,"", $_GET['start_price'], $_GET['end_price']);
           
			echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
        ?>
        	
        </ul>
        <?php
			$C = new OCategory(2);
			
			echo (sizeof($product_res) > 8 ? '<p class="all-items">'.anchor($C->get_link(), 'Lihat Koleksi yang Lain &#8230').'</p>' : '');
		?>
      </div>
      
      
      <div class="special-dolls">
        <h3 class="rib-head rib-yhead">BONEKA</h3>
        <ul class="product-list">
        <?php
			$product_res = OProduct::get_filtered_list_by_price(get_location(),"",'3',"","","","p.id DESC",0,8,"", $_GET['start_price'], $_GET['end_price']);
			
			echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
        ?>
        </ul>
        <?php
			$C = new OCategory(3);
			
			echo (sizeof($product_res) > 8 ? '<p class="all-items">'.anchor($C->get_link(), 'Lihat Koleksi yang Lain &#8230').'</p>' : '');
		?>
      </div>
    </div><!--.main-content-->
  </div><!--.main-container--><?php */?>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
<?=$this->load->view("tpl_newsletter", NULL, TRUE)?>

<div id="column-right">
	<?=$this->load->view("tpl_rank", NULL, TRUE)?>    
    <br><br><br>    
    <?=$this->load->view("tpl_fb_connect_sidebar", NULL, TRUE)?>
</div>

<?=$this->load->view('tpl_sidebar')?>
	
<div id="container">
    <div class="breadcrumb">
        <?=$this->load->view("tpl_select_city", NULL, TRUE)?>
    </div>
	<br>
</div>

<?=$this->load->view("tpl_banner", NULL, TRUE)?>

<div id="main-container">
    <h1>OUR SPECIAL GIFT FOR YOU</h1>
    <div class="box-content">
        <div class="box-product">
        	<?php
				$product_res = OProduct::get_filtered_list_by_price(get_location(),"","","","","","p.id DESC",0,3,1, $_GET['start_price'], $_GET['end_price']);
				echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
			?>
        </div>
    </div>
</div>

<div id="main-container2">
    <div class="box-content">
        <div class="box-product">
            <h1>SPECIAL CAKE</h1>
            <?php
			$product_res = OProduct::get_filtered_list_by_price(get_location(),"",'1',"","","","p.id DESC",0,8,"", $_GET['start_price'], $_GET['end_price']);
			echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);    
			/*$C = new OCategory(1);
			echo (sizeof($product_res) > 8 ? '<div id="column-right">'.anchor($C->get_link(), '.....More special cakes').'</div>' : '');*/
            ?>
        </div>
    </div>
</div>

<div id="main-container2">
    <div class="box-content">
        <div class="box-product">
            <h1>BEAUTIFUL FLOWER</h1>				
            <?php
			$product_res = OProduct::get_filtered_list_by_price(get_location(),"",'2',"","","","p.id DESC",0,8,"", $_GET['start_price'], $_GET['end_price']);
			echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
			/*$C = new OCategory(2);
			echo (sizeof($product_res) > 8 ? '<div id="column-right">'.anchor($C->get_link(), '.....More special flowers').'</div>' : '');*/
            ?>
        </div>
    </div>
</div>

<div id="main-container2">
    <div class="box-content">
        <div class="box-product">
            <h1>CUTE DOLL</h1>
            <?php
			$product_res = OProduct::get_filtered_list_by_price(get_location(),"",'3',"","","","p.id DESC",0,8,"", $_GET['start_price'], $_GET['end_price']);
			echo $this->load->view('tpl_homepage_product_list', array('product_res' => $product_res), TRUE);
			/*$C = new OCategory(3);
			echo (sizeof($product_res) > 8 ? '<div id="column-right">'.anchor($C->get_link(), '.....More special dolls').'</div>' : '');*/
            ?>
        </div>
    </div>
</div>