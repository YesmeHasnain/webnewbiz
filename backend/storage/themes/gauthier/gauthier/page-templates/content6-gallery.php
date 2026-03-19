<?php
/*
* Content display template.
*/
?>
<div class="single-wrapper">
    <div class="singletitle-wrapper">
        <div class="singletitle-prev">
            <div class="singletitle-time">
                <div class="singletitle-date">
                    <h1>
                        <?php the_time( 'jS' ); ?>
                    </h1>
                </div>
                <div class="singletitle-year">
                    <?php the_time( 'F' ); ?>
                </div>
            </div>
        </div>
        <div class="singletitle-title">
            <header class="entry-headerdefault">
                <div class="top-title-meta">
                    <div class="submeta-singlepost">
                        <?php gauthier_breadcrumb(); ?>
                        <div class="head-divider"></div>
                        <div class="head-date"> <?php echo get_the_date(); ?> </div>
                    </div>
                    <div class="adt-comment">
                        <div class="features-onsinglepost">
                            <?php if (function_exists("sharing_display")) {echo sharing_display();} ?>
                        </div>
                    </div>
                </div>
                <h1 class="entry-title">
                    <?php the_title(); ?>
                </h1>
            </header>
        </div>
    </div>
    <div class="singletitle-wrapperimage">
        <div class="singletitle-meta">
            <div class="coauthor-wrapper">
                <?php if ( function_exists( 'get_coauthors' ) ) {
					$coauthors = get_coauthors();
					foreach ( $coauthors as $coauthor ) {
						?>
                <div class="coauthor-wrapperinside"> <?php echo coauthors_get_avatar( $coauthor, 40 ); ?>
                    <div class="coauthor-desc"> <a href="<?php echo get_author_posts_url( $coauthor->ID, $coauthor->user_nicename); ?>" > <?php echo esc_html ($coauthor->display_name); ?></a> <?php echo esc_html ($coauthor->Position); ?> </div>
                </div>
                <?php  }
				// treat author output normally
				} else { ?>
                <div class="coauthor-wrapperinside"> <?php echo get_avatar( get_the_author_meta( 'user_email' ), 40 ); ?>
                    <div class="coauthor-desc"> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"><?php printf( __( ' %s', 'gauthier' ), get_the_author() ); ?></a> <?php echo esc_html (get_the_author_meta('Position'));?> </div>
                </div>
                <?php }?>
            </div>
            <div class="singlepost-totalmeta"><a class="link-comments" href="<?php comments_link(); ?>">
                <?php comments_number(__('0 Comments','gauthier'),__('1 Comment','gauthier'),__('% Comments','gauthier')); ?>
                </a> </div>
            <div class="singlepost-totalview">
                <?php if (function_exists("gauthier_get_post_views")) { ?>
                <?php echo esc_html (gauthier_get_post_views()); ?>
                <?php } else { ?>
                <?php } ?>
            </div>
            <div class="singlepost-totalread">
                <?php if (function_exists("gauthier_reading_times")) { ?>
                <?php echo esc_html (gauthier_reading_times(get_the_ID())); ?>
                <?php } else { ?>
                <?php } ?>
            </div>
        </div>
        <div class="singletitle-image">
            <div class="feature-postimgdefault"><?php echo do_shortcode ( get_post_meta( get_the_ID(), 'gauthier_gallery', true ) ); ?></div>
        </div>
    </div>
    <div class="singlemidle-wrapper <?php echo esc_html(get_post_meta(get_the_ID(), "gauthier_sidebar", true) ); ?>">
        <?php while ( have_posts() ) : the_post(); ?>
        <div id="primary" <?php body_class('site-content'); ?>>
            <div id="content"  class="single4content"role="main">
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry-content contentinwrapper">
                        <div class="entry-contentinside">
                            <?php if(get_post_meta( get_the_ID(), 'adv_top_id' , true )){ ?>
                            <div class="adv-gauthier"> <a href="<?php echo esc_html(get_post_meta(get_the_ID(), "adv_toplink", true) ); ?>" target="_blank"><?php echo  ''.$image_two = wp_get_attachment_image( get_post_meta( get_the_ID(), 'adv_top_id', 1 ), 'poster' ).''; ?></a></div>
                            <?php }	?>
                            <div class="postcolumns <?php echo esc_html(get_post_meta(get_the_ID(), "gauthier_post_columns", true) ); ?>">
                                <?php the_content(esc_html__('Continue reading <span class="meta-nav">&rarr;</span>',"gauthier")); ?>
                            </div>
                            <?php wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'gauthier' ), 'after' => '</div>' ) ); ?>
                            <?php if(get_post_meta( get_the_ID(), 'adv_horz_id' , true )){ ?>
                            <div class="adv-bottomgauthier"><a href="<?php echo esc_html(get_post_meta(get_the_ID(), "adv_bottomlink", true) ); ?>" target="_blank"><?php echo  ''.$image_two = wp_get_attachment_image( get_post_meta( get_the_ID(), 'adv_horz_id', 1 ), 'poster' ).''; ?></a></div>
                            <?php }	?>
                        </div>
                        <?php if(get_post_meta( get_the_ID(), 'adv_vert_id' , true )){ ?>
                        <div class="entry-contentadv"><a href="<?php echo esc_html(get_post_meta(get_the_ID(), "adv_vertlink", true) ); ?>" target="_blank"><?php echo  ''.$image_two = wp_get_attachment_image( get_post_meta( get_the_ID(), 'adv_vert_id', 1 ), 'poster' ).''; ?></a></div>
                        <?php }	?>
                    </div>
                </article>
                <footer class="entry-meta">
                    <?php esc_html_e('TAGS: ', 'gauthier'); ?>
                    <?php $before = '#';
					$seperator = '#'; // blank instead of comma
					$after = '';
					the_tags( $before, $seperator, $after );
					?>
                </footer>
                <div class="singledefault relatedpost"> 
                    <!-- .nav-single -->
                    <nav class="nav-single"> <span class="nav-previous">
                        <?php get_template_part("inc/prev"); ?>
                        </span> <span class="nav-next">
                        <?php get_template_part("inc/next"); ?>
                        </span> </nav>
                </div>
                <div class="singledefault commentblock">
                    <?php comments_template( '', true ); ?>
                </div>
                <?php  get_template_part( 'inc/related-post2' ); ?>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="sidebar">
            <div class="single2-widget">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</div>