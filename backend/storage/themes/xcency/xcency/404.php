<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Xcency
 */

get_header();

$error_banner      = xcency_option('error_banner', true);
$error_banner_title = xcency_option('error_page_title');
// btt = Banner Title Tag
$btt = xcency_option('banner_title_tag', 'h2');
$banner_text_align = xcency_option('banner_default_text_align', 'left');
$not_found_text     = xcency_option('not_found_text');
$go_back_home       = xcency_option('go_back_home', true);
$error_img = xcency_option('error_image','');

?>

    <?php if($error_banner == true) : ?>
        <div class="banner-area error-page-banner">
            <div class="container h-100">
                <div class="row h-100">
                    <div class="col-lg-12 my-auto">
                        <div class="banner-content text-<?php echo esc_attr( $banner_text_align ); ?>">
                            <<?php echo esc_html($btt);?> class="banner-title">
	                            <?php echo esc_html($error_banner_title); ?>
                            </<?php echo esc_html($btt);?>>

                            <?php if ( function_exists( 'bcn_display' ) ) :?>
                                <div class="breadcrumb-container">
                                    <?php bcn_display();?>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <div id="primary" class="content-area">
        <div class="container not-found-content text-center">
            <div class="row">
                <div class="col-12">

                        <?php if($error_img['url']) { ?>
                        <div class="error-page-image">
                            <img src="<?php echo esc_url($error_img['url']); ?>" alt="<?php echo esc_attr( get_post_meta( $error_img['id'], '_wp_attachment_image_alt', true )); ?>">
                        </div>
                        <?php }else{ ?>
                        <div class="text-404">
                            <h2>404</h2>
                        </div>
                        <?php }?>

                    <div class="not-found-text-wrapper">
						<?php
						echo wp_kses( $not_found_text, xcency_allow_html() );
						?>

						<?php if ($go_back_home == true) : ?>
                            <div class="error-page-button">
                                <a class="xcency-button" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('Go Back Home', 'xcency') ?></a>
                            </div>
						<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- #primary -->

<?php
get_footer();