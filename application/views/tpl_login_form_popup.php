<?php /*?><?=form_open("account/login")?>
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
<?=form_close()?> <?php */?>






<link rel="shortcut icon" href="<?=base_url('_assets/nw_css_login/favicon.ico')?>"> 
<link rel="stylesheet" type="text/css" href="<?=base_url('_assets/nw_css_login/css/demo.css')?>" />
<link rel="stylesheet" type="text/css" href="<?=base_url('_assets/nw_css_login/css/style.css')?>" />
<link rel="stylesheet" type="text/css" href="<?=base_url('_assets/nw_css_login/css/animate-custom.css')?>" />


<div class="container">
    <section>				
        <div id="container_demo" >
            <a class="hiddenanchor" id="toregister"></a>
            <a class="hiddenanchor" id="tologin"></a>
            <div id="wrapper">
                <div id="login" class="animate form">
                    <form action="post" autocomplete="on"> 
                        <h1>Log in</h1> 
                        <p> 
                            <label for="username" class="uname"> Connect With :</label>
                           <div id="fb"><a><img src="<?=base_url('_assets/nw_css_login/f-conn_ico.png')?>"></a></div>
                        </p>
                        <br>
                        <p> 
                            <label for="username" class="uname" data-icon="u" > Your email or username </label>
                            <input id="username" name="username" required="required" type="text"/>
                        </p>
                        <p> 
                            <label for="password" class="youpasswd" data-icon="p"> Your password </label>
                            <input id="password" name="password" required="required" type="password"/> 
                        </p>
                        <p class="keeplogin"> 
                            <a><label for="loginkeeping">Forgot Password ?</label></a>
                        </p>
                        <p class="login button"> 
                            <input type="submit" value="Login" /> 
                        </p>
                        <p class="change_link">
                            Not a member yet ?
                            <a href="#toregister" class="to_register">Sign Up</a>
                        </p>
                    </form>
                </div>

                <div id="register" class="animate form">
                    <form action="mysuperscript.php" autocomplete="on"> 
                        <h1> Non Member Sign up </h1> 
                        <p> 
                            <label for="usernamesignup" class="uname" data-icon="u">Nama Lengkap</label>
                            <input id="usernamesignup" name="usernamesignup" required="required" type="text"  />
                        </p>
                        <p> 
                            <label for="emailsignup" class="youmail" data-icon="e" >Email</label>
                            <input id="emailsignup" name="emailsignup" required="required" type="email"/> 
                        </p>
                        <p> 
                            <label for="passwordsignup" class="youpasswd" data-icon="p">Password </label>
                            <input id="passwordsignup" name="passwordsignup" required="required" type="password"/>
                        </p>
                        <p> 
                            <label for="passwordsignup_confirm" class="youpasswd" data-icon="p">Konfirmasi password </label>
                            <input id="passwordsignup_confirm" name="passwordsignup_confirm" required="required" type="password" />
                        </p>
                        <p class="signin button"> 
                            <input type="submit" value="Sign up"/> 
                        </p>
                        <p class="change_link">  
                            Already a member ?
                            <a href="#tologin" class="to_register"> Go and log in </a>
                        </p>
                    </form>
                </div>
                
            </div>
        </div>  
    </section>
</div>