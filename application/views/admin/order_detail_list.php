<h2>Order Detail List</h2><br/>

<?=print_error($this->session->flashdata('warning'))?>
<?=print_success($this->session->flashdata('success'))?>
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
<table class="tbl_list">
    <thead>
        <tr>
            <th>ID</th>        	
            <th>Product Name</th>
            <th>Size Name</th>
            <th>Qty</th>
            <th>Price</th>            
            <?php /*?><th>Supplier Name</th>
            <th>Supplier Cost</th>
            <th>Supplier Final Cost</th><?php */?>
            <th>Final Price</th>
            <?php /*?><th>Action</th><?php */?>
            <th>Add On</th>
        </tr>
    </thead>
    <tbody>
    <?php $i=1 + $uri; ?>
    <?php 
		foreach($list as $row) :
            $S = new OSupplier($row->supplier_id);            
    ?>       
        <tr class="<?=alternator("odd", "even")?>">
            <td><?=$row->id?></td>            
            <td><?=$row->product_name?></td>
            <td><?=$row->size_name?></td>
            <td><?=$row->qty?></td>
            <td style="text-align:right"><?=format_number($row->price)?></td>            
            <?php /*?><td><?=$S->row->name?></td>
            <td style="text-align:right"><?=format_number($row->supplier_cost)?></td>
            <td style="text-align:right"><?=format_number($row->supplier_final_cost)?></td><?php */?>
            <td style="text-align:right"><?=format_number($row->final_price)?></td>
            <?php /*?><td>
                <?=anchor($this->curpage.'/delete_details/'.$row->order_id.'/'.$row->id, 'delete', array("onclick" => "return confirm('Are you sure?');"))?> |
                <?=anchor($this->curpage.'/edit_details/'.$row->order_id.'/'.$row->id, 'edit')?>
            </td><?php */?>
            <td>
				<?php 
					$add_on = json_decode($row->addons, true); 
					
					if(sizeof($add_on) <= 0) echo "<strong>Add On : -</strong>";
					else 
					{
						echo "<strong>Add On :</strong><br />";
						foreach($add_on as $k => $v)
						{
							$AO = new OAddOn($k);
							$price = $AO->get_price($row->product_id);
							
							if($v != 0) echo $AO->row->name." ( Qty: ".$v." | Price: Rp ".format_number($price->price)." | Total: Rp ".format_number(doubleval($v) * doubleval($price->price)).") <br />";
						}
					}
				?>
           </td>
        </tr>
    <?php 
			unset($U, $L, $S);
			$i++;
		endforeach; 
	?>
        <tr>
            <td colspan="5"></td>
            <td>Sub-Total</td>
            <td style="text-align:right"><?=format_number($O->row->subtotal)?></td>
        </tr>
        
        <tr>
            <td colspan="5"></td>
            <td>Shipping Fee</td>
            <td style="text-align:right"><?=format_number($O->row->shipping_cost)?></td>
        </tr>
        <?php
		if(doubleval($O->row->discount) > 0)
		{
			?>
            <tr>
                <td colspan="5"></td>
                <td>Discount</td>
                <td style="text-align:right"><?=format_number($O->row->discount)?></td>
            </tr>
        	<?php
		}
		?>
        <tr>
            <td colspan="5"></td>
            <td><span style="font-size:larger"><strong>Grand Total</strong></span></td>
            <td style="text-align:right"><span style="font-size:larger"><strong><?=format_number($O->row->grand_total)?></strong></span></td>
        </tr>
    </tbody>
</table>

<?=$pagination?>
<br />
<?=anchor($this->curpage, '&laquo; back')?>