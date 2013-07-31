<!-- footer -->
<div class="footer">
	<div class="container">
		<div class="row-fluid">
			<div class="span4">
				<h4><?=langtext('why_choose_us')?>?</h4>
				<ul>
					<li>
						<a href="#">Brand i Research ranks among the industry</a>
					</li>
					<li>
						<a href="#">Brand i Research ranks among the industry</a>
					</li>
					<li>
						<a href="#">Brand i Research ranks among the industry</a>
					</li>
					<li>
						<a href="#">Brand i Research ranks among the industry</a>
					</li>
				</ul>
			</div>
			<div class="span4">
				<h4 class="text">Latest Tweets <a href="http://twitter.com/happy_kado">@happy_kado</a></h4>
				<a class="twitter-timeline" href="https://twitter.com/Happy_Kado" data-widget-id="322612857105678336" data-chrome="nofooter">Tweets by @Happy_Kado</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
			<!--<div class="span4">
				<h4 class="text">Latest Tweets <a href="http://twitter.com/happy_kado">@happy_kado</a></h4>
				<a class="twitter-timeline" href="https://twitter.com/Happy_Kado" data-widget-id="322612857105678336">Tweets by @Happy_Kado</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
			<style>
			.twtr-hd h4 {
				padding-left: 0;
				padding-bottom: 0;
				border-bottom: none;
			}
			</style>
			<div class="span4">
				<h4><?=langtext('latest_tweet')?> <a href="http://twitter.com/happy_kado">@happy_kado</a></h4>
				<script src="http://widgets.twimg.com/j/2/widget.js"></script>
				<script>
					new TWTR.Widget({version: 2,type: 'profile',rpp: 5,interval: 5000,width: 300,height: 400,theme: {shell: {background: '#69D',color: 'white'},tweets: {background: 'white',color: 'black',links: '#69D'}},features: {scrollbar: false,loop: false,live: false,hashtags: false,timestamp: false,avatars: false,toptweets: false}}).render().setUser('happy_kado').start();
				</script>
			</div>-->
			<div class="span4">
				<h4>Facebook Fanpage</h4>
				<div class="fb-like-box" style="background:#ffffff;" data-href="http://www.facebook.com/happykado" data-width="300" data-height="245" data-show-faces="true" data-stream="false" data-header="true"></div>
			</div>
		</div>
	</div>

</div>
<!-- end footer -->

<!-- bottom section -->
<div class="bottom-section">
	<div class="container">
		<p class="pull-left">
			<a href="https://www.facebook.com/HappyKado" target="_blank">
				<img src="<?=base_url('assets/img/facebook_21x21.png')?>">
			</a>
			<a href="https://twitter.com/happy_kado" target="_blank">
				<img src="<?=base_url('assets/img/twitter_21x21.png')?>">
			</a>
			<a href="<?=base_url()?>">happykado.com</a> @ 2013
		</p>
		<ul class="inline pull-right">
			<li>
				<a href="<?=base_url()?>">Home</a>
			</li>
			<li>
				<?=anchor('about_us', langtext('about_us'))?>
			</li>
			<li>
				<?=anchor('contact_us', langtext('contact_us'))?>
			</li>
			<li>
				<?=anchor('site_map', langtext('site_map'))?>
			</li>
			<li>
				<?=anchor('faq', 'F.A.Q')?>
			</li>
			<li>
				<?=anchor('shipping_info', langtext('shipping_info'))?>
			</li>
			<li>
				<?=anchor('links', 'Links')?>
			</li>
			<li>
				<?=anchor('account_supplier/register_login_form', langtext('supplier_login'))?>
			</li>
		</ul>
	</div>
</div>
<!-- end bottom section -->

<!-- script -->
    <script src="<?=base_url('assets/js/bootstrap-transition.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-alert.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-modal.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-dropdown.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-scrollspy.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-tab.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-tooltip.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-popover.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-button.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-collapse.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-carousel.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-typeahead.js')?>"></script>
    <script src="<?=base_url('assets/js/bootstrap-affix.js')?>"></script>
    <script src="<?=base_url('assets/js/hideMaxListItem-min.js')?>"></script>

    <script src="<?=base_url('assets/js/application.js')?>"></script>

    <script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({publisher: "b0d8f2a2-d574-4a19-bc22-2df91619b92c", doNotHash: true, doNotCopy: false, hashAddressBar: false});</script>
	
    <script language="javascript">
	$('#custom').raty({
		scoreName: 'entity.score',
		path: '<?=base_url('assets/img/')?>',
		number: 5,
		start: 1
	});

	var val = $('.fixed').data('value');
	$('.fixed').raty({
	  readOnly:  true,
	  path: '<?=base_url('assets/img/')?>',
	  start: val
	});
</script>
<style>
	#custom img, #fixed img {		
		width:15px;
		height:15px;
		display:inline-block;
		margin:0px;
	}
</style>

    <script>
		!function ($) {
			$(function(){
			  $('#myCarousel').carousel()
			})
		}(window.jQuery)

		$('#popover').popover({ 
				html : true,
				title: function() {
				return $("#popover-head").html();
			},
				content: function() {
				return $("#popover-content").html();
			}
		});
		
		$('#pupover').popover({ 
				html : true,
				title: function() {
				return $("#pupover-head").html();
			},
				content: function() {
				return $("#pupover-content").html();
			}
		});
		
		$("#loc1").click(function () {
		  $("#fup").slideToggle();
		});
    </script>
	
	<script src="<?=base_url('assets/js/jquery.number.min.js')?>"></script>
	<script>
		$(function(){
			   
			$('.create-own select').change(function() {
				$('#subtotal_after').remove();
				
				var total_add_on = get_addon_total();
				var product_size_price = get_size_total();
				var total_qty = get_qty();
				
				var sub_total = $('#sub_total').text().replace(/\./g, '');
				var total = parseInt(total_qty) * (parseFloat(sub_total) + parseFloat(product_size_price) + parseFloat(total_add_on));
				
				$('#sub_total').hide().parent().append('<span id="subtotal_after">'+total+'</span>');
				$('#subtotal_after').number( total, 0, ',', '.' );
				
				var point_setting = $('#point_setting').val();
				var total_point_received = Math.floor(total / point_setting);			
				$('#current_total_point').text(total_point_received);
			});
			
			$('.cart .prosizid').change(function() {
				$('#subtotal_after').remove();
				
				var total_add_on = get_addon_total();
				var product_size_price = get_size_total();
				var total_qty = get_qty();
				
				var sub_total = $('#sub_total').text().replace(/\./g, '');
				var total = parseInt(total_qty) * (parseFloat(sub_total) + parseFloat(product_size_price) + parseFloat(total_add_on));
				
				$('#sub_total').hide().parent().append('<span id="subtotal_after">'+total+'</span>');
				$('#subtotal_after').number( total, 0, ',', '.' );
				
				var point_setting = $('#point_setting').val();
				var total_point_received = Math.floor(total / point_setting);			
				$('#current_total_point').text(total_point_received);
			});
			
			$('.cart .new_qty').change(function() {
				$('#subtotal_after').remove();
				
				var total_add_on = get_addon_total();
				var product_size_price = get_size_total();			
				var total_qty = get_qty();
				
				var sub_total = $('#sub_total').text().replace(/\./g, '');
				var total = parseInt(total_qty) * (parseFloat(sub_total) + parseFloat(product_size_price) + parseFloat(total_add_on));
				
				$('#sub_total').hide().parent().append('<span id="subtotal_after">'+total+'</span>');
				$('#subtotal_after').number( total, 0, ',', '.' );
				
				var point_setting = $('#point_setting').val();
				var total_point_received = Math.floor(total / point_setting);			
				$('#current_total_point').text(total_point_received);
			});
			
			function get_addon_total()
			{
				var total_add_on = 0;
				$('.create-own table tbody tr td.man').each(function(index){
					var add_on_price = $(this).find("span").text().replace('Rp ', '');
					var qty = $(this).find("select").val();
					var total_add_on_each = parseFloat(add_on_price) * parseInt(qty);
					total_add_on = total_add_on + total_add_on_each;
				});
				return total_add_on;
			}
			
			function get_size_total()
			{
				var product_size_price = "";
				$('.cart .prosizid option:selected').each(function() {
					var elm = $(this).text().indexOf('Rp ');
					product_size_price += $(this).text().substr(elm + 2, $(this).text().length).replace(/\./g, '');
				});
				return product_size_price;
			}
			
			function get_qty()
			{
				var new_qty = $('.cart .new_qty').val();
				return new_qty;
			}
		})
	</script>

	<script>
		$(function() {
			var val = $('#estimate_delivery').data('value');
			
			var date = new Date();  
			var d  = date.getDate();  

			$( "#datepicker" ).datepicker({
				dateFormat: 'yy-mm-dd',
				minDate: "+<?=$estimate_delivery?>"
			});			
		});
	</script>

	<script type="text/javascript">
		function pilih_location_details(){
			var location_details = $("#shipping_location_details").val();
			var location_id = $("#shipping_location_id").val();
			$.ajax({
				type: "POST",
				url: "<?=base_url()?>ajax/get_shipping_fee",
				data: {"location_details":location_details, "location_id":location_id},
				complete: function(resp){
					var data = resp.responseText;
					var Obj = $.parseJSON(data);
					$("#shipping_fee").val(Obj.shipping_fee);
				}
			});
			
			if(location_details == 'outside'){
				$( "#location2" ).css('display','block');
				$( "#location2" ).attr('required','required');
			} else {
				$( "#location2" ).css('display','none');
				$( "#location2" ).removeAttr('required');
			}
		}
		
		/*function set_kosong(){
			$("#shipping_location_details").val("");
			$("#shipping_fee").val("");
		}*/
	</script>
	
	<script>
		$( "#datepicker" ).datepicker({
			dateFormat: 'yy-mm-dd',
			minDate: "+<?=$estimate_delivery?>"
		});	
	</script>
	
	<script>
		$(function() {
			$( "#shipping_no_address" ).change(function(){
				// alert($(this).val());
				if($(this).val() == 'telepon ke penerima'){
					$( "#shipping_phone" ).attr('required','required');
				} else {
					$( "#shipping_phone" ).removeAttr('required');
				}
			});
		});
	</script>
	
<!-- end script -->
</body>

</html>