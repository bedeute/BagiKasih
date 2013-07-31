<?=form_open("admin/orders/shipped/$id")?>
    <table class="tbl_form">
        <tr>
            <th>Note</th><td>:</td>
            <td><textarea name="shipped_note" cols="50" rows="15"><?=$shipped_note?></textarea></td>
        </tr>    
        <tr>
            <td></td><td></td>
            <td><input type="submit" value="submit"/></td>
        </tr>
    </table>
<?=form_close()?>