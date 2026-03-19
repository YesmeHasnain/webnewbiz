<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() && !post_password_required() ) : ?>
		<div class="entry-thumbnail post-media post-image">
			<?php 
			the_post_thumbnail( 'full' );
			?>
		</div>
	<?php endif; ?>
	<div class="post-body clearfix">
		<!-- Article content -->
		<div class="entry-content clearfix">
		<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'saasten' ),
				'after'  => '</div>',
			) );
		?>	
		</div>
    </div> 
</article>