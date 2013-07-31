<?php
	$showform = true;
	$category_id = $_GET['category_id'];
	if($row)
	{	
		extract(get_object_vars($row));
	}
	extract($_POST);
?>
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
						<li>
							<a href="#">Categories</a> <span class="divider">/</span>	
						</li>
						<li class="active">Sub Categories</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Sub Categories Management</div>
					</div>
					<div class="block-content collapse in">

						<?php if($showform) { ?>
							<?=form_open('', array('class' => 'form-horizontal'))?>
								<fieldset>
									<legend>
									<?php if($row) { ?>
										EDIT Sub Categories ( ID : <?=$row->id?> )
									<? } else { ?>
										ADD Sub Categories
									<? } ?>
									</legend>
									<table class="tbl_form">    	
										<tr>
											<th>Name (ID)</th>
											<td> : <input type="text" name="name" value="<?=$name?>" placeholder="Subcategory Name (ID)" autofocus required/> <br/><?=form_error('name')?></td>
										</tr>
										<tr>
											<th>Name (EN)</th>
											<td> : <input type="text" name="name_en" value="<?=$name_en?>" placeholder="Subcategory Name (EN)" required/> <br/><?=form_error('name_en')?></td>
										</tr>
										<tr>
											<th>Category</th>
											<td> : <?php echo OCategory::drop_down_select("category_id",$category_id); ?> <br/><?=form_error('category_id')?></td>
										</tr>
										<tr>         
											<td colspan="2" align="center">
												<br>
												<button class="btn btn-success" type="submit">Save</button>
												<button class="btn btn-default" type="reset">Reset</button>
												
												<?php if($row) { ?>
												<button class="btn btn-default" type="button" onclick="location.href='<?=site_url("admin/subcategories")?>';"><span class="leftarrow icon"></span>Cancel</button>
												<? } ?>
											</td>
										</tr>    
									</table>
								</fieldset>
							<?=form_close()?>
						<? } ?>
						<hr>
						<p>
							<strong>Filter by Category : </strong><?=anchor("admin/subcategories/","All")?> | 
							<?php
							$catlist = OCategory::get_list(0,0);

							$i = 1;
							foreach($catlist as $cat)
							{
								echo anchor("admin/subcategories/?category_id={$cat->id}",$cat->name);
								
								echo ($i == sizeof($catlist) ? '' : " | ");

								$i++;
							}
							?>
						</p>
						<?=print_error($this->session->flashdata('warning'))?>
						<?=print_success($this->session->flashdata('success'))?>
						<?php if(sizeof($list) <= 0) { ?>
							<p class='text_red'>Subcategory List is empty.</p>
						<? } else { ?>
							<form>
								<div class="input-append">
									<input class="span9" id="appendedInputButtons" type="text" name="keyword" value="<?=$_GET["keyword"]?>" placeholder="Type Keywords..." />
									<button class="btn" type="submit">Search</button>
									<?=anchor('admin/subcategories','Refresh', array('class' => 'btn'))?>
								</div>
							</form>
							<table class="table table-bordered table-striped tbl_list">
								<thead>
									<tr>
										<th>No.</th>        	
										<th>Name (ID)</th>
										<th>Name (EN)</th>
										<th>Category</th>
										<th>Action</th>                             
									</tr>
								</thead>
								<tbody>
								<?php 
									$i=1 + $uri;
									$ob = new OSubcategory();
									foreach($list as $row):
										$ob->setup($row);
									?>
									<tr>
										<td><?=$i?></td>            
										<td><?=$row->name?></td>
										<td><?=$row->name_en?></td>
										<td><?=$ob->get_category()->name?></td>
										<td>
											
											<?=anchor($this->curpage.'/delete/'.$row->id, 'delete', array("onclick" => "return confirm('Are you sure?');"))?> |
											<?=anchor($this->curpage.'/listing/'.$row->id, 'edit')?>
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