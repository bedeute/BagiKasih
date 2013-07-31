<?=form_open("supplier/shipped/$id")?>
    <table class="tbl_form">
    	
        <tr>
            <th>Shipping Note</th><td>:</td>
            <td><textarea name="shipping_note" cols="50" rows="15"><?=$shipping_note?></textarea></td>
        </tr>
        <tr>
            <th>Shipping Date</th><td>:</td>
            <td><input type="text" name="shipping_date" id="datepicker" /></td>
        </tr>    
        <tr>
            <td></td><td></td>
            <td><input type="submit" value="submit"/></td>
        </tr>
    </table>
<?=form_close()?>

<script>
	$(function() {
		$( "#datepicker" ).datepicker({
					dateFormat: 'yy-mm-dd'
				});			
	});
</script>