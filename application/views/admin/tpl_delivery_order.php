<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>    
	<p>Dear Mr./Mrs. <?=$OU->row->name?>,<p>
    <br />
    <p>Your Order number #<?=$OO->row->id?> is complete.</p>
    <p>We will immediately send the order to you, on <?=parse_date($OO->row->shipping_date)?> at <?=$OO->row->shipping_time?></p>
    <p>Please check your order status at <?=anchor(site_url('account/login'), 'your account')?>.</p>
	<br />
	<p>Thank you for shopping at <?=anchor(site_url(), 'hapikado.com')?>.</p>
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