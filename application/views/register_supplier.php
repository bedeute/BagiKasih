<?=$this->load->view('tpl_sidebar')?>

    <div class="main-content">
        <h3 class="title-register">Register Supplier</h3>
        
        <form action="" method="post" class="register-form">
			<?=print_error($this->session->flashdata("error_string"))?>
            <?=print_error($error_string)?>
            <?=print_error(validation_errors())?>
        
            <table>
                <tr>
                    <th>Name</th>                    		
                    <td><input type="text" name="name" value="<?=set_value('name')?>" autofocus required></td>
                </tr>
                <tr>
                    <th>Address</th>                    		
                    <td><textarea name="address" cols="60" rows="7" required><?=set_value('address')?></textarea></td>
                </tr>
                <tr>
                    <th>Phone</th>                    		
                    <td><input type="text" name="phone" value="<?=set_value('phone')?>" required></td>
                </tr>
                <tr>
                    <th>Fax</th>                    		
                    <td><input type="text" name="fax" value="<?=set_value('fax')?>" required></td>
                </tr>
                <tr>
                    <th>Website</th>                    		
                    <td><input type="url" name="website" value="<?=set_value('website')?>" required></td>
                </tr>
                <tr>
                    <th>Location</th>                    		
                    <td><?=Olocation::drop_down_select("location_id", $location_id, "", "- Select Location -")?></td>
                </tr>
                <tr>
                    <th>Description</th>                    		
                    <td><textarea name="description" cols="60" rows="7" required><?=set_value('description')?></textarea></td>
                </tr>        
                <tr>
                    <th>Email</th>                            
                    <td><input type="email" class="text" name="email" value="<?=set_value('email')?>" required></td>
                </tr>
                <tr>
                    <th>Password</th>                            
                    <td><input type="password" name="password" required></td>
                </tr>
                <tr>
                    <th>Confirm Password</th>                    
                    <td><input type="password" name="confirm_password" required></td>
                </tr>
                <tr>
                    <th>Security Code</th>                            
                    <td><?=$captcha?></td>
                </tr>
                <tr>
                    <th></th>                            
                    <td><input type="submit" value="Register Now" class="register-btn"></td>
                </tr>
            </table>
            
            <div class="register-link">
            	<a href="<?=site_url('account_supplier/login')?>">Login</a> | <a href="<?=site_url('account_supplier/forgot_password')?>">Lost Your Password?</a>
            </div>
        </form>    
    </div><!--.main-content-->
</div><!--.main-container-->