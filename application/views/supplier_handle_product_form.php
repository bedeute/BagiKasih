<?=$this->load->view('tpl_sidebar')?>

    <div class="main-content">
        <h3 class="title-register">activate the product</h3>
        
        <form action="" method="post" class="register-form">
            <table>
                <tr>
                    <th>Price</th>                    		
                    <td><input type="text" name="price" value="<?=$price?>" /></td>
                </tr>
                <tr>
                    <th></th>                            
                    <td><input type="submit" value="Save" class="register-btn"></td>
                </tr>
            </table>
        </form>    
    </div><!--.main-content-->
</div><!--.main-container-->