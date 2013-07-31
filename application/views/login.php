<?=$this->load->view('tpl_sidebar')?>

    <div class="main-content">
        <h3 class="title-register">Login</h3>
        
        <form action="" method="post" class="register-form">
            <fieldset>
				<?=print_error($this->session->flashdata("error_string"))?>
                <?=print_error($error_string)?>
                <?=print_error(validation_errors())?>
                
                <table>
                    <tr>
                        <th>Email</th>
                        <td><input type="text" name="email" id="email" value="<?=$email?>" autofocus required></td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td><input type="password" name="password" id="password" value="<?=$password?>" required></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><input type="submit" value="Submit" class="register-btn"></td>
                    </tr>
                </table>
                
                <div class="register-link">
                	<a href="<?=site_url('account/register')?>">Register</a> | <a href="<?=site_url('account/forgot_password')?>">Lost Your Password?</a>
                </div>
                
                <div class="register-link">
            	<a href="<?=site_url('account_supplier/login')?>">Are You A Supplier ?? Login Now !!</a>
            </div>
            </fieldset>
        </form>  
    </div><!--.main-content-->
</div><!--.main-container-->