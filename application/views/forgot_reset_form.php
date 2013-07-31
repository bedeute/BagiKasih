<?=$this->load->view('tpl_sidebar')?>

    <div class="main-content">
        <h3 class="title-register">Forgot Form Thank You</h3>
        <form action="" method="post" class="register-form">         
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
                    <th>New Password</th>                    
                    <td><input type="password" name="password" value="<?=$password?>" required autofocus /></td>
                </tr>
                <tr>
                    <th>Confirm Password</th>                    
                    <td><input type="password" name="confirm_password" value="<?=$confirm_password?>" required></td>
                </tr>
                <tr>
                    <th></th>
                    <td><input type="submit" value="Send" class="register-btn"></td>
                </tr>
            </table>
            
            <div class="register-link">
            	<a href="<?=site_url('account/register')?>">Register</a> | <a href="<?=site_url('account/forgot_password')?>">Lost Your Password?</a>
            </div>        
        </form>
        <? } ?>
	</div><!--.main-content-->
</div><!--.main-container-->     
    
          