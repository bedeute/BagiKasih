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
						<li><?=anchor('user/home', langtext('account'))?> <span class="divider">/</span></li>
						<li class="active"><?=anchor('user/confirm_payment_list', langtext('payment_confirmation'))?></li>
					</ul>
					<div class="row-fluid">
						<div class="span12">
							<?=form_open('confirm_payment/send_select_confirm_payment')?>
							<?=print_error(validation_errors())?>
							<?=print_success($this->session->flashdata('confirm_msg'))?>
								<fieldset>
									<legend><?=langtext('payment_confirmation')?></legend>
									<table>
										<tr>
											<td><b>Invoice #:</b></td>
											<td>
												<?=$order_id?> 
												<input type="hidden" name="order_id" value="<?=$order_id?>" readonly="readonly" />
											</td>
										</tr>
										<tr>
											<td><b><?=langtext('amount')?>:</b></td>
											<td>
												Rp. <?=format_number($total)?>
												<input type="hidden" name="amount_total" value="<?=$total?>" readonly="readonly" />
											</td>
										</tr>
										<tr>
											<td><b><?=langtext('date')?>:</b></td>
											<td><input type="text" name="date" id="datepicker" required /></td>	  
										</tr>
										<tr>
											<td><?=langtext('pay_method')?>:</td>
											<td><input type="text" name="payment_method" value="Transfer" readonly="readonly" required /></td>
										</tr>
										<tr>	
											<td><?=langtext('to')?>:	</td>
											<td><?=OBankAccount::drop_down_select("account",$account)?></td>
										</tr>
										<tr>
											<td><?=langtext('amount')?>:</td>
											<td><input name="amount" class="large-field" type="text"></td>
										</tr>
										<tr>
											<td><?=langtext('expl')?>:</td>
											<td> <textarea name="keterangan" rows="2"></textarea></td>
										</tr>
										<tr>
											<td>
												<?=anchor('user/confirm_payment_list', 'Back', array('class' => 'btn btn-default'))?>
												<button class="btn btn-success">Continue</button>
											</td>
										</tr>
									</table>
								</fieldset>
							<?= form_close() ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->