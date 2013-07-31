<div id="cols-wrap" class="clearfix"> 
	
	<!-- account detail-->	
	 <div id="content">  <div class="breadcrumb">
        
     </div>
     
		<h1>My Account</h1>
		  <div class="content-inner">
			<div style="display: block;" class="checkout-content">
			
			<div class="content-inner">
  <?php /*?><form action="#" method="post" enctype="multipart/form-data" id="password"><?php */?>
  			<?php /*?><?=form_open('confirm_payment/send_confirm_payment/'.$order_id, array('id' => 'payment_form'))?><?php */?>
            
            <?=form_open('confirm_payment/confirm_payment_from_email/'.$row->id, array('id' => 'payment_form'))?>
            	<?=print_error(validation_errors())?>
                <?=print_success($this->session->flashdata('confirm_msg'))?>
                
    <h2>Payment Confirmation</h2>
	<br>
    <div class="content">
      <table class="form">
        <tbody><tr>
          <td><b>Invoice #:</b></td>
          <td>
          	<?=$row->id?>
          </td>
        </tr>
        <? $OU = new OUser($row->user_id); ?>
        <tr>
          <td><b>Name:</b></td>
          <td>          		
                <input type="text" name="name" value="<?=$OU->row->name?>" />
          </td>
        </tr>
        <tr>
          <td><b>Email:</b></td>
          <td>          		
                <input type="text" name="email" value="<?=$OU->row->email?>" />
          </td>
        </tr>
        <tr>
          <td><b>Amount:</b></td>
          <td>
          		Rp. <?=format_number($row->grand_total)?>
                <input type="hidden" name="amount" value="<?=$row->grand_total?>" readonly="readonly" />
          </td>
        </tr>
        <tr>
          <td><b>Date:</b></td>
          <td><input type="text" name="date" id="datepicker" required /></td>	  
        </tr>
        <tr>
          <td>Payment Method:</td>
		  <td><input type="text" name="payment_method" value="Transfer" readonly="readonly" required /></td>
		</tr>	
		</tr>	
			
		</tr>
		</tr>	
			<td>To:	</td>
			<?php /*?><td><?=OUserBankAccount::drop_down_select("account",$account,"", $cu->id)?></td><?php */?>
            <td><?=OBankAccount::drop_down_select("account",$account)?></td>
		</tr>
        <tr>
			<td>Amount:</td><br>
			<td><input name="amount" class="large-field" type="text"></td>
		</tr>
		<tr>
			<td>Keterangan:</td><br>
			<td> <textarea name="keterangan" rows="2" style="width: 56%;"></textarea></td>
        </tr>
      </tbody></table>
    </div>
    <div class="buttons">
      <div class="left">
      	
        
        <a class="button" href="javascript:;" onclick="$('#payment_form').submit();"><span>Send</span></a></div>
    </div>
  </form>
  </div>	
  </div>
		</div>
		</div>        
        
<style type="text/css">
	.breadcrumb { left: 0; top: -28px; }	
	h1, .welcome { font-size: 13px; line-height: 14px; margin-bottom: 0; padding: 7px 5px 11px 12px; margin-left:0; }
</style>

<script>
	$( "#datepicker" ).datepicker({
		dateFormat: 'yy-mm-dd'
	});
</script>