<?=$this->load->view('tpl_sidebar')?>

    <div class="main-content">
        <h3 class="title-register">User Bank Account</h3>
        
        
			<?=print_success($this->session->flashdata("account_msg"))?>
                    	
            <?=anchor('user/add_bank_account', 'Add Bank Account')?>
            <br /><br />
            
            
			<?php
            if(sizeof($list) <= 0) echo '<p class="text-red">Bank Account is empty.</p>';
            else
            {
				?>
                <table class="tbl_list">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Bank Name</th>
                            <th>Account Number</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="<?=alternator("odd", "even")?>">
                	<?php
                    $i = 1;
                    foreach($list as $r)
                    {
                        ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$r->customer_name?></td>
                            <td><?=$r->bank_name?></td>
                            <td><?=$r->account_number?></td>
                            <td>
                            	<?=anchor("user/delete_bank_account/".$r->id, "Delete", array("onclick" => "return confirm('Are you sure?');"))?>
								<?=" | ".anchor("user/edit_bank_account/".$r->id, "Edit")?>
                            </td>
                        </tr>
                        <?php	
                        $i++;
                    }	
                    ?>
                    </tbody>
            	</table>
                <?php	
            }
            ?>
                    
    </div><!--.main-content-->
</div><!--.main-container-->

<style type="text/css">
	table.tbl_list { border-collapse:collapse; }
	table.tbl_list thead tr{background-color: #06F; color:#fff; }
	table.tbl_list td, 
	table.tbl_list th{padding: 4px 6px; vertical-align:top; margin:0; border: 1px solid #ddd; font-size:12px; }
	table.tbl_list tbody tr.even{background-color: #fffff;}
	table.tbl_list tbody tr.odd{background-color: #0FF;}
	table.tbl_list tbody tr.hovcolor,
	table.tbl_list tbody tr:hover{background-color: #CCC;color:#000;}
</style>