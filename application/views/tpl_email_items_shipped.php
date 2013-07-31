<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<?php 
$U = new OUser($user_id);
$OD = new OOrderDetail($order_detail_id);
$P = new OProduct($OD->row->product_id);
$S = new OSize($OD->row->size_id);
?>
Dear Mr. / Mrs. <?=$U->row->name?>, <br /><br />

This item has been sent for Order #<?=$order_id?>
<br /><br />
<?php /*?><table class="tbl_list">
    <thead>
        <tr>
            <th>ID.</th>        	
            <th>Product Name</th>
            <th>Size Name</th>
            <th>Add On</th>
        </tr>
    </thead>
    <tbody>
    	<tr>
        	<td>#<?=$OD->row->id?></td>
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
							$price = $AO->get_price($P->row->id);
							
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
            <td><?=$shipping_note?></td>
        </tr>
    </tbody>
</table><?php */?>




<div style="font-size: 11px; border-bottom: 1px solid #000;">
    <table>
        <tr>
            <td><?=$i?></td>
            <td width="150px" style="font-weight: bold;">Product Name</td>
            <td>:</td>
            <td width="150px"><?=anchor(site_url($P->get_link()), $P->row->name)?></td>
            <td><img src="<?=site_url($P->get_photo())?>" alt="" height="50px" width="50px" /></td>
        </tr>
        <?php /*?><tr>
            <td></td>
            <td width="150px" style="font-weight: bold;">Qty</td>
            <td>:</td>
            <td width="150px"><?=$qty?></td>
            <td></td>
        </tr><?php */?>
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
        <tr>
            <td></td>
            <td width="150px" style="font-weight: bold;">Shipping Note</td>
            <td>:</td>
            <td width="150px"><?=$shipping_note?></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td width="150px" style="font-weight: bold;">Shipping Date</td>
            <td>:</td>
            <td width="150px"><?=parse_date($shipping_date)?></td>
            <td></td>
        </tr>
    </table>
</div>
		


<br /><br />

<strong><p>NB: This is just for notification. Don&prime;t reply this E-mail.</p></strong>
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