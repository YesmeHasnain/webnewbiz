<?php 

/**
 * Template part for displaying footer layout two
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package seacab
*/

$seacab_footer_logo = get_theme_mod( 'seacab_footer_logo' );
$seacab_footer_bg_url_from_page = function_exists( 'get_field' ) ? get_field( 'seacab_footer_bg' ) : '';
$seacab_footer_bg_color_from_page = function_exists( 'get_field' ) ? get_field( 'seacab_footer_bg_color' ) : '';
$footer_bg_color = get_theme_mod( 'seacab_footer_bg_color' );
$footer_style_2_switch = get_theme_mod( 'footer_style_2_switch', false );

// bg color
$bg_color = !empty( $seacab_footer_bg_color_from_page ) ? $seacab_footer_bg_color_from_page : $footer_bg_color;

$footer_columns = 0;
$footer_widgets = get_theme_mod( 'footer_widget_number', 4 );

for ( $num = 1; $num <= $footer_widgets; $num++ ) {
    if ( is_active_sidebar( 'footer-2-' . $num ) ) {
        $footer_columns++;
    }
}

switch ( $footer_columns ) {
case '1':
    $footer_class[1] = 'col-lg-12';
    break;
case '2':
    $footer_class[1] = 'col-lg-6 col-md-6 wow fadeInUp';
    $footer_class[2] = 'col-lg-6 col-md-6 wow fadeInUp';
    break;
case '3':
    $footer_class[1] = 'col-xl-4 col-lg-6 col-md-5 wow fadeInUp';
    $footer_class[2] = 'col-xl-4 col-lg-6 col-md-7 wow fadeInUp';
    $footer_class[3] = 'col-xl-4 col-lg-6 wow fadeInUp';
    break;
case '4':
    $footer_class[1] = 'col-xl-3 col-lg-6 col-md-6 wow fadeInUp';
    $footer_class[2] = 'col-xl-3 col-lg-6 col-md-6 wow fadeInUp';
    $footer_class[3] = 'col-xl-3 col-lg-6 col-md-6 wow fadeInUp';
    $footer_class[4] = 'col-xl-3 col-lg-6 col-md-6 wow fadeInUp';
    break;
default:
    $footer_class = 'col-xl-3 col-lg-3 col-md-6';
    break;
}

?>

<footer>
    <div class="site-footer" data-bg-color="<?php print esc_attr( $bg_color );?>">
        <div class="container">
            <?php 
                if ( $footer_style_2_switch ) {
                
                    if ( is_active_sidebar( 'footer-2-1' ) OR is_active_sidebar( 'footer-2-2' ) OR is_active_sidebar( 'footer-2-3' ) OR is_active_sidebar( 'footer-2-4' ) ): ?>
                        <div class="site-footer__top">
                            <div class="row">
                                <?php
                                    if ( $footer_columns < 4 ) {
                                    print '<div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp">';
                                    dynamic_sidebar( 'footer-2-1' );
                                    print '</div>';

                                    print '<div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp">';
                                    dynamic_sidebar( 'footer-2-2' );
                                    print '</div>';

                                    print '<div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp">';
                                    dynamic_sidebar( 'footer-2-3' );
                                    print '</div>';

                                    print '<div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp">';
                                    dynamic_sidebar( 'footer-2-4' );
                                    print '</div>';
                                    } else {
                                        for ( $num = 1; $num <= $footer_columns; $num++ ) {
                                            if ( !is_active_sidebar( 'footer-2-' . $num ) ) {
                                                continue;
                                            }
                                            print '<div class="' . esc_attr( $footer_class[$num] ) . '">';
                                            dynamic_sidebar( 'footer-2-' . $num );
                                            print '</div>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    <?php endif;
                }
            ?>
            <div class="site-footer__bottom">
                <p class="site-footer__bottom-text"><?php print seacab_copyright_text(); ?></p>
            </div>
        </div>
    </div>
</footer>