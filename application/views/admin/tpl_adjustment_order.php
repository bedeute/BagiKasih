<html>
<head>
    <title></title>
</head>
<body>    	
    <div class="header">
    	<p>Thank you for ordering at <a href="http://www.hapikado.devsites.us/">Hapikado.com</a>.</p>        
        
        <?
		if(intval($total_refund) > 0)
		{
		?>
        <p>There is a change in your order. A refund of <strong>Rp. <?=format_number($total_refund)?></strong> will be credited to your bank account. Our customer rep will contact you regarding this.</p>
        <?
		}
		?>
        
        <div class="header-left">
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
                        <strong>Name :</strong> <?=$user->name?><br />
                        <strong>Address :</strong> <?=$user->address?>, <?=$user->city?><br />
                        <strong>Phone / Fax :</strong> <?=$user->phone?> / <?=$user->fax?><br />
                        <strong>Email :</strong> <?=$user->email?><br />
                    </td>
                </tr>
                <tr>
                    <th>Shipping Info</th><td>:</td>                   
                    <?php //$L = new OLocation($O->row->location_id); ?>
                    <td>
                        <strong>Name :</strong> <?=$O->row->name?><br />
                        <strong>Address :</strong> <?=$O->row->address?>, <?=$O->row->city?><br />
                        <?php /*?><?=$L->row->name?><br /><?php */?>
                        <strong>Phone :</strong> <?=$O->row->phone?><br />
                        <strong>Email :</strong> <?=$O->row->email?><br />    
                    </td>
                </tr>
        	</table>  
        </div>
        <div class="header-right"></div>
    </div>        
    <br /><br /><br /><br />
    
    <div class="konten">
        <div class="title">
            <h1>Your Order</h1>
        </div>
      <?php /*?>  <div class="order">      
            <table>
                <tr>
                    <th width="100px" style="text-align:left">Produk</th>
                    <th width="100px" style="text-align:left">Size</th>
                    <th width="450px" style="text-align:left">Add On</th>
                    <th width="100px" style="text-align:left">Price</th>
                    <th width="50px" style="text-align:center">Qty</th>                    
                    <th width="200px" style="text-align:right">Sub-Total</th>
                </tr>                
                <?php
				foreach($list as $r)
				{
					$P = new OProduct($r->product_id);
					$SZ = new OSize($r->size_id);
					?>
                    <tr>	
                        <td width="100px">
                        	<img src="<?=site_url($P->get_photo())?>" alt="" width="50px" height="50px" />
							<?=anchor(site_url($P->get_link()), $P->row->name)?>
                        </td>
                        <td width="100px"><?=$SZ->row->name?></td>                        
                        <th width="450px" style="text-align:left; font-size:11px;">
                            <?php
								$add_on = json_decode($r->addons, true); 
								
								if(sizeof($add_on) <= 0) echo "-";
								else
								{
									foreach($add_on as $k => $v)
									{
										if($v != 0)
										{
											$AO = new OAddOn($k);
											$price = $AO->get_price($P->row->id);
											
											?><img src="<?=site_url($AO->get_photo())?>" alt="" width="50px" height="50px" /><?
											
											echo "<strong>".$AO->row->name."</strong> ( <strong>Qty:</strong> ".$v." | <strong>Price:</strong> ".$price->price." | <strong>Total:</strong> ".doubleval($v) * doubleval($price->price).") <br />";						
											unset($AO);
										}
									}
								}
							?>                            
                            
                        </th>
                        <td width="100px" style="text-align:right">IDR <?=format_number($r->price)?></td>
                        <td width="100px" style="text-align:right">
							<?=$r->qty?> <br />
                            (<strong>Old Qty : <span style="color:#F00;"><?=$r->old_qty?></span></strong>)</td>
                        <td width="200px" style="text-align:right">IDR <?=format_number($r->final_price)?></td>
                    </tr>
                    <?php	
				}
				?>
            </table>
        </div>  <?php */?>  	
    </div>
    <br />
    
    <div class="total">
        <table>
        	<tr>
                <th colspan="3" style="text-align:right">Sub Total</th>                
                <td style="text-align:right"><strong>IDR <?=format_number($O->row->subtotal)?></strong></td>
            </tr>
        	<tr>
                <th colspan="3" style="text-align:right">Shipping Fee</th>                
                <td style="text-align:right"><strong>IDR <?=format_number($O->row->shipping_cost)?></strong></td>
            </tr>            
            <tr>
                <th colspan="3" style="text-align:right">Total Payment</th>                
                <td style="text-align:right"><strong>IDR <?=format_number($O->row->grand_total)?></strong></td>
            </tr>            
        </table>
    </div>
    <br /><br />
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
<?php
	foreach($list as $r)
	{
		$P = new OProduct($r->product_id);
		$SZ = new OSize($r->size_id);
		?>        
        <div style="font-size: 11px; border-bottom: 1px solid #000;">
        	<table>
            	<tr>
                	<td><?=$i?></td>
                    <td width="150px" style="font-weight: bold;">Product Name</td>
                    <td>:</td>
                    <td width="150px"><?=anchor(site_url($P->get_link()), $P->row->name)?></td>
                    <td><img src="<?=site_url($P->get_photo())?>" alt="" width="50px" height="50px" /></td>
                </tr>
            	<tr>
                	<td></td>
                    <td width="150px" style="font-weight: bold;">Qty</td>
                    <td>:</td>
                    <td width="150px"><?=$r->qty?> (<strong>Old Qty : <span style="color:#F00;"><?=$r->old_qty?></span></strong>)</td>
                    <td></td>
                </tr>
                <tr>
                	<td></td>
                    <td width="150px" style="font-weight: bold;">Size</td>
                    <td>:</td>
                    <td width="150px"><?=$SZ->row->name?></td>
                    <td></td>
                </tr>
                <tr>
                	<td></td>
                    <td width="150px" style="font-weight: bold;">Add on</td>
                    <td>:</td>
                    <td width="150px"></td>
                    <td></td>                    
                </tr>
				<?php
                $add_on = json_decode($r->addons, true); 
                
                if(sizeof($add_on) <= 0) echo "-";
                else
                {
                    foreach($add_on as $k => $v)
                    {
                        if($v != 0)
                        {
                            $AO = new OAddOn($k);
                            $price = $AO->get_price($row->product_id);
							$total = doubleval($v) * doubleval($price->price);
                            ?>
                            <tr>
                                <td></td>
                                <td width="150px" style="font-weight: bold;"></td>
                                <td></td>
                                <td width="150px">
                                    <?=$AO->row->name?> (Qty : <?=$v?> | Price : Rp <?=format_number($price->price)?> | Total : Rp <?=format_number($total)?>)
                                </td>
                                <td><img src="<?=site_url($AO->get_photo())?>" alt="" width="50px" height="50px" /></td> 
                            </tr>
                            <?
                            unset($AO);
                        }
                    }
                }
                ?>
                <tr>
                	<td></td>
                    <td width="150px" style="font-weight: bold;">Price</td>
                    <td>:</td>
                    <td width="150px">Rp <?=format_number($r->price)?></td>
                    <td></td>
                </tr>
                <tr>
                	<td></td>
                    <td width="150px" style="font-weight: bold;">Sub-Total</td>
                    <td>:</td>
                    <td width="150px">Rp <?=format_number($r->final_price)?></td>
                    <td></td>
                </tr>
            </table>
        </div>
<?php
		unset($P, $SZ);
	}
?>
    <div class="total">
            <table>
                <tr>
                    <th colspan="3" style="text-align:right">Sub Total</th>                
                    <td style="text-align:right"><strong>IDR <?=format_number($O->row->subtotal)?></strong></td>
                </tr>
                <tr>
                    <th colspan="3" style="text-align:right">Shipping Fee</th>                
                    <td style="text-align:right"><strong>IDR <?=format_number($O->row->shipping_cost)?></strong></td>
                </tr>            
                <tr>
                    <th colspan="3" style="text-align:right">Total Payment</th>                
                    <td style="text-align:right"><strong>IDR <?=format_number($O->row->grand_total)?></strong></td>
                </tr>            
            </table>
        </div>
        <br /><br />
    
    <p>Please reply to this email immediately. If you agree with this adjustment, we will process the new order, and we will return the rest of your money. If not, we will return all the money that you pay.</p>
    <p>Thank you.</p>
</body>
</html>

<style>
	body, table { font:Arial, Helvetica, sans-serif; font-size:12px; }
	th { font-weight:bold; }
	th, td { height:15px; }
	
	.header { width:800px; }
	.header-left { float:left; width:600px; }
	.header-right { float:left; width:200px; margin-bottom:80px; }
	
	.batas { width:800px; }
	
	.konten { width:800px; }
	.title { margin-left:0px; }
	.title h1 { font-size:14px; margin-bottom:20px; }
	.order { margin-bottom:30px; }
</style>