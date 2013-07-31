<?=$this->load->view('tpl_sidebar')?>

    <div class="main-content">
        <h3 class="title-register">User Address</h3>
        
        
			<?=print_success($this->session->flashdata("address_msg"))?>
                    	
            <?=anchor('user/add_address', 'Add Address')?>
            <br /><br />
            
            
			<?php
            if(sizeof($list) <= 0) echo '<p class="text-red">Address is empty.</p>';
            else
            {
				?>
                <table class="tbl_list">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Address</th>
                            <th>City</th>
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
                            <td><?=$r->address?></td>
                            <td><?=$r->city?></td>
                            <td>
                            	<?=anchor("user/delete_address/".$r->id, "Delete", array("onclick" => "return confirm('Are you sure?');"))?>
								<?=" | ".anchor("user/edit_address/".$r->id, "Edit")?>
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