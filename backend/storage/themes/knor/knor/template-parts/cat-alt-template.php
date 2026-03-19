<?php 
/*
 * Ctaegory Name Box with Color
 * @package Knor
 * @since 1.0.0
 * */  
?> 
   
	<?php $cat = get_the_category(); ?> 

	<?php foreach( $cat as $key => $category):
		$meta = get_term_meta($category->term_id, 'knor', true);
		$catColor = !empty( $meta['cat-color'] )? $meta['cat-color'] : '#0073FF';
		$catbgColor = !empty( $meta['catbg-color'] )? $meta['catbg-color'] : '#0073ff1a';
	?>

	<a class="news-cat_Name" href="<?php echo esc_url(get_category_link($category->term_id)); ?>" style="background-color:<?php echo esc_attr($catbgColor); ?>; color:<?php echo esc_attr($catColor); ?>">
		<?php echo esc_html($category->cat_name); ?>
	</a>
   
	<?php endforeach; ?>