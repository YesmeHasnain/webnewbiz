<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;
$nixer_redux_demo = get_option('redux_demo');
?>

<section class="tp-shop-breadcrumb-ptb pt-160 pb-100 p-relative">
	<div class="tp-about-me-bg" data-background="<?php echo esc_url($nixer_redux_demo['cart-bg']['url']); ?>"></div>
	<div class="container">
		<div class="row">
			<div class="col-xl-12">
				<div class="tp-about-us-heading">
					<span class="tp-breadcrumb-subtitle">
						<?php echo esc_attr($nixer_redux_demo['cart-heading']); ?>
					</span>
					<h3 class="tp-breadcrumb-title tp-title-anim">
						<?php if ((isset($nixer_redux_demo['cart-title'])) && ('' != $nixer_redux_demo['cart-title'])) { ?>
							<?php echo esc_attr($nixer_redux_demo['cart-title']); ?>
						<?php } else { ?>
							<?php echo esc_html__( 'Shopping Cart', 'nixer' )?>
						<?php } ?>
					</h3>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="tp-cart-area pb-120 pt-120">
	<div class="container">
		<?php
		/*
		 * @hooked wc_empty_cart_message - 10
		 */
		do_action( 'woocommerce_cart_is_empty' );
		
		if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
			<p class="return-to-shop">
				<a class="button wc-backward<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
					<?php
						/**
						 * Filter "Return To Shop" text.
						 *
						 * @since 4.6.0
						 * @param string $default_text Default text.
						 */
						echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Return to shop', 'nixer' ) ) );
					?>
				</a>
			</p>
		<?php endif; ?>
	</div>
</section>
