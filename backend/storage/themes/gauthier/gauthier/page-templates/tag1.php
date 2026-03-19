<div <?php body_class(); ?>>
    <div class="category1-topheader">
        <div class="category1-topinside">
            <div class="category1-titlewrapper ">
                <div class="category1-totalpost">
                    <?php esc_html_e('Post Count: ', 'gauthier'); ?>
                    <?php echo esc_html ($wp_query->found_posts);?> </div>
                <h1><?php single_cat_title(); ?></h1>
                <?php if ( category_description() ) : ?>
                <?php echo'<span>' .category_description() . '</span>';?>
                <?php endif; ?>
            </div>
            <?php $posttags = get_the_tags();
			if ( $posttags ) {
				foreach($posttags as $tag) {
					echo wp_get_attachment_image( get_term_meta( $tag->term_id, 'catfit_id', 1 ), 'large' );
				}
			}?>
            <div class="bottom-gradientblack"></div>
        </div>
    </div>
</div>
<div class="category1-wrapperinside">
  <div id="primary" <?php body_class("site-content"); ?>>
    <div id="content" role="main">
      <?php if ( have_posts() ) : ?>
        <?php if ( get_query_var('paged') ) {
			$paged = get_query_var('paged');
			} else if ( get_query_var('page') ) {
				$paged = get_query_var('page');
			} else {
				$paged = 1;
			}
		?>
        <?php while(have_posts()) :
		the_post();
		?>
          <div <?php post_class('clearfix'); ?> >
            <?php get_template_part( 'page-templates/catcontent1', get_post_format() ); ?>
          </div>
        <?php endwhile; ?>
        <?php else : ?>
        <?php get_template_part( 'page-templates/content2', 'none' ); ?>
        <?php endif; ?>
    </div>
    <!-- #content -->
    <?php if (function_exists("gauthier_numbered_pages")) { ?>
    <?php gauthier_numbered_pages(); ?>
    <?php } else { ?>
    <nav>
      <ul class="pager">
        <li class="previous">
          <?php next_posts_link( esc_attr__( '&laquo; Older Entries', 'gauthier' ) ); ?>
        </li>
        <li class="next">
          <?php previous_posts_link( esc_attr__( 'Newer Entries &raquo;', 'gauthier' ) ); ?>
        </li>
      </ul>
    </nav>
    <?php } ?>
  </div>
  <!-- #primary -->
  <div class="sidebar">
    <?php get_sidebar(); ?>
  </div>
</div>
