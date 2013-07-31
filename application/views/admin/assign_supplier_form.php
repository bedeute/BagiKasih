<h2>Assign Supplier Form</h2><br/>
<?php
$allmatch = TRUE;
// if error exists
if($this->session->flashdata('msg_qty') != "")
{
	// decode post data from json
	$posts = json_decode($this->session->flashdata('posts_data'));
}
?>

<?=print_error($this->session->flashdata('warning'))?>
<?=print_success($this->session->flashdata('success'))?>

<?=print_success($this->session->flashdata('send_email_msg'))?>
<?=print_error($this->session->flashdata('msg_qty'))?>
<?=print_success($this->session->flashdata('approve_new_order_success'))?>


<div style="float: left;">
	<div style="float: left; width: 400px; margin-right: 50px;"> 
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
    </div>
    
    <div style="float: left; width: 600px;">
        <?php
		if(sizeof($histories) > 0)
		{
		?>
        	<p style="font-weight:bold;">Order Histories</p>
            <table class="tbl_list">
                <tr>
                    <th>Date</th>
                    <th>Note</th>
                </tr>
                <?php
                foreach($histories as $r)
                {
                    ?>
                    <tr>
                        <td><?=$r->dt_added?></td>
                        <td><?=$r->note?></td>
                    </tr>
                    <?
                }
                ?>
            </table>
        <?php
		}
		?>
    </div>
</div>
<br /><br />

<div style="float:left">
<?=form_open($this->curpage.'/add_assign_suppliers/'.$O->row->id)?>
<table class="tbl_list">
    <thead>
        <tr>
            <th>No.</th>        	
            <th>Product Name</th>
            <th>Size Name</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php 
		$i=1 + $uri; 
		foreach($list as $row) :
			$od = $row;
			if($row)
			{
				$S = new OSupplier();
				$supplier_res = $S->get_order_assign_supplier($O->row->id); 

				$supplier_id_arr = $qty = NULL;
				foreach($supplier_res as $r)
				{					
					$check_id_arr[] = $r->supplier_id;
					$qty[$r->supplier_id] = $r->qty;
				}
				unset($S);
			}			
			extract($_POST);
    ?>       
        <tr class="<?=alternator("odd", "even")?>">
            <td><?=$row->id?></td>            
            <td>
				<?=$row->product_name?><br />
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
            <td><?=$row->size_name?></td>
            <td><?=$row->qty?></td>
            <td style="text-align:right"><?=format_number($row->price)?></td>
            <?php
			/*if(intval($row->supplier_id) == 0)
			{*/
			?>
            <td>
            	<?php
				/* ADDED BY : FAJAR */
				$OPR = new OProduct($row->product_id);
				
				// cek product tsb. apakah termasuk GIFT ato bukan (id = 4 adalah GIFT)
				// jika GIFT, maka supplier tidak berdasarkan lokasi
				if($OPR->row->category_id == 4)
				{
					$suppliers = OSupplier::get_supplier_by_location_product_size(0, 0, "s.id DESC", "", $row->product_id, $row->size_id, "sps.supplier_id", "active");	
				}
				else
				{
					$suppliers = OSupplier::get_supplier_by_location_product_size(0, 0, "s.id DESC", $O->row->location_id, $row->product_id, $row->size_id, "sps.supplier_id", "active");
				}
				// end 
				
				//$suppliers = OSupplier::get_supplier_by_location_product_size(0, 0, "s.id DESC", $O->row->location_id, $row->product_id, $row->size_id, "sps.supplier_id", "active");		
				$supcount = 0;
				$leftover = $row->qty;
				foreach($suppliers as $sup)
				{					
					$disabled = '';
					$status = "pending";
					$q = "SELECT * FROM order_detail_assign_suppliers WHERE order_detail_id = ? AND supplier_id = ?";
					$tmpres = $this->db->query($q,array($od->id,$sup->supplier_id));
					if(emptyres($tmpres) && $sup->primary == "1") 
					{
						$checked = 'checked="checked"';	
						$tmpqty = $row->qty;
					}
					else if(emptyres($tmpres)) 
					{ 
						$checked = ''; 
						$tmpqty = 0;
					}
					else
					{
						$checked = 'checked="checked"';
						$tmprow = $tmpres->row();
						$tmpqty = $tmprow->qty;
						$status = $tmprow->status;
						$assign_sup_id = $tmprow->id;
						
						if($status != "pending")
						{
							//$disabled = 'readonly="readonly"';
							$leftover -= $tmpqty;
						}
						
					}
					// if this is an error in the form, we need to update the data with the posted data
					if($this->session->flashdata('msg_qty') != "")
					{
						$post_suppliers = (array) $posts->supplier_id->{$od->id};
						if(in_array($sup->supplier_id,$post_suppliers)) $checked = 'checked="checked"'; 
						else $checked = '';
						$post_qtys = $posts->qty->{$od->id};
						$tmpqty = $post_qtys[$supcount];
					}
					/*
					if($status == "pending")
					{
						?>
						<input type="checkbox" name="supplier_id[<?=$od->id?>][<?php echo $supcount; ?>]" value="<?php echo $sup->supplier_id; ?>" <?php echo $checked; ?> <?php echo $disabled; ?> /> <?php echo $sup->name; ?> <input type="text" name="qty[<?php echo $od->id; ?>][<?php echo $supcount; ?>]" value="<?php echo $tmpqty; ?>" <?php echo $disabled; ?> /> STATUS: <?php echo $status; ?> <input type="hidden" name="status[<?=$od->id?>][<?=$supcount?>]" value="<?php echo $status; ?>" /><br />
                        <?php
					}
					else
					{
						?>
                        <input type="hidden" name="supplier_id[<?=$od->id?>][<?php echo $supcount; ?>]" value="<?php echo $sup->supplier_id; ?>" /> <input type="checkbox" name="" checked="checked" disabled="disabled" /> <?php echo $sup->name; ?> <input type="text" name="qty[<?php echo $od->id; ?>][<?php echo $supcount; ?>]" value="<?php echo $tmpqty; ?>" <?php echo $disabled; ?> /> STATUS: <?php echo $status; ?> <input type="hidden" name="status[<?=$od->id?>][<?=$supcount?>]" value="<?php echo $status; ?>" /><br />
                        <?php
					}
					*/
					?>
                    
                   
					<input type="checkbox" name="supplier_id[<?=$od->id?>][<?php echo $supcount; ?>]" value="<?php echo $sup->supplier_id; ?>" <?php echo $checked; ?> <?php echo $disabled; ?> /> <?php echo $sup->name; ?> <input type="text" name="qty[<?php echo $od->id; ?>][<?php echo $supcount; ?>]" value="<?php echo $tmpqty; ?>" <?php echo $disabled; ?> /> STATUS: <?php echo $status; ?> <input type="hidden" name="status[<?=$od->id?>][<?=$supcount?>]" value="<?php echo $status; ?>" />
                    <?=($status == "pending" && $tmpqty != 0 ? " | ".anchor($this->curpage.'/approve_order_manually/'.$O->row->id.'/'.$od->id.'/'.$assign_sup_id, 'Approve Order Manually') : '')?>
                    <br />
                    
                    
                    <?php
					$supcount++;
				}
				if($leftover > 0)
				{
					?>
					Sisa yang perlu diassign: <?php echo $leftover; ?>
					<?php
					$allmatch = FALSE;
				}
				else
				{
					?>
					<span style="color:green; font-weight:bold;">Semua telah berhasil diassign.</span>
					<?php
				}
				?>
            </td>
            <?php
			/*}
			else echo "<td></td>";*/
			?>
        </tr>
    <?php 
        $i++;
    	endforeach; 
	?>        
    </tbody>
</table>
<br />
<p>NOTE:<br />
1. Jika anda telah menambahkan atau mengubah supplier yang ada, pastikan anda "SAVE" kemudian menekan "Send Email to Supplier".<br />
2. Jika ada supplier yang tidak bisa menyanggupi, silahkan menghubungi pemesan tentang perubahan pesanan. Setelah itu, lakukan perubahan pemesanan dengan menekan tombol "Edit Quantity and Send Adjustment to Buyer"<br />
3. Jika jumlah yang telah diassign ke supplier dan telah dikonfirmasi sesuai dengan jumlah pesanan, tombol "FINALIZE ORDER" akan otomatis keluar. Silahkan klik tombol tersebut untuk memberitahu seluruh supplier yg bersangkutan untuk mempersiapkan dan mengirimkan barang mereka.<br />
</p>
<button class="positive button" type="submit"><span class="check icon"></span>Save</button>

<button class="negative button" type="button" onclick="location.href='<?=site_url($this->curpage.'/send_email_to_suppliers/'.$O->row->id)?>';">Send Email to Supplier</button>
<?php /*?><button class="negative button" type="button" onclick="location.href='<?=site_url($this->curpage.'/send_adjustment/'.$O->row->id)?>';">Send Adjusment to Buyer</button><?php */?>

<button class="negative button" type="button" onclick="location.href='<?=site_url($this->curpage.'/edit/'.$O->row->id)?>';">Edit Quantity &amp; Send Adjustment to Buyer</button>
<?php
if($allmatch)
{
	?>
    <button class="positive button" type="button" onclick="location.href='<?=site_url($this->curpage.'/finalize_order/'.$O->row->id)?>';"><span class="check icon"></span>FINALIZE ORDER</button>

    <?php
}
?><br /><br />
<button class="negative button" type="button" onclick="location.href='<?=site_url($this->curpage)?>';"><span class="leftarrow icon"></span>Cancel</button>
<?=form_close()?>
</div>

<?php /*?><?=anchor($this->curpage.'/send_email_to_suppliers/'.$O->row->id, 'Send Email to Supplier')?> | <?=anchor($this->curpage.'/send_adjustment/'.$O->row->id, 'Send Adjusment to Buyer')?>
<br /><br />
<?=anchor($this->curpage, '&laquo; back')?><?php */?>


<style>
	table.sup_list td, table.sup_list th {
		border:0px !important;
		margin: 0;
		padding: 4px 6px;
		vertical-align: top;
	}
</style>