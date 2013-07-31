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
						<li class="active">Add Ons</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Add On Form</div>
					</div>
					<div class="block-content collapse in">
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						
						<?=form_open()?>
							<?php 
								$AO = new OAddOn();
								
								$AO->setup($row);
								if($AO->row->image != "")
								{
									$img_url = $AO->get_photo("200xcrops");
									$img = '<img src="'.$img_url.'" alt="Preview" width="100px" height="100px" />';
								}
								
								if(sizeof($_POST) > 0)
								{
									$input_file = '<input class="userfile" type="hidden" value="'.$userfile.'" name="userfile">';
									$img = '<img src="/_assets/images/temp/resize_'.$userfile.'?'.time().'" alt="Preview" width="200" />';
								}
							?>
							<table class="tbl_form">
								<tr>
									<th>Name</th>
									<td> : <input type="text" name="name" value="<?=$name?>" autofocus required /> <br/><?=form_error('name')?></td>
								</tr>
								<tr>
									<th>Photo URL</th>
									<td><div style="display:block;" id="preview-photo"><?=$img?></div>
										<div id="swfupload-control-photo">
											<p>Upload just 1 image files(jpg, png, gif), having maximum size of 1MB  &amp; minimum resolution 80px x 80px</p>
											<input type="button" id="button-photo" />
											<p id="queuestatus-photo" ></p>
											<ol id="log-photo"></ol>
										</div>
									</td>
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

 	<script type="text/javascript" src="<?=base_url('assets/js/swfupload/swfupload.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('assets/js/jquery.swfupload.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('assets/js/jquery.swfupload.init.js')?>"></script>
    <script type="text/javascript">
        $(function(){
            jquery_upload_image("-photo",".set","userfile","#preview-photo","1 MB",1);
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
    </style>
        
           
         