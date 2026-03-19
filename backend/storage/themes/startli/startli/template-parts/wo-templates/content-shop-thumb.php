<?php
$settings = '';
global $startli_option;
$post_id     = get_the_ID();
$product     = wc_get_product($post_id);
$rating      = $product->get_average_rating();
$rating_html = wc_get_rating_html($product->get_average_rating());
$is_feat     = $product->is_featured();
$rcount      = $product->get_rating_count();
$content 	 = get_the_content();
$client  	 = get_post_meta(get_the_ID(), 'client', true);
$p2ndImg 	 = get_post_meta(get_the_ID(), 'rt_product_2nd_img_id', true);
$arating 	 = get_post_meta(get_the_ID(), '_wc_average_rating', true);
$tsale   	 = get_post_meta(get_the_ID(), 'total_sales', true);
$price   	 = get_post_meta(get_the_ID(), '_price', true);

$gallery = $product->get_gallery_image_ids();
if($gallery){
	array_unshift($gallery, get_post_thumbnail_id());
	if($p2ndImg){
		$gallery[] = $p2ndImg;
	}
}

// The product average rating (or how many stars this product has)
$average_rating = $product->get_average_rating();

// The product stars average rating html formatted.
$average_rating_html = wc_get_rating_html($average_rating);

// Display stars average rating html
$terms = get_the_terms($product->get_id(), 'product_cat');
// var_dump($terms);
$unique = rand(2012, 35120);

?>
<div class="product-inner">
	<div class="product-list">
		<?php if(has_post_thumbnail()): ?>
			<div class="product-img">
			
				<a href="<?php the_permalink();?>" class="feature--image">
					<?php woocommerce_template_loop_product_thumbnail('woocommerce_thumbnail');?>
				</a>
			
				<div class="sale--box">
					<?php

					if ( $product->is_on_sale() )  {    
						woocommerce_show_product_loop_sale_flash();
					}
					
					?>
				</div>
				
					
			</div>
		<?php endif;?>	
	</div>


