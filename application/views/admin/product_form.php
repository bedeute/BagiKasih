<?php
	if($row)
	{
		extract(get_object_vars($row));	
		$P = new OProduct();
		$P->setup($row);
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
						<li class="active">Product Form</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Product Form</div>
					</div>
					<div class="block-content collapse in">
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						
						<?=form_open_multipart()?>
							<table class="tbl_form">
								<tr>
									<th>Name</th><td>:</td>
									<td><input type="text" name="name" value="<?=$name?>" autofocus required /> <br/><?=form_error('name')?></td>
								</tr>
								<tr>
									<th>Category</th><td>:</td>
									<td><?=OCategory::drop_down_select("id_category", $id_category)?></td>
								</tr>
								<tr>
									<th>Supplier</th><td>:</td>
									<td><?=OSupplier::drop_down_select("id_supplier", $id_supplier)?></td>
								</tr>
								<tr>
									<th valign="top">Description</th><td valign="top">:</td>
									<td><textarea name="deskripsi" rows="10"><?=$deskripsi?></textarea> <br/><?=form_error('deskripsi')?></td>
								</tr>
								<tr>
									<th>Price</th><td> : </td>
									<td>
										<div class="input-prepend">
											<span class="add-on"><small>RP</small></span>
											<input type="text" class="span12" id="appendedInput" name="harga" value="<?=$harga?>" required />
										</div>
										<br /><?=form_error('harga')?>
									</td>
								</tr>
								<tr>
									<th>Photos</th><td>:</td>
									<td>
										<input name="userfile" type="file" id="userfile" />
										<p>Image types allowed (jpg, png, gif), having maximum size of 1 MB &amp; minimum resolution 150px x 150px</p>
									</td>
								</tr>
								<?php if($foto) { ?>
								<tr>
									<th></th><td></td>
									<td><img src="<?=base_url('assets/images/'.$foto)?>" width="100" class="img-polaroid" /></td>
								</tr>
								<? } ?>
								<tr>
									<td colspan="3" align="center">
										<button class="btn btn-success" type="submit"><span class="check icon"></span>Save</button>
										<button class="btn btn-default" type="reset"><span class="reload icon"></span>Reset</button>
										<button class="btn btn-default" type="button" onclick="location.href='<?=site_url($this->curpage)?>';"><span class="leftarrow icon"></span>Cancel</button>
									</td>
								</tr> 
							</table>
							<span class="set"><?=implode("", $input_file)?></span>
						<?=form_close()?>
					</div>
				</div>
				<!-- /block -->
			</div>
		</div>
	</div>
</div>

<? if(($max_photo-$total_photos) > 0) { ?>
	<script type="text/javascript" src="<?=base_url('assets/js/swfupload/swfupload.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('assets/js/jquery.swfupload.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('assets/js/jquery.swfupload.init.js')?>"></script>
	
	<script type="text/javascript">
	$(function(){
		var total_photos = $('#total_photos').text();
		jquery_upload_image("-photo",".set","image[]","#preview-photo","1 MB",total_photos);
	});	
	</script>
<? } ?>
	
<style type="text/css" >
	#SWFUpload_Console { display:none; position:absolute; z-index:1000; bottom:0; right:10px; }
	#swfupload-control-photo p
	{ margin:10px 5px; font-size:0.9em; }
	#log-photo
	{ margin:0; padding:0; width:500px;}
	#log-photo li
	{ list-style-position:inside; margin:2px; border:1px solid #ccc; padding:10px; font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#333; background:#fff; position:relative;}
	#log-photo li .progressbar
	{ border:1px solid #333; height:5px; background:#fff; }
	#log-photo li .progress
	{ background:#999; width:0%; height:5px; }
	#log-photo li p
	{ margin:0; line-height:18px; }
	#log-photo li.success
	{ border:1px solid #339933; background:#ccf9b9; }
	#log-photo li span.cancel
	{ position:absolute; top:5px; right:5px; width:20px; height:20px; background:url('<?=base_url('assets/js/swfupload/cancel.png')?>') no-repeat; cursor:pointer; }
	
	.form_style .full { width:98%; }
	.error{color:#FF0000}
	
	.tbl_separate{}
	.tbl_separate tr{ vertical-align:top; }
	.tbl_separate tr td{ padding:5px; vertical-align:top; }
	
	.image_list{ list-style:none; float:left; }
	.image_list li{ float:left; margin: 0 10px 10px 0; }
</style>

<script src="<?=base_url("assets/js/tiny_mce_advanced.js")?>"></script>