<?php global $redux_gauthier;  
if (isset($redux_gauthier["header_layout"])) {
    get_header($redux_gauthier["header_layout"]);
} else {
    get_header();
}
?>
<div class="single2-wrapper <?php if(isset($redux_gauthier['jp_sidebar']) ){ ?><?php echo esc_html($redux_gauthier['jp_sidebar']); ?><?php } ?>">
	<div id="primary" <?php body_class("site-content"); ?>>
		<div class="author-wrapper">
			<div class="author-info">
				<div class="author-avatar">
				<?php $avatar_url = get_avatar_url( get_the_author_meta( 'ID' ), array( 'size' => 500 ) );
				echo '<img src="' . aq_resize( $avatar_url, 300, 400, true, true, true ) . '">';
				?>
				</div>
				<div class="author-description">	
				<div class="author-position">					
					<?php if(get_the_author_meta('Position') ): ?>
					<?php echo get_the_author_meta('Position'); ?>
					<?php esc_attr_e(' - ', 'gauthier'); ?>					
					<?php else: ?>
					<?php endif; ?>
					<?php global $author;
					$post_count = count_user_posts( get_the_author_meta( 'ID' ) );
					printf( _n( '%s article', '%s articles', $post_count, 'gauthier' ), number_format_i18n( $post_count ) );
					?>
				</div>	
					<h2><?php printf(__("About %s", "gauthier"), get_the_author()); ?></h2>
					<p><?php the_author_meta("description"); ?></p>
					<div class="author-contact-wrapper">
						<?php
						if (get_the_author_meta("twitter")) { ?>
							<div class="author-socmed-wrapper">
								<a rel="me" href="http://twitter.com/<?php the_author_meta("twitter"); ?>"><div class="author-twitter"></div></a>
								<span class="tooltiptext"><?php _e("X", "gauthier"); ?></span>
							</div>
						<?php }
						if (get_the_author_meta("facebook")) { ?>
							<div class="author-socmed-wrapper">
								<a rel="me" href="http://facebook.com/<?php the_author_meta("facebook"); ?>"><div class="author-facebook"></div></a>
								<span class="tooltiptext"><?php _e("Facebook", "gauthier"); ?></span>
							</div>
						<?php }
						if (get_the_author_meta("youtube")) { ?>
							<div class="author-socmed-wrapper">
								<a rel="me" href="http://youtube.com/<?php the_author_meta("youtube"); ?>"><div class="author-youtube"></div></a>
								<span class="tooltiptext"><?php _e("Youtube", "gauthier"); ?></span>
							</div>
						<?php }
						if (get_the_author_meta("vimeo")) { ?>
							<div class="author-socmed-wrapper">
								<a rel="me" href="http://vimeo.com/<?php the_author_meta("vimeo"); ?>"><div class="author-vimeo"></div></a>
								<span class="tooltiptext"><?php _e("Vimeo", "gauthier"); ?></span>
							</div>
						<?php }
						if (get_the_author_meta("linkedin")) { ?>
							<div class="author-socmed-wrapper">
								<a rel="me" href="http://linkedin.com/<?php the_author_meta("linkedin"); ?>"><div class="author-linkedin"></div></a>
								<span class="tooltiptext"><?php _e("Linkedin", "gauthier"); ?></span>
							</div>
						<?php }
						if (get_the_author_meta("devianart")) { ?>
							<div class="author-socmed-wrapper">
								<a rel="me" href="http://devianart.com/<?php the_author_meta("devianart"); ?>"><div class="author-devianart"></div></a>
								<span class="tooltiptext"><?php _e("Deviant", "gauthier"); ?></span>
							</div>
						<?php }
						if (get_the_author_meta("dribble")) { ?>
							<div class="author-socmed-wrapper">
								<a rel="me" href="http://dribble.com/<?php the_author_meta("dribble"); ?>"><div class="author-dribble"></div></a>
								<span class="tooltiptext"><?php _e("Dribble", "gauthier"); ?></span>
							</div>
						<?php }
						if (get_the_author_meta("flickr")) { ?>
							<div class="author-socmed-wrapper">
								<a rel="me" href="http://flickr.com/<?php the_author_meta("flickr"); ?>"><div class="author-flickr"></div></a>
								<span class="tooltiptext"><?php _e("Flickr", "gauthier"); ?></span>
							</div>
						<?php }
						if (get_the_author_meta("instagram")) { ?>
							<div class="author-socmed-wrapper ">
								<a rel="me" href="http://instagram.com/<?php the_author_meta("instagram"); ?>"><div class="author-instagram"></div></a>
								<span class="tooltiptext"><?php _e("Instagram", "gauthier"); ?></span>
							</div>
						<?php }
						if (get_the_author_meta("behance")) { ?>
							<div class="author-socmed-wrapper">
								<a rel="me" href="http://behance.com/<?php the_author_meta("behance"); ?>"><div class="author-behance"></div></a>
								<span class="tooltiptext"><?php _e("Behance", "gauthier"); ?></span>
							</div>
						<?php }
						if (get_the_author_meta("reddit")) { ?>
							<div class="author-socmed-wrapper">
								<a rel="me" href="http://reddit.com/<?php the_author_meta("reddit"); ?>"><div class="author-reddit"></div></a>
								<span class="tooltiptext"><?php _e("Reddit", "gauthier"); ?></span>
							</div>
						<?php }
						if (get_the_author_meta("github")) { ?>
							<div class="author-socmed-wrapper">
								<a rel="me" href="http://github.com/<?php the_author_meta("github"); ?>"><div class="author-github"></div></a>
								<span class="tooltiptext"><?php _e("Github", "gauthier"); ?></span>
							</div>
						<?php }
						if (get_the_author_meta("pinterest")) { ?>
							<div class="author-socmed-wrapper">
								<a rel="me" href="http://pinterest.com/<?php the_author_meta("pinterest"); ?>"><div class="author-pinterest"></div></a>
								<span class="tooltiptext"><?php _e("Pinterest", "gauthier"); ?></span>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
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
            <div class="category1-wrapper">
                <div <?php post_class('clearfix'); ?> >
                    <div class="categorydefault-wrapper">
                        <?php get_template_part( 'page-templates/catcontent', get_post_format() ); ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
            <?php else : ?>
            <?php get_template_part( 'page-templates/content2', 'none' ); ?>
            <?php endif; ?>
        </div>
		<?php if (function_exists("gauthier_numbered_pages")) { ?>
		<?php gauthier_numbered_pages(); ?>
		<?php } else { ?>
		<nav>
			<ul class="pager">
				<li class="previous">
					<?php next_posts_link(esc_attr__("&laquo; Older Entries", "gauthier")); ?>
				</li>
				<li class="next">
					<?php previous_posts_link(esc_attr__("Newer Entries &raquo;", "gauthier")); ?>
				</li>
			</ul>
		</nav>
		<?php } ?>
	</div>
	<div class="sidebar">
		<?php get_sidebar(); ?>
	</div>
</div>
<?php if (isset($redux_gauthier["footer_layout"])) {
    get_template_part($redux_gauthier["footer_layout"]);
} else {
    get_footer();
}
?>