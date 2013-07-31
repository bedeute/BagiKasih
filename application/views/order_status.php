<!-- mid-content -->
<div class="mid-content">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div class="content">
					<ul class="breadcrumb">
						<li><?=anchor('/', 'Home')?> <span class="divider">/</span></li>
						<li class="active"><a><?=anchor('order_status', langtext('order_status'))?></a></li>
					</ul>
					<h3 class="text-left"><?=langtext('order_status')?></h3>
					<div class="row-fluid">
						<div class="span12">
							<div class="row-fluid">
								<div class="span12">
									<?=print_error($this->session->flashdata('no_rec'))?>
									<?=form_open('order_status/invoice', array('id' => 'order_status'))?>
										<fieldset>
											<legend><?=langtext('enter_detail')?></legend>
											<table>
												<tbody>
													<tr>
														<td><b>* Invoice ID</b></td>
														<td>: <input type="text" name="order_id" placeholder="Invoice ID" required /></td>
													</tr>
													<tr>
														<td>* E-mail</td>
														<td>: <input type="email" name="email" placeholder="Email" required /></td>
													</tr>
													<tr>
														<td>
															<button type="submit" class="btn btn-success">Submit</submit>
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