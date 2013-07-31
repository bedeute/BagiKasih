<?=$this->load->view('tpl_sidebar')?>

    <div class="main-content">
        <h3 class="title-register">Supplier Shipping Fee Form</h3>
        
        <form action="" method="post" class="register-form">
            <table>
            	<tr>
                    <th>Available Stock</th>                    		
                    <td><input type="text" name="available_stock" value="<?=$available_stock?>" /></td>
                </tr>
                <tr>
                    <th>Shipping Fee</th>                    		
                    <td><input type="text" name="shipping_fee" value="<?=$shipping_fee?>" /></td>
                </tr>
                <tr>
                    <th></th>                            
                    <td><input type="submit" value="Save" class="register-btn"></td>
                </tr>
            </table>
        </form>    
    </div><!--.main-content-->
</div><!--.main-container-->