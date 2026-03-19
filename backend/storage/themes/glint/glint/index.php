<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Glint
 * @since 1.0.0
 */

get_header(); 

$glint_func   = glint_function('Functions');
$glint_blog_layout = glint_function('Functions')->page_layout();

?>

<!--:::::WELCOME ATRA START :::::::-->
<div class="welcome-area-wrap blog-breadcrumb-bg">
    <!--::::: WELCOME CAROUSEL START :::::::-->
    <div class="welcome-area inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="inner-wlc">
                        <h2><?php esc_html_e('BLOG', 'glint'); ?></h2>
                        <h3><a href="<?php echo esc_url( home_url('/') ); ?>"><?php esc_html_e('Home', 'glint'); ?></a>&nbsp;/&nbsp;<?php esc_html_e('Blog', 'glint'); ?></h3>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="inner-filltext">
                        <h1><?php esc_html_e('BLOG', 'glint'); ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--::::: WELCOME CAROUSEL END:::::::-->
</div>
<!--:::::WELCOME AREA END :::::::-->

<div id="primary" class="content-area glint-page-containerr glint-blog-page blog-page-area inner-bg-shpes section-padding">
    <main id="main" class="site-main">
        <div class="blog-area">
	    	<div class="blog-shapes">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/img/bg/service-bg.svg" alt="<?php the_title_attribute(); ?>" class="inner-shape1">
	            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/bg/inner-bg1.svg" alt="<?php the_title_attribute(); ?>" class="inner-shape2">
	            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/bg/inner-bg1.svg" alt="<?php the_title_attribute(); ?>" class="inner-shape3">
	    	</div>
            <div class="container">
                <div class="row">

                    <div class="<?php echo esc_attr( $glint_blog_layout['content_column_class'] ); ?>">
                        <div class="blog-inner blog-page-left mr-lg-4">

                            <?php
								if ( have_posts() ) :
									/* Start the Loop */
									while ( have_posts() ) :
										the_post();
										/*
										 * Include the Post-Type-specific template for the content.
										 * If you want to override this in a child theme, then include a file
										 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
										 */
										get_template_part( 'template-parts/content', get_post_format() );
									endwhile;
								?>

                            <div class="glint-pagination text-center">

                                <?php
									the_posts_pagination(array(
									'next_text'          => '<i class="fa fa-long-arrow-right"></i>',
									'prev_text'          => '<i class="fa fa-long-arrow-left"></i>',
									'screen_reader_text' => ' ',
									'type'               => 'list'
								));
								?>
                            </div>
                            <?php	
									
								else :
									get_template_part( 'template-parts/content', 'none' );
								endif;
								?>
                        </div>
                    </div>
                    <?php if ( 'default' != $glint_blog_layout['glint_sidebar_activity'] ) : //if sidebar active and layout is not full width ?>
                    <div class="col-lg-4 <?php echo esc_attr( $glint_blog_layout['sidebar_column_class'] ); ?>">
                        <?php get_sidebar();?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main><!-- #main -->
</div><!-- #primary -->
<?php

get_footer();