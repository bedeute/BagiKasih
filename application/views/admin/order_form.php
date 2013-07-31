<?php
if($row)
{
	extract(get_object_vars($row));	
}
extract($_POST);
?>

<h2>Order Form</h2><br/>

<?=form_open()?>
<table class="tbl_form">    	
    <tr>
        <th>Name</th>
        <td><input type="text" name="name" value="<?=$name?>"/> <br/><?=form_error('name')?></td>
    </tr>
    <tr>
        <th>Address 1</th>
        <td><textarea name="address_1" cols="5" rows="20"><?=$address_1?></textarea> <br/><?=form_error('address_1')?></td>
    </tr>
    <tr>
        <th>Address 2</th>
        <td><textarea name="address_2" cols="5" rows="20"><?=$address_2?></textarea> <br/><?=form_error('address_2')?></td>
    </tr>
    <tr>
        <th>City</th>
        <td><input type="text" name="city" value="<?=$city?>"/> <br/><?=form_error('city')?></td>
    </tr>
    <tr>
        <th>State</th>
        <td><input type="text" name="state" value="<?=$state?>"/> <br/><?=form_error('state')?></td>
    </tr>
    <tr>
        <th>Country</th>
        <td><input type="text" name="country" value="<?=$country?>"/> <br/><?=form_error('country')?></td>
    </tr>
    <tr>
        <th>Zip Code</th>
        <td><input type="text" name="zip_code" value="<?=$zip_code?>"/> <br/><?=form_error('zip_code')?></td>
    </tr>
    <tr>
        <th>Phone Number</th>
        <td><input type="text" name="phone_number" value="<?=$phone_number?>"/> <br/><?=form_error('phone_number')?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><input type="text" name="email" value="<?=$email?>"/> <br/><?=form_error('email')?></td>
    </tr>
    <tr>
        <th>Ship Name</th>
        <td><input type="text" name="ship_name" value="<?=$ship_name?>"/> <br/><?=form_error('ship_name')?></td>
    </tr>
    <tr>
        <th>Ship Address 1</th>
        <td><textarea name="ship_address1" cols="5" rows="20"><?=$ship_address1?></textarea> <br/><?=form_error('ship_address1')?></td>
    </tr>
    <tr>
        <th>Ship Address 2</th>
        <td><textarea name="ship_address2" cols="5" rows="20"><?=$ship_address2?></textarea> <br/><?=form_error('ship_address2')?></td>
    </tr>
    <tr>
        <th>Ship City</th>
        <td><input type="text" name="ship_city" value="<?=$ship_city?>"/> <br/><?=form_error('ship_city')?></td>
    </tr>
    <tr>
        <th>Ship State</th>
        <td><input type="text" name="ship_state" value="<?=$ship_state?>"/> <br/><?=form_error('ship_state')?></td>
    </tr>
    <tr>
        <th>Ship Country</th>
        <td><input type="text" name="ship_country" value="<?=$ship_country?>"/> <br/><?=form_error('ship_country')?></td>
    </tr>
    <tr>
        <th>Ship Zip Code</th>
        <td><input type="text" name="ship_zipcode" value="<?=$ship_zipcode?>"/> <br/><?=form_error('ship_zipcode')?></td>
    </tr>
    <tr>
        <th>Ship Phone Number</th>
        <td><input type="text" name="ship_phone_number" value="<?=$ship_phone_number?>"/> <br/><?=form_error('ship_phone_number')?></td>
    </tr>
    <tr>
        <th>Ship Email</th>
        <td><input type="text" name="ship_email" value="<?=$ship_email?>"/> <br/><?=form_error('ship_email')?></td>
    </tr>
    <tr>
        <th>Total</th>
        <td><input type="text" name="total" value="<?=$total?>"/> <br/><?=form_error('total')?></td>
    </tr>
    <?php /*?><tr>
        <th>Tax</th>
        <td><input type="text" name="tax" value="0"/> <br/><?=form_error('tax')?></td>
    </tr><?php */?>
    <tr>
        <th>Amount</th>
        <td><input type="text" name="amount" value="<?=$amount?>"/> <br/><?=form_error('amount')?></td>
    </tr>           
    <tr>
        <td></td>         
        <td>
            <button type="submit" class="positive"><span class="icon plus"></span>Save</button> 
            <button type="reset" class="button"><span class="icon reload"></span>Reset</button>
        </td>
    </tr>    
</table>      

<?=anchor($this->curpage, '&laquo; back')?>
<?=form_close()?>   
