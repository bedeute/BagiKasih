<?=$this->load->view('tpl_sidebar')?>

    <div class="main-content">
        <h3 class="title-register">Add Bank Account</h3>
        
        		
            <?=print_error(validation_errors())?>
            
            <div class="product-details">
            	<div class="padd-container-wrap">
                	<div class="tabs-container" id="padd-testimoni">
						<?=form_open()?>            
                            <fieldset>
                                <div class="input-wrap">
                                    <label for="testi_message">Customer Name</label>
                                    <div class="input-content">
                                    	<input type="text" name="customer_name" id="customer_name" value="<?=$row->customer_name?>" required="required" />
                                	</div>
                                </div>
                                <div class="input-wrap">
                                    <label for="testi_message">Bank Name</label>
                                    <div class="input-content">
                                    	<input type="text" name="bank_name" id="bank_name" value="<?=$row->bank_name?>" required="required" />
                                	</div>
                                </div>
                                <div class="input-wrap">
                                    <label for="testi_message">Acccount Number</label>
                                    <div class="input-content">
                                    	<input type="text" name="account_number" id="account_number" value="<?=$row->account_number?>" required="required" />
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