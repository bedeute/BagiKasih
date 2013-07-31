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
							<a href="#">Users</a> <span class="divider">/</span>	
						</li>
						<li class="active">Admin Emails</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Admin Emails</div>
					</div>
					<div class="block-content collapse in">
						<p><?=anchor($this->curpage."/add", "<i class='icon-plus'></i> Create new",array("class" => "btn"))?></p>
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						<?php if(sizeof($list) <= 0) { ?>
							<p class='text_red'>Admin Emails is empty.</p>
						<? } else { ?>
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>No</th>
										<th>Email</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									
								<?php 
									$i=1 + $uri;
									foreach($list as $row):				
										extract(get_object_vars($row));
										$O = new OAdminEmail();                        
										$O->setup($row); 
								?>
									<tr>
										<td><?=$i?></td>
										<td><?=$email?></td>
										<td>
											<?=anchor($this->curpage."/edit/".$id, "Edit",array('title' => 'Edit'))?>
											<?=" | "?> 
											<?=anchor($this->curpage."/delete/".$id, "Delete", array("onclick" => "return confirm('Are you sure?');", "title" => "Delete"))?>
										</td>
									</tr>		
							<?php 
								unset($O);
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