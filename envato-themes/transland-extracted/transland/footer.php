<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Transland
 */

    $opt = get_option('transland_opt');

    $footer_copyright_content = isset( $opt['footer_copyright_content'] ) ? $opt['footer_copyright_content'] : '<p>&copy; <b>Transland </b> Ltd - 2022. All rights reserved.</p>';

    $footer_visibility = function_exists( 'get_field' ) ? get_field( 'footer_visibility' ) : '1';
    $footer_visibility = isset($footer_visibility) ? $footer_visibility : '1';

?>

    <?php if( $footer_visibility  == '1' ) : ?>
    <footer class="footer-wrap">
        <?php if ( is_active_sidebar( 'footer_widgets' ) ) : ?>
        <div class="footer-widgets-wrapper theme-bg">
            <div class="container">
                <div class="row">
                    <?php dynamic_sidebar( 'footer_widgets' ); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ( class_exists('ReduxFrameworkPlugin') ) : ?>
        <div class="footer-bottom">
            <div class="container align-items-center">
                <div class="bottom-content-wrapper">
                    <div class="row">
                        <?php if (!empty ($footer_copyright_content) ) : ?>
                        <div class="col-md-6 col-12">
                            <div class="copy-rights">
                                <?php echo wp_specialchars_decode( esc_html( $footer_copyright_content ) ); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="col-md-6 mt-2 mt-md-0 col-12 text-md-end">
                            <div class="social-links">
                                <?php transland_social_links(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty ($footer_copyright_content) ) : ?>
        <div class="footer-bottom-bar <?php if ( class_exists('ReduxFrameworkPlugin') ) : ?>d-none<?php endif; ?>">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-12 text-center">
                        <div class="copyright-info">
                            <?php echo wp_specialchars_decode( esc_html( $footer_copyright_content ) ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </footer>
    <?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
