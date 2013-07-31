<html>
<head>
    <title></title>
</head>
<body>    	
	<p>Dear Admin, </p>
    <p>This is New Order for you :</p><br />
    <div class="header">
    	<div class="header-left">
            <table>
            	<tr>
                    <td colspan="3">Buyer Info</td>
                </tr>
                <? 
					$OO = new OOrder($new); 
					$OU = new OUser($OO->row->user_id);
				?>
                <tr>
                    <td>Name</td>
                    <td>:</td>
                    <td><?=$OU->row->name?></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>:</td>
                    <td><?=$OU->row->address?></td>
                </tr>
                <tr>
                    <td>City</td>
                    <td>:</td>
                    <td><?=$OU->row->city?></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>:</td>
                    <td><?=$OU->row->phone?></td>
                </tr>
                <tr>
                    <td>Fax</td>
                    <td>:</td>
                    <td><?=$OU->row->fax?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td><?=$OU->row->email?></td>
                </tr>
            </table>    
        </div>
        <br /><br />
    	<div class="header-left">
            <table>
            	<tr>
                    <td colspan="3">Shipping Info</td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>:</td>
                    <td><?=$shipping_details['shipping_name']?></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>:</td>
                    <td><?=$shipping_details['shipping_address']?></td>
                </tr>
                <tr>
                    <td>City</td>
                    <td>:</td>
                    <td><?=$shipping_details['shipping_city']?></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>:</td>
                    <td><?=$shipping_details['shipping_phone']?></td>
                </tr>
                <tr>
                    <td>Fax</td>
                    <td>:</td>
                    <td><?=$shipping_details['shipping_fax']?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td><?=$shipping_details['shipping_email']?></td>
                </tr>
            </table>    
        </div>
        <br /><br />
        <div class="header-right">
            Order ID : #<?=$new?>	    	
        </div>
    </div>        
    <br /><br />
    
    <div class="konten">
        <div class="title">
            <h1>Your Order</h1>
        </div>
        <div class="order">      
            <table>
                <tr>
                    <th width="100px" style="text-align:left">Produk</th>
                    <th width="450px" style="text-align:left">Keterangan</th>
                    <th width="50px" style="text-align:center">Jumlah</th>                    
                    <th width="200px" style="text-align:right">Sub-Total</th>
                </tr>
                
               <?php /*?> <?php
                foreach($konten as $r)
                {
					$SPS = new OSupplierProductSize($r['id']);
					$P = new OProduct($SPS->row->product_id);
					
					foreach($this->cart->product_options($r['rowid']) as $option_name => $option_value) 
					{	
						$arr_opt[$option_name] = $option_value;
					}
					$SZ = new OSize($arr_opt['size_id']);
					?>
					<tr>	
						<td width="200px"><?=$P->row->name?></td>
						<th width="200px" style="text-align:left">
                        	<?=$SZ->row->name?><br />
                            <strong>Price:</strong> IDR <?=$this->cart->format_number($r['price'])?>
                        </th>
						<td width="200px" style="text-align:right"><?=$r['qty']?></td>
						<td width="200px" style="text-align:right">IDR <?=$this->cart->format_number($r['subtotal'])?></td>
					</tr>
				<?php
					unset($SPS, $P, $SZ);
                }
                ?><?php */?>
                
                
                
                <?php
					$total_all_addon = 0;
					foreach($konten as $r)
					{
						/*$SPS = new OSupplierProductSize($r['id']);
						$P = new OProduct($SPS->row->product_id);*/
						$PS = new OProductSize($r['id']);
						$P = new OProduct($PS->row->product_id);
						
						if($this->cart->has_options($r['rowid']) == TRUE)
						{ 
							foreach($this->cart->product_options($r['rowid']) as $option_name => $option_value)
							{
								$arr_opt[$option_name] = $option_value;												
								if($option_name == "addon")
								{
									$total_price_addon = 0;
									foreach($option_value as $id => $qty)
									{
										if($qty != "")
										{
											$j_option = json_encode($option_value);
											
											$AO = new OAddOn($id);
											$res = $AO->get_price($P->row->id);
											
											$total_price_addon = $total_price_addon + (doubleval($qty) * doubleval($res->price));
										}
									}
								}
							}
							$SZ = new OSize($arr_opt['size_id']);
							?>
                            	<tr>	
                                    <td width="100px"><?=anchor(site_url($P->get_link()), $P->row->name)?></td>
                                    <th width="450px" style="text-align:left; font-size:11px;">
                                    	<?php
											$add_on = json_decode($j_option, true); 
					
											foreach($add_on as $k => $v)
											{
												if($v != 0)
												{
													$AO = new OAddOn($k);
													$price = $AO->get_price($P->row->id);
													
													echo $AO->row->name." ( Qty: ".$v." | Price: ".$price->price." | 
																		   Total: ".doubleval($v) * doubleval($price->price).") <br />";
												}
											}
										?>
                                        <?=$SZ->row->name?><br />
                                        <strong>Price:</strong> IDR <?=$this->cart->format_number($r['price'])?>
                                    </th>
                                    <td width="50px" style="text-align:right"><?=$r['qty']?></td>
                                    <td width="200px" style="text-align:right">IDR <?=$this->cart->format_number($r['subtotal'] + doubleval($total_price_addon))?></td>
                                </tr>
                            <?php							
						}
						$total_all_addon = $total_all_addon + $total_price_addon;
						unset($PS, $P, $SZ);
					}
				?>
            </table>
        </div>    	
    </div>
    <br />
    
    <div class="total">
        <table>
        	<tr>
                <th colspan="3" style="text-align:right">Sub Total</th>                
                <td style="text-align:right"><strong>IDR <?=$this->cart->format_number(intval($this->cart->total())/* + doubleval($total_all_addon)*/)?></strong></td>
            </tr>
        	<tr>
                <th colspan="3" style="text-align:right">Shipping Fee</th>                
                <td style="text-align:right"><strong>IDR <?=$this->cart->format_number(doubleval($shipping_fee))?></strong></td>
            </tr>
            <tr>
                <th colspan="3" style="text-align:right">Discount</th>                
                <td style="text-align:right"><strong>IDR <?=$this->cart->format_number(doubleval($credit_point))?></strong></td>
            </tr>            
            <tr>
                <th colspan="3" style="text-align:right">Total Payment</th>                
                <td style="text-align:right"><strong>IDR <?=$this->cart->format_number(intval($this->cart->total()) /*+ doubleval($total_all_addon)*/ + doubleval($shipping_fee) - doubleval($credit_point))?></strong></td>
            </tr>            
        </table>
    </div>
        
</body>
</html>
<?
unset($OO, $OU);
?>

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