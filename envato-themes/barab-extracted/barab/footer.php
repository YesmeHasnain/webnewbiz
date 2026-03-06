<?php
/**
 * @Packge     : Barab
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */

// Block direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

    /**
    *
    * Hook for Footer Content
    *
    * Hook barab_footer_content
    *
    * @Hooked barab_footer_content_cb 10
    *
    */
    do_action( 'barab_footer_content' );

    /**
    *
    * Hook for Back to Top Button
    *
    * Hook barab_back_to_top
    *
    * @Hooked barab_back_to_top_cb 10
    *
    */
    do_action( 'barab_back_to_top' );

    /**
    *
    * barab grid lines
    *
    * Hook barab_grid_lines
    *
    * @Hooked barab_grid_lines_cb 10
    *
    */
    do_action( 'barab_grid_lines' );

    wp_footer();
    ?>
</body>
</html>