<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

?>

<div class="vlt-about-author">

	<div class="vlt-about-author__avatar">

		<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
			<?php echo get_avatar( get_the_author_meta( 'email' ), 260, '', '', array( 'extra_attr' => 'loading=lazy' ) ); ?>
		</a>

	</div>

	<div class="vlt-about-author__content">

		<div class="vlt-about-author__header">

			<h4 class="vlt-about-author__title">

				<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
					<?php the_author(); ?>
				</a>

			</h4>

			<a class="vlt-about-author__link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php esc_html_e( 'Author', 'ziomm' ); ?></a>

		</div>

		<?php if ( get_the_author_meta( 'description' ) ) : ?>
			<p class="vlt-about-author__text"><?php the_author_meta( 'description' ); ?></p>
		<?php endif; ?>

	</div>

</div>
<!-- /.vlt-about-author -->