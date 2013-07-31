<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div class="content">
					<ul class="breadcrumb">
						<li><?=anchor('/', 'Home')?> <span class="divider">/</span></li>
						<li><?=anchor('cart/user_cart', 'Shopping Cart')?> <span class="divider">/</span></li>
						<li class="active"><a><?=anchor('cart/member_area', 'Checkout')?></a></li>
					</ul>
					<h3 class="text-left">Checkout</h3>
					<div class="row-fluid">
						<div class="span12">
							<div class="alert alert-info">Member Area</div>
							<div class="row-fluid">
								<div class="span6">
									<?=form_open('', array('id'	=> 'member_area_form'))?>
										<fieldset>
											<legend>Member Login Area</legend>
												<?=print_error($error_string)?>
												<?=print_error(validation_errors())?>
												&nbsp;
												<table>
													<tr>
														<td class="name">Your Email Address </td>
														<td class="model"> : <input type="text" name="email" value="<?=set_value('email')?>"/></td>
													</tr>
													<tr>
														<td class="name">Password</td>
														<td class="model"> : <input type="password" name="password" /></td>
													</tr>
												</table>
												<br />
												<p><?=anchor('account/forgot_password', 'Forgot Your Password ?')?></p>
												<div class="left">
													<a class="btn btn-warning" href="javascript:;" onclick="$('#member_area_form').submit();"><span>login</span></a>
												</div>
										</fieldset>
									<?=form_close()?>
								</div>
								<div class="span6">
									<?=form_open('', array('id'	=> 'member_area_form'))?>
										<fieldset>
											<legend>Non Member Area</legend>
												<div class="center">
													<?=anchor('cart/check_out', '<span>Non member Shopping Next</span>', array('class' => 'btn btn-info'))?>
												</div>
												<br>
												<p align="center">If you dont want to register, click next...</p>
										</fieldset>
									<?=form_close()?>
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