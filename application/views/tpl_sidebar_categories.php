			<div class="span3">
					<div class="thumbnail">
						<div class="sidebar-title">
							<h4 class="text-center">
								<?
									$co		= new OCategory($P->row->category_id);
									$kat	= array('cake', 'flower', 'parcel', 'gifts');
									if(in_array($this->uri->segment(3),$kat)) {
								?>
									<?= ucwords($this->uri->segment(3)) ?>
								<?
									} else if(in_array($co->row->url_title, $kat)) {
								?>
									<?= $co->row->name ?>
								<? } else { ?>
									<?=langtext('shopping_guide')?>
								<? } ?>
							</h4>
						</div>

						<div class="tab-content" id="myTabContent">
							<?php
							if(!isset($fullproducts)) {
								$oc = new OCategory($C->row->name,"urltitle");
								if($oc->row->id == "1" || $oc->row->id == "2" || $oc->row->id == "3")
								{
									$fullproducts = OProduct::get_filtered_list(get_location(),$_GET['brandid'],$oc->row->id,$_GET['subcatid'],$_GET['sizeid'],$_GET['topicid'],"p.views DESC",0,0, "", $_GET['typeid'], $_GET['themeid'],$_GET['pricerangeid']);
								}
								else
								{
									$fullproducts = OProduct::get_filtered_list_for_gift($_GET['brandid'],$oc->row->id,$_GET['subcatid'],$_GET['sizeid'],$_GET['topicid'],"p.views DESC",0,0, "", $_GET['typeid'], $_GET['themeid'],$_GET['pricerangeid']);	
								}
							}
							
							$catid = (isset($catid)) ? $catid : $P->row->category_id ;
							$this->load->library('OListGenerator');
							$olg = new OListGenerator($catid);
							$olg->process_products($fullproducts);
							$categories = $olg->get_subcategories();
							$brands = $olg->get_brands();
							$types = $olg->get_types();
							$themes = $olg->get_themes();
							$price_ranges = $olg->get_price_ranges();
							if($this->uri->segment(1) != "categories"){
								$base = "categories/details/".$co->row->url_title;
							} else {
								$base = @implode("/",$this->uri->segment_array());
							}
							$gets = $_GET;
							?>
							<h5><?=langtext('category',2)?></h5>
							<ul class="nav nav-pills nav-stacked">
								<?php 
								if(!isset($_GET['subcatid']))
								{
									foreach($categories as $cat)
									{
										$tmpgets = $gets;
										unset($tmpgets['subcatid']);
										$tmpgets['subcatid'] = $cat['id'];
										
										?>
										<li><?php echo anchor($base."?".http_build_query($tmpgets),$cat['name']." <span class='label label-success' style='float:right;'>".$cat['total']."<span> ", ($_GET['subcatid'] == $cat['id'] ? array('style' => 'font-weight:bold;') : array('style' => ''))); ?></a></li>
										<?
									}
								}
								else
								{
									foreach($categories as $cat)
									{
										if($cat['id'] != $_GET['subcatid']) continue;
										$tmpgets = $gets;
										unset($tmpgets['subcatid']);
										$tmpgets['subcatid'] = $cat['id'];
										?>
										<li><?php echo anchor($base."?".http_build_query($tmpgets),$cat['name']." <span class='label label-success' style='float:right;'>".$cat['total']."</span> ", ($_GET['subcatid'] == $cat['id'] ? array('style' => 'font-weight:bold;') : '')); ?></a></li>
										<?
									}
									$tmpgets = $gets;
									unset($tmpgets['subcatid']);
									?>
									<li><?php echo anchor($base."?".http_build_query($tmpgets),"Clear Filter"); ?></a></li>
									<?php
								}
								?>
							</ul>
							<!--
							<h5><?=langtext('brand',2)?></h5>
							<ul class="nav nav-pills nav-stacked">
								<?php
								if(!isset($_GET['brandid']))
								{
									foreach($brands as $cat)
									{
										$tmpgets = $gets;
										unset($tmpgets['brandid']);
										$tmpgets['brandid'] = $cat['id'];
										?>
										<li><?php echo anchor($base."?".http_build_query($tmpgets),$cat['name']." <span class='label label-success' style='float:right;'>".$cat['total']."</span> ", ($_GET['brandid'] == $cat['id'] ? array('style' => 'font-weight:bold;') : '')); ?></a></li>
										<?
									}
								}
								else
								{
									foreach($brands as $cat)
									{
										
										if($cat['id'] != $_GET['brandid']) continue;
										$tmpgets = $gets;
										unset($tmpgets['brandid']);
										$tmpgets['brandid'] = $cat['id'];
										?>
										<li><?php echo anchor($base."?".http_build_query($tmpgets),$cat['name']." <span class='label label-success' style='float:right;'>".$cat['total']."</span> ", ($_GET['brandid'] == $cat['id'] ? array('style' => 'font-weight:bold;') : '')); ?></a></li>
										<?
									}
									$tmpgets = $gets;
									unset($tmpgets['brandid']);
									?>
									<li><?php echo anchor($base."?".http_build_query($tmpgets),"Clear Filter"); ?></a></li>
									<?php
								}
								?>
							</ul>
							-->
							<h5><?=langtext('type',2)?></h5>
							<ul class="nav nav-pills nav-stacked">
								<?php
								if(!isset($_GET['typeid']))
								{
									foreach($types as $cat)
									{
										$tmpgets = $gets;
										unset($tmpgets['typeid']);
										$tmpgets['typeid'] = $cat['id'];
										?>
										<li><?php echo anchor($base."?".http_build_query($tmpgets),$cat['name']." <span class='label label-success' style='float:right;'>".$cat['total']."</span> ", ($_GET['typeid'] == $cat['id'] ? array('style' => 'font-weight:bold;') : '')); ?></a></li>
										<?
									}
								}
								else
								{
									foreach($types as $cat)
									{
										
										if($cat['id'] != $_GET['typeid']) continue;
										$tmpgets = $gets;
										unset($tmpgets['typeid']);
										$tmpgets['typeid'] = $cat['id'];
										?>
										<li><?php echo anchor($base."?".http_build_query($tmpgets),$cat['name']." <span class='label label-success' style='float:right;'>".$cat['total']."</span> ", ($_GET['typeid'] == $cat['id'] ? array('style' => 'font-weight:bold;') : '')); ?></a></li>
										<?
									}
									$tmpgets = $gets;
									unset($tmpgets['typeid']);
									?>
									<li><?php echo anchor($base."?".http_build_query($tmpgets),"Clear Filter"); ?></a></li>
									<?php
								}
								?>
							</ul>
							<h5><?=langtext('theme',2)?></h5>
							<ul class="nav nav-pills nav-stacked">
								<?php
								if(!isset($_GET['themeid']))
								{
									foreach($themes as $cat)
									{
										$tmpgets = $gets;
										unset($tmpgets['themeid']);
										$tmpgets['themeid'] = $cat['id'];
										?>
										<li><?php echo anchor($base."?".http_build_query($tmpgets),$cat['name']." <span class='label label-success' style='float:right;'>".$cat['total']."</span> ", ($_GET['themeid'] == $cat['id'] ? array('style' => 'font-weight:bold;') : '')); ?></a></li>
										<?
									}
								}
								else
								{
									foreach($themes as $cat)
									{
										
										if($cat['id'] != $_GET['themeid']) continue;
										$tmpgets = $gets;
										unset($tmpgets['themeid']);
										$tmpgets['themeid'] = $cat['id'];
										?>
										<li><?php echo anchor($base."?".http_build_query($tmpgets),$cat['name']." <span class='label label-success' style='float:right;'>".$cat['total']."</span> ", ($_GET['themeid'] == $cat['id'] ? array('style' => 'font-weight:bold;') : '')); ?></a></li>
										<?
									}
									$tmpgets = $gets;
									unset($tmpgets['themeid']);
									?>
									<li><?php echo anchor($base."?".http_build_query($tmpgets),"Clear Filter"); ?></a></li>
									<?php
								}
								?>
							</ul>
							<h5><?=langtext('price',2)?></h5>
							<ul class="nav nav-pills nav-stacked">
								<?php
								if(!isset($_GET['pricerangeid']))
								{
									foreach($price_ranges as $cat)
									{
										$tmpgets = $gets;
										unset($tmpgets['pricerangeid']);
										$tmpgets['pricerangeid'] = $cat['id'];
										?>
										<li><?php echo anchor($base."?".http_build_query($tmpgets),$cat['name']." <span class='label label-success' style='float:right;'>".$cat['total']."</span> ", ($_GET['pricerangeid'] == $cat['id'] ? array('style' => 'font-weight:bold;') : '')); ?></a></li>
										<?
									}
								}
								else
								{
									foreach($price_ranges as $cat)
									{
										
										if($cat['id'] != $_GET['pricerangeid']) continue;
										$tmpgets = $gets;
										unset($tmpgets['pricerangeid']);
										$tmpgets['pricerangeid'] = $cat['id'];
										?>
										<li><?php echo anchor($base."?".http_build_query($tmpgets),$cat['name']." <span class='label label-success' style='float:right;'>".$cat['total']."</span> ", ($_GET['pricerangeid'] == $cat['id'] ? array('style' => 'font-weight:bold;') : '')); ?></a></li>
										<?
									}
									$tmpgets = $gets;
									unset($tmpgets['pricerangeid']);
									?>
									<li><?php echo anchor($base."?".http_build_query($tmpgets),"Clear Filter"); ?></a></li>
									<?php
								}
								?>
							</ul>
						</div>
					</div>
					<br>
					<div class="thumbnail">
						<?= $this->load->view('tpl_fb_connect_sidebar'); ?>
					</div>
					<br>
					<div class="thumbnail">
						<div class="sidebar-title">
							<h4 class="text-center"><?=langtext('what_they_say')?></h4>
						</div>
						<?php
							$testimonials = OProductReview::get_list(0, 5, "id DESC", "", "published");
							if(empty($testimonials)) 
							{ 
							?>
								<blockquote>
									<p>No Testimonials Found.</p>
								</blockquote>
							<?
							}
							else
							{
								foreach($testimonials as $t)
								{
									$ou = new OUser($t->user_id);
									?>
									<blockquote>
										<p><?=$t->description?></p>
										<small><?=$ou->row->name?>, <cite title="this is for cite">Member# <?=$t->user_id?></cite></small>
									</blockquote>
									<?
									unset($ou);
								}
							}
						?>
						<!--<div class="clearfix">
							<a class="pull-right" style="margin-right: 20px;" href="#">See all</a>
						</div>--> 
					</div>
				</div>
				<script>
					$(document).ready(function() {
						$('.nav-pills,.nav-stacked').hideMaxListItems({ 'max':3, 'speed':500 });
					});
				</script>