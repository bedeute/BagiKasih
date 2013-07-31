<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
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
<br /><br />
<table class="tbl_list">
    <thead>
        <tr>
            <th>No.</th>        	
            <th>Product Name</th>
            <th>Size Name</th>
            <th>Qty</th>
            <th>Price</th>            
            <?php /*?><th>Supplier Name</th>
            <th>Supplier Cost</th>
            <th>Supplier Final Cost</th><?php */?>
            <th>Final Price</th>
        </tr>
    </thead>
    <tbody>
    <?php $i=1 + $uri; ?>
    <?php 
		$order = new OOrder($O->row->id);
		$details = $order->get_details();
		
		foreach($details as $row) :            
    ?>       
        <tr class="<?=alternator("odd", "even")?>">
            <td><?=$i?></td>            
            <td><?=$row->product_name?></td>
            <td><?=$row->size_name?></td>
            <td><?=$row->qty?></td>
            <td style="text-align:right"><?=format_number($row->price)?></td>            
            <?php /*?><td><?=$S->row->name?></td>
            <td style="text-align:right"><?=format_number($row->supplier_cost)?></td>
            <td style="text-align:right"><?=format_number($row->supplier_final_cost)?></td><?php */?>
            <td style="text-align:right"><?=format_number($row->final_price)?></td>
        </tr>
    <?php 
        //unset($U, $L, $S);
        $i++;
    endforeach; ?>
        <tr>
            <td colspan="4"></td>
            <td>Sub-Total</td>
            <td style="text-align:right"><?=format_number($O->row->subtotal)?></td>
        </tr>
       <?php /*?> <tr>
            <td colspan="7"></td>
            <td>Tax</td>
            <td style="text-align:right"><?=format_number($O->row->tax_cost)?></td>
        </tr><?php */?>
        <tr>
            <td colspan="4"></td>
            <td>Shipping Fee</td>
            <td style="text-align:right"><?=format_number($O->row->shipping_cost)?></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td><span style="font-size:larger"><strong>Grand Total</strong></span></td>
            <td style="text-align:right"><span style="font-size:larger"><strong><?=format_number($O->row->grand_total)?></strong></span></td>
        </tr>
    </tbody>
</table>

</body>
</html>


<style type="text/css">
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