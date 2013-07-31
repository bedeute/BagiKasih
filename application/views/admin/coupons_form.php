<?php
	if($row)
	{
		extract(get_object_vars($row));	 
		$O = new OCoupon();
		$O->setup($row);
	}
	extract($_POST);
?>
<div class="row-fluid">
	<!--/span-->
	<div class="span12" id="content">
		<div class="row-fluid">
			<div class="navbar">
				<div class="navbar-inner">
					<ul class="breadcrumb">
						<li>
							<a href="#">Home</a> <span class="divider">/</span>	
						</li>
						<li>
							<a href="#">Coupons</a> <span class="divider">/</span>	
						</li>
						<li class="active">Coupon List</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Coupon Form</div>
					</div>
					<div class="block-content collapse in">
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						
						<?=form_open()?>
							<table class="tbl_form">
								<tr>
									<th>Code</th>
									<td> : <input type="text" name="code" value="<?=$code?>" required autofocus /> <br/><?=form_error('code')?></td>
								</tr>
								<tr>
									<th>Discount</th>
									<td> : 
										<div class="input-append">
											<input type="text" class="span3" id="appendedInput" name="discount" value="<?=$discount?>" required />
											<span class="add-on"><b>%</b></span>
										</div>
										<br /><?=form_error('discount')?>
									</td>
								</tr>
								<tr>
									<th>Start Date</th>
									<td> : <input type="text" name="start_date" class="datepicker" value="<?=$start_date?>" required  /> <br/><?=form_error('start_date')?></td>
								</tr>
								<tr>
									<th>Expired Date</th>
									<td> : <input type="text" name="expired_date" class="datepicker1" value="<?=$expired_date?>" required /> <br/><?=form_error('expired_date')?></td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<button class="btn btn-success" type="submit">Save</button>
										<button class="btn btn-default" type="reset">Reset</button>
										<button class="btn btn-default" type="button" onclick="location.href='<?=site_url("admin/coupons")?>';">Cancel</button>
									</td>
								</tr>    
							</table>
						<?=form_close()?>
					</div>
				</div>
				<!-- /block -->
			</div>
		</div>
	</div>
</div>
<script>
	$(function() {
		$( ".datepicker, .datepicker1" ).datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
			minDate: 0
		});			
	});
</script>