<?php
/**
 * @Packge     : Barab
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */
 
// Block direct access
if( !defined( 'ABSPATH' ) ){
    exit();
}

	if( ! is_active_sidebar( 'barab-woo-sidebar' ) ){
		return;
	}
?>
<div class="col-lg-4 col-xl-3">
	<!-- Sidebar Begin -->
	<aside class="sidebar-area shop-sidebar">
		<?php
			dynamic_sidebar( 'barab-woo-sidebar' );
		?>
	</aside>
	<!-- Sidebar End -->
</div>