<?php
get_header();
$twb_is_built_with_elementor = FALSE;
if ( defined('ELEMENTOR_VERSION') ) {
    $twb_page = Elementor\Plugin::instance()->documents->get( get_the_ID() );
    if ( !is_bool($twb_page) && $twb_page->is_built_with_elementor() ) {
        $twb_is_built_with_elementor = TRUE;
    }
}
if ( !$twb_is_built_with_elementor ) { ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
		<?php if ( have_posts() ) :
			if ( is_home() && ! is_front_page() ) : ?>
				<header><h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1></header>
			<?php endif;
			while ( have_posts() ) : the_post();
				get_template_part( 'template-parts/content', get_post_type() );
			endwhile;
			the_posts_navigation();
		else :
			get_template_part( 'template-parts/content', 'none' );
		endif; ?>
		</main>
	</div>
<?php } else {
  while (have_posts()) : the_post(); the_content(); endwhile;
}
get_footer();
