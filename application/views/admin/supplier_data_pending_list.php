<h2>Supplier Data Order List</h2><br/>

<?=print_error($this->session->flashdata('warning'))?>
<?=print_success($this->session->flashdata('success'))?>
<br />


<?php
    if(sizeof($list) <= 0) echo "<p class='red'>There is no data found.</p>";
    else
    {
?>
		<table class="tbl_list">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Order Date</th>
                    <th>Product Name</th>
                    <th>Qty</th>                    
                    <th>TIMER (The length of time the goods are not delivered)</th>
                </tr>
            </thead>
            <tbody>                
            <?php 
                $i=1 + $uri;				
                foreach($list as $row):
                	extract(get_object_vars($row));
					
					$OD = new OOrderDetail($order_detail_id);
					$P = new OProduct($OD->row->product_id);
            ?>		        
                <tr class="<?=alternator("odd", "even")?>" data_id="<?=$row->id?>">
                    <td><?=$i?></td>            
                    <td><?=$dt?></td>
                    <td><?=$P->row->name?></td>
                    <td><?=$qty?></td>
                    <?php $note_arr = datediff(date("Y-m-d"), $dt); ?>
                    <td>
						<?=$note_arr['years']?> year, 
                    	<?=$note_arr['months']?> month,
                        <?=$note_arr['days']?> day
                    </td>
                </tr>
            <?php				
                $i++;
                endforeach; 
            ?>	
            </tbody>
        </table>

        <?=$pagination?>        
<?php
    }
?>