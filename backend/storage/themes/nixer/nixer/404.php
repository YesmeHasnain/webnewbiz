<?php 
$nixer_redux_demo = get_option('redux_demo');
get_template_part( 'header/header', 'error' );
?>

<main>
	<section class="tp-error-ptb pt-175 pb-170">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="tp-error-wrapper text-center">
						<div class="tp-error-thumb mb-45">
							<?php if (!empty($nixer_redux_demo['404-img']['url'])) { ?>
								<img src="<?php echo esc_url($nixer_redux_demo['404-img']['url']); ?>" alt="<?php bloginfo( 'name' ); ?>">
							<?php } else { ?>
								<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/others/404-error.png" alt="<?php bloginfo( 'name' ); ?>">
							<?php } ?>
						</div>
						<div class="tp-error-content mb-50">
							<h4 class="tp-error-title">
								<?php if (!empty($nixer_redux_demo['404-title'])) { ?>
									<?php echo html_entity_decode($nixer_redux_demo['404-title']); ?>
								<?php } else { ?>
									<?php echo html_entity_decode( 'Ooop! Error page!' , ENT_COMPAT , 'nixer' ) ?>
								<?php } ?>
							</h4>
							<p>
								<?php if (!empty($nixer_redux_demo['404-text'])) { ?>
									<?php echo html_entity_decode($nixer_redux_demo['404-text']); ?>
								<?php } else { ?>
									<?php echo html_entity_decode( "The page you are looking for doesn't <br> exist or has been moved." , ENT_COMPAT , 'nixer' ) ?>
								<?php } ?>
							</p>
						</div>
						<div class="tp-error-btn-wrap">
							<div class="tp-error-btn">
								<a class="tp-btn-2" href="<?php echo esc_url(home_url('/')); ?>">
									<?php if (!empty($nixer_redux_demo['404-button'])) { ?>
										<?php echo html_entity_decode($nixer_redux_demo['404-button']); ?>
									<?php } else { ?>
										<?php echo html_entity_decode( 'Back To Home' , ENT_COMPAT , 'nixer' ) ?>
									<?php } ?>
									<span>
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
											<path d="M15.7767 7.42864C15.9198 7.58033 16 7.78521 16 7.99872C16 8.21223 15.9198 8.41711 15.7767 8.56882L8.92138 15.77C8.77516 15.9169 8.58094 15.9992 8.37867 16C8.27759 15.9995 8.17746 15.9793 8.08351 15.9399C7.94489 15.8789 7.8266 15.7759 7.74358 15.6441C7.66056 15.5124 7.61651 15.3579 7.61698 15.1999V8.79885H0.761697C0.559684 8.79885 0.365942 8.71456 0.223096 8.5645C0.0802502 8.41444 0 8.21094 0 7.99872C0 7.78652 0.0802502 7.583 0.223096 7.43295C0.365942 7.28289 0.559684 7.1986 0.761697 7.1986H7.61698V0.797552C7.61651 0.639587 7.66056 0.485009 7.74358 0.3533C7.8266 0.22159 7.94489 0.118642 8.08351 0.0574314C8.2242 -0.00021391 8.37756 -0.0148385 8.52585 0.0152518C8.67412 0.0453405 8.81125 0.118914 8.92138 0.22746L15.7767 7.42864Z" fill="currentColor"/>
										</svg>
									</span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_template_part( 'footer/footer', '0' );
?>