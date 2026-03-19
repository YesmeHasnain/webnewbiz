<?php
/**
 * @Packge    : Solak
 * @Version   : 1.0
 * @Author    : Themeholy
 * @Author URI: https://themeforest.net/user/themeholy
 *
 */

// Block direct access
if (!defined('ABSPATH')) {
    exit;
}

if ( ! is_active_sidebar( 'solak-page-sidebar' ) ) {
    return;
}
?>

<div class="col-lg-4">
    <div class="page-sidebar">
    <?php 
        dynamic_sidebar( 'solak-page-sidebar' );
    ?>               
    </div>
</div>