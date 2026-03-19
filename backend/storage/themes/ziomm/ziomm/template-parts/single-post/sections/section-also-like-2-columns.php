<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$args = array(
	'post_type' => 'post',
	'posts_per_page' => 2,
	'meta_key' => 'ziomm_post_views_count',
	'orderby' => 'meta_value_num',
	'order' => 'DESC',
	'post__not_in' => array( get_the_ID() ),
	'ignore_sticky_posts' => true,
);

$new_query = new WP_Query( $args );

?>

<div class="vlt-also-like-posts">

	<div class="vlt-gap-100"></div>

	<div class="vlt-isotope-grid" data-columns="2" data-layout="masonry" data-x-gap="30|30" data-y-gap="30|30">

		<div class="grid-sizer"></div>

		<?php

			if ( $new_query->have_posts() ) : while ( $new_query->have_posts() ) : $new_query->the_post();

				echo '<div class="grid-item">';

				get_template_part( 'template-parts/post/post-style', 'also-like' );

				echo '</div>';

			endwhile; endif;

			wp_reset_postdata();

		?>

	</div>
	<!-- /.vlt-isotope-grid -->

</div>
<!-- /.vlt-also-like-posts -->