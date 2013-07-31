<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
	<h1>REVIEW ORDER</h1>
	<table class="tbl_form">
        <tr>
            <th>Order ID</th><td>:</td>
            <td><strong>#<?=$O->row->id?></strong></td>
        </tr>
    </table>
<br /><br />
<table class="tbl_list">
    <thead>
        <tr>
            <th>No.</th>        	
            <th>Product Name</th>
            <th>Size Name</th>
            <th>Add On</th>
            <th>QTY</th>
            <th>Price</th>
            <th>Final Price</th>
        </tr>
    </thead>
    <tbody>
    <?php 
		$order_details = OOrderDetail::get_list(0, 0, "id DESC", $O->row->id);
		
		$i=1 + $uri; 
		
		$grand_total = 0;
		foreach($order_details as $row) :
			$P = new OProduct($row->product_id);
			$S = new OSize($row->size_id);
			
			$next_total = intval($row->price) * intval($row->qty);
			
			$OD = new OOrderDetail();
			$OD->setup($row);
    ?>       
        <tr class="<?=alternator("odd", "even")?>">
            <td><?=$i?></td>            
            <td>
            	<img src="<?=site_url($P->get_photo())?>" alt="" height="50px" width="50px" /><br />
				<?=anchor(site_url($P->get_link()), $P->row->name)?>
            </td>
            <td><?=$S->row->name?></td>
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
							
							?><img src="<?=site_url($AO->get_photo())?>" alt="" width="50px" height="50px" /><br /><?
							echo "<strong>".$AO->row->name."</strong> ( <strong>Qty:</strong> ".$v." | <strong>Price:</strong> ".$price->price." | <strong>Total:</strong> ".doubleval($v) * doubleval($price->price).") <br />";
							unset($AO);
						}
						else 
						{
							echo "-";
						}
					}
				}
				?>
            </td>
            <th><?=$row->qty?></th>
            <td style="text-align:right">Rp <?=format_number($row->price)?></td>            
            <td style="text-align:right">Rp <?=format_number($next_total)?></td>
        </tr>
    <?php 
			$grand_total = intval($grand_total) + intval($next_total);
			unset($P, $S);
			$i++;
		endforeach; ?>
        <tr>
            <td colspan="5"></td>
            <td><span><strong>Total</strong></span></td>
            <td style="text-align:right"><span><strong>Rp <?=format_number(intval($grand_total))?></strong></span></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td><span><strong>Shipping Fee</strong></span></td>
            <td style="text-align:right"><span><strong>Rp <?=format_number(intval($O->row->shipping_cost))?></strong></span></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td><span><strong>Grand Total</strong></span></td>
            <td style="text-align:right"><span><strong>Rp <?=format_number(intval($grand_total) + intval($O->row->shipping_cost))?></strong></span></td>
        </tr>
    </tbody>
</table>
<br /><br />

<strong><p>NB: This is just for REVIEW. Don&prime;t reply this E-mail.</p></strong>
<p></p>
</body>
</html>


<style type="text/css">
	table.tbl_form tr th, table.tbl_form tr td 
	{
		margin: 0;
		padding: 3px;
		vertical-align: middle;
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
	
	table, th, td { border:1px solid #000; }
</style>