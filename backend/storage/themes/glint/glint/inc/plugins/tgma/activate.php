<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme banting for publication on ThemeForest
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

require_once get_template_directory() . '/inc/plugins/tgma/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'glint_register_required_plugins' );

function glint_register_required_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        array(
            'name'               => 'Glint Extra',
            'slug'               => 'glint-extra',
            'source'             => get_template_directory() . '/inc/plugins/glint-extra.zip',
            'required'           => true,
        ),
        array(
            'name'         => 'Elementor Page Builder',
            'slug'         => 'elementor',
            'required'     => true,
        ),
        array(
            'name'         => 'Contact Form 7',
            'slug'         => 'contact-form-7',
            'required'     => true,
        ),

        array(
            'name'         => 'MC4WP: Mailchimp for WordPress',
            'slug'         => 'mailchimp-for-wp',
            'required'     => true,
        ),

        array(
            'name'         => 'WordPress Social Sharing Plugin – Sassy Social Share',
            'slug'         => 'sassy-social-share',
            'required'     => true,
        ),

        array(
            'name'         => 'Breadcrumb NavXT',
            'slug'         => 'breadcrumb-navxt',
            'required'     => true,
        ),

        array(
            'name'         => 'One Click Demo Import',
            'slug'         => 'one-click-demo-import',
            'required'     => true,
        ),

    );

    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id'           => 'tettram',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',

    );

    tgmpa( $plugins, $config );
}