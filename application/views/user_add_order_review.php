<?=$this->load->view('tpl_sidebar')?>

    <div class="main-content">
        <h3 class="title-register">Add Order Reviews</h3>        
        		
            <?=print_error(validation_errors())?>
        	
            <?=anchor('user_review/product_reviews', 'Product Reviews')?> | <?=anchor('user_review/order_reviews', 'Order Reviews')?>
            <br /><br />   
            
            <div class="product-details">
            	<div class="padd-container-wrap">
                	<div class="tabs-container" id="padd-testimoni">
						<?=form_open()?>            
                            <fieldset>
                            	
                            	<div class="input-wrap">
                                    <label for="testi_topic">Order ID</label>
                                    <div class="input-content">
                                    	<?php 
										if($this->uri->segment(3) == "")
										{
											echo OOrder::get_order_reviews_ddl("order_id", $order_id, "", $cu->id);		
										}
										else
										{
											?><input type="text" name="order_id" id="order_id" value="<?=$this->uri->segment(3)?>" readonly="readonly" /><?php	
										}
										?>                                    	
                                	</div>
                                </div>
                                <?php /*?><div class="input-wrap">
                                    <label for="testi_topic">Topic</label>
                                    <div class="input-content">
                                    	<input type="text" name="topic" id="topic" value="<?=set_value('topic')?>" required="required">
                                	</div>
                                </div><?php */?>
                                <div class="input-wrap">
                                    <label for="testi_message">Review</label>
                                    <div class="input-content">
                                    	<textarea name="description" id="description" cols="30" rows="10" required="required"><?=set_value('description')?></textarea>
                                	</div>
                                </div>
                                <div class="input-wrap">
                                    <label for="testi_message">Rating</label>
                                    <div class="input-content">
                                    	<div id="custom" data-value="<?=$rating?>"></div>
                                	</div>
                                </div>
                                <div class="input-wrap submit-wrap">
                                	<input type="submit" value="Kirim Testimoni" />
                                </div>
                            </fieldset>
                        <?=form_close()?>
                    </div>
                </div>
            </div>
            
            <div class="register-link">
            	<?=anchor('user/home', 'Profile')?> | <?=anchor('user/view_orders', 'View Orders')?>                
            </div>
          
    </div><!--.main-content-->
</div><!--.main-container-->

<style type="text/css">
	#padd-testimoni select {
		background: none repeat scroll 0 0 #F7F7F8;
		border: 1px solid #008CD6;
		line-height: 1.2em;
		margin-top: 5px;
		padding: 2px 5px;
	}
	
	#padd-testimoni input[type="text"] {
		height: 16px;
		width: 100px !important;
	}
</style>

<script>
	//var val = $('#custom').data('value');
	$('#custom').raty({
		scoreName: 'entity.score',
		path: '/_assets/img/',
		number: 5,
		start: 1
	});
</script>

<style>
	#custom img, #fixed img {		
		width:15px;
		height:15px;
		display:inline-block;
		margin:0px;
	}
</style>