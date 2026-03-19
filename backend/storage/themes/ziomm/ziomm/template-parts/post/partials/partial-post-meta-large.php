<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

?>

<div class="vlt-post-meta">

	<span class="vlt-post-author"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author_meta( 'display_name' ); ?></a></span>

	<?php if ( ziomm_get_post_taxonomy( get_the_ID(), 'category' ) ) : ?>

		<span class="vlt-post-cats"><?php echo ziomm_get_post_taxonomy( get_the_ID(), 'category', ', ' ); ?></span>

	<?php endif; ?>

	<span class="vlt-post-comments"><a href="<?php comments_link(); ?>" target="_self"><?php comments_number( '0 ' . esc_html__( 'Comments', 'ziomm' ), '1 ' . esc_html__( 'Comment', 'ziomm' ), '% ' . esc_html__( 'Comments','ziomm' ) ); ?></a></span>

	<?php if ( ziomm_get_post_taxonomy( get_the_ID(), 'post_tag' ) ) : ?>

		<span class="vlt-post-tags">

			<i class="icon-tag"></i>

			<?php echo ziomm_get_post_taxonomy( get_the_ID(), 'post_tag' ); ?>

		</span>

	<?php endif; ?>

</div>
<!-- /.vlt-post-meta -->