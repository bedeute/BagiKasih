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
									<?=print_error(validation_errors())?>
									<?//print_r($my_data)?>
									<form action="" method="post">
									<fieldset>
										<legend>Your Account</legend>
										<table>
											<tbody>
												<tr>
													<td>Password</td>
													<td>: <input type="password" name="password" required autofocus /></td>
												</tr>
												<tr>
													<td>Confirm Password</td>
													<td>: <input type="password" name="confirm_password" required /></td>
												</tr>
												<tr>
													<td>No. Handphone</td>
													<td>: <input type="text" name="no_handphone" required /></td>
												</tr>
												<tr>
													<td colspan="2" align="center">
														<input type="submit" value="Ok" class="btn btn-success" />
													</td>
												</tr>
											</tbody>
										</table>
									</fieldset>
									</form>
								</div>
								<div class="span6">
									<fieldset>
									<legend>Your Facebook Detail</legend>
										<table class="table">
											<tbody>
												<tr>
													<td rowspan="5">
														<img src="https://graph.facebook.com/<?=$my_data[id]?>/picture?type=normal" class="img-polaroid" />
													</td>
													<td>Name</td>
													<td><b>: <?=$my_data[name]?></b></td>
												</tr>
												<tr>
													<td>Email</td>
													<td><b>: <?=$my_data[email]?></b></td>
												</tr>
												<tr>
													<td>Birthday</td>
													<td><b>: <?=fb_date($my_data[birthday])?></b></td>
												</tr>
												<tr>
													<td>Gender</td>
													<td><b>: <?=$my_data[gender]?></b></td>
												</tr>
												<tr>
													<td>Location</td>
													<td><b>: <?=$my_data[location][name]?></b></td>
												</tr>
											</tbody>
										</table>
									</fieldset>
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