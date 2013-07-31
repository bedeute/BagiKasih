<?=$this->load->view('tpl_sidebar')?>

    <div class="main-content">
        <h3 class="title-register">User Profile</h3>
        
        <form action="" method="post" class="register-form">
			<?=print_error($this->session->flashdata("error_string"))?>
            <?=print_error($error_string)?>
            <?=print_error(validation_errors())?>
        
            <table>
                <tr>
                    <th>Name</th>                    		
                    <td><?=$cu->name?></td>
                </tr>
                <tr>
                    <th>Address</th>                    		
                    <td><?=$cu->address?></td>
                </tr>
                <tr>
                    <th>City</th>                    		
                    <td><?=$cu->city?></td>
                </tr>
                <tr>
                    <th>Phone</th>                    		
                    <td><?=$cu->phone?></td>
                </tr>
                <tr>
                    <th>Fax</th>                    		
                    <td><?=$cu->fax?></td>
                </tr>
                <tr>
                    <th>Location</th>
                    <?php $L = new Olocation($cu->location_id); ?>                    		
                    <td><?=$L->row->name?></td>
                </tr>               
                <tr>
                    <th>Email</th>                            
                    <td><?=$cu->email?></td>
                </tr>
            </table>
            
            <div class="register-link">
            	<a href="<?=site_url('user/edit_profile')?>">Edit Profile</a>
            </div>
        </form>    
    </div><!--.main-content-->
</div><!--.main-container-->