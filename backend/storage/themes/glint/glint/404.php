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

$error_main_title = cs_get_option('error_main_title');
$error_title     = cs_get_option('error_text');
$go_back_btn      = cs_get_option('button_text');

get_header();
?>

<!--::::: WELCOME ATRA START :::::-->
<div class="welcome-area-wrap blog-breadcrumb-bg">
    <!--::::: WELCOME CAROUSEL START :::::-->
    <div class="welcome-area inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="inner-wlc">
                        <h2><?php esc_html_e('Error Page', 'glint'); ?></h2>
                        <h3><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'glint'); ?></a>&nbsp;/&nbsp;<?php esc_html_e('404', 'glint'); ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--::::: WELCOME CAROUSEL END :::::-->
</div>
<!--::::: ELCOME AREA END :::::-->

<div id="primary" class="content-area glint-page-containerr glint-blog-page blog-page-area inner-bg-shpes section-padding">
    <main id="main" class="site-main">
        <div class="blog-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <section class="error-404 not-found">
                            <header class="page-header">
                                <h1 class="error-heading">
                                    <?php
                                        if (!empty($error_main_title)){
                                            echo esc_html($error_main_title);
                                        }else{
                                            echo esc_html__('404! Not Found...', 'glint');
                                        }
                                    ?>
                                </h1>
                                <h2 class="error-sub-title">
                                    <?php
                                        if (!empty($error_title)){
                                            echo esc_html($error_title);
                                        }else{
                                            echo esc_html__('Oops! That page can not be found.', 'glint');
                                        }
                                    ?>
                                </h2>
                            </header><!-- .page-header -->
                            <div class="page-content error-page-inner">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="go-back-btnn">
                                    <?php
                                        if (!empty($go_back_btn)){
                                            echo esc_html($go_back_btn);
                                        }else{
                                            echo esc_html__('Go Back To Home', 'glint');
                                        }
                                    ?>
                                </a>
                            </div><!-- .page-content -->
                        </section><!-- .error-404 -->
                    </div>
                    <div class="col-lg-4">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
            </div>
        </div>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();