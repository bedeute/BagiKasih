<?php
	$showform = true;
	$category_id = $_GET['category_id'];
	if($row)
	{	
		extract(get_object_vars($row));
	}
	extract($_POST);
?>
<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="sidebar">
				<?=$this->load->view('supplier_right_sidebar')?>
			</div>
			<div class="span9">
				<div class="content">
					<div class="row-fluid">
						<?=validation_errors()."<br>"?>
						<?=print_success($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						<?=form_open('', array('class' => 'form-horizontal'))?>
							<fieldset style="margin-top:-20px;">
								<legend>
								<?php if($row) { ?>
									EDIT Brands ( ID : <?=$row->id?> )
								<? } else { ?>
									ADD Brands
								<? } ?>
								</legend>
								<table class="tbl_form">    	
									<tr>
										<th>Brand Name</th>
										<td> : <input type="text" name="name" value="<?=$name?>" autofocus required /> <br/><?=form_error('name')?></td>
									</tr>
									<tr>
										<th>Category</th>
										<td> : <?php echo OCategory::drop_down_select("category_id",$category_id); ?> <br/><?=form_error('category_id')?></td>
									</tr>
									<tr>
										<th>Shipping Fee</th>
										<td> : 
											<div class="input-prepend">
												<span class="add-on"><small>Rp</small></span>
												<input type="text" name="shipping_fee" value="<?=$shipping_fee?>" class="span12" required /> <br/><?=form_error('shipping_fee')?>
											</div>
										</td>
									</tr>
									<tr>         
										<td colspan="2" align="center">
											<br>
											<button class="btn btn-success" type="submit">Save</button>
											<button class="btn btn-default" type="reset">Reset</button>
											<button class="btn btn-default" type="button" onclick="location.href='<?=site_url("supplier/supplier_brand_list")?>';"><span class="leftarrow icon"></span>Cancel</button>
										</td>
									</tr>    
								</table>
							</fieldset>
						<?=form_close()?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->