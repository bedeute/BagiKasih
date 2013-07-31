<?php
	if($row)
	{
		extract(get_object_vars($row));		
	}
	extract($_POST);
?>
<h2>Mark As Shipped Form</h2><br/>

<?=form_open()?>
<?=print_error($this->session->flashdata('warning'))?>
<?=print_success($this->session->flashdata('success'))?>

<?=form_open()?>
    <table class="tbl_form">
    	<tr>
            <th>Date Shipped</th>
            <td><input type="text" name="dt_shipped" id="datepicker" value="<?=$dt_shipped?>" required /> <br/><?=form_error('dt_shipped')?></td>
        </tr>
        <tr>
        	<th>Service Delivery</th>
            <td><input type="text" name="service_delivery" value="<?=$service_delivery?>" required /> <br/><?=form_error('service_delivery')?></td>
        </tr>
        <tr>
        	<th>Tracking</th>
			<td><input type="text" name="tracking" value="<?=$tracking?>" required /> <br/><?=form_error('tracking')?></td>
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

<script>
	$(function() {
		$( "#datepicker" ).datepicker({
					dateFormat: 'yy-mm-dd',
					showOtherMonths: true,
					selectOtherMonths: true,
					changeMonth: true,
					changeYear: true
				});			
	});
</script>
