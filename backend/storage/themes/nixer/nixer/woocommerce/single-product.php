<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$nixer_redux_demo = get_option('redux_demo');
get_template_part( 'header/header', '9' ); ?>
<main>
	<section class="tp-shop-breadcrumb-ptb pt-160 pb-100 p-relative">
		<?php if (!empty($nixer_redux_demo['shop-bg']['url'])): ?>
			<div class="tp-about-me-bg" data-background="<?php echo esc_url($nixer_redux_demo['shop-bg']['url']); ?>"></div>
		<?php endif ?>
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="tp-about-us-heading">
						<?php if (!empty($nixer_redux_demo['shop-heading'])): ?>
							<span class="tp-breadcrumb-subtitle"><?php echo wp_specialchars_decode(esc_attr($nixer_redux_demo['shop-heading']));?></span>
						<?php endif ?>
						<h3 class="tp-breadcrumb-title tp-title-anim">
							<?php if(isset($nixer_redux_demo['shop-title']) && $nixer_redux_demo['shop-title']!=''){?>
								<?php echo wp_specialchars_decode(esc_attr($nixer_redux_demo['shop-title']));?>
							<?php }else{?>
								<?php echo esc_html__( 'Shop Detail', 'nixer' );
							}?>
						</h3>
					</div>
				</div>
			</div>
		</div>
	</section>

	
	<?php while ( have_posts() ) : ?>
		
		<?php the_post(); ?>

		<?php wc_get_template_part( 'content', 'single-product' ); ?>

	<?php endwhile; ?>

	<?php if (!empty($nixer_redux_demo['shop-related-switch'])): ?>
		<?php get_template_part( 'single-templates/single', 'related-product' );?>
	<?php endif ?>

</main>
	

<?php
get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
