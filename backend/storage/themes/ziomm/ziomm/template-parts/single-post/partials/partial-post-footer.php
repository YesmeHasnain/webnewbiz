<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

?>

<?php if ( get_the_tags() ) : ?>

	<div class="vlt-post-tags">

		<i class="icon-tag"></i>

		<?php the_tags( '', ', ' ); ?>

	</div>
	<!-- /.vlt-post-tags -->

<?php endif; ?>

<?php if ( function_exists( 'vlthemes_get_post_share_buttons' ) && ziomm_get_theme_mod( 'show_share_post' ) == 'show' ) : ?>

	<div class="vlt-post-share">

		<span><?php esc_html_e( 'Share:', 'ziomm' ); ?></span>

		<div class="vlt-post-share__links">

			<?php echo vlthemes_get_post_share_buttons( get_the_ID() ); ?>

		</div>

	</div>
	<!-- /.vlt-post-share -->

<?php endif; ?>