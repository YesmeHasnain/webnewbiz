<?php

function transland_ocdi_intro_text( $default_text ) {
    $default_text .= '<div class="ocdi_custom-intro-text notice notice-info inline">';
    $default_text .= sprintf (
        '%1$s <a href="%2$s" target="_blank">%3$s</a> %4$s',
        esc_html__( 'Install and activate all ', 'transland' ),
        get_admin_url(null, 'themes.php?page=tgmpa-install-plugins' ),
        esc_html__( 'required plugins', 'transland' ),
        esc_html__( 'before you click on the "Import" button.', 'transland' )
    );
    $default_text .= sprintf (
        ' %1$s <a href="%2$s" target="_blank">%3$s</a> %4$s',
        esc_html__( 'You will find all the pages in ', 'transland' ),
        get_admin_url(null, 'edit.php?post_type=page' ),
        esc_html__( 'Pages.', 'transland' ),
        esc_html__( 'Other pages will be imported along with the main Homepage.', 'transland' )
    );
    $default_text .= '<br>';
    $default_text .= sprintf (
        '%1$s <a href="%2$s" target="_blank">%3$s</a>',
        esc_html__( 'If you fail to import the demo data, follow the alternative way', 'transland' ),
        'https://cutt.ly/URcNum5',
        esc_html__( 'here.', 'transland' )
    );
    $default_text .= '</div>';

    return $default_text;
}
add_filter( 'pt-ocdi/plugin_intro_text', 'transland_ocdi_intro_text' );


// OneClick Demo Importer
add_filter( 'pt-ocdi/import_files', 'transland_import_files' );
function transland_import_files() {

    return array(

        array(
            'import_file_name'             => esc_html__('Home 1', 'transland'),
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'inc/demo/contents.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'inc/demo/widgets.wie',
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ).'inc/demo/home1.jpg',
            'import_notice'                => esc_html__( 'All other pages will be imported along with the main Homepage.', 'transland' ),
            'preview_url'                  => 'https://modinatheme.com/transland/',
            'categories'                   => array ( esc_html__( 'Logistics', 'transland' ) ),
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . 'inc/demo/settings.json',
                    'option_name' => 'transland_opt',
                ),
            ),
        ),

        array(
            'import_file_name'             => esc_html__('Home 2', 'transland'),
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'inc/demo/contents.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'inc/demo/widgets.wie',
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ).'inc/demo/home2.jpg',
            'import_notice'                => esc_html__( 'All other pages will be imported along with the main Homepage.', 'transland' ),
            'preview_url'                  => 'https://modinatheme.com/transland/home-2',
            'categories'                   => array ( esc_html__( 'Transport', 'transland' ) ),
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . 'inc/demo/settings.json',
                    'option_name' => 'transland_opt',
                ),
            ),
        ),

        array(
            'import_file_name'             => esc_html__('Home 3', 'transland'),
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'inc/demo/contents.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'inc/demo/widgets.wie',
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ).'inc/demo/home3.jpg',
            'import_notice'                => esc_html__( 'All other pages will be imported along with the main Homepage.', 'transland' ),
            'preview_url'                  => 'https://modinatheme.com/transland/home-3',
            'categories'                   => array ( esc_html__( 'Logistics', 'transland' ) ),
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . 'inc/demo/settings.json',
                    'option_name' => 'transland_opt',
                ),
            ),
        ),

        array(
            'import_file_name'             => esc_html__('Home 4', 'transland'),
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'inc/demo/contents.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'inc/demo/widgets.wie',
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ).'inc/demo/home4.jpg',
            'import_notice'                => esc_html__( 'All other pages will be imported along with the main Homepage.', 'transland' ),
            'preview_url'                  => 'https://modinatheme.com/transland/home-4',
            'categories'                   => array ( esc_html__( 'Warehouse', 'transland' ) ),
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . 'inc/demo/settings.json',
                    'option_name' => 'transland_opt',
                ),
            ),
        ),

        array(
            'import_file_name'             => esc_html__('Home 5', 'transland'),
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'inc/demo/contents.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'inc/demo/widgets.wie',
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ).'inc/demo/home5.jpg',
            'import_notice'                => esc_html__( 'All other pages will be imported along with the main Homepage.', 'transland' ),
            'preview_url'                  => 'https://modinatheme.com/transland/home-5',
            'categories'                   => array ( esc_html__( 'Warehouse', 'transland' ) ),
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . 'inc/demo/settings.json',
                    'option_name' => 'transland_opt',
                ),
            ),
        ),

        array(
            'import_file_name'             => esc_html__('Home 6', 'transland'),
            'local_import_file'            => trailingslashit( get_template_directory() ) . 'inc/demo/contents.xml',
            'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'inc/demo/widgets.wie',
            'import_preview_image_url'     => trailingslashit( get_template_directory_uri() ).'inc/demo/home6.jpg',
            'import_notice'                => esc_html__( 'All other pages will be imported along with the main Homepage.', 'transland' ),
            'preview_url'                  => 'https://modinatheme.com/transland/home-6',
            'categories'                   => array ( esc_html__( 'Transport', 'transland' ) ),
            'local_import_redux'           => array(
                array(
                    'file_path'   => trailingslashit( get_template_directory() ) . 'inc/demo/settings.json',
                    'option_name' => 'transland_opt',
                ),
            ),
        ),

    );

}


function transland_after_import_setup($selected_import) {
    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'main_menu' => $main_menu->term_id,
        )
    );

    // Assign front page and posts page (blog page).

    if ( 'Home 1' == $selected_import['import_file_name'] ) {
        $front_page_id = get_page_by_title( 'Home 1' );
    }

    if ( 'Home 2' == $selected_import['import_file_name'] ) {
        $front_page_id = get_page_by_title( 'Home 2' );
    }

    if ( 'Home 3' == $selected_import['import_file_name'] ) {
        $front_page_id = get_page_by_title( 'Home 3' );
    }

    if ( 'Home 4' == $selected_import['import_file_name'] ) {
        $front_page_id = get_page_by_title( 'Home 4' );
    }

    if ( 'Home 5' == $selected_import['import_file_name'] ) {
        $front_page_id = get_page_by_title( 'Home 5' );
    }

    if ( 'Home 6' == $selected_import['import_file_name'] ) {
        $front_page_id = get_page_by_title( 'Home 6' );
    }

    $blog_page_id  = get_page_by_title( 'News' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

   // Disable Elementor's Default Colors and Default Fonts
    update_option( 'elementor_disable_color_schemes', 'yes' );
    update_option( 'elementor_disable_typography_schemes', 'yes' );
    update_option( 'elementor_global_image_lightbox', '' );
    
}
add_action( 'pt-ocdi/after_import', 'transland_after_import_setup' );

