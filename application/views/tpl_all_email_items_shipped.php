<html>
<head>
    <title></title>
</head>
<body>    	
    <div class="header">
    	<p><strong>All your order has been shipped.</strong></p>
        
        
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
                        <?=$user->name?><br />
                        <?=$user->address?><br />
                        <?=$user->city?><br />
                        <?=$user->email?><br />                    
                        <?=$user->phone?><br />
                        <?=$user->fax?><br />
                        <?=$user->email?><br />
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
                        <?=$O->row->email?><br />
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
        <div class="order">      
            <table>
                <tr>
                    <th width="100px" style="text-align:left">Produk</th>
                    <th width="100px" style="text-align:left">Size</th>
                    <th width="450px" style="text-align:left">Add On</th>
                    <?php /*?><th width="100px" style="text-align:left">Price</th><?php */?>
                    <th width="50px" style="text-align:center">Qty</th>                    
                   <?php /*?> <th width="200px" style="text-align:right">Sub-Total</th><?php */?>
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
										$AO = new OAddOn($k);
										$price = $AO->get_price($P->row->id);
										
										?><img src="<?=site_url($AO->get_photo())?>" alt="" width="50px" height="50px" /><?
										
										echo "<strong>".$AO->row->name."</strong> ( <strong>Qty:</strong> ".$v." | <strong>Price:</strong> ".$price->price." | <strong>Total:</strong> ".doubleval($v) * doubleval($price->price).") <br />";						
										unset($AO);
									}
								}
							?>                            
                            <?php /*?><?=$r->addons?><br /><?php */?>
                        </th>
                       <?php /*?> <td width="100px" style="text-align:right">IDR <?=format_number($r->price)?></td><?php */?>
                        <td width="50px" style="text-align:right"><?=$r->qty?></td>
                      <?php /*?>  <td width="200px" style="text-align:right">IDR <?=format_number($r->final_price)?></td><?php */?>
                    </tr>
                    <?php	
				}
				?>
            </table>
        </div>    	
    </div>
    <br />
    
    <?php /*?><div class="total">
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
    </div><?php */?>
    <br /><br />
    
    <?php /*?><p>Please reply to this email immediately. If you agree with this adjustment, we will process the new order, and we will return the rest of your money. If not, we will return all the money that you pay.</p><?php */?>
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