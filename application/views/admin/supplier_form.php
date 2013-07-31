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
							<a href="#">Suppliers</a> <span class="divider">/</span>	
						</li>
						<li class="active">Supplier List</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Supplier Form</div>
					</div>
					<div class="block-content collapse in">
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						
						<?=form_open()?>
							<?php
								$os = new OSupplier();
								$os->setup($row);
							?> 
							<input type="hidden" name="next" value="<?=$_SERVER['HTTP_REFERER']?>" />
							<table class="tbl_form">
								<tr>
									<th>Name</th>
									<td> : <input type="text" name="name" value="<?=$name?>" autofocus required /> <br/><?=form_error('name')?></td>
								</tr>
								<tr>
									<th>Location</th>
									<td> : <?=OLocation::drop_down_select("id_city",$id_city)?></td>
								</tr>
								<tr>
									<th>Bank</th>
									<td> : <?=OBank::drop_down_select("id_bank",$id_bank)?></td>
								</tr>
								<tr>
									<th>Cabang</th>
									<td> : <input type="text" name="cabang" value="<?=$cabang?>" required /> <br /><?=form_error('cabang')?></td>
								</tr>
								<tr>
									<th>No. Rekening</th>
									<td> : <input type="text" name="no_rekening" value="<?=$no_rekening?>" required /> <br /><?=form_error('no_rekening')?></td>
								</tr>
								<tr>
									<th>Atas Nama</th>
									<td> : <input type="text" name="on_behalf" value="<?=$on_behalf?>" required /> <br /><?=form_error('on_behalf')?></td>
								</tr>
								<tr>
									<th valign="top">Address</th>
									<td> : <textarea name="address" required><?=$address?></textarea><br /><?=form_error('address')?></td>
								</tr>
								<tr>
									<th>Phone</th>
									<td> : <input type="text" name="phone" value="<?=$phone?>" required /> <br /><?=form_error('phone')?></td>
								</tr>
								<tr>
									<th>Fax</th>
									<td> : <input type="text" name="fax" value="<?=$fax?>" /> <br /><?=form_error('fax')?></td>
								</tr>
								<tr>
									<th valign="top">Description</th>
									<td> : <textarea name="description" required><?=$description?></textarea><br /><?=form_error('description')?></td>
								</tr>
								<tr>
									<th>Email</th>
									<td> : <input type="text" name="email" value="<?=$email?>" required /> <br /><?=form_error('email')?></td>
								</tr>    
								<tr>
									<th>Password </th>
									<td> : <input type="password" name="password" /> <br /><?=form_error('password')?></td>
								</tr>
								<tr>
									<th>Retype Password</th>
									<td> : <input type="password" name="confirm_password" /> <br /><?=form_error('retype_password')?></td>
								</tr>
								<tr>            
									<td colspan="2" align="center">
										<button class="btn btn-success" type="submit">Save</button>
										<button class="btn btn-default" type="reset">Reset</button>
										<button class="btn btn-default" type="button" onclick="location.href='<?=$_SERVER['HTTP_REFERER']?>';">Cancel</button>
									</td>
								</tr>    
							</table>
							<span class="set"><?=$input_file?></span>
						<?=form_close()?>
					</div>
				</div>
				<!-- /block -->
			</div>
		</div>
	</div>
</div>