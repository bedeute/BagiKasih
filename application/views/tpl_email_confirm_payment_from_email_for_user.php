Dear <?=$name?>, <br /><br />

We have received your payment confirmation. The following are details of your payment confirmation.<br /><br />
<?
$O = new OBankAccount($account);	
?>
Date : <?=$date?> <br />
Name : <?=$name?> <br />
Email : <?=$email?> <br />
Order : <?=$order_id?> <br />
Account : <?=$O->row->name?> A/C <?=$O->row->account_number?><br />
Amount : Rp <?=format_number($amount)?> <br />
Payment Method : <?=$payment_method?> <br />
Keterangan : <?=nl2br($keterangan)?> <br />

<?
unset($O);
?>