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

?>

<!--:::::WELCOME ATRA START :::::::-->
<div class="welcome-area-wrap blog-breadcrumb-bg">
    <!--::::: WELCOME CAROUSEL START :::::::-->
    <div class="welcome-area inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="inner-wlc">
                        <h2><?php esc_html_e('Portfolio', 'glint'); ?></h2>
                        <h3><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'glint'); ?></a>&nbsp;/&nbsp;<?php esc_html_e('Portfolio Details', 'glint'); ?></h3>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="inner-filltext">
                        <h1><?php esc_html_e('Portfolio', 'glint'); ?></h1>
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
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </main><!-- #main -->
</div>
</div><!-- #primary -->
<?php get_footer();
