<?=print_error($this->session->flashdata('newsletter_warning'))?>
<?=print_success($this->session->flashdata('newsletter_success'))?>
<?=print_error(validation_errors())?>

<div id="newsletter">	
	<h4>NEWSLETTER 
    	<?=form_open('newsletters/add', array('id' => 'newsletter-form'))?>
        	<input type="text" name="newsletter_email" value="<?=set_value('newsletter_email')?>" />
            <a href="javascript:;" onclick="$('#newsletter-form').submit();" class="button"><span>Submit</span></a>
        <?=form_close()?>
    </h4>
</div>
<div id="banner-index">
	<div class="headline"><h4> Why? Coz HAPPY KADO give you more...and more...</h4></div>
</div>