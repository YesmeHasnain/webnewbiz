<?php

	// Elementor `footer` location
	if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
		get_template_part( 'template-parts/footer/footer' );
	}

	$acf_back_to_top = ziomm_get_theme_mod( 'page_custom_back_to_top', true );

	if ( ziomm_get_theme_mod( 'back_to_top', $acf_back_to_top ) == 'show' ) {
		echo '<a href="#" class="vlt-btn vlt-btn--primary vlt-btn--effect vlt-btn--back-to-top">';
		echo '<i class="icon-arrow-top"></i>';
		echo '</a>';
	}

?>

<?php if ( ZIOMM_WOOCOMMERCE ) : ?>

	<?php if ( ziomm_get_theme_mod( 'shop_cart_icon' ) == 'show' && ( is_page_template( 'template-woocommerce-page.php' ) || is_woocommerce() ) ) : ?>

		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="vlt-shop-cart-icon">

			<i class="icon-cart"></i>

			<span class="vlt-shop-cart-icon-counter"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>

		</a>

	<?php endif; ?>

<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>