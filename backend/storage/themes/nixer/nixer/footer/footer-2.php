<?php 
$nixer_redux_demo = get_option('redux_demo'); 
?>
			   	<?php 
				$footer_slug = isset($nixer_redux_demo['f2-select']) ? $nixer_redux_demo['f2-select'] : '';
				if ($footer_slug && empty($nixer_redux_demo['f2-widget-switch'])) {
					$footer_post = get_page_by_path($footer_slug, OBJECT, 'custom_footer');

					if ($footer_post) {
						if (class_exists('\Elementor\Plugin') && isset(\Elementor\Plugin::$instance)) {
							$elementor_instance = \Elementor\Plugin::$instance;
							$document = $elementor_instance->documents->get($footer_post->ID);

							if ($document && $document->is_built_with_elementor()) {
								$elementor_instance->frontend->enqueue_styles();
								$elementor_instance->frontend->enqueue_scripts();
								echo '<div class="custom-footer-elementor">';
								echo html_entity_decode($elementor_instance->frontend->get_builder_content_for_display($footer_post->ID));
								echo '</div>';
							}
						}
					}
				} else { ?>
					<section class="tp-footer-2-ptb" data-bg-color="<?php echo esc_attr($nixer_redux_demo['f2-bg-color']); ?>">
						<div class="tp-footer-2-copyright-ptb">
							<div class="container container-1480">
								<div class="row">
									<div class="col-lg-12 text-center">
										<div class="tp-footer-2-copyright-text">
											<?php if (!empty($nixer_redux_demo['f2-copyright'])) { ?>
												<p><?php echo html_entity_decode($nixer_redux_demo['f2-copyright']); ?></p>
											<?php } else { ?>
												<p><?php echo html_entity_decode( 'All rights reserved — 2025 © Shtheme.' , ENT_COMPAT , 'nixer' ) ?></p>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				<?php } ?>
			</div>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>
