<h2>Product Photo List</h2><br/>

<p><?=anchor($this->curpage."/add_photo/".$lvproduct->row->id, "Add")?></p>
<?=print_error($this->session->flashdata('warning'))?>
<?=print_success($this->session->flashdata('success'))?>

<strong>Product Name : </strong> <?=$lvproduct->row->name?> <br />

<?php
    if(sizeof($list) <= 0) echo "<p class='text_red'>Product Photo list is empty.</p>";
    else
    {
?>
        <table class="tbl_list">
            <thead>
                <tr>
                    <th>No.</th>        	
                    <th>Image</th>                    
                    <th>Caption</th>
                    <th>Ordering</th>                    
                    <th>Action</th>
                </tr>
            </thead>
                
            <?php 
                $i=1 + $uri;
                foreach($list as $row):
				
					$lvproductphoto = new LVProductPhoto();
					$lvproductphoto->setup($row);
					$img_url = $lvproductphoto->get_photo("100");
					$img = '<img src="'.$img_url.'" alt="Preview" />';
				
               	 	extract(get_object_vars($row));
            ?>		        
            <tbody>
                <tr class="<?=alternator("odd", "even")?>">
                    <td><?=$i?></td>            
                    <td><?=$img?></td>                    
                    <td><?=trimmer($caption, 100)?></td>
                    <td><?=$ordering?></td>
                    <td>
                    	<?=anchor($this->curpage."/delete_photo/".$product_id."/".$id, "delete", array("onclick" => "return confirm('Are you sure?');"))?> |
                        <?=anchor($this->curpage."/edit_photo/".$product_id."/".$id, "edit")?>
                    </td>
            	</tr>        
            </tbody>
            <?php 
                $i++; 
                endforeach; 
            ?>	
        </table>

        <?=$pagination?>
<?php
    }
	echo anchor($this->curpage, '&laquo; back');
?>