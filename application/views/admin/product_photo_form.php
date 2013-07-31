<?php
	if($row)
	{
		extract(get_object_vars($row));	 
	}
	extract($_POST);
?>

<h2>Product Form</h2><br/>	
<?=print_error($this->session->flashdata('warning'))?>
<?=print_success($this->session->flashdata('success'))?>

<strong>Product Name : </strong><?=$lvproduct->row->name?>

<?=form_open()?>
	<?php
		$lvproductphoto = new LVProductPhoto();
		$lvproductphoto->setup($row);
		
		if($row->image != "")
		{
			$img_url = $lvproductphoto->get_photo("100");
			$img = '<img src="'.$img_url.'" alt="Preview" />';			
		}
		
		if(sizeof($_POST) > 0)
		{
			$input_file = '<input class="userfile" type="hidden" value="'.$userfile.'" name="userfile">';
			$img = '<img src="/_assets/images/temp/resize_'.$userfile.'?'.time().'" alt="Preview" />';
		}
	?>
	<table class="tbl_form">    	
		<tr>
            <th>Image</th>
            <td><div style="display:block;" id="preview-img"><?=$img?></div>
                <?php
				if($lvproductphoto)
				{
					echo ($lvproductphoto->get_photo("100", TRUE) != "" ? "<br />".anchor("admins/products/delete_photo_img/".$product_id."/".$id, "Delete this image") : "");
				}
                ?>
                <div id="swfupload-control-img">
                    <p>Upload maximum 1 image files(jpg, png, gif), having maximum size of 1 MB</p>
                    <input type="button" id="button-img" />
                    <p id="queuestatus-img" ></p>
                    <ol id="log-img"></ol>
                </div>
            </td>
        </tr>    
        <tr>
			<th>Caption</th>
			<td><textarea name="caption" cols="30" rows="5" required><?=$caption?></textarea> <br/><?=form_error('caption')?></td>
		</tr>
        <tr>
			<th>Ordering</th>
			<td><input type="text" name="ordering" value="<?=$ordering?>" required /> <br/><?=form_error('ordering')?></td>
		</tr>		
		<tr>
			<td></td>
			<td>
            	<input type="submit" value="Save" />
                <input type="reset" value="Cancel" />
			</td>
		</tr>    
	</table>    
	
    <span class="set"><?=$input_file?></span>
	
<?=form_close()?>

<?=anchor($this->curpage."/photos/".$product_id, "&laquo; back")?>

<script type="text/javascript" src="/_assets/js/swfupload/swfupload.js"></script>
<script type="text/javascript" src="/_assets/js/jquery.swfupload.js"></script>
<script type="text/javascript" src="/_assets/js/jquery.swfupload.init.js"></script>
<script type="text/javascript">

$(function(){
	jquery_upload_image("-img",".set","userfile","#preview-img","1 MB",10);
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
{ position:absolute; top:5px; right:5px; width:20px; height:20px; background:url('/_assets/js/swfupload/cancel.png') no-repeat; cursor:pointer; }

.form_style .full { width:98%; }
.error{color:#FF0000}
</style>