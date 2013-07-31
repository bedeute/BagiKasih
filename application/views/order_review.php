<?=$this->load->view('tpl_sidebar')?>

<div class="main-content">
    <h3 class="title-register">Order Reviews</h3>
    
    <?=print_success($this->session->flashdata("testimonial_msg"))?>
                
    <?=anchor('user_review/product_reviews', 'Product Reviews')?> | <?=anchor('user_review/order_reviews', 'Order Reviews')?>
    <br /><br />            
    <?=anchor('user_review/add_order_reviews', 'Add Order Reviews')?>
    <br /><br />
            
    <div class="product-details">
        <div class="padd-container-wrap">
            <div class="tabs-container" id="padd-testimoni">
				<?php
                if(sizeof($order_reviews) <= 0) echo '<p class="text-red">Order Reviews is empty</p>';
                else
                {
                    ?>
                    <ul class="padd-testi-list">
                        <?php
                        foreach($order_reviews as $r)
                        {
                            /*$P = new OProduct($r->product_id);*/
                            ?>                                   
                            <li>
                                <?php /*?><?=anchor($P->get_link(), '<img src="'.$P->get_photo().'" alt="" width="60px" height="60px" />', array('class' => 'ftimg'))?><?php */?>
                                <?php /*?>#<strong><?=$r->order_id?></strong><?php */?>
                                <div class="padd-tlist-main">
                                    <p class="order-no"><?php /*?>Order No: 8543375<?php */?><?php /*?><?=$r->topic?><?php */?>
                                        Order ID : #<strong><?=$r->order_id?></strong>
                                    </p>
                                    <p>Anda berkata : "<?=$r->description?>"</p>
                                </div>
                                <div class="padd-tlist-info">
                                    <time datetime="2012-06-12T17:55+07:00">on <?=parse_date_time($r->dt)?></time>
                                    <div class="fixed" data-value="<?=$r->rating?>"></div>
                                </div>
                            </li>                                                                              
                            <?php	
                        }	
                        ?>                                    
                    </ul>	
                    <?php	
                }
                ?>
            </div>
        </div>
    </div>
            
    <div class="register-link">
        <?=anchor('user/home', 'Profile')?> | <?=anchor('user/view_orders', 'View Orders')?>                
    </div>    
</div><!--.main-content-->

</div><!--.main-container-->


<script>
	var val = $('.fixed').data('value');
	
	$('.fixed').raty({
	  readOnly:  true,
	  path: '/_assets/img/',
	  start:    val
	});
</script>

<style>
	#custom img, #fixed img { width:15px; height:15px; display:inline-block; margin:0px; }
</style>