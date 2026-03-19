<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

$size = 'ziomm-1920x960';

$post_video_link = ziomm_get_field( 'post_video_link' );

?>

<div class="vlt-post-media-title vlt-post-media-title--style-3 jarallax">

	<?php if ( $post_video_link ) : ?>

		<div class="vlt-post-media-title__overlay"></div>

		<div class="vlt-video-button center-mode">

			<a data-fancybox data-small-btn="true" href="<?php echo esc_url( $post_video_link ); ?>">

				<i class="icon-play"></i>

			</a>

		</div>

	<?php endif; ?>

	<?php the_post_thumbnail( $size, array( 'class' => 'jarallax-img', 'loading' => 'lazy' ) ); ?>

</div>
<!-- /.vlt-post-media-title -->



