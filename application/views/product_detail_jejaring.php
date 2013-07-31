<?=$this->load->view('tpl_sidebar')?>

<div class="main-content">
      <div class="product-details">
        <h3><?=$P->row->name?></h3>
        <div class="product-details-content">
        	<?php $img_def = '<img src="'.base_url('_assets/img/no_image.png').'" alt="" class="ftimg" />'; ?>
            
            <?=($P->get_photo() != "" ? '<img src="'.$P->get_photo("1", "300xcrops").'" alt="" class="ftimg" />' : $img_def)?>
          <div class="product-info-wrap">
            <ul class="product-info">
              <li>
                <p class="pd-info-label">Nama Product</p>
                <p class="pd-info-main"><?=$P->row->name?></p>
              </li>
              <li>
                <p class="pd-info-label">Kategori</p>
                <?php $C = new OCategory($P->row->category_id); ?>
                <p class="pd-info-main"><?=$C->row->name?></p>
              </li>
              <li>
                <p class="pd-info-label">Merk</p>
                <?php $B = new OBrand($P->row->brand_id); ?>
                <p class="pd-info-main"><?=$B->row->name?></p>
              </li>
            </ul>
            <div class="product-order">
              <?=form_open('cart/add_to_cart')?>
                <fieldset>
                  <div class="input-wrap">
                    <div class="clearfix">
                      <label for="product_options">Ukuran:</label>
                      <div class="custom-select">
                      	<?php /*?><?php
							$sizes = $P->get_supplier_product_size(0, 0, "p.id DESC");
							
							foreach($sizes as $r)
							{
									
							}
						?>
                        <select name="product_options" id="product_options">
                          <option value="1">Sedang &#124; IDR 250.000</option>
                          <option value="2">Kecil &#124; IDR 125.000</option>
                          <option value="3">Besar &#124; IDR 300.000</option>
                        </select><?php */?>
                        <?=OSupplierProductSize::drop_down_select("supplier_product_size_id",$supplier_product_size_id,"", get_location(), $P->row->id)?>
                      </div>
                    </div>
                  </div>
                  <div class="input-wrap submit-wrap"><input type="submit" value="Pesan Sekarang" /></div>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
        <div class="product-details-share">
          <div class="pd-share-content">
            <span class='st_facebook_hcount' displayText='Facebook'></span>
            <span class='st_twitter_hcount' displayText='Tweet'></span>
            <span class='st_email_hcount' displayText='Email'></span>
            <span class='st_pinterest_hcount' displayText='Pinterest'></span>
            <span class='st_blogger_hcount' displayText='Blogger'></span>
            <span class='st_fblike_hcount' displayText='Facebook Like'></span>
          </div>
        </div>
      </div><!--.product-details-->
      <div class="products-additional-info">
        <nav class="padd-nav">
          <ul>
            <li><a href="#padd-photo" class="tabs-controller">Foto Produk</a></li>
            <li><a href="#padd-testimoni" class="tabs-controller">Testimoni</a></li>
            <li><a href="#padd-detail" class="tabs-controller">Detail</a></li>
            <li><a href="#padd-faq" class="tabs-controller">F.A.Q</a></li>
            <li><a href="#padd-reason" class="tabs-controller">Alasan Memilih</a></li>
            <li><a href="#jejaring" class="tabs-controller">Jejaring</a></li>
          </ul>
        </nav>
        
        
        <div class="padd-container-wrap">
          <div class="tabs-container" id="padd-network">
            <h4>Jejaring</h4>
            <div class="padd-timeline">
             <?php /*?> <script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
              <script>
              new TWTR.Widget({
                version: 2,
                type: 'search',
                search: 'cake',
                rpp: 7,
                interval: 3000,
                title: '',
                subject: '',
                width: 738,
                height: 870,
                theme: {
                  shell: {
                    background: 'transparent',
                    color: '#ffffff'
                  },
                  tweets: {
                    background: '#ffffff',
                    color: '#444444',
                    links: '#1985b5'
                  }
                },
                features: {
                  scrollbar: false,
                  live: false,
                  loop: false,
                  behavior: 'default'
                }
              }).render().start();
              </script><?php */?>
              
              
                <div id="disqus_thread"></div>
					<script type="text/javascript">
                        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                        var disqus_shortname = 'hapikado'; // required: replace example with your forum shortname
            
                        /* * * DON'T EDIT BELOW THIS LINE * * */
                        (function() {
                            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                            dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
                            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                        })();
                    </script>
                    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
        
            </div>  
          </div>
        </div><!--.padd-container-wrap-->
        
        
        
        
        
        
      </div><!--.products-additional-info-->
    </div><!--.main-content-->
  </div><!--.main-container-->
  
  
  <?php /*?><script src="js/mylibs/jquery.cycle.all.min.js"></script>
  <script>var switchTo5x=true;</script>
  <script src="http://w.sharethis.com/button/buttons.js"></script>
  <script>stLight.options({publisher: "ur-ba2a98df-9d1a-9156-bb15-cfbb54923bdd"}); </script><?php */?>