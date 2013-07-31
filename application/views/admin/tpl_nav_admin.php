<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
			 <span class="icon-bar"></span>
			 <span class="icon-bar"></span>
			</a>
			<a class="brand" href="#">Admin Panel</a>
			<div class="nav-collapse collapse">
				<ul class="nav">
					<li><?=anchor("admin/home", "Home")?></li>
					<li><?=anchor("admin/users", "Users")?></li>
					<li class="dropdown">
						<?=anchor("#", 'LSM <i class="caret"></i>', array('role' => 'button', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))?>
						<ul class="dropdown-menu">
							<li><?=anchor("admin/lsm_list", "LSM List")?></li>
							<li><?=anchor("admin/lsm_category", "LSM Category")?></li>
							<li><?=anchor("admin/lsm_member", "LSM Member")?></li>
							<li><?=anchor("admin/lsm_organizer", "LSM Organizer")?></li>
							<li><?=anchor("admin/lsm_photo", "LSM Photo")?></li>
							<li><?=anchor("admin/lsm_update", "LSM Blog Update")?></li>
						</ul>
					</li>
					<li><?=anchor("admin/suppliers", "Suppliers")?></li>
					<li class="dropdown">
						<?=anchor("#", 'Products <i class="caret"></i>', array('role' => 'button', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'))?>
						<ul class="dropdown-menu">
							<li><?=anchor("admin/categories", "Category")?></li>
							<li><?=anchor("admin/products", "Product List")?></li>
						</ul>
					</li>
					<li><?=anchor("admin/locations", "Location")?></li>
					<li><?=anchor("admin/bank", "Bank")?></li>
					<li><?=anchor("admin/home/logout", "Logout")?></li>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
	</div>
</div>