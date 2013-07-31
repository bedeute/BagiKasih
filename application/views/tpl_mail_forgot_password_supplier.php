Dear <?=$name?>,<br /><br /><br /><br />

Please click <?=anchor('account_supplier/forgot_password/reset/'.md5(md5($email))."/".$pass, 'here')?> to reset your password<br /><br /><br />

Thank you.<br /><br />

Please do not reply to this email. This mailbox is not monitored and you will not receive a response.<br /><br />

_________________________<br />
<?=anchor("","Hapikado.com")?><br />
&copy; 2012<br />