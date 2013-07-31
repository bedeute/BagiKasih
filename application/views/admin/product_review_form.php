<?php
	if($row)
	{
		extract(get_object_vars($row));		
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
							<a href="#">Products</a> <span class="divider">/</span>	
						</li>
						<li class="active">Product Testimonials</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Product Testimonial Form</div>
					</div>
					<div class="block-content collapse in">
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						
						<?=form_open()?>
							<table class="tbl_form">
								<tr>
									<th>Product Name</th>
									<td> : <?=OProduct::drop_down_select("product_id",$product_id)?></td>
								</tr>
								<tr>
									<th>User</th>
									<td> : <?=OUser::drop_down_select("user_id",$user_id)?></td>
								</tr>
								<tr>
									<th>Rating</th>
									<td><div id="custom" data-value="<?=$rating?>"></div></td>
								</tr>  
								<tr>
									<th valign="top">Description</th>
									<td><textarea name="description" rows="8" required><?=$description?></textarea> <br /><?=form_error('description')?></td>
								</tr>
								<tr>            
									<td colspan="2" align="center">
										<button class="btn btn-success" type="submit">Save</button>
										<button class="btn btn-default" type="reset">Reset</button>
										<button class="btn btn-default" type="button" onclick="location.href='<?=site_url($this->curpage)?>';">Cancel</button>
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

<script type="text/javascript">
	var val = $('#custom').data('value');
	$('#custom').raty({
		scoreName: 'entity.score',
		path: '<?=base_url('assets/img/')?>',
		number: 5,
		start: val
	});
</script>

<style>
	#custom img {		
		width:15px;
		height:15px;
		display:inline-block;
		margin:0px;
	}
</style>