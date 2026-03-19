<?php 
/*
 * Ctaegory Name Box 
 * @package Knor
 * @since 1.0.0
 * */  
?> 
   
	<?php $cat = get_the_category(); ?> 

	<?php foreach( $cat as $key => $category):
		$meta = get_term_meta($category->term_id, 'knor', true);
		$catColor = !empty( $meta['cat-color'] )? $meta['cat-color'] : '#FF3524';
	?>

	<a class="news-cat_Name" href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
		<?php echo esc_html($category->cat_name); ?>
	</a>
   
	<?php endforeach; ?>