<h2>Order Detail List</h2><br/>

<?=print_error($this->session->flashdata('warning'))?>
<?=print_success($this->session->flashdata('success'))?>
<?=print_error($this->session->flashdata('edit_error'))?>

<table class="tbl_form">
<tr>
    <th>Order ID</th><td>:</td>
    <td><strong>#<?=$O->row->id?></strong></td>
</tr>
<tr>
    <th>Billing Info</th><td>:</td>
    <?php
		$U = new OUser($O->row->user_id);
		$user = $U->row;
	?>
    <td>
		<?=$user->name?><br />
		<?=$user->address?><br />
        <?=$user->city?><br />
        <?=$user->email?><br />                    
        <?=$user->phone?><br />
        <?=$user->fax?><br />
 	</td>
</tr>
<tr>
    <th>Shipping Info</th><td>:</td>
   
    <?php $L = new OLocation($O->row->location_id); ?>
    <td>    	
		<?=$O->row->name?><br />
		<?=$O->row->address?><br />
        <?=$O->row->city?><br />
        <?=$L->row->name?><br />
        <?=$O->row->phone?><br />
        <?=$O->row->email?><br /><br />
        
        Pengiriman : <strong><?=$O->row->shipping_date?> (<?=$O->row->shipping_time?>)</strong><br />
        Note : <strong><?=nl2br($O->row->shipping_note)?></strong>
    </td>
</tr>
</table>
<br /><br />

<?=form_open()?>
<table class="tbl_list">
    <thead>
        <tr>
            <th>No.</th>        	
            <th>Product Name</th>
            <th>Size Name</th>
            <th>Qty</th>
            <th>Fulfilled *</th>
            <th>Price</th>            
            <?php /*?><th>Supplier Name</th>
            <th>Supplier Cost</th>
            <th>Supplier Final Cost</th><?php */?>
            <th>Final Price</th>
            <?php /*?><th>Action</th><?php */?>
        </tr>
    </thead>
    <tbody>
    <?php $i=1 + $uri; ?>
    <?php 
		foreach($list as $row) :
            $S = new OSupplier($row->supplier_id);            
    ?>       
        <tr class="<?=alternator("odd", "even")?>">
            <td><?=$i?></td>            
            <td><?=$row->product_name?><br />
            <?php 
					$add_on = json_decode($row->addons, true); 
					
					foreach($add_on as $k => $v)
					{
						$AO = new OAddOn($k);
						$price = $AO->get_price($row->product_id);
						
						echo $AO->row->name." ( Qty: ".$v." | Price: ".$price->price." | Total: ".doubleval($v) * doubleval($price->price).") <br />";
					}
				
			?>
            </td>
            <td><?=$row->size_name?></td>
            <td>
            	<?php
				$q = "SELECT * FROM order_detail_assign_suppliers WHERE order_detail_id = ? AND status = 'pending'";
				$tmpres = $this->db->query($q,array($row->id));
				if(!emptyres($tmpres)) { $readonly = 'readonly="readonly"'; $text = "NO EDIT **"; }
				else { $readonly = ''; $text = ""; }
//				var_dump($this->db->last_query());	
				?>
            	<input type="text" size="3" maxlength="3" name="qty[<?=$row->id?>]" value="<?=$row->qty?>" <?php echo $readonly; ?> />
                <input type="hidden" name="order_detail_id[]" value="<?=$row->id?>" readonly="readonly" /><br /><?php echo $text; ?>
            </td>
            <td>
            <?php
			$q = "SELECT SUM(qty) AS total FROM order_detail_assign_suppliers WHERE order_detail_id = ? AND status = 'approved'";
			$tmpres = $this->db->query($q,array($row->id));
			if(emptyres($tmpres)) { echo 0; }
			else
			{
				$tmprow = $tmpres->row();
				echo intval($tmprow->total);
			}
			?>
            </td>
            <td style="text-align:right">Rp <?=format_number($row->price)?></td>            
            <?php /*?><td><?=$S->row->name?></td>
            <td style="text-align:right"><?=format_number($row->supplier_cost)?></td>
            <td style="text-align:right"><?=format_number($row->supplier_final_cost)?></td><?php */?>
            <td style="text-align:right">Rp <?=format_number($row->final_price)?></td>
            <?php /*?><td>
                <?=anchor($this->curpage.'/delete_details/'.$row->order_id.'/'.$row->id, 'delete', array("onclick" => "return confirm('Are you sure?');"))?> |
                <?=anchor($this->curpage.'/edit_details/'.$row->order_id.'/'.$row->id, 'edit')?>
            </td><?php */?>
            
        </tr>
    <?php 
			unset($U, $L, $S);
			$i++;
		endforeach; 
	?>
        <tr>
            <td colspan="5"></td>
            <td>Sub-Total</td>
            <td style="text-align:right">Rp <?=format_number($O->row->subtotal)?></td>
        </tr>
        
        <tr>
            <td colspan="5"></td>
            <td>Shipping Fee</td>
            <td style="text-align:right">Rp <?=format_number($O->row->shipping_cost)?></td>
        </tr>
         <?php
		if(doubleval($O->row->discount) > 0)
		{
			?>
            <tr>
                <td colspan="5"></td>
                <td>Discount</td>
                <td style="text-align:right">Rp <?=format_number($O->row->discount)?></td>
            </tr>
        	<?php
		}
		?>
        <tr>
            <td colspan="5"></td>
            <td><span style="font-size:larger"><strong>Grand Total</strong></span></td>
            <td style="text-align:right"><span style="font-size:larger"><strong>Rp <?=format_number($O->row->grand_total)?></strong></span></td>
        </tr>
    </tbody>
</table>
<br />
<p>* Fulfilled adalah jumlah yang telah dikonfirmasi oleh supplier</p>
<p>** NO EDIT - field ini tidak bisa diedit oleh karena ada supplier yang telah diassign dengan quantity tertentu dan masih belum membalas. Untuk mengedit, silahkan ke halaman <?=anchor("admin/orders/assign_suppliers/{$O->row->id}","Assign Suppliers")?> dan uncheck supplier yang masih pending.</p>

<br />
<button class="positive button" type="submit"><span class="check icon"></span>Save &amp; Send Adjustment to Buyer</button>
<button class="negative button" type="button" onclick="location.href='<?=site_url($this->curpage)?>';"><span class="leftarrow icon"></span>Cancel</button>
<?=form_close()?>
