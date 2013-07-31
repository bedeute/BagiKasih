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
							<a href="#">Products</a> <span class="divider">/</span>	
						</li>
						<li class="active">Add Ons</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Add On List</div>
					</div>
					<div class="block-content collapse in">
						<p><?=anchor($this->curpage."/add", "<i class='icon-plus'></i> Create new",array("class" => "btn"))?></p>
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						<?php if(sizeof($list) <= 0) { ?>
							<p class='text_red'>Add On List is empty.</p>
						<? } else { ?>
							<form>
								<div class="input-append">
									<input class="span9" id="appendedInputButtons" type="text" name="keyword" value="<?=$_GET["keyword"]?>" placeholder="Type Keywords..." />
									<button class="btn" type="submit">Search</button>
									<?=anchor('admin/add_ons','Refresh', array('class' => 'btn'))?>
								</div>
							</form>
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>No.</th>        	
										<th>Name</th>                    
										<th>Image</th>                    
										<th>Action</th>
									</tr>
								</thead>
								<tbody>                
								<?php 
									$i=1 + $uri;
									
									foreach($list as $row):
										$OA = new OAddOn();
										$OA->setup($row);
										$img_url = $OA->get_photo("200xcrops");
										$img = '<img src="'.base_url($img_url).'" class="img-polaroid" alt="'.$OA->row->name.'" width="80" />';	
													
									extract(get_object_vars($row));
								?>		        
									<tr>
										<td><?=$i?></td>            
										<td><?=$row->name?></td>
										<td><?=$img?></td>                    
										<td>						
											<?=anchor($this->curpage."/delete/".$id, "Delete", array("onclick" => "return confirm('Are you sure?');"))?>
											<?=" | ".anchor($this->curpage."/edit/".$id, "Edit")?> 
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