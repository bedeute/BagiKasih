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
						<li class="active">Assign Supplier List</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<!-- block -->
				<div class="block">
					<div class="navbar navbar-inner block-header">
						<div class="muted pull-left">Assign Supplier List</div>
					</div>
					<div class="block-content collapse in">

					<?=print_error($this->session->flashdata('warning'))?>
					<?=print_success($this->session->flashdata('success'))?>

					<?php
						if($row)
						{
							$dlsproduct = new OProduct();
							$dlsproduct->setup($row);
							
							$supplier_product_size_res = $dlsproduct->get_supplier_product_sizes();
							$supplier_product_size_id_arr = NULL;
							$price = NULL;
							foreach($supplier_product_size_res as $r)
							{ 
								$supplier_product_size_id_arr[] = $r->supplier_product_size_id;
								$price[$r->supplier_product_size_id] = $r->price;				
							}
							unset($dlsproduct);
						}
					?>
							

					<?=form_open()?>
						<table> 
							<tr>
								<td><strong>Select Location</strong>: <?=OLocation::drop_down_select("location_id", $location_id, 'onchange="show_data();" onkeyup="show_data();"', '-Select Location-')?></td>
							</tr>
							<!--<tr>
								<td>
									<span id="supplier_ddl">Select Your Location First.</span>
								</td>
							</tr>-->
							<tr>
								<td>
									<div id="div_tbl_data"></div>
									<input type='hidden' name='val_supp' id='val_supp' value='0'>
								</td>
							</tr>
							<tr>            	
								<td>
									<input type="hidden" id="category_id" value="<?=$category_id?>" name="category_id" />
									<button class="btn btn-success" type="submit">Save</button>                
								</td>
							</tr>
					   </table>
					<?=form_close()?>

					</div>
				</div>
				<!-- /block -->
			</div>
		</div>
	</div>
</div>

   <style type="text/css">.hide { display:none; }</style>
   
<script type="text/javascript">
	$(document).ready(function() {
		$('#supplier_search').change(function() {
			update_table($(this).val());
		});
		$(document).on('keyup','#supplier_search',function() {
			update_table($(this).val());
		});
		$(document).on('click','#supplier_search',function() {
			update_table($(this).val());
		});
	});
	
function update_table(supplier_id)
{
	
  console.log(supplier_id);
  if(supplier_id != 0)
  {
	$('#supplier_ddl span').addClass('hide');
	$('#supplier_ddl span[supplier_id='+supplier_id+']').removeClass('hide');
  }
  else {
	$('#supplier_ddl span').removeClass('hide');
  }
}
	function pilih_location(product_id)
	{
		var location_id = $("#location_id").val();		
		var category_id = $("#category_id").val();
		$.ajax({
			type: "POST",
			url: "<?=base_url("ajax/get_suppliers")?>",
			data: {"location_id":location_id, "category_id":category_id, "product_id":product_id},
			complete: function(resp){				
				var data = resp.responseText;
				var Obj = $.parseJSON(data);
				$("#supplier_ddl").html(Obj.supplier);
			}
		});
	}
	
	function show_data()
	{
		$.post("<?=base_url("ajax/get_suppliers2")?>", { location_id : $("#location_id").val(), category_id : $("#category_id").val(), product_id : '<?=$product_id?>', supplier_id : $('#val_supp').val() }, function(data) { $('#div_tbl_data').html(data); });
		return false;
	}
	
	show_data();
</script>