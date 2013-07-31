<?=form_open("admin/refunds/set_done/$id")?>
    <table class="tbl_form">
        <tr>
            <th>Note</th><td>:</td>
            <td><textarea name="note" cols="50" rows="15"><?=$note?></textarea></td>
        </tr>    
        <tr>
            <td></td><td></td>
            <td><input type="submit" value="submit"/></td>
        </tr>
    </table>
<?=form_close()?>