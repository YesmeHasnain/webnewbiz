<?php
	$image_block_width = rdt_get_option('single_product_image');
	if($image_block_width == 'smaller') {
		$class = '5';
	}elseif($image_block_width == 'larger'){
		$class= '8';
	}else{
		$class= '6';
	}
?>
<div class="showcase-content">
	<div class="container">
		<div class="row">
			<div class="col-thumb col-lg-<?php echo (12-$class);?> col-md-6 col-12">
				<div class="summary entry-summary">
					<?php
						/**
						 * Hook: woocommerce_single_product_summary.
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						 * @hooked WC_Structured_Data::generate_product_data() - 60
						 */
						do_action( 'woocommerce_single_product_summary' );
					?>
				</div>
			</div>
			<div class="col-image col-lg-<?php echo esc_attr($class);?> col-md-6 col-12">
				<?php
				/**
				 * Hook: woocommerce_before_single_product_summary.
				 *
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );
				?>
			</div>
			
		</div>
	</div>
</div>
<div class="container">
	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'vasia_after_single_product_summary' );
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>