<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="sidebar">
				<?=$this->load->view('user_right_sidebar')?>
			</div>
			<div class="span9">
				<div class="content thumbnail">
					<ul class="breadcrumb">
						<li><?=anchor('/', 'Home')?> <span class="divider">/</span></li>
						<li><?=anchor('user/home', langtext('account'))?> <span class="divider">/</span></li>
						<li class="active"><a><?=anchor('user/confirm_payment_list', langtext('payment_confirmation'))?></a></li>
					</ul>
					<h3 class="text-left"><?=langtext('payment_status')?></h3>
					<div class="row-fluid">
						<?=print_error(validation_errors())?>
						<?=print_success($this->session->flashdata('confirm_msg'))?>
						<?=print_error($this->session->flashdata('msg_pay'))?> 
						<div class="span12">
							<?=form_open('confirm_payment/select_confirm_payment', array('id' => 'select_confirm_payment_form'))?>
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<td class="left">Order #</td>
											<td class="left"><?=langtext('amount')?></td>
											<td class="left"><?=langtext('payment_status')?></td>
											<td class="center"><?=langtext('confirmation')?></td>
										</tr>
									</thead>
									<tbody>
										<?php foreach($list as $r) { ?>
											<tr>
												<td class="left"><?=$r->id?></td>
												<td class="left">Rp. <?=format_number($r->grand_total)?></td>
												<td class="left"><b><?=$r->status?></b></td>
												<? if($r->is_confirm_payment == "0") { ?>
													<td style="text-align:center; vertical-align:middle"><input type="checkbox" name="order_id[]" value="<?=$r->id?>"> <?=langtext('confirmation')?></td>
												<? } else { ?>
													<td style="text-align:center; vertical-align:middle; font-weight:bold;"><?=langtext('notif_conf')?></td>
												<? } ?>
											</tr>        
										<? } ?>
										<tr>
											<td colspan="4">
												<a href="javascript:;" onclick="$('#select_confirm_payment_form').submit();" class="btn btn-success"><span>Confirm</span></a>
											</td>
										</tr>
									</tbody>
								</table>
							<?=form_close()?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end mid-content -->