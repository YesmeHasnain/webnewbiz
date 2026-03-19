<?php

/**
 * Register Sidebar.
 */
function siberia_register_sidebar() {
	register_sidebar( 
		array(
			'name'          => esc_html__( 'Blog Sidebar', 'siberia' ),
			'id'            => 'blog_sidebar',
			'before_widget' => '<aside id="%1$s" class="%2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<div class="text-divider"><h5>',
			'after_title'   => '</h5></div>'
		)
	);
	register_sidebar( 
		array(
			'name'          => esc_html__( 'Fullscreen Menu Socials', 'siberia' ),
			'id'            => 'socials',
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '<h5 class="h5">',
			'after_title'   => '</h5>'
		)
	);
	register_sidebar( 
		array(
			'name'          => esc_html__( 'Follow Us Socials', 'siberia' ),
			'id'            => 'fu_socials',
			'before_widget' => '<div class="ms_fuw--list">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="h5">',
			'after_title'   => '</h5>'
		)
	);
}
add_action( 'widgets_init', 'siberia_register_sidebar' );

function hide_update_msg_non_admins(){
 if (current_user_can( 'manage_options' )) { // non-admin users
        echo '<style>.notice.is-dismissible[data-dismissible="dismiss-coblocks-21"] { display: none; } .blockgallery-notice {display: none; visibility: hidden;}</style>';
    }
}
add_action( 'admin_head', 'hide_update_msg_non_admins');

if ( ! function_exists( 'wp_body_open' ) ) {
        function wp_body_open() {
                do_action( 'wp_body_open' );
        }
}

/*  Display Thumb in admin panel
/* ------------------------------------ */
function display_thumbnail_column($column_name, $post_id){
    switch($column_name){
        case 'new_post_thumb':
            $post_thumbnail_id = get_post_thumbnail_id($post_id);
            if ($post_thumbnail_id) {
                $post_thumbnail_img = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' );
                echo '<img width="50" src="' . $post_thumbnail_img[0] . '" />';
            }
        break;
    }
}