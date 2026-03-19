<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Glint
 * @since 1.0.0
 */

get_header();

$glint_func           = glint_function('Functions');
$glint_archive_page_layout = glint_function('Functions')->page_layout();

?>

<!--:::::WELCOME ATRA START :::::::-->
<div class="welcome-area-wrap" style="background-size: cover;background-position: center;">
    <!--::::: WELCOME CAROUSEL START :::::::-->
    <div class="welcome-area inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="inner-wlc">
                        <?php
                            the_archive_title('<h2 class="braedcrumb-blog-title">', '</h2>');
                            the_archive_description('<div class="archive-description">', '</div>');
                        ?>
                        <h3><?php if (function_exists('bcn_display')) bcn_display(); ?></h3>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="inner-filltext">
                        <h1><?php the_archive_title('<h1 class="braedcrumb-blog-subtitle">', '</h1>'); ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--::::: WELCOME CAROUSEL END:::::::-->
</div>
<!--:::::WELCOME AREA END :::::::-->

<div id="primary" class="content-area glint-page-containerr glint-archive-page">
    <main id="main" class="site-main">
        <div class="blog-area">
            <div class="container">
                <div class="row">
                    <div class="<?php echo esc_attr($glint_archive_page_layout['content_column_class']); ?>">
                        <div class="blog-inner">
                            <?php
                                if (have_posts()):
                                   /* Start the Loop */
                                   while (have_posts()):
                                      the_post();
                                      /*
                                       * Include the Post-Type-specific template for the content.
                                       * If you want to override this in a child theme, then include a file
                                       * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                                      */
                                      get_template_part('template-parts/content', get_post_format());
                                   endwhile;
                            ?>
                            <div class="glint-pagination text-center">
                                <?php
                                   the_posts_pagination(array(
                                      'next_text' => '<i class="fa fa-long-arrow-right"></i>',
                                      'prev_text' => '<i class="fa fa-long-arrow-left"></i>',
                                      'screen_reader_text' => ' ',
                                      'type' => 'list'
                                    ));
                                ?>
                            </div>
                            <?php
                                else:
                                   get_template_part('template-parts/content', 'none');
                                endif;
                            ?>
                        </div>
                    </div>
                    <?php if ('default' != $glint_archive_page_layout['glint_sidebar_activity']): ?>
                        <div class="col-lg-4 <?php echo esc_attr($glint_archive_page_layout['sidebar_column_class']); ?>">
                            <?php get_sidebar(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();