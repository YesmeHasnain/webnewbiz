<?php
	define('SITE_URL', 'https://reactheme.com/products/license');
	
	function enqueue_license_ajax_script() {
	    
        wp_enqueue_script('theme-license-js', get_template_directory_uri() . '/assets/js/license.js', array('jquery'), null, true);
    
		$admin_email = get_option('admin_email');
		$admin = get_user_by( 'email', $admin_email );
		$first_name = $admin->first_name;
		$last_name = $admin->last_name;
		$domain = site_url();
		$current_theme = wp_get_theme();
		$item_name = $current_theme->get('Name');
		$item_id = md5($item_name);
		$client_name = $admin->user_login;

		$license_data = array( 
			'item_name' => $item_name, 
			'item_id' => $item_id,  
			'admin_email' => $admin_email,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'client_name' => $client_name,
			'domain' => $domain,
		);

        // Localize script to pass AJAX URL and nonce
        wp_localize_script('theme-license-js', 'license_ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('license_ajax_nonce'),
			'license_data' => $license_data
        ));
    }
    add_action('admin_enqueue_scripts', 'enqueue_license_ajax_script');


    add_action( 'wp_head', 'check_reactheme_license' );
	
	function check_reactheme_license(){

		$current_domain = site_url();
		$license_code = get_option( 'reacthemes_license_code', true );
		$url = SITE_URL . '/wp-json/reacthemes/v2/check_license_domain/?relation=and&code='.$license_code.'&domain='.$current_domain;

		$response = wp_remote_post( $url, array(
			'method' => 'GET',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => array( 'code' => $license_code ),
			'cookies' => array()
			)
		);
	
		$status = json_decode(wp_remote_retrieve_body( $response ) );
				
	
		if($status === '404'){
			delete_option( 'reacthemes_license_status' );
			delete_option( 'reacthemes_license_code' );
			if(get_page_by_title( 'Reacthemes licence', 'page' ) == NULL) {
				$createPage = array(
					'post_title'    => 'Reacthemes licence',
					'post_content'  => '<h2 class="wp-block-heading has-text-align-center has-vivid-red-color has-text-color has-link-color wp-elements-6c820cef03ffd0c09683f73666742809">Your theme is not registered! Please register your theme to use all features.</h2>',
					'post_status'   => 'publish',
					'post_type'     => 'page',
					'post_name'     => 'Reacthemes licence'
				  );
				  // Insert the post into the database
				  wp_insert_post( $createPage );
			}
			if(get_page_by_title( 'Reacthemes licence', 'page' ) != NULL) {
				if(get_the_title() != 'Reacthemes licence'){
					wp_redirect( site_url().'/reacthemes-licence' );
				}
			}
		}
	}

	if(get_option( 'reacthemes_license_status') != "activated"){

		add_action( 'admin_notices', 'reacthemes_admin_notice_warn' );
		remove_action( 'tgmpa_register', 'finbiz_register_required_plugins' );


		global $pagenow;

		if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {

			wp_redirect( get_admin_url() . 'admin.php?page=reacthemes-license' ); // Your admin page URL
			
		}	
		
	}

	// delete domain from license
	add_action( 'rest_api_init', function () {

		register_rest_route( 'reacthemes/v2', '/remove_license_domain', array(

		'methods' => 'POST',
		'callback' => 'remove_license_domain',
		'args' => array(),
		'permission_callback' => '__return_true',
		) );
		
	
	} );  

	function remove_license_domain(){
		delete_option( 'reacthemes_license_status' );
		delete_option( 'reacthemes_license_code' );
		delete_option( 'reacthemes_license_is_envato_elements' );
		return '200';
	}	

	function reacthemes_admin_notice_warn() {
		echo '<div class="notice notice-warning is-dismissible">
			<p>Important: Your theme is not registered! Please register your theme to upload demo and use all features.</p>
			</div>'; 
	}


	class ReacthemesLicense {
		private $reacthemes_license_options;
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'reacthemes_license_add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'reacthemes_license_page_init' ) );
			
			// Hook for logged-in users
            add_action('wp_ajax_activate_license', [$this, 'handle_activate_license_request']);
            add_action('wp_ajax_remove_license', [$this, 'handle_remove_license_request']);
		}

		public function reacthemes_license_add_plugin_page() {
			add_menu_page(
				'Reacthemes License', // page_title
				'Reacthemes License', // menu_title
				'manage_options', // capability
				'reacthemes-license', // menu_slug
				array( $this, 'reacthemes_license_create_admin_page' ), // function
				'dashicons-admin-generic', // icon_url
				2 // position
			);
		}


		public function verifyEnvatoElementsToken($client_token){

		}
		
		
		public function handle_activate_license_request(){
		    
		     
		    if(isset($_POST['action']) && $_POST['action'] == 'activate_license' 
			&& isset($_POST['status']) && isset($_POST['license_code']) 
			&& !empty($_POST['license_code'])){
		       
		        $activation_request_response =  sanitize_text_field(wp_unslash($_POST['status']));
		        $license_code = $_POST['license_code'];
		        $is_envato_elements = $_POST['is_envato_elements'];

				update_option( 'reacthemes_license_status', 'activated' );
				update_option( 'reacthemes_license_code', $license_code );
				update_option( 'reacthemes_license_is_envato_elements', $is_envato_elements == 'on' ? 'yes' : 'no' );
				
				wp_send_json_success( array(
					'message' => 'License activated successfully!', // message to display
				));

		    }else{
				wp_send_json_error( array(
					'message' => 'License activation failed!', // message to display
				));
			}

			wp_die();
		    
		}

		public function handle_remove_license_request(){
		    
		     
		    if(isset($_POST['action']) && $_POST['action'] == 'remove_license'){
		       
		        delete_option( 'reacthemes_license_status' );
				delete_option( 'reacthemes_license_code' );
				delete_option( 'reacthemes_license_is_envato_elements' );
				
				wp_send_json_success( array(
					'message' => 'License removed successfully!', // message to display
				));

		    }

			wp_die();
		    
		}


        


		public function reacthemes_license_create_admin_page() {
				
				?>
					<?php settings_errors(); ?>
					<div class="reacthemes-license-activator-form-wrapper">
						<div class="reacthemes-license-activator-form-header">
							<h1>Activate your Licence</h1>
							<p>Thank you for Using Our Themes! <br> This theme need to be activated to allow demo data import and customer support.</p>
						</div>
						
						<form method="POST" id="reacthemes-license-activator-form">
							<div class="form-status"></div>
							<?php
						
								$current_license_Status = get_option( 'reacthemes_license_status' );
								$license_code = get_option( 'reacthemes_license_code' );
								
								if($current_license_Status === 'activated'){
									echo '<h3 style="color:green;text-align: center">Registered!.</h3>';
								}

								settings_fields( 'reacthemes_license_option_group' );
								do_settings_sections( 'reacthemes-license-admin' ); 
								
								
								if($current_license_Status != 'activated'){
									echo '<p class="submit"><input type="submit" name="reacthemes_license_options[activate_license]" id="submit" class="button button-primary" value="Activate" data-submit-type="activation"></p>';
								}else{
									echo '<p class="submit"><input type="submit" name="reacthemes_license_options[deactivate_license]" id="submit" class="button button-primary" value="Deactivate" data-submit-type="deactivation"></p>';
								}
							?>
							
						</form>
						<div class="reacthemes-license-activator-form-footer">
							<a href="https://themeforest.net/user/reacthemes/portfolio" target="_blank">Check our portofolio if you want to buy more license code.</a>
						</div>
					</div>
					
				
				<?php 
			

		}

		public function reacthemes_license_page_init() {
			register_setting(
				'reacthemes_license_option_group', // option_group
				'reacthemes_license_options', // option_name
				array( $this, 'reacthemes_license_sanitize' ) // sanitize_callback
			);

			add_settings_section(
				'reacthemes_license_setting_section', // id
				'', // title
				array( $this, 'reacthemes_license_section_info' ), // callback
				'reacthemes-license-admin' // page
			);

			add_settings_field(
				'license_code', // id
				'', // title
				array( $this, 'license_code_callback' ), // callback
				'reacthemes-license-admin', // page
				'reacthemes_license_setting_section' // section
			);
		}

		public function reacthemes_license_sanitize($input) {
			$sanitary_values = array();
			if ( isset( $input['license_code'] ) ) {
				$sanitary_values['license_code'] = sanitize_text_field( $input['license_code'] );
			}

			return $sanitary_values;
		}

		public function reacthemes_license_section_info() {
			
		}

		public function license_code_callback() {

			$current_license_Status = get_option( 'reacthemes_license_status' ); 
			$license_code = get_option( 'reacthemes_license_code' ); 
			$reacthemes_license_is_envato_elements = get_option( 'reacthemes_license_is_envato_elements' );
			
			

			if( $current_license_Status === "activated" ){
				?>
					<input class="regular-text"  type="hidden" name="reacthemes_license_options[license_code]" id="license_code" value="<?php echo $license_code ? $license_code : '' ?>" placeholder="Enter your purchase code here">
				<?php
			}

			?>
			
			<div>
				<input class="regular-text" <?php echo $current_license_Status === "activated" ?  "disabled" : '' ?> type="password" name="reacthemes_license_options[license_code]" id="license_code" value="<?php echo $license_code ? $license_code : '' ?>" placeholder="Enter your purchase code here">

				<label for="is_envato_elements" style="display:block;margin-top:10px;" class="is_envato_elements_chehckbox_wrapper">
					<input type="checkbox" name="reacthemes_license_options[is_envato_elements]" id="is_envato_elements" <?php echo $reacthemes_license_is_envato_elements == 'yes' ? 'checked' : ''; ?> value="<?php echo $reacthemes_license_is_envato_elements ?>">
					I downloaded the theme from Envato Elements.
				</label>
				
			</div>

			<?php

				$current_theme = wp_get_theme();
				$theme_name = $current_theme->get( 'Name' );

				?>
				
				<p id="reacthemes-code-help-elements" style="display:<?php echo $reacthemes_license_is_envato_elements == 'yes' ? 'block' : 'none'; ?>">

				<?php
				echo wp_kses( sprintf( __( ' <a href="%s" target="_blank">Follow this link to generate a new Envato Elements Token.</a>', 'coiffure' ), esc_url( 'https://api.extensions.envato.com/extensions/begin_activation'
					. '?extension_id=' . md5( $theme_name )
					. '&extension_type=envato-wordpress'
					. '&extension_description='. $theme_name .' (' . get_home_url() . ')'
					) ), 'reacthemes' );

				?>
				</p>

				<p>Example: 86781236-23d0-4b3c-7dfa-c1c147e0dece <b> See how to <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">get Purchase code</a>?</b> </p>
				
			
			<?php

		}

	}

	if ( is_admin() )
	$reacthemes_license = new ReacthemesLicense();