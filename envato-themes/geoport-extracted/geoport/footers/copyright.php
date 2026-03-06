<div class="copyright-text text-center">
    <div class="container">
        <p>
        <?php if ( function_exists( 'geoport_framework_init' ) ) { ?>
            <?php echo wp_kses_stripslashes( geoport_get_option('copyrights') ); ?>
        <?php } else { ?>
            <?php esc_html_e( 'Copyright &copy; 2024 by - Geoport', 'geoport' ); ?>
        <?php } ?>
        </p>
    </div>
</div>