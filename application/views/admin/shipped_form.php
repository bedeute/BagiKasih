<?=form_open("admin/orders/mark_as_shipped/$id")?>
<table class="tbl_form">
	<tr>
    	<th>Date Shipped</th><td>:</td>
        <td><input name="shipped_date" type="date" class="text datepicker" value="" required autofocus /></td>
    </tr>    
	<tr>
    	<th>Note</th><td>:</td>
        <td><textarea name="shipped_note" cols="40" rows="3"></textarea></td>        
    </tr>    
    <tr>
    	<td></td><td></td>
        <td><input type="submit" value="submit"/></td>
    </tr>
</table>
<?=form_close()?>