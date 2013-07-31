<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div class="content">
					<ul class="breadcrumb">
						<li><?=anchor('/', 'Home')?> <span class="divider">/</span></li>
						<li class="active"><a><?=anchor('confirm_payment/send_confirm_payment', 'Payment Confirmation')?></a></li>
					</ul>
					<h3 class="text-left">My Account</h3>
					<div class="row-fluid">
						<div class="span12">
							<div class="row-fluid">
								<div class="span12">
									<?=form_open('confirm_payment/send_confirm_payment/'.$order_id, array('id' => 'payment_form'))?>
									<?=print_error(validation_errors())?>
									<?=print_success($this->session->flashdata('confirm_msg'))?>
									<?=print_error($this->session->flashdata('no_order_id'))?>
									<?=print_success($confirm_msg)?>
										<fieldset>
											<legend>Payment Confirmation</legend>
											<table>
												<tbody>
													<tr>
														<td><b>Invoice #:</b></td>
														<td><input type="text" name="order_id" /></td>
													</tr>
													<tr>
														<td>Name:</td>
														<td><input type="text" name="name" /></td>
													</tr>
													<tr>
														<td>Email:</td>
														<td><input type="text" name="email" /></td>
													</tr>
													<tr>
														<td>Date:</td>
														<td><input type="text" name="date" id="datepicker" required /></td>	  
													</tr>
													<tr>
														<td>Payment Method:</td>
														<td><input type="text" name="payment_method" value="Transfer" readonly="readonly" required /></td>
													</tr>
													<tr>	
														<td>Bank Account:</td>
														<td><input type="text" name="bank_account" /></td>
													</tr>
													<tr>	
														<td>Account Number:	</td>
														<td><input type="text" name="account_number" /></td>
													</tr>
													<tr>
														<td>Amount:</td>
														<td><input name="amount" class="large-field" type="text"></td>
													</tr>
													<tr>
														<td>Keterangan:</td>
														<td><textarea name="keterangan" rows="2"></textarea></td>
													</tr>
													<tr>
														<td>
															<?=anchor('user/confirm_payment_list', '<span>Back</span>', array('class' => 'btn'))?>
															<a class="btn btn-success" href="javascript:;" onclick="$('#payment_form').submit();"><span>Continue</span></a>
														</td>
													</tr>
												</tbody>
											</table>
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