<?php
/*
 * Template Name: Nixer Checkout Page Templates
 * Description: A Page Template with a Page Builder design.
 */
$nixer_redux_demo = get_option('redux_demo');
get_header();
?>
<main>
	<section class="tp-shop-breadcrumb-ptb pt-160 pb-100 p-relative">
		<?php if (!empty($nixer_redux_demo['checkout-bg']['url'])): ?>
			<div class="tp-about-me-bg" data-background="<?php echo esc_url($nixer_redux_demo['checkout-bg']['url']); ?>"></div>
		<?php endif ?>
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="tp-about-us-heading">
						<?php if (!empty($nixer_redux_demo['checkout-heading'])): ?>
							<span class="tp-breadcrumb-subtitle"><?php echo esc_html($nixer_redux_demo['checkout-heading']); ?></span>
						<?php endif ?>
						<h3 class="tp-breadcrumb-title tp-title-anim">
							<?php if ((isset($nixer_redux_demo['checkout-title'])) && ('' != $nixer_redux_demo['checkout-title'])) { ?>
								<?php echo esc_attr($nixer_redux_demo['checkout-title']); ?>
							<?php } else { ?>
								<?php echo esc_html__( 'Checkout', 'nixer' )?>
							<?php } ?>
						</h3>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="tp-checkout-area pb-120 pt-120" data-bg-color="#EFF1F5">
		<div class="container">
			<?php if (have_posts()){ ?>
				<?php while (have_posts()) : the_post()?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			<?php }else {
				echo esc_html__( 'Nixer Checkout Page Templates', 'nixer' );
			}?>
		</div>
	</section>
</main>
<?php 
get_footer();
?>