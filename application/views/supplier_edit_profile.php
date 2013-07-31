<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="sidebar">
				<?=$this->load->view('supplier_right_sidebar')?>
			</div>
			<div class="span9">
				<div class="content">
					<ul class="breadcrumb">
						<li><?=anchor('/', 'Home')?> <span class="divider">/</span></li>
						<li><?=anchor('supplier', 'Supplier')?> <span class="divider">/</span></li>
						<li class="active"><a><?=anchor('supplier/edit_profile', langtext('edit_profile'))?></a></li>
					</ul>
					<h3 class="text-left"><?=langtext('my_account')?></h3>
					<div class="row-fluid">
						<div class="span12">
						<?=print_error($this->session->flashdata("error_string"))?>
						<?=print_error($error_string)?>
						<?=print_error(validation_errors())?>
						<?=print_success($this->session->flashdata('post_item_success'))?>
						<?=form_open('', array('id' => 'account_form'))?>
							<div class="row-fluid">
								<div class="span4">
									<fieldset>
										<legend><?=langtext('yperdetails')?></legend>
										<label>* <?=langtext('name')?></label>
										<input name="name" value="<?=$cu->name?>" class="large-field" type="text">
										
										<label>* E-Mail</label>
										<input name="email" value="<?=$cu->email?>" class="large-field" type="text">
										
										<label>* <?=langtext('telephone')?></label>
										<input name="phone" value="<?=$cu->phone?>" class="large-field" type="text">
										
										<label>Fax</label>
										<input name="fax" value="<?=$cu->fax?>" class="large-field" type="text">
									</fieldset>
								</div>
								<div class="span4">
									<fieldset>
										<legend><?=langtext('address')?></legend>
										<label>* <?=langtext('address')?></label>
										<input name="address" value="<?=$cu->address?>" class="large-field" type="text">
										
										<label>* <?=langtext('city')?></label>
										<?=OLocation::drop_down_select("location_id",$location_id,"")?>
										
										<label>Web Site</label>
										<input name="website" value="<?=$cu->website?>" class="large-field" type="text">
										
										<label><?=langtext('description')?></label>              
										<textarea name="description" class="large-field"><?=$cu->description?></textarea>
									</fieldset>
								</div>
								<div class="span4">
									<fieldset>
										<legend>&nbsp;</legend>
										<label>Current Password</label>
										<input type="password" name="old_password" />

										<label>New Password</label>
										<input type="password" name="password" />
										
										<label>Confirm Password</label>
										<input type="password" name="retype_password" />
									</fieldset>
								</div>
							</div>
							<a class="btn btn-success" href="javascript:;" onclick="$('#account_form').submit();"><span>Save</span></a>
						<?=form_close()?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->