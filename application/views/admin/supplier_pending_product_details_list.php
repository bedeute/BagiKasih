<h2>Supplier Pending Product Detail List</h2><br/>

<?=print_error($this->session->flashdata('warning'))?>
<?=print_success($this->session->flashdata('success'))?>

<table class="tbl_form">
    <tr>
        <th>Supplier Pending Product ID</th><td>:</td>
        <td><strong><?=$SPP->row->id?></strong></td>
    </tr>
    <tr>
        <th>Supplier Name</th><td>:</td>
        <td><?=$SPP->row->name?></td>
    </tr>
    <tr>
    	<?php $C = new OCategory($SPP->row->category_id); ?>
        <th>Category Name</th><td>:</td>
        <td><?=$C->row->name?></td>
    </tr>
    <tr>
    	<th>Brand Name</th><td>:</td>
        <td><?=$SPP->row->brand_name?></td>
    </tr>
    <tr>
    	<th>Size Name</th><td>:</td>
        <td><?=$SPP->row->size_name?></td>
    </tr>
    <tr>
    	<th>Description</th><td>:</td>
        <td><?=nl2br($SPP->row->description)?></td>
    </tr>
    <tr>
    	<th>Status</th><td>:</td>
        <td><?=$SPP->row->status?></td>
    </tr>
</table>

<br /><br />
	<?php
	$SPP = new OSupplierPendingProduct($row);
	$list = $SPP->get_details(0, 0);
	
	if(sizeof($list) <= 0) echo '<p class="text-red">Photos Not Found.</p>';
	else
	{
	?>
<table class="tbl_list">
    <thead>
        <tr>
            <?php /*?><th>No.</th><?php */?>
            <th>Image</th>            
        </tr>
    </thead>
    <tbody>    
    <?php 
		foreach($list as $row) :
            $SPPP = new OSupplierPendingProductPhoto($SPP->row->supplier_pending_product_id);
			$SPPP->setup();
			
			$img_url = $SPPP->get_image('200xcrops');
			$img = '<img src="'.$img_url.'" alt="" width="100px" height="100px" />';
    ?>       
        <tr class="<?=alternator("odd", "even")?>">
            <?php /*?><td><?=$i?></td><?php */?>
            <td><?=$img?></td>           
        </tr>
    <?php 
        unset($SPPP);
       /* $i++;*/
    endforeach; ?>
    </tbody>
</table>
<?php
	}
?>

<?php /*?><?=$pagination?><?php */?>
<br />
<?=anchor($this->curpage, '&laquo; back')?>