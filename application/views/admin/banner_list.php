<div class="row-fluid">
	<!--/span-->
	<div class="span12" id="content">
		<div class="row-fluid">
			<div class="navbar">
				<div class="navbar-inner">
					<ul class="breadcrumb">
						<li>
							<a href="#">Home</a> <span class="divider">/</span>	
						</li>
						<li>
							<a href="#">Other</a> <span class="divider">/</span>	
						</li>
						<li class="active">Banners</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Banner List</div>
					</div>
					<div class="block-content collapse in">
						<p><?=anchor($this->curpage."/add", "<i class='icon-plus'></i> Create new",array("class" => "btn"))?></p>
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						<?php if(sizeof($list) <= 0) { ?>
							<p class='text_red'>Banner List is empty.</p>
						<? } else { ?>
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>ID</th>               
										<th>Picture</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>                
								<?php 
									$i=1 + $uri;
									
									foreach($list as $row):
										$ob = new OBanner();
										$ob->setup($row);
										$img_url = $ob->get_photo("200");
										$img = '<img src="'.base_url($img_url).'" alt="'.$ob->row->title.'" width="100" />';	
													
										extract(get_object_vars($row));
								?>		        
									<tr>
										<td><?=$row->id?></td>
										<td><?=anchor($ob->row->url,$img)?><br /> <?=$title?></td>
										<td>						
											<?
											echo anchor($this->curpage."/delete/".$id, "Delete", array("onclick" => "return confirm('Are you sure?');"));
											echo " | ".anchor($this->curpage."/edit/".$id, "Edit");						
											?> 
										</td>       
									</tr>
								<?php				
									$i++;
									endforeach; 
								?>	
								</tbody>
							</table>
							<div class="pagination">
								<?=$pagination?>
							</div>
						<? } ?>
					</div>
				</div>
				<!-- /block -->
			</div>
		</div>
	</div>
</div>