<?php 
$nixer_redux_demo = get_option('redux_demo');
get_template_part('header/header', 9);
?>
<?php 
while (have_posts()): the_post();
	$port_thumb_1 = get_post_meta(get_the_ID(),'_cmb_port_thumb_1', true);
	$port_thumb_2 = get_post_meta(get_the_ID(),'_cmb_port_thumb_2', true);
	$port_title = get_post_meta(get_the_ID(),'_cmb_port_title_excerpt', true);
	$port_client = get_post_meta(get_the_ID(),'_cmb_port_client', true);
	$port_link = get_post_meta(get_the_ID(),'_cmb_port_link', true);
	$port_intro = get_post_meta(get_the_ID(),'_cmb_port_intro', true);
	$port_parti = get_post_meta(get_the_ID(),'_cmb_port_participants', true);
?>
	<main data-bg-color="#FBFBFB">
		<section class="tp-portfolio-details-ptb p-header pt-80 p-relative">
			<div class="container-fluid gx-0">
				<div class="row gx-0">
					<div class="col-lg-12">
						<div class="tp-portfolio-details-top">
							<?php if (!empty($port_thumb_1)) { ?>
								<img src="<?php echo wp_get_attachment_url($port_thumb_1);?>" alt="<?php the_title_attribute(); ?>">
							<?php } else { ?>
								<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/project/projet-details/project-details-thumb-1.jpg" alt="<?php the_title_attribute(); ?>"> 
							<?php } ?>
						</div>
					</div>
				</div>
			</div>

			<div class="container container-1520">
				<div class="tp-portfolio-details-wrap">
					<div class="row">
						<div class="col-lg-6">
							<h3 class="tp-portfolio-details-title">
								<?php if ( '' !== wp_specialchars_decode($port_title)) { ?>
									<?php echo wp_kses_post($port_title); ?>
								<?php } else { ?>
									<?php the_title(); ?>
								<?php } ?>
							</h3>
							<div class="tp-portfolio-details-info">
								<?php if (!empty($nixer_redux_demo['port-show-cat'])): ?>
									<div class="tp-portfolio-details-info-item">
										<?php if (!empty($nixer_redux_demo['port-info-cat'])): ?>
											<span><?php echo wp_kses_post($nixer_redux_demo['port-info-cat']);?></span>
										<?php endif ?>
										<p><?php echo get_the_term_list( get_the_ID(), 'portfolio-cat', '', ', ', '' ); ?></p>
									</div>
								<?php endif ?>
								<?php if (!empty($nixer_redux_demo['port-show-date'])): ?>
									<div class="tp-portfolio-details-info-item">
										<?php if (!empty($nixer_redux_demo['port-info-date'])): ?>
											<span><?php echo wp_kses_post($nixer_redux_demo['port-info-date']);?></span>
										<?php endif ?>
										<p><?php the_time(get_option( 'date_format' ));?></p>
									</div>
								<?php endif ?>
								<?php if (!empty($nixer_redux_demo['port-show-client']) && !empty($port_client)): ?>
									<div class="tp-portfolio-details-info-item">
										<?php if (!empty($nixer_redux_demo['port-info-client'])): ?>
											<span><?php echo wp_kses_post($nixer_redux_demo['port-info-client']);?></span>
										<?php endif ?>
										<p><?php echo wp_kses_post($port_client); ?></p>
									</div>
								<?php endif ?>
								<?php if (!empty($nixer_redux_demo['port-show-website']) && !empty($port_link)): ?>
									<div class="tp-portfolio-details-info-btn">
										<a href="<?php echo wp_kses_post($port_link); ?>"><?php echo wp_kses_post($nixer_redux_demo['port-info-website']);?></a>
									</div>
								<?php endif ?>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="tp-portfolio-details-right">
								<?php if (!empty($nixer_redux_demo['port-show-intro'])): ?>
									<div class="tp-portfolio-details-heading">
										<?php if (!empty($nixer_redux_demo['port-intro'])): ?>
											<span><?php echo wp_kses_post($nixer_redux_demo['port-intro']);?></span>
										<?php endif ?>
										<p><?php echo wp_kses_post($port_intro); ?></p>
									</div>
								<?php endif ?>
								<?php if (!empty($nixer_redux_demo['port-show-participants'])): ?>
									<div class="tp-portfolio-details-list">
										<?php if (!empty($nixer_redux_demo['port-participants'])): ?>
											<h4 class="tp-portfolio-details-list-title"><?php echo wp_kses_post($nixer_redux_demo['port-participants']);?></h4>
										<?php endif ?>
										<?php if ( ! empty( $port_parti ) && is_array( $port_parti ) ) : ?>
											<ul>
												<?php
												foreach ( $port_parti as $index => $parti ) :
													?>
													<?php if (!empty($parti)): ?>
														<li>
															<span>
																<svg xmlns="http://www.w3.org/2000/svg" width="13" height="8" viewBox="0 0 13 8" fill="none">
																	<path d="M4.99099 7.84596C4.87207 7.94512 4.71768 8 4.55762 8C4.39756 8 4.24317 7.94512 4.12425 7.84596L0.663542 4.87475C0.578894 4.8045 0.511188 4.71852 0.464838 4.62243C0.418487 4.52634 0.394531 4.42228 0.394531 4.31705C0.394531 4.21181 0.418487 4.10776 0.464838 4.01167C0.511188 3.91557 0.578894 3.8296 0.663542 3.75934L1.09691 3.38722C1.27518 3.23827 1.50681 3.15581 1.74697 3.15581C1.98713 3.15581 2.21876 3.23827 2.39703 3.38722L4.55762 5.24204L10.3951 0.231404C10.5734 0.0824528 10.805 0 11.0451 0C11.2853 0 11.5169 0.0824528 11.6952 0.231404L12.1286 0.603529C12.2132 0.673784 12.2809 0.759759 12.3273 0.855852C12.3736 0.951945 12.3976 1.056 12.3976 1.16123C12.3976 1.26647 12.3736 1.37052 12.3273 1.46661C12.2809 1.56271 12.2132 1.64868 12.1286 1.71894L4.99099 7.84596Z" fill="#19191A"/>
																</svg>
															</span> 
															<?php echo wp_kses_post( $parti ); ?>
														</li>
													<?php endif ?>
												<?php endforeach; ?>
											</ul>
										<?php endif ?>
									</div>
								<?php endif ?>
							</div>
						</div>
					</div>
				</div>
				<?php if (!empty($port_thumb_2)): ?>
					<div class="row">
						<div class="col-lg-12">
							<div class="tp-portfolio-details-thumb fix mb-60">
								<img data-speed=".8" src="<?php echo wp_get_attachment_url($port_thumb_2);?>" alt="<?php the_title_attribute(); ?>">
							</div>
						</div>
					</div>
				<?php endif ?>
			</div>
		</section>
		<?php the_content(); ?>
	</main>
<?php endwhile; ?>
<?php get_template_part( 'footer/footer', '2' ); ?>