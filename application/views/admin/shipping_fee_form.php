<?=form_open("admin/orders/assign_shipping_fee/$id")?>
    <table class="tbl_form">
        <tr>
            <th>Shipping Fee</th><td>:</td>
            <td><input type="text" name="shipping_fee" /></td>        
        </tr>    
        <tr>
            <td></td><td></td>
            <td><input type="submit" value="submit"/></td>
        </tr>
    </table>
<?=form_close()?>