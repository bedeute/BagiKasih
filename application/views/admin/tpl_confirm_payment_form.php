<?=form_open("admin/orders/confirm_payment/$id")?>
    <table class="tbl_form">
        <tr>
            <th>Note</th><td>:</td>
            <td><textarea name="confirm_payment_note" cols="50" rows="15"><?=$confirm_payment_note?></textarea></td>
        </tr>    
        <tr>
            <td></td><td></td>
            <td><input type="submit" value="submit"/></td>
        </tr>
    </table>
<?=form_close()?>