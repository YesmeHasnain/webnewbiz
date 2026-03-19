<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */

?>

<article <?php post_class( 'vlt-page' ); ?>>

	<div class="container">

		<div class="vlt-content-markup">

			<?php the_content(); ?>

		</div>

		<div class="clearfix"></div>

		<?php
			wp_link_pages( array(
				'before' => '<div class="vlt-link-pages"><h6>' . esc_html__( 'Pages: ', 'ziomm' ) . '</h6>',
				'after' => '</div>',
				'separator' => '<span class="sep">|</span>',
				'nextpagelink' => esc_html__( 'Next page', 'ziomm' ),
				'previouspagelink' => esc_html__( 'Previous page', 'ziomm' ),
				'next_or_number' => 'next'
			) );
		?>

	</div>

</article>
<!-- /.vlt-page -->