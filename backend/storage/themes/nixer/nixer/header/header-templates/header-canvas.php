<?php 
$nixer_redux_demo = get_option('redux_demo');
?>
<div class="offcanvas__area"> 
	<div class="offcanvas__close">
		<button class="offcanvas__close-btn offcanvas-close-btn">
			<svg xmlns="http://www.w3.org/2000/svg" width="37" height="38" viewBox="0 0 37 38" fill="none">
				<path d="M9.19238 9.80762L27.5772 28.1924" stroke="currentColor" stroke-opacity="0.6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				<path d="M9.19238 28.1924L27.5772 9.80761" stroke="currentColor" stroke-opacity="0.6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			 </svg>
		</button>
	</div>
	<div class="offcanvas__wrapper">
		<div class="offcanvas__content">
			<div class="offcanvas__top mb-80">
				<div class="offcanvas__logo">
					<a href="<?php echo esc_url(home_url('/')); ?>">
						<?php if (!empty($nixer_redux_demo['hd-cv-logo']['url'])) { ?>
							<img data-width="120" src="<?php echo esc_url($nixer_redux_demo['hd-cv-logo']['url']); ?>" alt="<?php bloginfo( 'name' ); ?>">
						<?php } else { ?>
							<img data-width="120" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/logo/logo.png" alt="<?php bloginfo( 'name' ); ?>">
						<?php } ?>
					</a>
				</div>
			</div>
			<div class="tp-offcanvas-menu fix d-xl-none mb-30">
				<nav></nav>
			</div>
			<?php if (!empty($nixer_redux_demo['hd-cv-title']) || !empty($nixer_redux_demo['hd-cv-text'])): ?>
				<div class="offcanvas__contact d-none d-xl-block">
					<?php if (!empty($nixer_redux_demo['hd-cv-title'])): ?>
						<h4 class="offcanvas__title"><?php echo esc_html($nixer_redux_demo['hd-cv-title']); ?></h4>
					<?php endif ?>
					<?php if (!empty($nixer_redux_demo['hd-cv-text'])): ?>
						<p><?php echo esc_html($nixer_redux_demo['hd-cv-text']); ?></p>
					<?php endif ?>
				</div>
			<?php endif ?>
			<?php if (!empty($nixer_redux_demo['hd-cv-img']['url'])): ?>
				<div class="offcanvas__thumb mb-50">
					<img src="<?php echo esc_url($nixer_redux_demo['hd-cv-img']['url']); ?>" alt="<?php bloginfo( 'name' ); ?>">
				</div>
			<?php endif ?>
			<?php if (!empty($nixer_redux_demo['hd-cv-social-switch'])): ?>
				<?php if (!empty($nixer_redux_demo['hd-cv-rp-social']['redux_repeater_data']) && is_array($nixer_redux_demo['hd-cv-rp-social']['redux_repeater_data'])): ?>
					<div class="offcanvas__social">
						<?php if (!empty($nixer_redux_demo['hd-cv-social-title'])): ?>
							<h4 class="offcanvas__social-title"><?php echo esc_html($nixer_redux_demo['hd-cv-social-title']); ?></h4>
						<?php endif ?>
						<?php 
						$num = is_array($nixer_redux_demo['hd-cv-rp-social']['redux_repeater_data']) ? count($nixer_redux_demo['hd-cv-rp-social']['redux_repeater_data']) : 0;
						$i = 0;
						for ($i=0; $i < $num ; $i++) { 
							$social_img_url = $nixer_redux_demo['hd-cv-rp-social']['img-field'][$i]['url'] ?? '';
							$social_link_field = $nixer_redux_demo['hd-cv-rp-social']['link-field'][$i] ?? '';
						?>
							<?php if (!empty($social_link_field) && !empty($social_img_url)): ?>
								<a class="icon" href="<?php echo esc_attr($social_link_field); ?>">
									<span>
										<?php if (strtolower(pathinfo($social_img_url, PATHINFO_EXTENSION)) === 'svg') { ?>
											<?php echo nixer_inline_svg_from_url($social_img_url); ?>
										<?php } else { ?>
											<img src="<?php echo esc_url($social_img_url); ?>" alt="<?php echo wp_kses_post($item['text_item']); ?>">
										<?php } ?>
									</span>
								</a>
							<?php endif ?>
						<?php } ?>
					</div>
				<?php endif ?>
			<?php endif ?>
		</div>
	</div>
</div>
<div class="body-overlay"></div>