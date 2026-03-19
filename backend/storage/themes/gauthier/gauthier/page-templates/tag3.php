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
    <div id="primary" class="site-content">
	<a href="<?php echo esc_html ( get_term_meta( $category_id, 'adv_cattoplink', 1 ), 'true' ); ?>" target="_blank">
        <?php $categories = get_the_category();
		$category_id = $categories[0]->cat_ID;
		echo '<div class="advcat">';
		echo wp_get_attachment_image( get_term_meta( $category_id, 'catadv_top_id', 1 ), 'gauthier-large' );
		echo '</div>';	
		?>
     </a>	
        <div id="content" class="catcontent4">
            <?php if ( have_posts() ) : ?>
            <?php if ( get_query_var('paged') ) {
			$paged = get_query_var('paged');
			} else if ( get_query_var('page') ) {
				$paged = get_query_var('page');
			} else {
				$paged = 1;
			}?>
            <?php while(have_posts()) :
			the_post(); ?>
            <div class="category3-jtop">
                <div <?php post_class('clearfix'); ?> >
                    <?php get_template_part( 'page-templates/catcontent3', get_post_format() ); ?>
                </div>
            </div>
            <?php endwhile; ?>
            <?php else : ?>
            <?php get_template_part( 'content2', 'none' ); ?>
            <?php endif; ?>
        </div>
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
        <a href="<?php echo esc_html ( get_term_meta( $category_id, 'adv_catbottomlink', 1 ), 'true' ); ?>" target="_blank">
        <?php $categories = get_the_category();
		$category_id = $categories[0]->cat_ID;
		echo '<div class="advcatbottom">';
		echo wp_get_attachment_image( get_term_meta( $category_id, 'catadv_bottom_id', 1 ), 'gauthier-large' );
		echo '</div>';	
		?>
        </a>
	</div>
    <!-- #primary -->
    <div class="sidebar">
        <?php get_sidebar(); ?>
    </div>
</div>