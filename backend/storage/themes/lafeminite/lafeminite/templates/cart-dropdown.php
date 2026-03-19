<?php
	if ( ! current_theme_supports( 'vamtam-cart-dropdown' ) ) {
		return;
	}
?>

<div class="cart-dropdown hidden">
	<div class="cart-dropdown-inner">
		<a class="vamtam-cart-dropdown-link" href="<?php echo esc_url( vamtam_wc_get_cart_url() ) ?>">
			<span><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path d="M31.297 7.372c-0.593-0.755-1.503-1.238-2.526-1.247h-20.516l-0.64-2.493c-0.361-1.42-1.626-2.454-3.133-2.458h-3.436c-0.576 0.003-1.042 0.469-1.045 1.045v0c0 0.573 0.471 1.044 1.045 1.044h3.434c0.505 0 0.944 0.337 1.077 0.843l4.112 16.374c0.361 1.42 1.625 2.455 3.133 2.46h13.441c1.483 0 2.797-1.011 3.133-2.46l2.528-10.377c0.061-0.23 0.097-0.495 0.097-0.768 0-0.748-0.266-1.434-0.708-1.968l0.004 0.005zM26.501 28.886c0 2.587-3.881 2.587-3.881 0s3.88-2.587 3.88 0zM16.052 28.886c0 2.587-3.881 2.587-3.881 0s3.88-2.587 3.88 0zM28.771 8.248c0.356 0.005 0.672 0.175 0.874 0.437l0.002 0.003c0.201 0.267 0.301 0.604 0.201 0.941l-2.526 10.377c-0.125 0.487-0.561 0.841-1.078 0.841-0 0-0 0-0 0h-13.442c-0 0-0 0-0 0-0.518 0-0.953-0.354-1.077-0.834l-0.002-0.008-2.93-11.758z"></path></svg></span>
			<span class="products cart-empty">...</span>
		</a>
		<div class="widget woocommerce widget_shopping_cart hidden">
			<div class="widget_shopping_cart_content"></div>
		</div>
	</div>
</div>
