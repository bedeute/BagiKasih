<!DOCTYPE html>
<html>
	<head>
		<title>Admin Login</title>
		<!-- Bootstrap -->
		<link href="<?=base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet" media="screen">
		<link href="<?=base_url('assets/css/bootstrap-responsive.min.css')?>" rel="stylesheet" media="screen">
		<link href="<?=base_url('assets/admin-styles.css')?>" rel="stylesheet" media="screen">
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<script src="vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
	</head>
	<body id="login">
		<div class="container">
			<?php if(sizeof($_POST) > 0) extract($_POST); ?>
			<?=form_open('', array('class' => 'form-signin'))?>
				<h2 class="form-signin-heading">Login Form</h2>
				<?php echo print_error(validation_errors()); ?>
				<?php echo print_error($this->session->flashdata('warning')); ?>
				<input type="text" name="username" class="input-block-level" placeholder="Username" value="<?=$username?>" autofocus />
				<input type="password" name="password" class="input-block-level" placeholder="Password" value="<?=$password?>" />
				<center>
					<button class="btn btn-default" type="submit">
						<i class="icon-lock"></i> Log in
					</button>
				</center>
			<?=form_close()?>
		</div> <!-- /container -->
		<script src="vendors/jquery-1.9.1.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>