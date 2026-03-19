<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function xcency_default_theme_options() {
	return array(
		'copyright_text' => wp_kses( __( '&copy; Xcency 2025 | All Right Reserved', 'xcency' ), xcency_allow_html() ),

		'footer_info_left_text' => wp_kses( __( 'Xcency | Developed by: <a target="_blank" href="https://quintexbd.com">Quintexbd</a>', 'xcency' ), xcency_allow_html() ),

		'not_found_text' => wp_kses( __( '<h2>Oops!</h2><h2> That page can&rsquo;t be found.</h2><p>We are really sorry but the page you requested is missing.</p>', 'xcency' ), xcency_allow_html() ),

		'post_banner_title'   => esc_html__( 'Blog', 'xcency' ),
		'blog_title'          => esc_html__( 'Blog', 'xcency' ),
		'blog_read_more_text' => esc_html__( 'Read More', 'xcency' ),
		'error_page_title'    => esc_html__( 'Error 404', 'xcency' ),
		'search_placeholder'  => esc_html__( 'Search...', 'xcency' ),
		'preloader_big_text'  => esc_html__( 'Xcency', 'xcency' ),
		'preloader_small_text'  => esc_html__( 'Loading...', 'xcency' ),
	);
}

//Get theme options
if ( ! function_exists( 'xcency_option' ) ) {
	function xcency_option( $option = '', $default = null ) {
		$defaults = xcency_default_theme_options();
		$options  = get_option( 'xcency_theme_options' );
		$default  = ( ! isset( $default ) && isset( $defaults[ $option ] ) ) ? $defaults[ $option ] : $default;

		return ( isset( $options[ $option ] ) ) ? $options[ $option ] : $default;
	}
}

//Add custom icon set

/**
 * Enqueue Backend Styles And Scripts.
 **/

function xcency_enqueue_backend_icon_for_csf() {
	wp_enqueue_style( 'flaticon', get_theme_file_uri( 'assets/fonts/flaticon/flaticon.css' ), array(), '1.0.0', 'all' );
}

add_action( 'admin_enqueue_scripts', 'xcency_enqueue_backend_icon_for_csf' );

if ( ! function_exists( 'xcency_csf_custom_icons' ) ) {

	function xcency_csf_custom_icons( $icons ) {

		// Adding new icons
		$icons[] = array(
			'title' => esc_html__( 'xcency Icons', 'xcency' ),
			'icons' => array(
				'flaticon-phone-call'           => 'flaticon-phone-call',
				'flaticon-email'                => 'flaticon-email',
				'flaticon-arroba'               => 'flaticon-arroba',
				'flaticon-search'               => 'flaticon-search',
				'flaticon-search-1'             => 'flaticon-search-1',
				'flaticon-menu'                 => 'flaticon-menu',
				'flaticon-left-arrow'           => 'flaticon-left-arrow',
				'flaticon-right-arrow'          => 'flaticon-right-arrow',
				'flaticon-right-arrow-1'        => 'flaticon-right-arrow-1',
				'flaticon-left-arrow-1'         => 'flaticon-left-arrow-1',
				'flaticon-quote'                => 'flaticon-quote',
				'flaticon-quote-1'              => 'flaticon-quote-1',
				'flaticon-left-quote'           => 'flaticon-left-quote',
				'flaticon-left-quote-1'         => 'flaticon-left-quote-1',
				'flaticon-quote-left'           => 'flaticon-quote-left',
				'flaticon-right-quote-sign'     => 'flaticon-right-quote-sign',
				'flaticon-up'                   => 'flaticon-up',
				'flaticon-up-2'                 => 'flaticon-up-2',
				'flaticon-customer-service'     => 'flaticon-customer-service',
				'flaticon-technical-support'    => 'flaticon-technical-support',
				'flaticon-quotation'            => 'flaticon-quotation',
				'flaticon-link'                 => 'flaticon-link',
				'flaticon-link-1'               => 'flaticon-link-1',
				'flaticon-link-2'               => 'flaticon-link-2',
				'flaticon-link-3'               => 'flaticon-link-3',
				'flaticon-shopping-cart'        => 'flaticon-shopping-cart',
				'flaticon-shopping-cart-1'      => 'flaticon-shopping-cart-1',
				'flaticon-comment'              => 'flaticon-comment',
				'flaticon-checkmark'            => 'flaticon-checkmark',
				'flaticon-cross'                => 'flaticon-cross',
				'flaticon-close'                => 'flaticon-close',
				'flaticon-cross-1'              => 'flaticon-cross-1',
				'flaticon-check'                => 'flaticon-check',
				'flaticon-check-1'              => 'flaticon-check-1',
				'flaticon-checkbox'             => 'flaticon-checkbox',
				'flaticon-conversation'         => 'flaticon-conversation',
				'flaticon-plus'                 => 'flaticon-plus',
				'flaticon-plus-1'               => 'flaticon-plus-1',
				'flaticon-plus-2'               => 'flaticon-plus-2',
				'flaticon-add'                  => 'flaticon-add',
				'flaticon-add-1'                => 'flaticon-add-1',
				'flaticon-image'                => 'flaticon-image',
				'flaticon-image-gallery'        => 'flaticon-image-gallery',
				'flaticon-about'                => 'flaticon-about',
				'flaticon-about-1'              => 'flaticon-about-1',
				'flaticon-action'               => 'flaticon-action',
				'flaticon-call-to-action'       => 'flaticon-call-to-action',
				'flaticon-flag'                 => 'flaticon-flag',
				'flaticon-flag-1'               => 'flaticon-flag-1',
				'flaticon-slider'               => 'flaticon-slider',
				'flaticon-image-1'              => 'flaticon-image-1',
				'flaticon-price-tag'            => 'flaticon-price-tag',
				'flaticon-tag'                  => 'flaticon-tag',
				'flaticon-discount'             => 'flaticon-discount',
				'flaticon-portfolio'            => 'flaticon-portfolio',
				'flaticon-portfolio-1'          => 'flaticon-portfolio-1',
				'flaticon-gallery'              => 'flaticon-gallery',
				'flaticon-image-gallery-1'      => 'flaticon-image-gallery-1',
				'flaticon-attach'               => 'flaticon-attach',
				'flaticon-offer'                => 'flaticon-offer',
				'flaticon-pricing'              => 'flaticon-pricing',
				'flaticon-megaphone'            => 'flaticon-megaphone',
				'flaticon-promotion'            => 'flaticon-promotion',
				'flaticon-sections'             => 'flaticon-sections',
				'flaticon-t'                    => 'flaticon-t',
				'flaticon-blog'                 => 'flaticon-blog',
				'flaticon-brifcase'             => 'flaticon-brifcase',
				'flaticon-listing'              => 'flaticon-listing',
				'flaticon-note'                 => 'flaticon-note',
				'flaticon-files'                => 'flaticon-files',
				'flaticon-question'             => 'flaticon-question',
				'flaticon-question-mark'        => 'flaticon-question-mark',
				'flaticon-question-mark-1'      => 'flaticon-question-mark-1',
				'flaticon-working-time'         => 'flaticon-working-time',
				'flaticon-working'              => 'flaticon-working',
				'flaticon-placeholder'          => 'flaticon-placeholder',
				'flaticon-map'                  => 'flaticon-map',
				'flaticon-phone'                => 'flaticon-phone',
				'flaticon-telephone'            => 'flaticon-telephone',
				'flaticon-maps-and-flags'       => 'flaticon-maps-and-flags',
				'flaticon-map-1'                => 'flaticon-map-1',
				'flaticon-mail-inbox-app'       => 'flaticon-mail-inbox-app',
				'flaticon-phone-call-1'         => 'flaticon-phone-call-1',
				'flaticon-telephone-1'          => 'flaticon-telephone-1',
				'flaticon-iphone'               => 'flaticon-iphone',
				'flaticon-time'                 => 'flaticon-time',
				'flaticon-long-right-arrow'     => 'flaticon-long-right-arrow',
				'flaticon-long-left-arrow'      => 'flaticon-long-left-arrow',
				'flaticon-quotation-1'          => 'flaticon-quotation-1',
				'flaticon-internet'             => 'flaticon-internet',
				'flaticon-pencil'               => 'flaticon-pencil',
				'flaticon-connection'           => 'flaticon-connection',
				'flaticon-computer'             => 'flaticon-computer',
				'flaticon-brainstorming'        => 'flaticon-brainstorming',
				'flaticon-design'               => 'flaticon-design',
				'flaticon-software'             => 'flaticon-software',
				'flaticon-layers'               => 'flaticon-layers',
				'flaticon-data-analysis'        => 'flaticon-data-analysis',
				'flaticon-3d-model'             => 'flaticon-3d-model',
				'flaticon-tech'                 => 'flaticon-tech',
				'flaticon-coding'               => 'flaticon-coding',
				'flaticon-startup'              => 'flaticon-startup',
				'flaticon-rocket'               => 'flaticon-rocket',
				'flaticon-device'               => 'flaticon-device',
				'flaticon-idea'                 => 'flaticon-idea',
				'flaticon-idea-1'               => 'flaticon-idea-1',
				'flaticon-time-tracking'        => 'flaticon-time-tracking',
				'flaticon-cloud-network'        => 'flaticon-cloud-network',
				'flaticon-networking'           => 'flaticon-networking',
				'flaticon-network'              => 'flaticon-network',
				'flaticon-cloud-network-1'      => 'flaticon-cloud-network-1',
				'flaticon-link-4'               => 'flaticon-link-4',
				'flaticon-technical-support-1'  => 'flaticon-technical-support-1',
				'flaticon-project-management'   => 'flaticon-project-management',
				'flaticon-helpdesk'             => 'flaticon-helpdesk',
				'flaticon-right-quote'          => 'flaticon-right-quote',
				'flaticon-data-processing'      => 'flaticon-data-processing',
				'flaticon-database'             => 'flaticon-database',
				'flaticon-data-science'         => 'flaticon-data-science',
				'flaticon-data'                 => 'flaticon-data',
				'flaticon-distributed'          => 'flaticon-distributed',
				'flaticon-cloud-server'         => 'flaticon-cloud-server',
				'flaticon-monitor'              => 'flaticon-monitor',
				'flaticon-development'          => 'flaticon-development',
				'flaticon-growth'               => 'flaticon-growth',
				'flaticon-data-visualization'   => 'flaticon-data-visualization',
				'flaticon-data-analysis-1'      => 'flaticon-data-analysis-1',
				'flaticon-database-1'           => 'flaticon-database-1',
				'flaticon-rating'               => 'flaticon-rating',
				'flaticon-team'                 => 'flaticon-team',
				'flaticon-assistant'            => 'flaticon-assistant',
				'flaticon-curve'                => 'flaticon-curve',
				'flaticon-design-1'             => 'flaticon-design-1',
				'flaticon-cyber-security'       => 'flaticon-cyber-security',
				'flaticon-cyber-security-1'     => 'flaticon-cyber-security-1',
				'flaticon-cyber-security-2'     => 'flaticon-cyber-security-2',
				'flaticon-planning'             => 'flaticon-planning',
				'flaticon-cpu'                  => 'flaticon-cpu',
				'flaticon-technology'           => 'flaticon-technology',
				'flaticon-technology-1'         => 'flaticon-technology-1',
				'flaticon-chip'                 => 'flaticon-chip',
				'flaticon-cloud'                => 'flaticon-cloud',
				'flaticon-software-development' => 'flaticon-software-development',
				'flaticon-analyticis'           => 'flaticon-analyticis',
				'flaticon-seo'                  => 'flaticon-seo',
				'flaticon-analysis'             => 'flaticon-analysis',
				'flaticon-seo-1'                => 'flaticon-seo-1',
				'flaticon-encrypted'            => 'flaticon-encrypted',
				'flaticon-hacker'               => 'flaticon-hacker',
				'flaticon-cyber-security-3'     => 'flaticon-cyber-security-3',
				'flaticon-cyber-attack'         => 'flaticon-cyber-attack',
				'flaticon-bug'                  => 'flaticon-bug',
				'flaticon-cloud-data'           => 'flaticon-cloud-data',
				'flaticon-database-storage'     => 'flaticon-database-storage',
				'flaticon-cloud-1'              => 'flaticon-cloud-1',
				'flaticon-team-1'               => 'flaticon-team-1',
				'flaticon-meeting'              => 'flaticon-meeting',
				'flaticon-developing'           => 'flaticon-developing',
				'flaticon-turnover'             => 'flaticon-turnover',
				'flaticon-menu-1'               => 'flaticon-menu-1',
				'flaticon-more'                 => 'flaticon-more',
				'fab fa-x-twitter'              => 'fab fa-x-twitter',
				'fa-brands fa-square-x-twitter' => 'fa-brands fa-square-x-twitter',
			),
		);

		return $icons;
	}

	add_filter( 'csf_field_icon_add_icons', 'xcency_csf_custom_icons' );
}