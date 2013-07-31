<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="sidebar">
				<?=$this->load->view('supplier_right_sidebar')?>
			</div>
			<div class="span9">
				<div class="content">
					<h3 class="text-left">Products</h3>
					<div class="row-fluid">
						<div class="span12">
							<div class="row-fluid">
								<?=validation_errors()."<br>"?>
								<?=print_error($this->session->flashdata('cek_product_unique_key'))?>
								
								<?php
									if(sizeof($_POST) > 0)
									{
										$input_file = $img_preview = NULL;
										foreach($_POST['image'] as $image)
										{
											$input_file[] = '<input class="image" type="hidden" value="'.$image.'" name="image[]">';
											$img_preview[] = '<img src="assets/images/temp/resize_'.$image.'?'.time().'" alt="Preview" width="100" />';
										}
									}
								?>
								
								<?=form_open('', array('id' => 'product_form'))?>
									<table class="form">
										<tr>
											<td><b><span class="required">*</span>Product Name</b></td>
											<td> <input type="text" name="product_name" value="<?=set_value('product_name')?>" /></td>
										</tr>
										<tr>
											<td><b>Category</b></td>
											<td>                  
												<?=OCategory::drop_down_select("category_id", $category_id, 'onchange="pilih_kategori()" onkeyup="pilih_kategori()"',"Pilih Kategori ...")?>
											</td>
										</tr>
										<tr>
											<td><b>Jenis</b></td>
											<td>
												<?php
												if($row) echo '<span id="type_ddl">'.OTypecategory::get_type_ddl("type_id", $type_id, "", $row->category_id).'</span>';
												else { ?><span id="type_ddl">Select Category First.</span> <?php } ?>
											</td>
										</tr>
										<tr>
											<td><b>Tema</b></td>
											<td> 
												<?php
												if($row) echo '<span id="theme_ddl">'.OThemecategory::get_theme_ddl("theme_id", $theme_id, "", $row->category_id).'</span>';
												else { ?><span id="theme_ddl">Select Category First.</span> <?php } ?>
											</td>
										</tr>
										<tr>
											<td><b>Brand</b></td>
											<td>
												<?php
												if($row) echo '<span id="brand_ddl">'.OBrand::get_brand_ddl("brand_id", $brand_id, $optional, $row->category_id).'</span>';
												else { ?><span id="brand_ddl">Select Category First.</span> <?php } ?>
											</td>
										</tr>
										<tr>
											<td valign="top">
												<b>Lain - lain </b><br />
												<span class="help">Use the enter key to separate each fields.</span>
												<span class="help">Ex :</span>
												<span class="help">Size:xxx</span>
												<span class="help">Color:xxx</span>
											</td>
											<td> <textarea name="multiple_text_fields" rows="10"><?=set_value('multiple_text_fields')?></textarea></td>
										</tr>
										<tr>
											<td valign="top">
												<b>Ukuran </b><br />
												<span class="help">Pastikan anda memasukkan ukuran dengan jelas dan harga dengan menggunakan angka saja.</span>
											</td>
											<td>
												<div id="size_price_list">
													<div style="float:left; width:160px;">Size</div>
													<div style="float:left">Price (angka saja)</div>
													<div style="clear:both;"></div>
													<div id="size_price_div" style="margin-bottom:5px;"><input type="text" name="size[]" style="width:150px;" /> <input type="text" name="price[]" /></div>
												</div>
												<button type="button" onclick="$('#size_price_list').append($('#size_price_div').clone());">Add More</
											
											</td>
										</tr>	
										<tr>
											<td><b>Image</b></td>
											<td>
												<div style="display:block;" id="last-photo"><?=$images?></div>
												<div style="display:block;" id="preview-photo"><?=$img_preview?></div>                        
												<div id="swfupload-control-photo">
													<p>Upload maximum <span id="total_photos">5</span> image files(jpg, png, gif), having maximum size of 1 MB &amp; minimum resolution 150px x 150px
													</p>
													<input type="button" id="button-photo" />
													<p id="queuestatus-photo" ></p>
													<ol id="log-photo"></ol>
												</div>
											</td>
										</tr>
										<tr>
											<td><b>Perkiraan Pengiriman</b></td>
											<td> <?php
											for($i = 1; $i <= 20; $i++) $arr[$i] = $i;
											echo dropdown("estimate_delivery",$arr,$estimate_delivery)." hari";
											?></td>
										</tr>
										<tr>
											<td><b>Description</b></td>
											<td> <textarea name="description" cols="30" rows="10"><?=set_value('description')?></textarea></td>
										</tr>
										<tr>
											<td colspan="2" align="center">
												<a class="btn btn-success" href="javascript:;" onclick="$('#product_form').submit();"><span>Save</span></a>
												<?=anchor('supplier/supplier_handle_products', '<span>Back</span>', array('class' => 'btn btn-default'))?>
											</td>
										</tr>
									</table>
									<span class="set"><?=implode("", $input_file)?></span>
								<?=form_close()?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->

<script type="text/javascript" src="<?=base_url('assets/js/swfupload/swfupload.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/jquery.swfupload.js')?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/jquery.swfupload.init.js')?>"></script>
<script type="text/javascript">
$(function(){
	jquery_upload_image("-photo",".set","image[]","#preview-photo","1 MB",5);
});	
</script>

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

<script type="text/javascript">
	function pilih_kategori(){
		var category_id = $("#category_id").val();
		$.ajax({
			type: "POST",
			url: "<?=base_url('ajax/get_type_theme_product')?>",
			data: {"category_id":category_id},
			complete: function(resp){
				var data = resp.responseText;
				var Obj = $.parseJSON(data);
				if(Obj != null)
				{
					$("#type_ddl").html(Obj.type);
					$("#theme_ddl").html(Obj.theme);
					$("#brand_ddl").html(Obj.brand);
				}
			}
		});
	}			
</script>