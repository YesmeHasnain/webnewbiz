<?php ?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="footer-container">
			<div class="footer-columns">
				<div class="footer-col footer-col-brand">
					<?php
					the_custom_logo();
					?>
					<p class="footer-site-title"><?php bloginfo( 'name' ); ?></p>
					<p class="footer-tagline"><?php bloginfo( 'description' ); ?></p>
				</div>
				<div class="footer-col footer-col-nav">
					<h4 class="footer-heading"><?php esc_html_e( 'Quick Links', 'flavor-oceanic' ); ?></h4>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer_menu',
						'menu_id'        => 'footer-menu',
						'container'      => false,
						'fallback_cb'    => '__return_false',
						'depth'          => 1,
					) );
					if ( ! has_nav_menu( 'footer_menu' ) ) {
						wp_nav_menu( array(
							'theme_location' => 'header_menu',
							'menu_id'        => 'footer-menu-fallback',
							'container'      => false,
							'fallback_cb'    => false,
							'depth'          => 1,
						) );
					}
					?>
				</div>
				<div class="footer-col footer-col-contact">
					<h4 class="footer-heading"><?php esc_html_e( 'Contact', 'flavor-oceanic' ); ?></h4>
					<p class="footer-contact-text"><?php echo esc_html( get_option('admin_email', '') ); ?></p>
				</div>
			</div>
			<div class="footer-bottom">
				<p>&copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'flavor-oceanic' ); ?></p>
			</div>
		</div>
	</footer>

</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
