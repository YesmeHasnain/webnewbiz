<?php 
$nixer_redux_demo = get_option('redux_demo');
get_header();
?>
	<main data-bg-color="#121212">
		<?php 
		while (have_posts()) : the_post();
			$sv_title = get_post_meta(get_the_ID(),'_cmb_sv_title_excerpt', true);
			$sv_subtitle = get_post_meta(get_the_ID(),'_cmb_sv_subtitle', true);
			$sv_text = get_post_meta(get_the_ID(),'_cmb_sv_text', true);
		?>

			<section class="tp-service-details-ptb pt-200 pb-140">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<div class="tp-service-details-wrapper p-relative mb-100">
								<div class="tp-service-details-heading pb-120">
									<h3 class="tp-service-details-title">
										<?php if ( '' !== wp_specialchars_decode($sv_title)) { ?>
											<?php echo wp_kses_post($sv_title); ?>
										<?php } else { ?>
											<?php the_title(); ?>
										<?php } ?>
									</h3>
									<?php if (!empty($sv_subtitle)): ?>
										<p><?php echo wp_kses_post($sv_subtitle); ?></p>
									<?php endif ?>
									</div>
									<?php if (has_post_thumbnail()): ?>
										<div class="tp-service-details-thumb tp_img_reveal">
											<img class="ratio-275x322" src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>">
										</div>
									<?php endif ?>
									<?php if ( ! empty( $sv_text ) && is_array( $sv_text ) ) : ?>
										<div class="tp-service-details-text tp-text-effect">
											<?php
											foreach ( $sv_text as $index => $text ) :
												?>
												<?php if (!empty($text)): ?>
													<span>
														<?php echo wp_kses_post( $text ); ?>
														<svg xmlns="http://www.w3.org/2000/svg" width="72" height="75" viewBox="0 0 72 75" fill="none">
															<path d="M0.311616 52.1165L10.1554 66.0545C10.6975 66.825 11.7664 67.0108 12.5394 66.467L25.7386 57.208V73.2955C25.7386 74.2381 26.5047 75 27.4487 75H44.5505C45.4945 75 46.2607 74.2381 46.2607 73.2955V57.208L59.4599 66.4688C60.2346 67.0108 61.2983 66.8267 61.8439 66.0563L71.6876 52.1182C72.2315 51.3494 72.0451 50.2858 71.2738 49.7438L53.8248 37.5L71.2721 25.2563C72.0451 24.7159 72.2298 23.6523 71.6859 22.8818L61.8421 8.94375C61.2983 8.1733 60.2329 7.9892 59.4582 8.53125L46.2607 17.792V1.70455C46.2607 0.761932 45.4945 0 44.5505 0H27.4487C26.5047 0 25.7386 0.761932 25.7386 1.70455V17.792L12.5394 8.53125C11.7664 7.99091 10.6992 8.175 10.1571 8.94545L0.313326 22.8835C-0.230511 23.6523 -0.0441011 24.7159 0.72719 25.258L18.1744 37.5L0.72719 49.7438C-0.0458112 50.2841 -0.232221 51.3477 0.311616 52.1165ZM22.1301 38.8926C23.464 37.8545 22.6893 36.4432 22.1301 36.1057L4.09453 23.4511L11.9699 12.3017L26.4654 22.471C27.8284 23.3659 29.1846 22.2375 29.1606 21.0784V3.40909H42.8421V21.0767C42.7737 22.0125 44.0597 23.4051 45.5373 22.4693L60.0328 12.3L67.9081 23.4494L49.8692 36.1074C49.1475 36.5949 48.6686 38.0182 49.8692 38.8943L67.9047 51.5506L60.0294 62.7L45.5339 52.5307C44.778 52.0091 42.9823 52.05 42.8386 53.9233V71.5909H29.1572V53.9233C29.0802 52.2256 27.2572 51.9136 26.462 52.5307L11.9665 62.7L4.09111 51.5506L22.1301 38.8926Z" fill="white" fill-opacity="0.3"/>
														</svg>
													</span>
												<?php endif ?>
											<?php endforeach; ?>
										</div>
									<?php endif ?>
								</div>
							</div>
						</div>
					</div>
				</section>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</main>
<?php get_footer(); ?>