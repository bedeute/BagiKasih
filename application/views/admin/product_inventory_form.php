<?php
	if($row)
	{
		extract(get_object_vars($row));	 
	}
	extract($_POST);
?>

<h2>Product Inventory Form</h2><br/>
<?=print_error($this->session->flashdata('warning'))?>
<?=print_success($this->session->flashdata('success'))?>

<p>Product Name: <strong><?=$dlsproduct->row->name?></strong><br />
Current Qty: <strong><?=$dlsproduct->row->qty?></strong></p>
<?=form_open()?>
	<table class="tbl_form">    	
        <tr>
			<th>Operator</th>
			<td><?=dropdown("type",array("add" => "(+) Add", "substract" => "(-) Substract"),$operator,"required","-- Choose --")?> <br/><?=form_error('type')?></td>
		</tr>		
        <tr>
			<th>Quantity</th>
			<td><input type="number" name="qty" id="qty" value="<?=$qty?>" required /> <br/><?=form_error('qty')?></td>
		</tr>
        <tr>
			<th>Description</th>
			<td><textarea name="notes" cols="80" rows="8"><?=$notes?></textarea> <br/><?=form_error('notes')?></td>
		</tr>
		<tr>
			<td></td>
			<td>
            	<button class="positive button" type="submit"><span class="check icon"></span>Save</button>
                <button class="button" type="reset"><span class="reload icon"></span>Reset</button><br />
                <button class="negative button" type="button" onclick="location.href='<?=site_url($this->curpage)?>';"><span class="leftarrow icon"></span>Cancel</button>
			</td>
		</tr>    
	</table>    
<?=form_close()?>

<style type="text/css" >
.full { width:98%; }
.error{color:#FF0000}
</style>