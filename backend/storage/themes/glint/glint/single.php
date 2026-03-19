<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Glint
 * @since 1.0.0
 */

get_header();

$glint_single_page_layout = glint_function('Functions')->page_layout();

?>

<!--:::::WELCOME ATRA START :::::::-->
<div class="welcome-area-wrap blog-breadcrumb-bg">
    <!--::::: WELCOME CAROUSEL START :::::::-->
    <div class="welcome-area inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="inner-wlc">
                        <h2><?php echo the_title(); ?></h2>
                        <h3><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'glint'); ?></a>&nbsp;/&nbsp;<?php echo wp_trim_words(get_the_title(), 7, '...'); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--::::: WELCOME CAROUSEL END:::::::-->
</div>
<!--:::::WELCOME AREA END :::::::-->

<div id="primary" class="content-area glint-blog-details blog-page-area inner-bg-shpes section-padding">
    <main id="main" class="site-main">
        <div class="blog-details-area">
            <div class="blog-shapes">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/bg/service-bg.svg" alt="<?php the_title_attribute(); ?>" class="inner-shape1">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/bg/inner-bg1.svg" alt="<?php the_title_attribute(); ?>" class="inner-shape2">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/bg/inner-bg1.svg" alt="<?php the_title_attribute(); ?>" class="inner-shape3">
            </div>
            <div class="container">
                <div class="row">
                    <div class="<?php echo esc_attr($glint_single_page_layout['content_column_class']); ?>">
                        <div class="blog-page-left mr-lg-4">
                            <?php while (have_posts()):
                                the_post();
                                //get_template_part( 'template-parts/content', 'single' );
                                get_template_part('template-parts/content', 'single');
                            ?>
                                <div class="blog-details-comment">
                                    <?php
                                    // If comments are open or we have at least one comment, load up the comment template.
                                    if (comments_open() || get_comments_number()):
                                        comments_template();
                                    endif;
                                    ?>
                                </div>
                            <?php endwhile; // End of the loop. 
                            ?>
                        </div>
                    </div>
                    <?php if ('default' != $glint_single_page_layout['glint_sidebar_activity']): ?>
                        <div class="col-lg-4 <?php echo esc_attr($glint_single_page_layout['sidebar_column_class']); ?>">
                            <?php get_sidebar(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main><!-- #main -->
</div><!-- #primary -->
<?php get_footer();
