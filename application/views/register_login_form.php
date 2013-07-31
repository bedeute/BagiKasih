<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div class="content">
					<div class="row-fluid">
						<div class="span12">
							<div class="row-fluid">
								<div class="span6">
									<?
									if($act == "login")
									{
									?>
									<?=print_error($this->session->flashdata("error_string"))?>
									<?=print_error($error_string)?>
									<?=print_error(validation_errors())?>
									<?
									}
									?>
									<?=form_open('account_supplier/register_login_form/login', array('id' => 'login_form'))?>
									<fieldset>
										<legend>Supplier Login</legend>
										<table>
											<tbody>
												<tr>
													<td class="name">Your Email Address </td>
													<td class="model"> : <input type="text" name="email" id="email" value="<?=$email?>" autofocus required></td>
												</tr>
												<tr>
													<td class="name">Password</td>
													<td class="model"> : <input type="password" name="password" id="password" value="<?=$password?>" required></td>
												</tr>
												<tr>
													<td><?=anchor('account_supplier/forgot_password', 'Forgot Your Password ?')?></td>
												</tr>
												<tr>
													<td colspan="2" align="right"><button type="button" class="btn btn-success" onclick="$('#login_form').submit();">Login</button></td>
												</tr>
											</tbody>
										</table>
									</fieldset>
									<?=form_close()?>
								</div>
								<div class="span6">
									<?php
										if($act == "register")
										{
											echo print_error($this->session->flashdata("error_string"));
											echo print_error($error_string);
											echo print_error(validation_errors());
										}
									?>
									<?=form_open('account_supplier/register_login_form/register', array('id' => 'register_form'))?>
									<fieldset>
										<legend>New Supplier</legend>
										<table>
											<tbody>
												<tr>
													<td class="name"><span class="required">*</span> Nama Lengkap </td>
													<td class="model"> : <input type="text" name="name" value="<?=set_value('name')?>" autofocus required></td>
												</tr>
												<tr>
													<td class="name"><span class="required">*</span> Address </td>                    		
													<td class="model"> : <textarea name="address" rows="2" required><?=set_value('address')?></textarea></td>
												</tr>
												<tr>
													<td class="name"><span class="required">*</span> Email </td>
													<td class="model"> : <input type="email" class="text" name="email" value="<?=set_value('email')?>" required></td>
												</tr>
												<tr>
													<td class="name"><span class="required">*</span> Telepon </td>
													<td class="model"> : <input type="text" name="phone" value="<?=set_value('phone')?>" required></td>
												</tr>
												<tr>
													<td class="name">Website </td>
													<td class="model"> : <input type="url" name="website" class="text" value="<?=set_value('website')?>" required></td>
												</tr>
												<tr>
													<td class="name">Fax</td>
													<td class="model"> : <input type="text" name="fax" value="<?=set_value('fax')?>" required></td>
												</tr>                   
												<tr>
													<td class="name"><span class="required">*</span> Location</td>                    		
													<td class="model"> : <?=Olocation::drop_down_select("location_id", $location_id, "", "- Select Location -")?></td>
												</tr>
												<tr>
													<td class="name">Description </td>
													<td class="model"> : <textarea name="description" rows="2" required><?=set_value('description')?></textarea></td>
												</tr>
												<tr>
													<td class="name"><span class="required">*</span> Password </td>
													<td class="model"> : <input type="password" name="password" required></td>
												</tr>
												<tr>
													<td class="name"><span class="required">*</span> Confirm Password </td>
													<td class="model"> : <input type="password" name="confirm_password" required></td>
												</tr>
												<tr>
													<td colspan="2" align="right"><button type="button" class="btn btn-success" onclick="$('#register_form').submit();">Submit</button></td>
												</tr>
											</tbody>
										</table>
									</fieldset>
									<?= form_close(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->