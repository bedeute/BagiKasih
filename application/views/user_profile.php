<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="sidebar">
				<?=$this->load->view('user_right_sidebar')?>
			</div>
			<div class="span9">
				<div class="content">
					<ul class="breadcrumb">
						<li><?=anchor('/', 'Home')?> <span class="divider">/</span></li>
						<li class="active"><a><?=anchor('user/home', langtext('account'))?></a></li>
					</ul>
					<h3 class="text-left"><?=langtext('my_account')?></h3>
					<div class="row-fluid">
						<div class="span12">
							<div class="row-fluid">
								<?=print_error($this->session->flashdata("error_string"))?>
								<?=print_error($error_string)?>
								<?=print_error(validation_errors())?>
								<?=print_success($this->session->flashdata('post_item_success'))?>
								<div class="span6">
									<fieldset>
										<legend><?=langtext('yperdetails')?></legend>
										<label><?=langtext('name')?></label>
										<input name="name" value="<?=$cu->name?>" class="large-field" type="text" readonly="readonly">
										
										<label>E-Mail</label>
										<input name="email" value="<?=$cu->email?>" class="large-field" type="text" readonly="readonly">
										
										<label><?=langtext('telephone')?></label>
										<input name="phone" value="<?=$cu->phone?>" class="large-field" type="text" readonly="readonly">
										
										<label>Fax</label>
										<input name="fax" value="<?=$cu->fax?>" class="large-field" type="text" readonly="readonly">
									</fieldset>
								</div>
								<div class="span6">
									<fieldset>
										<legend><?=langtext('address')?></legend>
										<label><?=langtext('address')?></label>
										<input name="address_1" value="<?=$cu->address?>" class="large-field" type="text" readonly="readonly">
										
										<label><?=langtext('city')?></label>
										<input name="city" value="<?=$cu->city?>" class="large-field" type="text" readonly="readonly">
										
										<label><?=langtext('zip_code')?></label>
										<input name="postcode" value="<?=$cu->zip_code?>" class="large-field" type="text" readonly="readonly">
										
										<label><?=langtext('country')?><label>
										<input name="country" value="Indonesia" class="large-field" type="text" readonly="readonly">
										
										<label><?=langtext('regstate')?>:<label>
										<input name="country" value="<?=$cu->state?>" class="large-field" type="text" readonly="readonly">
									</fieldset>
								</div>
								<?=anchor('user/edit_profile', 'Edit Profile', array('class' => 'btn btn-info'))?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->