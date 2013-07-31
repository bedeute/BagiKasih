<?=$this->load->view('tpl_sidebar')?>

    <div class="main-content">
        <h3 class="title-register">Add Address</h3>
        
        		
            <?=print_error(validation_errors())?>
            
            <div class="product-details">
            	<div class="padd-container-wrap">
                	<div class="tabs-container" id="padd-testimoni">
						<?=form_open()?>            
                            <fieldset>
                                <div class="input-wrap">
                                    <label for="testi_message">Address</label>
                                    <div class="input-content">
                                    	<textarea name="address" id="address" cols="30" rows="10" required><?=$row->address?></textarea>
                                	</div>
                                </div>
                                <div class="input-wrap">
                                    <label for="testi_message">City</label>
                                    <div class="input-content">
                                    	<input type="text" id="city" name="city" value="<?=$row->city?>" required />
                                	</div>
                                </div>
                                <div class="input-wrap submit-wrap">
                                	<input type="submit" value="Save" />
                                </div>
                            </fieldset>
                        <?=form_close()?>
                    </div>
                </div>
            </div>
    </div><!--.main-content-->
</div><!--.main-container-->