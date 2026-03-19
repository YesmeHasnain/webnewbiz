<?php
/**
 * @Packge    : Solak
 * @Version   : 1.0
 * @Author    : Themeholy
 * @Author URI: https://themeforest.net/user/themeholy
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
    * Hook solak_footer_content
    *
    * @Hooked solak_footer_content_cb 10
    *
    */
    do_action( 'solak_footer_content' );

    /**
    *
    * Hook for Back to Top Button
    *
    * Hook solak_back_to_top
    *
    * @Hooked solak_back_to_top_cb 10
    *
    */
    do_action( 'solak_back_to_top' );

    /**
    *
    * solak grid lines
    *
    * Hook solak_grid_lines
    *
    * @Hooked solak_grid_lines_cb 10
    *
    */
    do_action( 'solak_grid_lines' );

    wp_footer();
    ?>
</body>
</html>