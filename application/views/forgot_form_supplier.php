<div id="cols-wrap" class="clearfix"> 		
	<div class="content-inner">
		<div style="display: block;" class="checkout-content">
			<div class="left">
				<div id="member"><h1>Supplier Forgot Password</h1></div>
				<br />                
                <?=print_error($this->session->flashdata("error_string"))?>
                <?=print_error($error_string)?>
                <?=print_error(validation_errors())?>
                
                <?=form_open('account_supplier/forgot_password', array('id' => 'forgot_form'))?>
                <table>
                    <tr>
                        <td class="name">Your Email Address </td>
                        <td class="model"> : <input type="text" name="forgot_email" size="50px" value="<?=$forgot_email?>" required autofocus /></td>
                    </tr>
                    <tr>
                        <td class="name">Security Code</td>
                        <td class="model"> : <?=$captcha?></td>
                    </tr>
                </table>
                <?=form_close()?>
                
				<div class="buttons">
                    <div class="left">
                    	<a class="button" href="javascript:;" onclick="$('#forgot_form').submit();"><span>Submit</span></a>
                    </div>	
                </div>
			</div>
		</div>
	</div>
</div>
<br />

<style type="text/css">
	.content-inner { border: 0; padding: 20px; }
</style>