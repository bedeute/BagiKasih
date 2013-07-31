<?php
	if($row)
	{
		extract(get_object_vars($row));		
	}
	extract($_POST);
?>
<h2>Supplier Pending Product Approve Form</h2><br/>

<?=print_error($this->session->flashdata('warning'))?>
<?=print_success($this->session->flashdata('success'))?>

<?=form_open()?>
    <table class="tbl_form">
    	<tr>
            <th>Supplier Name</th>
            <td>
            	<?php /*?><?php $S = new OSupplier($supplier_id); ?>
            	<input type="text" name="supplier_name" value="<?=$S->row->name?>" readonly="readonly" />
                <input type="hidden" name="supplier_id" value="<?=$supplier_id?>" readonly="readonly" /><?php */?>
                <?=OSupplier::drop_down_select("supplier_id", $supplier_id, '')?>
            </td>
        </tr>
        <tr>
        	<th>Product ID</th>
            <td><input type="text" name="product_unique_key" value="<?=$product_unique_key?>" readonly="readonly" /></td>
        </tr>
        <tr>
        	<th>Product Name</th>
            <td><input type="text" name="product_name" value="<?=$product_name?>" readonly="readonly" /></td>
        </tr>
        <tr>
        	<th>Category</th>
            <td>
            	<?php /*?><?php $C = new OCategory($category_id); ?>
            	<input type="text" name="category_name" value="<?=$C->row->name?>" readonly="readonly" />
                <input type="hidden" name="category_id" value="<?=$category_id?>" readonly="readonly" /><?php */?>
                <?=OCategory::drop_down_select("category_id", $category_id, 'onchange="pilih_kategori()"')?>
            </td>
        </tr>
        <tr>
        	<th>Type</th>
            <td>
                <?php
				if($row) echo '<span id="type_ddl">'.OTypecategory::get_type_ddl("type_id", $type_id, "", $row->category_id).'</span>';
				else { ?><span id="type_ddl">Select Category First.</span> <?php } ?>
            </td>
        </tr>
        <tr>
        	<th>Theme</th>
            <td>
                <?php
				if($row) echo '<span id="theme_ddl">'.OThemecategory::get_theme_ddl("theme_id", $theme_id, "", $row->category_id).'</span>';
				else { ?><span id="theme_ddl">Select Category First.</span> <?php } ?>
            </td>
        </tr>
        <tr>
        	<th>Brand</th>
            <td>
                <?php
				if($row) echo '<span id="brand_ddl">'.OBrand::get_brand_ddl("brand_id", $brand_id, $optional, $row->category_id).'</span>';
				else { ?><span id="brand_ddl">Select Category First.</span> <?php } ?>
            </td>
        </tr>
        <tr>
        	<th>Other</th>
            <td>
                <textarea name="multiple_text_fields" cols="40" rows="5"><?=$multiple_text_fields?></textarea>
            </td>
        </tr>
        <tr>
        	<th>Sizes</th>
            <td>
                <textarea name="multiple_sizes" cols="40" rows="5"><?=$multiple_sizes?></textarea>
            </td>
        </tr>
        <tr>
        	<th>Description</th>
            <td>
                <textarea name="description" cols="40" rows="5"><?=$description?></textarea>
            </td>
        </tr>
        <tr>
                <td>Perkiraan Pengiriman</td>
                <td> <?php
				for($i = 1; $i <= 20; $i++) $arr[$i] = $i;
				echo dropdown("estimate_delivery",$arr,$estimate_delivery)." hari";
                ?></td>
            </tr>
         <?php /*?><tr>
            <th>SEO Keyword</th>
            <td><textarea name="seo_keyword" cols="40" rows="5"><?=$seo_keyword?></textarea>
            </td>
        </tr>
       
		<tr>
        	<th>Brand Name</th>
            <td><input type="text" name="brand_name" value="<?=$brand_name?>" readonly="readonly" /></td>
        </tr>
        <tr>
        	<th>Size Name</th>
            <td><input type="text" name="size_name" value="<?=$size_name?>" readonly="readonly" /></td>
        </tr>
        <tr>
        	<th>Description</th>
            <td><textarea name="description" cols="60" rows="30" required><?=$description?></textarea> <br /><?=form_error('description')?></td>
        </tr>      
		<?php */?>
        <tr>
            <td></td>            
            <td>
                <button class="positive button" type="submit"><span class="check icon"></span>Save</button>
                <button class="button" type="reset"><span class="reload icon"></span>Reset</button><br />
                <button class="negative button" type="button" onclick="location.href='<?=site_url($this->curpage)?>';"><span class="leftarrow icon"></span>Cancel</button>
            </td>
        </tr>    
    </table>
<?=form_close()?>


<script type="text/javascript">
	function pilih_kategori(){
		var category_id = $("#category_id").val();
		$.ajax({
			type: "POST",
			url: "/ajax/get_type_theme_product",
			data: {"category_id":category_id},
			complete: function(resp){
				var data = resp.responseText;
				var Obj = $.parseJSON(data);
				if(Obj != null)
				{
					$("#type_ddl").html(Obj.type);
					$("#theme_ddl").html(Obj.theme);
					$("#brand_ddl").html(Obj.brand);
				}
			}
		});
	}			
</script>