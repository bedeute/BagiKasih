			<div class="span3">
				<?php if(!$nofilter) { ?>
					<div class="thumbnail">
						<div class="sidebar-title">
							<h4 class="text-center"><?=langtext('shopping_guide')?></h4>
						</div>
						<ul class="nav nav-tabs" id="myTab">
						<?php if(intval($catid) == 0) { ?>
							<?php
								$cats = OCategory::get_list(0,0,"ordering ASC","4");
								
								$i = 1;
								foreach($cats as $cat)
								{	
									$C = new OCategory();
									$C->setup($cat);
									?>
										<li <?=($i == 1 ? 'class="active"' : '' )?>><a data-toggle="tab" href="#sidebar-<?=$cat->id?>"><?=ucfirst($C->get_name())?></a></li>
									<?
									unset($C);
									$i++;
								}
							?>
						<? } ?>
						</ul>

						<div class="tab-content" id="myTabContent">
							<?php $seg = $this->uri->segment(1); ?>                    
                    
							<?php
								$cats = OCategory::get_list(0,0,"ordering ASC","4");
								$i = 1;
								foreach($cats as $cat)
								{
									$C = new OCategory();
									$C->setup($cat);
									if(intval($catid) > 0 && $catid != $cat->id) continue;
									?>
									<div class="tab-pane fade<?=($i == 1 ? '-in active' : '' )?>" id="sidebar-<?=$cat->id?>">
										<?
										$CAT = new OCategory($cat->id);
										if($seg == "categories") $subcats = OSubcategory::get_list(0, 0, "ordering ASC, id DESC",$cat->id);
										else $subcats = OSubcategory::get_list(0, 5, "ordering ASC, id DESC",$cat->id);
										if(!empty($subcats))
										{
											?>
											<h5><?=langtext('category',2)?></h5>
											<ul class="nav nav-pills nav-stacked">
												<?php								
													if(!empty($subcats))
													{
														foreach($subcats as $r)
														{
															$S = new OSubcategory();
															$S->setup($r);
															
															$count_product = $S->get_total_products_based_on_location(get_location());
															?>                                            
															<li>
																<?=anchor($S->get_cat_link($cat->id,$_GET), $r->name." ".($seg == "categories" ? "(".($count_product).")" : ''),($_GET['subcatid'] == $r->id ? array('class' => 'current') : ""))?>
															</li>
															<?
															unset($S);
														}
														
														if($seg != "categories")
														{
														?>
															<li><?=anchor($C->get_link(),langtext('all',1).' '.$C->get_name(), array('class' => 'kec'))?></li>
														<?php
														}
													}
												?>
											</ul>
											<?php
										}
										?>
								
										<?
										$prices = OPriceRange::get_list(0, 5, "ordering ASC, id DESC", $cat->id);
										if(!empty($prices))
										{
											?>
											<h5><?=langtext('price',2)?></h5>
											<ul class="nav nav-pills nav-stacked">
												<?php
													
													if(!empty($prices))
													{
														foreach($prices as $row)
														{
															$OPR = new OPriceRange();
															$OPR->setup($row);
															?><li><?=anchor($OPR->get_cat_link($cat->id,$_GET), $row->name,($_GET['typeid'] == $r->id ? array('class' => 'current') : ""))?></li><?php       
															unset($TP);
														}
														
														if($seg != "categories")
														{
														?>
															<li><?=anchor(OPriceRange::get_cat_all_link($cat->id,$_GET),langtext('all_prices'), array('class' => 'kec'))?></li>
														<?
														}
													}
												?>
											</ul>
											<?php
										}							
										?>                        
												  
										<?/*
										if($seg == "categories") $brands = OBrand::get_list(0, 0, "ordering ASC, id DESC", $cat->id);
										else $brands = OBrand::get_list(0, 5, "ordering ASC, id DESC", $cat->id);
										if(!empty($brands))
										{
											?>
											<h5><?=langtext('brand',2)?></h5>
											<ul class="nav nav-pills nav-stacked">
												<?php
													
													if(!empty($brands))
													{
														foreach($brands as $row)
														{
															$B = new OBrand();
															$B->setup($row);
															?><li><?=anchor($B->get_cat_link($cat->id,$_GET), $row->name,($_GET['brandid'] == $r->id ? array('class' => 'current') : ""))?></li><?php       
															unset($B);
														}
														
														if($seg != "categories")
														{
														?>
															<li><?=anchor(OBrand::get_cat_all_link($cat->id,$_GET),langtext('all_brands'), array('class' => 'kec'))?></li>
														<?
														}
													}
												?>
											</ul>
											<?php
										}
										*/?>
							
										<?
										if($seg == "categories") $types = OTypecategory::get_list(0, 0, "ordering ASC, id DESC", $cat->id);
										else $types = OTypecategory::get_list(0, 5, "ordering ASC, id DESC", $cat->id);
										if(!empty($types))
										{
											?>
											<h5><?=langtext('type',2)?></h5>
											<ul class="nav nav-pills nav-stacked">
												<?php
													
													if(!empty($types))
													{
														foreach($types as $row)
														{
															$TP = new OTypecategory();
															$TP->setup($row);
															?><li><?=anchor($TP->get_cat_link($cat->id,$_GET), $row->name,($_GET['typeid'] == $r->id ? array('class' => 'current') : ""))?></li><?php       
															unset($TP);
														}
														
														if($seg != "categories")
														{
														?>
															<li><?=anchor(OTypecategory::get_cat_all_link($cat->id,$_GET),langtext('all_types'), array('class' => 'kec'))?></li>
														<?
														}
													}
												?>
											</ul>
											<?php
										}							
										?>
							
										<?                        
										if($seg == "categories") $themes = OThemecategory::get_list(0, 0, "ordering ASC, id DESC", $cat->id);
										else $themes = OThemecategory::get_list(0, 5, "ordering ASC, id DESC", $cat->id);
										if(!empty($themes))
										{
											?>
											<h5><?=langtext('theme',2)?></h5>
											<ul class="nav nav-pills nav-stacked">
												<?php
													
													if(!empty($themes))
													{
														foreach($themes as $row)
														{
															$TM = new OThemecategory();
															$TM->setup($row);
															?><li><?=anchor($TM->get_cat_link($cat->id,$_GET), $row->name,($_GET['themeid'] == $row->id ? array('class' => 'current') : ""))?></li><?php       
															unset($TP);
														}
														
														if($seg != "categories")
														{
														?>
															<li><?=anchor(OThemecategory::get_cat_all_link($cat->id,$_GET),langtext('all_themes'), array('class' => 'kec'))?></li>
														<?
														}
													}
												?>
											</ul>
											<?php
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
					<br>
					<?php } ?>
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
									$oP = new OProduct($t->product_id);
									?>
									<blockquote>
										<p style="font-size:14px;"><?=$t->description?></p>
										<small><?=$ou->row->name?>, <cite title="this is for cite"><a style="color:#999999;" href="<?=$oP->get_link()?>"><?=$oP->row->name?></a></cite></small>
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