<?php
/**
 * solak Dashboard Install Plugins Page
 *
 * @package solak
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$solak_my_theme = wp_get_theme();
if ( $solak_my_theme->parent_theme ) {
	$solak_my_theme = wp_get_theme( basename( get_template_directory() ) );
}

?>

<div class="wrap about-wrap et-admin-wrap">

	<div class="et-header">
		<h1><?php echo esc_html__( 'Welcome to ', 'solak' ) . esc_html( $solak_my_theme->Name ); ?> <?php printf( esc_html__( 'V%s', 'solak' ), esc_html( $solak_my_theme->Version ) ); ?></h1>
		<div class="about-text"><?php echo esc_html( $solak_my_theme->Name ) . esc_html__( ' is now installed and ready to use!', 'solak' ); ?></div>
		<h2>Developed By <a href="<?php echo esc_url( 'https://themeforest.net/user/themeholy/portfolio' ); ?>" target="_blank"><?php esc_html_e( 'Themeholy', 'solak' ); ?></a></h2>
	</div>

	<h2 class="nav-tab-wrapper wp-clearfix">
		<a class="nav-tab" href="<?php echo esc_url( self_admin_url( 'admin.php?page=solak-dashboard' ) ); ?>"><?php esc_html_e( 'Dashboard', 'solak' ); ?></a>
		<a class="nav-tab nav-tab-active" href="<?php echo esc_url( self_admin_url( 'admin.php?page=solak-admin-plugins' ) ); ?>"><?php esc_html_e( 'Install Plugins', 'solak' ); ?></a>
		<a class="nav-tab" href="<?php echo esc_url( self_admin_url( 'themes.php?page=solak-demo-import' ) ); ?>"><?php esc_html_e( 'Demo Importer', 'solak' ); ?></a>
	</h2>
	<?php
    if ( isset($_POST['solak_demo']) ) {
		echo "<meta http-equiv='refresh' content='0'>";
        update_option('et_solak_demo_plugin_name', $_POST['solak_demo'], 'yes');
    }
    $solak_demo = get_option( 'et_solak_demo_plugin_name' );
    if($solak_demo):
        update_option('et_selected_solak_demo_plugin', $solak_demo, 'yes');
    else:
        update_option('et_selected_solak_demo_plugin', 'without_woocommerce', 'yes');
    endif;
    ?>

    <form action="" method="post" id="et-solak_demo-check" class="et-theme-register-form">
        <div class="solak_demo-plugin">
            <h3><?php echo esc_html__('Please select the preferred version of the theme you would like to use.', 'solak'); ?></h3>
            <ul> 
                <li>
                    <input type="radio" id="with_woocommerce" name="solak_demo" value="with_woocommerce" <?php if($solak_demo == 'with_woocommerce'): ?>checked<?php endif; ?>>
                    <label for="with_woocommerce"><?php echo esc_html__('With WooCommerce', 'solak'); ?></label>
                </li>
                <li>
                    <input type="radio" id="without_woocommerce" name="solak_demo" value="without_woocommerce" <?php if($solak_demo == 'without_woocommerce'): ?>checked<?php endif; ?>>
                    <label for="without_woocommerce"><?php echo esc_html__('Without WooCommerce', 'solak'); ?></label>
                </li>
            </ul>
        </div>

		

        <input type="submit" class="et-solak_demo-btn" value='Save'>
    </form>

	<div id="solak-dashboard" class="wrap about-wrap">
		<div class="welcome-content w-clearfix extra">
			<div class="solak-plugins solak-theme-browser-wrap">
				<div class="theme-browser rendered">
					<div class="whi-install-plugins-wrap">
						<h3><?php echo esc_html__( 'These below plugins are required', 'solak' ); ?></h3>
						<a href="#" class="solak-admin-btn whi-install-plugins"><?php echo esc_html__( 'Activate all plugins', 'solak' ); ?></a>
						

						
					</div>
					<div class="solak-plugins-wrap solak-plugins">

					<?php

					$tgmpa_list_table = new TGMPA_List_Table();
					$plugins          = TGM_Plugin_Activation::$instance->plugins;

					foreach ( $plugins as $plugin ) :

						$plugin_status              = '';
						$plugin['type']             = isset( $plugin['type'] ) ? $plugin['type'] : 'recommended';
						$plugin['sanitized_plugin'] = $plugin['name'];

						$plugin_action = $tgmpa_list_table->actions_plugin( $plugin );

						if ( strpos( $plugin_action, 'deactivate' ) !== false ) {
							$plugin_status = 'active';
							$plugin_action = '<div class="row-actions visible active"><span class="activate"><a class="button solak-admin-btn">' . esc_html__( 'Activated', 'solak' ) . '</a></span></div>';
						}

						?>
						<div class="solak-plugin wp-clearfix <?php echo esc_attr( $plugin_status ); ?>" data-plugin-name="<?php echo esc_html( $plugin['name'] ); ?>">
							<h4><?php echo esc_html( $plugin['name'] ); ?></h4>
							<?php echo '' . $plugin_action; ?>
						</div>

					<?php endforeach; ?>

					</div>
				</div>
			</div>
		</div>
	</div>

</div> <!-- end wrap -->
