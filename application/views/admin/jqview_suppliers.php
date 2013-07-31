<?//print_r($atad);?>
	<?php
		if(intval($atad['product_id']) > 0)
		{
			$O = new OProduct($atad['product_id']);
			$supplier = $O->get_supplier_product_sizes(0,0);
			// var_dump($supplier);
			if($supplier)
			{
				$cursuppliers = array();
				foreach($supplier as $r)
				{
					$cursuppliers[$r->supplier_id."_".$r->size_id] = $r;
				}
				unset($os);
			}

		}
		$suppliers = OSupplier::get_list(0, 0, "name ASC", $atad['location_id']);
	if(intval($atad['location_id']) != 0 || $atad['location_id'] != '') {
	?>
		Search by supplier: 
		<select name='supp_search' id='supp_search' onchange="
		$('#val_supp').val($(this).val()); show_data();">
			<option value='0'>All Suppliers</option>";
		<?php foreach($suppliers as $s) { ?>
			<option value="<?=$s->id?>" <?=($atad['supplier_id'] == $s->id ? 'selected' : '')?>><?=$s->name?></option>";
		<? } ?>
		</select>
		<input type='hidden' name='location_id' value='<?=$atad['location_id']?>' />
	<? } ?>
		
		<!-- get all the list for the location -->
	<?php
		$suppliers = OSupplier::get_list(0, 0, "name ASC", $atad['location_id'], $atad['supplier_id']);
		$arr = array();	
		if(sizeof($suppliers) > 0)
		{
	?>
		<table id="tabel" class="table table-bordered table-striped">
			<tr>
				<th width="150px" style="text-align: center">Supplier Name</th>
				<th width="150px" style="text-align: center">Size</th>
				<th width="150px" style="text-align: center">Price</th>
				<th width="150px" style="text-align: center">Primary Supplier</th>
			</tr>
		<?php
			$count = 0;
			foreach($suppliers as $r)
			{   
				$sizes = OSize::get_list(0, 0, "name ASC", $atad['category_id']);
				
				$i = 1;
				foreach($sizes as $row)
				{
					$curs = $cursuppliers[$r->id."_".$row->id];
					if($curs == NULL)
					{
						$curs->price = 0;
						$curs->primary = 0;
						
					}
					?>
						<tr>
							<td><input type='checkbox' value='<?=$r->id?>' name='supplier[<?=$count?>]' id='<?=$id?>_<?=url_title($r->id)?>' <?=($curs->supplier_id == $r->id ? 'checked="checked"' : '')?>> <?=$r->name?></td>
							<input type="hidden" name="size[<?=$count?>]" value="<?=$row->id?>" readonly />
							<td>Size : <?=$row->name?></td>								
							<td><input type="text" name="price[<?=$count?>]" value="<?=$curs->price?>" class="price" /></td>
							<td><input type="checkbox" name="primary_supplier[<?=$count?>]" value="1" <?=($curs->primary == 1 ? 'checked="checked"' : '')?>> Primary Supplier</td>
						</tr>
					<?
					$i++;
					$count++;
				}
			}
		?>
		</table>
	<? } ?>