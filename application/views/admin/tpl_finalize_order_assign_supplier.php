<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body> 
	<?php
		if(sizeof($arr_sup) > 0)
		{
			foreach($arr_sup as $sup)	
			{
				extract($sup);
				
				$order_id_new = $order_id;
				$supplier_id_new = $supplier_id;
				break;
			}
		}
	?>
	<h3>Order ID: #<?=$order_id_new?></h3>
	<br />
    
    <?php $S = new OSupplier($supplier_id_new); ?>
	Dear Supplier <?=$S->row->name?>,<br /><br />
    
    This is the finalized order. Please prepare the items below and send them to the address listed below.<br /><br />
    
    <strong>Shipping Info</strong><br />
    <?php $O = new OOrder($order_id_new); ?>
    Name : <?=$O->row->name?><br />
    Address : <?=$O->row->address?> <?=$O->row->city?><br />
    Phone : <?=$O->row->phone?><br />
    Shipping Date / Time : <?=parse_date($O->row->shipping_date)?> / <?=$O->row->shipping_time?><br />
	Isi Kartu : <?=$O->row->shipping_cards?><br />
    Special Request : <?=$O->row->shipping_note?><br /><br />
    <?php /*?>Email : <?=$O->row->email?><br /><br /><?php */?>
    
    <?php /*?><strong>Sender Info</strong><br />
    <?php $U = new OUser($O->row->user_id); ?>
    Name : <?=$U->row->name?><br />
    Address : <?=$U->row->address?> <?=$U->row->city?><br />
    Phone : <?=$U->row->phone?><br />
    Email : <?=$U->row->email?><br /><br /><?php */?>
    
    
<?php /*?><table class="tbl_list">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Add On</th>
            <th>Qty</th>
        </tr>
    </thead>
    <tbody>
    
    <?php
	if(sizeof($arr_sup) > 0)
	{
		foreach($arr_sup as $sup)	
		{
			extract($sup);
			
			$OD = new OOrderDetail($order_detail_id);
			$P = new OProduct($OD->row->product_id);
			
			?>
			<tr class="<?=alternator("odd", "even")?>">            
				<td width="100px">
					<img src="<?=site_url($P->get_photo())?>" width="50px" height="50px" alt="" /><br />
					<?=anchor(site_url($P->get_link()), $P->row->name)?>
				</td>
				<td>
					<?php
						$add_on = json_decode($OD->row->addons, true); 
						
						if(sizeof($add_on) <= 0) echo "-";
						else
						{
							foreach($add_on as $k => $v)
							{
								if($v != 0)
								{
									$AO = new OAddOn($k);
									$price = $AO->get_price($row->product_id);
									
									?><img src="<?=site_url($AO->get_photo())?>" alt="" width="50px" height="50px" /><?
									
									echo "<strong>".$AO->row->name."</strong> ( <strong>Qty:</strong> ".$v." | <strong>Price:</strong> ".$price->price." | <strong>Total:</strong> ".doubleval($v) * doubleval($price->price).") <br />";						
									unset($AO);
								}
							}
						}
					?>
				</td>
				<td><?=$qty?></td>
			</tr>
			<?
			unset($OD, $P);
		}
	}
	?>
    </tbody>
</table><?php */?>









<?php
if(sizeof($arr_sup) > 0)
{
	$i = 1;
	foreach($arr_sup as $sup)	
	{
		extract($sup);
		
		$OD = new OOrderDetail($order_detail_id);
		$P = new OProduct($OD->row->product_id);
		$S = new OSize($OD->row->size_id);
		?>
        
        <div style="font-size: 11px; border-bottom: 1px solid #000;">
        	<table>
            	<tr>
                	<td><?=$i?></td>
                    <td width="150px" style="font-weight: bold;">Product Name</td>
                    <td>:</td>
                    <td width="150px"><?=anchor(site_url($P->get_link()), $P->row->name)?></td>
                    <td><img src="<?=site_url($P->get_photo())?>" width="50px" height="50px" alt="" /></td>
                </tr>
            	<tr>
                	<td></td>
                    <td width="150px" style="font-weight: bold;">Qty</td>
                    <td>:</td>
                    <td width="150px"><?=$qty?></td>
                    <td></td>
                </tr>
                <tr>
                	<td></td>
                    <td width="150px" style="font-weight: bold;">Size</td>
                    <td>:</td>
                    <td width="150px"><?=$S->row->name?></td>
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
                $add_on = json_decode($OD->row->addons, true); 
                
                if(sizeof($add_on) <= 0) echo "-";
                else
                {
                    foreach($add_on as $k => $v)
                    {
                        if($v != 0)
                        {
                            $AO = new OAddOn($k);
                            $price = $AO->get_price($row->product_id);
                            ?>
                            <tr>
                                <td></td>
                                <td width="150px" style="font-weight: bold;"></td>
                                <td></td>
                                <td width="150px">
                                    <?=$AO->row->name?> (Qty : <?=$v?>)
                                </td>
                                <td><img src="<?=site_url($AO->get_photo())?>" alt="" width="50px" height="50px" /></td> 
                            </tr>
                            <?
                            unset($AO);
                        }
                    }
                }
                ?>
            </table>
        </div>
		<?
		$i++;
		unset($OD, $P);		
	}
}
?>

<br />
<strong>NOTE :</strong><br />
<strong><strong>Once you have shipped, please confirm your shipment by going to <?=anchor('account_supplier/register_login_form', 'your account')?>.</strong>
<?php /*?><br />
<strong>Go to page <?=anchor('', 'Hapikado.com')?> or <?=anchor('account_supplier/login', 'login here')?>.</strong><?php */?>
</body>
</html>


<style type="text/css">
	h3 { font-size:16px; font-weight:bold; }
	
	table.tbl_form tr th, table.tbl_form tr td 
	{
		margin: 0;
		padding: 3px;
		vertical-align: top;
	}
	
	table.tbl_form tr th 
	{
		font-weight: bold;
		text-align: left;
	}
	
	table.tbl_list { border-collapse:collapse; }
	table.tbl_list thead tr{background-color: #333; color:#fff;}
	table.tbl_list td, 
	table.tbl_list th{padding: 4px 6px; vertical-align:top; margin:0; border: 1px solid #ddd;}
	table.tbl_list tbody tr.even{background-color: #efefef;}
	table.tbl_list tbody tr.odd{background-color: #ccc;}
	table.tbl_list tbody tr.hovcolor,
	table.tbl_list tbody tr:hover{background-color: #999;color:#FFF;}
</style>