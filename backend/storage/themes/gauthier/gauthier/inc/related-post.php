<div class="related-wrapper">
	<div class="related-maintitle">
        <?php esc_html_e('Related Post', 'gauthier'); ?>
    </div>
<div class="related-firstwrapper">	
    <?php
	$cats = wp_get_post_categories($post->ID);
    if ($cats) {
    $first_cat = $cats[0];
    $args=array(
      'cat' => $first_cat, 
      'post__not_in' => array($post->ID),
	  'orderby' => 'rand',
      'showposts'=>4,
      'ignore_sticky_posts'=>1
    );
    $my_query = new WP_Query($args);
    if( $my_query->have_posts() ) {
	?> 
    <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
    <div class="related-subwrapper">
		<?php $thumb = get_post_thumbnail_id(); 
		$img_url = wp_get_attachment_url( $thumb,'full' ); 
		$image = aq_resize( $img_url, 444, 444, true,true,true ); ?>
		<?php if ($image) { ?>
			<div class="related-thumb">
			<img src="<?php echo esc_html($image) ?>"/>
		</div>	
	<?php } else { ?>
<?php } ?>		
        <div class="module4-meta"><?php echo esc_html (get_the_date()); ?></div>
        <div class="related-title">
			<h5>
			<a href="<?php the_permalink();?>">
			<?php echo esc_html(wp_trim_words( get_the_title(),7, ''), 'gauthier'); ?>
			</a></h5>
		</div>
    </div>
    <?php endwhile; } } 
  wp_reset_query();  
  ?>
</div>
</div>