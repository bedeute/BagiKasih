<?php
	$langu = $this->session->userdata("lang");
	
	$location = $this->session->userdata('location');	
	$set_location = $this->session->userdata('set_location');
	$ol = new OLocation();
	$city_default = $ol->get_default()->id;
	if($location == "" && $set_location == "")
	{
		$this->session->set_userdata('set_location', 'true');
		set_location($city_default);
	}
	unset($ol);
	
	$cu = get_logged_in_user();
	$cus = get_logged_in_supplier();
?>

<?php /*?><!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en" xmlns:fb="http://www.facebook.com/2008/fbml"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" xmlns:fb="http://www.facebook.com/2008/fbml"> <!--<![endif]--><?php */?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Happy Kado">
<meta name="keyword" content="">
<title>Happy Kado</title>
<!-- style -->
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/bootstrap.css')?>">
<link rel="stylesheet" type="text/css" href="<?=base_url('assets/style.css')?>">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/humanity/jquery-ui.css" />
<script src="<?=base_url('assets/js/jquery.js')?>"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script src="<?=base_url('assets/js/jquery.raty.min.js')?>"></script>
<!-- end style -->

<!-- google webfont -->
<link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700' rel='stylesheet' type='text/css'>

<?php
	$uori = array('about_us', 'account', 'account_supplier', 'ajax', 'blog' , 'brands', 'cart', 'categories', 'confirm_payment', 'contact_us', 'facebook_connect', 'faq', 'links', 'locations', 'newsletters', 'order_status', 'products', 'search', 'shipping_info', 'site_map', 'supplier', 'term_and_conditions', 'user', 'user_review');
	if(in_array($this->uri->segment(1), $uori)) {
?>
<style>
	.header-wrap {
		height:124px;
	}
</style>
<? } ?>

<style>
	.breadcrumb {
		margin-top:-15px; margin-bottom:5px;
	}
</style>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-41912288-1']);

  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>
<body>

<div id="fb-root"></div>
<script>
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/id_ID/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>

	<!-- top section -->
<div class="top-section">
	<div class="container">
		<div id="fup" class="row-fluid" style="display:none;">
			<div class="span12">
				<?php
				$locations = OLocation::get_list(0, 5, "ordering ASC, id DESC");
				if(sizeof($locations) > 0)
				{
					foreach($locations as $r)
					{
						echo anchor(base_url('locations/details/'.$r->url_title), $r->name, array('style' => 'color:#ffffff;'))."&emsp;";
					}
				}
				?>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span7">
				<?=langtext('change_location')?>?				
				<?php
					$locid = get_location();
					$ol = new OLocation($locid);
				?>
				<button id="loc1" class="btn btn-warning"><?=$ol->row->name?></button>
				
				<form method="post" action="<?=base_url('newsletters/add')?>" id="newsmail" class="navbar-form pull-right">
					<div class="input-append" style="margin:0;">
						<input class="span8" id="appendedInputButton" name="newsletter_email" type="email" placeholder="Newsletter E-mail">
						<button class="btn" type="button" onclick="$('#newsmail').submit();">Daftar</button>
					</div>
				</form>
			</div>
			<div class="span5">
				<ul class="inline pull-right" style="margin:5px 0;">
					<li>
						<?=anchor('order_status', langtext('check_order_status'))?>
					</li> |
					<li>
						<?=anchor('confirm_payment/send_confirm_payment', langtext('payment_confirmation'))?>
					</li> | 
					<li>
						<?=langtext('language')?>: 
						<a href="<?=base_url('home/set_lang/id')?>">
							<img src="<?=base_url('assets/img/flag_indonesia.png')?>">
						</a>
						<a href="<?=base_url('home/set_lang/en')?>">
							<img src="<?=base_url('assets/img/flag_uk.png')?>">
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- end top section -->

<!-- header -->
<div class="header-wrap">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<div class="span6">
					<a href="<?=base_url()?>">
						<img class="logo" src="<?=base_url('assets/img/happy_kado_logo.png')?>">
					</a>
				</div>
				<div class="span6">
					<div class="visitor-control pull-right">
						<?php
						if($cu)
						{
							echo '<em><strong>'.anchor('user/home', ucfirst($cu->name));
							echo " | ";
							echo anchor('account/logout', 'log out').'</strong></em><br>';
						}
						else if($cus)
						{
							echo 'Supplier <em><strong>'.anchor('supplier/home', ucfirst($cus->name));
							echo " | ";
							echo anchor('account_supplier/logout', 'log out').'</strong></em><br>';
						}
						else 
						{
						?>
						Hi, <em><strong>( <a href="#" id="popover" data-placement="bottom">Login</a>
				        <div id="popover-head" class="hide">Sign in</div>
				        <div id="popover-content" class="hide">
							<form action="<?=base_url('account/login')?>" class="form-horizontal" method="post">
								<label>Email</label>
									<input type="email" name="email" placeholder="Email" autofocus required>
								<label>Password</label>
									<input type="password" name="password" placeholder="Password" autofocus required>
								<label class="checkbox">
									<input type="checkbox"> Remember me
								</label>
								<button type="submit" class="btn btn-success">Sign in</button>
								or <button type="button" class="btn btn-primary">via Facebook</button>
							</form>
				        </div>
						)</em></strong> <?=langtext('or')?> <em><strong><a href="#" id="pupover" data-toggle="popover" data-placement="bottom"><?=langtext('create_account')?></a></strong></em><br>
						<div id="pupover-head" class="hide">Sign up</div>
				        <div id="pupover-content" class="hide">
							<form action="<?=base_url('account/register')?>" class="form-horizontal" method="post">
								<label>Nama Lengkap</label>
									<input type="text" name="name" placeholder="Nama Lengkap" />
								<label>E-mail</label>
									<input type="text" name="email" placeholder="Email" />
								<label>Password</label>
									<input type="password" name="password" placeholder="Password" />
								<label>Confirm Password</label>
									<input type="password" name="confirm_password" placeholder="Confirm Password" />
									<label></label>
				                <label><button type="submit" class="btn btn-success">Sign up</button></label>
							</form>
				        </div>
						<? } ?>
						<i class="icon-shopping-cart icon-white"></i>
						<?=langtext('shopping_cart')?>: <?=anchor('cart/user_cart', sizeof($this->cart->contents()))?> item<br>
						<?php if(!is_object($cu)) { ?>
						<div class="header-socmed">
							<a href="https://graph.facebook.com/oauth/authorize?client_id=472690359469850&redirect_uri=<?=site_url("account/facebook")?>&scope=email,user_birthday,user_location,friends_birthday">
								<img src="<?=base_url('assets/img/cwFacebookIcon.png')?>">
							</a>
						</div>
						<? } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="navbar navbar-inverse2">
			<div class="navbar-inner">
				<ul class="nav">
					<?php
						$cats = OCategory::get_list(0,0,"ordering ASC");            
						
						foreach($cats as $cat)
						{
							$C = new OCategory();
							$C->setup($cat);

							$this->load->library('OListGenerator');
							$olg = new OListGenerator($cat->id);
							$olg->process_products2();
							$base = "categories/details/".$cat->url_title;
					?>
					<li class="dropdown">
						<?=anchor($C->get_link(), strtoupper($C->get_name()). '<b class="caret"></b>', array("class" => "dropdown-toggle"))?>
						<ul class="dropdown-menu">
							<?php if(count($olg->get_subcategories2()) > 0) { ?>
								<?php
									foreach($olg->get_subcategories2() as $cat)
									{
										?>
										<li><?php echo anchor($base."?subcatid=".$cat[id],$cat[name]); ?></li>
										<?
									}
								?>
							<? } ?>
						</ul>
					</li>
					<?
						unset($C);
						}
					?>
					<li class="dropdown">
						<a href="http://blog.happykado.com">BLOG</a>
					</li>
				</ul>
				<form action="<?=base_url('search')?>" class="form-search navbar-form pull-right">
					<div class="input-append">
						<input type="text" name="s" class="span2 search-query" placeholder="Search...">
						<button type="submit" class="btn">Go!</button>
					</div>
				</form>
			</div>
		</div>
		<?=print_error($this->session->flashdata('newsletter_warning'))?>
		<?=print_success($this->session->flashdata('newsletter_success'))?>
		<?=print_error(validation_errors())?>
		<?php if($this->uri->segment(1) == 'home' || $this->uri->segment(1) == '') { ?>
		<div class="row-fluid">
			<div class="thumbnail span3">
				<div class="sidebar-title">
					<h4 class="text-center"><?=langtext('rank')?></h4>
				</div>
				<ul class="nav nav-tabs" id="myTab">
					<?php
						$cats = OCategory::get_list(0,0,"ordering ASC","4");
						$i = 1;
						foreach($cats as $cat)
						{	
							$C = new OCategory();
							$C->setup($cat);
							
							?><li <?=($i == 1 ? 'class="active"' : '' )?>><a data-toggle="tab" href="#tab<?=$cat->id?>"><?=ucfirst($C->get_name())?></a></li><?
								
							unset($C);
							$i++;
						}
					?>
				</ul>

				<div class="tab-content" id="myTabContent">
					<?php
						$cats = OCategory::get_list(0,0,"ordering ASC","4");
						$i = 1;
						foreach($cats as $cat)
						{	
							$C = new OCategory();
							$C->setup($cat);
							
							?>
							<div class="tab-pane fade<?=($i == 1 ? '-in active' : '' )?>" id="tab<?=$cat->id?>">
									<?php
									$top_views = OProduct::get_filtered_list(get_location(), "", $cat->id, "", "", "",$orderby = "p.views ASC",0 , 3, "");
									
									if(empty($top_views)) echo '<br><div class="span8"><p>No Top Views.</p></div>';
									else 
									{
										foreach($top_views as $tv)
										{
											$P = new OProduct();
											$P->setup($tv);
											
											$img_def = '<img src="'.base_url('assets/img/no_image.png').'" width="50px" height="50px" alt="default_image"  />';
											?>
												<div class="row-fluid rank-content">
													<a href="<?=$P->get_link()?>">
												<?
													if($P->get_photo())
													{
												?>
													<div class="span4">
														<img class="img-polaroid" src="<?=base_url($P->get_photo())?>" width="60" height="60" alt="<?=$tv->name?>" />
													</div>
												<?	
												}
													else echo $img_def;
												?>
													<div class="span8">
														<p style="margin-top:6px; margin-left:13px;"><?=(strlen($tv->name) > 15) ? substr($tv->name, 0, 15).'...' : $tv->name ;?></p>
														&emsp;<span>Rp. <?=format_number($P->get_lowest_price())?></span>
													</div>
													</a>
												</div><br>
											<?
											unset($P);
										}
									}
									?>
								</div>
							<?	
							unset($C);
							$i++;
						}
					?>
				</div>
			</div>
			<div class="span9 showcase">
				<div class="span9-2 showcase-carousel" style="height:380px;">
				<!-- carousel -->
				<div id="myCarousel" class="carousel slide">
				<ol class="carousel-indicators">
					<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
					<li data-target="#myCarousel" data-slide-to="1"></li>
					<li data-target="#myCarousel" data-slide-to="2"></li>
				</ol>
				<!-- Carousel items -->
				<div class="carousel-inner">
				<?php
					$banners = OBanner::get_list(0, 5, "ordering ASC, id DESC");
					$i = 1;
					if(emptyres($banners))
					{
						foreach($banners as $r)
						{
							$B = new OBanner();
							$B->setup($r);
							
							$img_url = $B->get_photo('600');
							$img = '<img src="'.$img_url.'" alt="'.$r->title.'" width="690" />';
							?>
							<div class="<?=($i == 1 ? 'active' : '' )?> item">
								<?=$img;?>
							</div>
							<?
							unset($B);
							$i++;
						}
					}
				?>
				</div>
				<!-- Carousel nav -->
				<!--<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
				<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>-->
				</div>
				</div>
			</div>
		</div>
		<br>
		<? } ?>
	</div>
</div> <!-- end header -->
<br>