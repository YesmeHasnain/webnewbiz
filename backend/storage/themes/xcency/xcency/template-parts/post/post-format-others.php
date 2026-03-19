<?php if ( has_post_thumbnail() ) : ?>

	<div class="post-thumbnail-wrapper">
		<img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'xcency-large' ) ?>"
		     alt="<?php echo get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ); ?>">
	</div>

<?php endif; ?>