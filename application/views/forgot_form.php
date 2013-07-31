<?=$this->load->view('tpl_sidebar')?>

<div class="main-content">
    <h3 class="title-register">Forgot Form</h3>
    <?php if(sizeof($_POST) > 0) extract($_POST); ?>
    
    <form action="" method="post" class="register-form">
        <?=print_error($this->session->flashdata("error_string"))?>
        <?=print_error($error_string)?>
        <?=print_error(validation_errors())?>
        
        <table>            	
            <tr>
                <th>Email</th>                            
                <td><input type="text" name="forgot_email" size="50px" value="<?=$forgot_email?>" required autofocus /></td>
            </tr>                
            <tr>
                <th>Security Code</th>                            
                <td><?=$captcha?></td>
            </tr>
            <tr>
                <th></th>
                <td><input type="submit" value="Send Reset Password Instruction" class="register-btn"></td>
            </tr>
        </table>
    </form>
</div><!--.main-content-->