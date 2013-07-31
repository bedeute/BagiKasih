<div id="cols-wrap" class="clearfix"> 		
	<div class="content-inner">
		<div style="display: block;" class="checkout-content">
			<div class="left">
				<div id="member"><h1>Thank You</h1></div>
				<br />         
                <?=form_open('', array('id' => 'reset_form'))?>       
					<? if($sess == "expired"){ ?>
                    
                    <p>Sorry..</p>
                    <p>This session has been expired..</p>
                    <script type="text/javascript">
                    setTimeout("location.href='<?=site_url()?>';", 5000);
                    </script>
                    
                    <? }else{ ?>
                    
                    <?=print_error($this->session->flashdata("error_string"))?>
                    <?=print_error($error_string)?>
                    <?=print_error(validation_errors())?>
                    
                    <table>
                        <tr>
                            <td class="name">New Password</td>                    
                            <td class="model"> : <input type="password" name="password" value="<?=$password?>" required autofocus /></td>
                        </tr>
                        <tr>
                            <td class="name">Confirm Password</td>                    
                            <td class="model"> : <input type="password" name="confirm_password" value="<?=$confirm_password?>" required></td>
                        </tr>
                    </table>
                    
                    <a class="button" href="javascript:;" onclick="$('#reset_form').submit();"><span>Submit</span></a>
                <?=form_close()?>
                <? } ?>
			</div>
		</div>
	</div>
</div>
<br />            

<style type="text/css">
	.content-inner { border: 0; padding: 20px; }
</style>	
    
          