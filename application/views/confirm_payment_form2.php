<div id="cols-wrap" class="clearfix"> 
	<?=$this->load->view('user_right_sidebar')?>
	<!-- account detail-->	
	 <div id="content">  <div class="breadcrumb">
        <?=anchor('/', 'Home')?> » <?=anchor('user/home', 'Account')?> » <?=anchor('user/confirm_payment_list', 'Konfirmasi Pembayaran')?>
     </div>
     
		<h1>My Account</h1>
		  <div class="content-inner">
			<div style="display: block;" class="checkout-content">
			
			<div class="content-inner">
    			<?=form_open('confirm_payment/send_confirm_payment/'.$order_id, array('id' => 'payment_form'))?>
            	<?=print_error(validation_errors())?>
                <?=print_success($this->session->flashdata('confirm_msg'))?>
                
    <h2>Payment Confirmation</h2>
	<br>
    <div class="content">
      <table class="form">
        <tbody><tr>
          <td><b>Invoice #:</b></td>
          <td><?=$order_id?> <input type="hidden" name="order_id" value="<?=$order_id?>" readonly="readonly" />
            </td>
        </tr>
        <tr>
          <td><b>Amount:</b></td>
          <td>Rp. <?=format_number($O->row->grand_total)?></td>
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
			<td><?=OUserBankAccount::drop_down_select("account",$account,"", $cu->id)?></td>
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
      	<?=anchor('user/confirm_payment_list', '<span>Back</span>', array('class' => 'button'))?>
        
        <a class="button" href="javascript:;" onclick="$('#payment_form').submit();"><span>Continue</span></a></div>
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
		dateFormat: 'yy-mm-dd',
		minDate: "+<?=$estimate_delivery?>"
	});
</script>