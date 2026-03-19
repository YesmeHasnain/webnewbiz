<?php 
$nixer_redux_demo = get_option('redux_demo'); 
?>
			   	<?php 
				$footer_slug = isset($nixer_redux_demo['f1-select']) ? $nixer_redux_demo['f1-select'] : '';
				if ($footer_slug && empty($nixer_redux_demo['f1-widget-switch'])) {
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
					<footer class="tp-footer-ptb pt-50" data-bg-color="<?php echo esc_attr($nixer_redux_demo['f1-bg-color']); ?>">
						<div class="container-fluid">
							<div class="tp-footer-copyright">
								<div class="row">
									<div class="col-lg-12 text-center">
										<div class="tp-footer-copyright-text">
											<?php if (!empty($nixer_redux_demo['f1-copyright'])) { ?>
												<p><?php echo html_entity_decode($nixer_redux_demo['f1-copyright']); ?></p>
											<?php } else { ?>
												<p><?php echo html_entity_decode( '©2025- All Rights Reserved' , ENT_COMPAT , 'nixer' ) ?></p>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</footer>
				<?php } ?>
			</div>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>
