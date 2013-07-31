<div class="add_on">
	ADD ON 1 <br />
    ADD ON 2 <br />
    ADD ON 3 <br />
    ADD ON 4 <br />
</div>

<style type="text/css">
	.add_on { border:1px solid #000; display:none; height:100px; position:absolute; }
</style>

<script type="text/javascript">
	$(document).ready(function(){
		$(".img-addon").click(function(){
			//$(".add_on").show();
			$(this).parent().find(".add_on").show();
		});
	});
</script>