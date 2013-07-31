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
                    <th>Month - Year</th>                    
                    <th>Total Order</th>                    
                    <th>Total Omzet</th>
                </tr>
            </thead>
            <tbody>                
            <?php 
                $i=1 + $uri;				
                foreach($list as $row):
                	extract(get_object_vars($row));
            ?>		        
                <tr class="<?=alternator("odd", "even")?>" data_id="<?=$row->id?>">
                    <td><?=$i?></td>            
                    <td><?=$per_month?> - <?=$per_year?></td>
                    <td><?=$total_order?></td>
                    <td><?=$total_price?></td>
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