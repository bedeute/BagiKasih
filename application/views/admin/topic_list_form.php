<h2>Topics Management</h2>
    
<?php
	$showform = true;
	$category_id = $_GET['category_id'];
	if($row)
	{	
		extract(get_object_vars($row));	
		
	}
	
		
	extract($_POST);
?>
<?php
if($showform)
{
	?>
	<?=form_open()?>    
        <table class="tbl_form">    	
            <tr>
                <th>Name</th>
                <td><input type="text" name="name" value="<?=$name?>" placeholder="Topic Name" autofocus required/> <br/><?=form_error('name')?></td>
            </tr>
            <tr>
                <th>Category</th>
                <td><?php echo OCategory::drop_down_select("category_id",$category_id); ?> <br/><?=form_error('category_id')?></td>
            </tr>
            <tr>
                <td></td>         
                <td>
                    <button class="positive button" type="submit"><span class="check icon"></span>Save</button>
                    <button class="button" type="reset"><span class="reload icon"></span>Reset</button>
                </td>
            </tr>    
        </table>   
    <?=form_close()?>
    <?
}
?>
<br /><hr />
<strong>Filter by Category: </strong><?=anchor("admin/topics/","All")?> | 
<?php
$catlist = OCategory::get_list(0,0);
foreach($catlist as $cat)
{
	echo anchor("admin/topics/?category_id={$cat->id}",$cat->name)." | ";
}
?><br /><br />
<?=print_error($this->session->flashdata('warning'))?>
<?=print_success($this->session->flashdata('success'))?>

<?php 
if(sizeof($list) <= 0) echo "<p class='text_red'>Topic list is empty.</p>";
else
{
?>
<table class="tbl_list">
	<thead>
		<tr>
			<th>No.</th>        	
			<th>Name</th>
            <th>Category</th>
			<th>Action</th>                             
		</tr>
	</thead>
	<tbody>
	<?php 
		$i=1 + $uri;
		$ob = new OTopic();
		foreach($list as $row):
			$ob->setup($row);
		?>
		<tr class="<?=alternator("odd", "even")?>" data_id="<?=$row->id?>">
			<td><?=$i?></td>            
			<td><?=$row->name?></td>
            <td><?=$ob->get_category()->name?></td>
			<td>
            	
				<?=anchor($this->curpage.'/delete/'.$row->id, 'delete', array("onclick" => "return confirm('Are you sure?');"))?> |
				<?=anchor($this->curpage.'/listing/'.$row->id, 'edit')?>
			</td>
		</tr>
        <?php 
		$i++;
		endforeach; 
	?>	
	</tbody>
</table>

<?=$pagination?>

<script type="text/javascript">
	$(document).ready(function()
		   {
			   $('table.tbl_list tbody').sortable({
										  update: function(event,ui)
										  {
											  update_sorting();
										  }
										  });
		   });
	
	function update_sorting()
	{
		var url = '/admin/topics/sorting';
		var total = $('table.tbl_list tbody tr').length;
		var orderlist = new Array(total);
		var count = 0;
		$('table.tbl_list tbody tr').each(function (elm)
														  {
															  orderlist[count] = $(this).attr('data_id');
															  count++;
														  });
		var updatelist = orderlist.join(",");
		$.ajax({
			   url: url,
			   data: { 'sorts' : updatelist },
			   type: 'POST'
			   });
	}
</script>

<?php
}
?>