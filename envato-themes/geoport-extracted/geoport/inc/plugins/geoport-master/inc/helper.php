<?php
/*------------------------------------------------------------------------------------------------------------------*/
/*  Page loading
/*------------------------------------------------------------------------------------------------------------------*/ 
function geoport_page_loader() {  
    $geoport_spine_preloader_enable = geoport_get_option( 'geoport_spine_preloader_enable' );
    $geoport_text_preloader_enable  = geoport_get_option( 'geoport_text_preloader_enable' );
    $loading_text                   = geoport_get_option( 'geoport_preloader_text' );
    $loading                        = strtoupper( $loading_text );
    $text_array                     = str_split( $loading );
?>
    <!-- preloader  -->
    <div id="preloader">
        <div id="ctn-preloader" class="ctn-preloader">
            <div class="animation-preloader">
                <?php if ( !empty( $geoport_spine_preloader_enable ) ) { ?>
                <div class="spinner"></div>
                <?php } if ( !empty( $geoport_text_preloader_enable ) ) { ?>
                <div class="txt-loading">
                    <?php if (is_array( $text_array )) { 
                        foreach ( $text_array as $key => $value ) {
                    ?>
                    <span data-text-preloader="<?php echo esc_attr( $value[0] ); ?>" class="letters-loading">
                        <?php echo esc_html( $value[0] ); ?>
                    </span>
                    <?php } 
                    } ?>
                </div>
                 <?php } ?>
            </div>
            <div class="preloading">
                <div class="row">
                    <div class="col-3 loader-section section-left">
                        <div class="bg"></div>
                    </div>
                    <div class="col-3 loader-section section-left">
                        <div class="bg"></div>
                    </div>
                    <div class="col-3 loader-section section-right">
                        <div class="bg"></div>
                    </div>
                    <div class="col-3 loader-section section-right">
                        <div class="bg"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- preloader end -->
<?php }
add_action('geoport_preloading', 'geoport_page_loader');

/*------------------------------------------------------------------------------------------------------------------*/
/*  Scroll to top
/*------------------------------------------------------------------------------------------------------------------*/ 
function geoport_scroll_to_top() {  
    $geoport_scroll_top_icon = geoport_get_option( 'geoport_scroll_top_icon' );
?>
    <div id="scroller">
        <i class="<?php echo esc_attr( $geoport_scroll_top_icon ); ?>" aria-hidden="true"></i>
    </div>
    <?php 
}
add_action('geoport_scrollup', 'geoport_scroll_to_top');

/*------------------------------------------------------------------------------------------------------------------*/
/*  Geoport Socials Share Buttons
/*------------------------------------------------------------------------------------------------------------------*/ 
add_action('geoport_social_share_media', 'geoport_social_share_media_btns');
function geoport_social_share_media_btns() { ?>
    <div class="cause-details-share">
        <h5><?php esc_html_e( 'Social Share:', 'geoport' ); ?></h5>
        <ul>
            <li><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>" title="Share this post on Facebook!" onclick="window.open(this.href); return false;" class="facebook"><i class="fab fa-facebook-f"></i></a></li>
            <li><a href="http://twitter.com/home?status=Reading: <?php the_permalink(); ?>" title="Share this post on Twitter!" target="_blank" class="twitter"><i class="fab fa-twitter"></i></a></li>
            <li><a data-pin-do="buttonPin" href="https://www.pinterest.com/pin/create/button/?url=<?php the_permalink();?>" data-pin-custom="true" target="_blank" class="pinterest"><i class="fab fa-pinterest-p"></i></a></li>
            <li><a href="javascript:void(0)" onclick="window.open( 'https://www.linkedin.com/share?url=<?php the_permalink(); ?>', 'sharer', 'toolbar=0, status=0, width=626, height=436');return false;" title="Linkdin"><span class="character"><i class="fab fa-linkedin-in"></i></span></a></li>
        </ul>
    </div>
<?php }

/*------------------------------------------------------------------------------------------------------------------*/
/*  Shortcode for language
/*------------------------------------------------------------------------------------------------------------------*/
function multi_lingual_shortcodecode($atts, $content=null) {
    extract(shortcode_atts( array(
        'lan' => '',
    ), $atts));

    $sitelang_icon = geoport_get_option('lan1_btn_shortcode_images');
    $attachment = wp_get_attachment_image_src( $sitelang_icon, 'full' );
    $sitelang_icon_img    = ($attachment) ? $attachment[0] : $sitelang_icon;


    $html ='';
    $html.='<div class="header-language">';
    $html.='<a class="language-name" href="'.esc_url(home_url('/')).'">';
        if ( !empty( $sitelang_icon_img ) ) {
            $html.= '<img src="'.esc_url( $sitelang_icon_img ).'" alt="img">';
        }else{
            $html.= '<img src="'. GEOPORT_PLG_URL .'assets/imgs/lan/english.png" alt="img">';
        }
    $html.='</a>
    <ul class="lang-sub-menu list-language" id="list-language">
    <li><a href="'.esc_url(home_url('/')).'"><img src="'. GEOPORT_PLG_URL .'assets/imgs/lan/english.png" alt="img"> English</a></li>
    <li><a href="'.esc_url(home_url('/')).'"><img src="'. GEOPORT_PLG_URL .'assets/imgs/lan/dennis.png" alt="img"> Dennis</a></li>
    <li><a href="'.esc_url(home_url('/')).'"><img src="'. GEOPORT_PLG_URL .'assets/imgs/lan/german.png" alt="img"> German</a></li>
    <li><a href="'.esc_url(home_url('/')).'"><img src="'. GEOPORT_PLG_URL .'assets/imgs/lan/chiness.png" alt="img"> Chiness</a></li>
    </ul></div>';
    return $html;

}
add_shortcode( 'geoportlang', 'multi_lingual_shortcodecode' );


/**
 * Polylang Shortcode - https://wordpress.org/plugins/polylang/
 * Add this code in your functions.php
 * Put shortcode [polylang_langswitcher] to post/page for display flags
 *
 * @return string
 */
function geoportpolylang_polylang_langswitcher() {

    $sitelang_icon = geoport_get_option('lan1_btn_shortcode_images');
    $attachment = wp_get_attachment_image_src( $sitelang_icon, 'full' );
    $sitelang_icon_img    = ($attachment) ? $attachment[0] : $sitelang_icon;

    $output = '';
    if ( function_exists( 'pll_the_languages' ) ) {
        $args   = [
            'show_flags' => 1,
            'show_names' => 1,
            'dropdown' => 0,
            'echo'       => 0,
        ];

        $output.= '<div class="header-language">
        <a class="language-name" href="'.esc_url(home_url('/')).'">';
        if ( !empty( $sitelang_icon_img ) ) {
            $output.= '<img src="'.esc_url( $sitelang_icon_img ).'" alt="img">';
        }else{
            $output.= '<img src="'. GEOPORT_PLG_URL .'assets/imgs/lan/english.png" alt="img">';
        }
        $output.= '</a>
        <ul class="lang-sub-menu list-language" id="list-language">
            '.pll_the_languages( $args ). '
        </ul></div>';
    }

    return $output;
}

add_shortcode( 'geoportpolylang', 'geoportpolylang_polylang_langswitcher' );

/*------------------------------------------------------------------------------------------------------------------*/
/*  W3C validator passing code
/*------------------------------------------------------------------------------------------------------------------*/
add_action( 'template_redirect', function(){
    ob_start( function( $buffer ){
        $buffer = str_replace( array( '<script type="text/javascript">', "<script type='text/javascript'>" ), '<script>', $buffer );
        return $buffer;
    });
});
add_action( 'template_redirect', function(){
    ob_start( function( $buffer2 ){
        $buffer2 = str_replace( array( "<script type='text/javascript' src" ), '<script src', $buffer2 );
        return $buffer2;
    });
});
add_action( 'template_redirect', function(){
    ob_start( function( $buffer3 ){
        $buffer3 = str_replace( array( 'type="text/css"', "type='text/css'", 'type="text/css"', ), '', $buffer3 );
        return $buffer3;
    });
});
add_action( 'template_redirect', function(){
    ob_start( function( $buffer4 ){
        $buffer4 = str_replace( array( '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0"', ), '<iframe', $buffer4 );
        return $buffer4;
    });
});
add_action( 'template_redirect', function(){
    ob_start( function( $buffer5 ){
        $buffer5 = str_replace( array( 'aria-required="true"', ), '', $buffer5 );
        return $buffer5;
    });
});

/*------------------------------------------------------------------------------------------------------------------*/
/* Add thumb manage column
/*------------------------------------------------------------------------------------------------------------------*/ 
add_image_size( 'admin-list-thumb', 80, 80, false );

// add featured thumbnail to admin post columns
function geoport_add_thumbnail_columns( $columns ) {
    $columns = array(
        'cb'                => '<input type="checkbox" />',
        'title'             => 'Title',
        'featured_thumb'    => 'Thumbnail',
        'categories'        => 'Categories',
        'date'              => 'Date'
    );
    return $columns;
}

function geoport_add_thumbnail_columns_data( $column, $post_id ) {
    switch ( $column ) {
        case 'featured_thumb':
        echo '<a href="' . get_edit_post_link() . '">';
        echo the_post_thumbnail( 'admin-list-thumb' );
        echo '</a>';
        break;
    }
}

if ( function_exists( 'add_theme_support' ) ) {
    add_filter( 'manage_posts_columns' , 'geoport_add_thumbnail_columns' );
    add_action( 'manage_posts_custom_column' , 'geoport_add_thumbnail_columns_data', 10, 2 );
}

/*------------------------------------------------------------------------------------------------------------------*/
/*  Service Post Manage Columns
/*------------------------------------------------------------------------------------------------------------------*/

// Add the custom columns to the book post type:
add_filter( 'manage_service_posts_columns', 'set_custom_edit_service_columns' );
function set_custom_edit_service_columns($columns) {
    unset( $columns['categories'] );
    unset( $columns['post_views'] );
    unset( $columns['date'] );
    $columns['service_cats'] = __( 'Categories', 'geoport' );
    $columns['date'] = __( 'Date', 'geoport' );

    return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_service_posts_custom_column' , 'custom_service_column', 3, 2 );
function custom_service_column( $column, $post_id ) {
    switch ( $column ) {
        case 'service_cats' :
        $terms = get_the_term_list( $post_id , 'service_category' , '' , ',' , '' );
        if ( is_string( $terms ) )
            echo $terms;
        else
            _e( 'Not under categories', 'geoport' );
        break;

        case 'publisher' :
        echo get_post_meta( $post_id , 'publisher' , true ); 
        break;
    }
}


/*------------------------------------------------------------------------------------------------------------------*/
/*  Events Manage post columns
/*------------------------------------------------------------------------------------------------------------------*/

// Add the custom columns to the book post type:
add_filter( 'manage_event_posts_columns', 'set_custom_edit_event_columns' );
function set_custom_edit_event_columns($columns) {
    unset( $columns['categories'] );
    unset( $columns['post_views'] );
    unset( $columns['date'] );
    $columns['event_cats'] = __( 'Categories', 'geoport' );
    $columns['date'] = __( 'Date', 'geoport' );

    return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_event_posts_custom_column' , 'custom_event_column', 10, 2 );
function custom_event_column( $column, $post_id ) {
    switch ( $column ) {
        case 'event_cats' :
        $terms = get_the_term_list( $post_id , 'event_category' , '' , ',' , '' );
        if ( is_string( $terms ) )
            echo $terms;
        else
            _e( 'Not under categories', 'geoport' );
        break;
        case 'publisher' :
        echo get_post_meta( $post_id , 'publisher' , true ); 
        break;
    }
}


/*------------------------------------------------------------------------------------------------------------------*/
/*  Custom shortode
/*------------------------------------------------------------------------------------------------------------------*/
function revo_slide_btn_shortcode( $atts, $content=null ) {
    extract( shortcode_atts( array(
        'url'   => '', 
        'url2'  => '', 
        'text'  => 'Request Quotes',
        'text2' => 'Learn More',
    ), $atts) );

    $html='';

    if(!empty( $url && $url2 )){
        $html.='<div class="slider-btn">';
        $html.='<a href="'.esc_url($url).'" class="btn red-btn wow fadeInLeft" data-wow-delay="0.6s"><i class="fal fa-paper-plane"></i> '.esc_html($text).'</a>';
        $html.='<a href="'.esc_url($url2).'" class="btn gray-btn wow fadeInRight" data-wow-delay="0.6s"><i class="fal fa-angle-right"></i> '.esc_html($text2).'</a>';
        $html.='</div>';
    } elseif( !empty($url) ){
        $html.='<div class="slider-btn">';
        $html.='<a href="'.esc_url($url).'" class="btn red-btn wow fadeInLeft" data-wow-delay="0.6s"><i class="fal fa-paper-plane"></i> '.esc_html($text).'</a>';
        $html.='</div>';
    } elseif ( !empty($url2) ) {
        $html.='<div class="slider-btn">';
        $html.='<a href="'.esc_url($url2).'" class="btn gray-btn wow fadeInRight" data-wow-delay="0.6s"><i class="fal fa-angle-right"></i> '.esc_html($text2).'</a>';
        $html.='</div>';
    } else {
        $html.='<div class="slider-btn">';
        $html.='<a href="'.esc_url($url).'" class="btn red-btn wow fadeInLeft" data-wow-delay="0.6s"><i class="fal fa-paper-plane"></i> '.esc_html($text).'</a>';
        $html.='<a href="'.esc_url($url2).'" class="btn gray-btn wow fadeInRight" data-wow-delay="0.6s"><i class="fal fa-angle-right"></i> '.esc_html($text2).'</a>';
        $html.='</div>';
    }
 return $html;

}
add_shortcode( 'geoportbtn', 'revo_slide_btn_shortcode' );


/*--------------------------------------------------------------------------------------------------*/
/* Set post views count using post meta
/*--------------------------------------------------------------------------------------------------*/
//Set the Post Custom Field in the WP dashboard as Name/Value pair 
function geoport_set_PostViews($post_ID) {
 
    //Set the name of the Posts Custom Field.
    $count_key = 'post_views_count'; 
     
    //Returns values of the custom field with the specified key from the specified post.
    $count = get_post_meta($post_ID, $count_key, true);
     
    //If the the Post Custom Field value is empty. 
    if($count == ''){
        $count = 0; // set the counter to zero.
         
        //Delete all custom fields with the specified key from the specified post. 
        delete_post_meta($post_ID, $count_key);
         
        //Add a custom (meta) field (Name/value)to the specified post.
        add_post_meta($post_ID, $count_key, '0');
        return $count;
     
    //If the the Post Custom Field value is NOT empty.
    } else {
        $count++; //increment the counter by 1.
        //Update the value of an existing meta key (custom field) for the specified post.
        update_post_meta($post_ID, $count_key, $count);
         
        //If statement, is just to have the singular form 'View' for the value '1'
        if($count == '1'){
        return $count;
        }
        //In all other cases return (count) Views
        else {
        return $count;
        }
    }
}


/*--------------------------------------------------------------------------------------------------*/
/* View column in dashboar post screen
/*--------------------------------------------------------------------------------------------------*/
function geoport_get_PostViews($post_ID){
    $count_key = 'post_views_count';
    $count = get_post_meta($post_ID, $count_key, true);
 
    return $count;
}

function post_column_views($newcolumn){
    $newcolumn['post_views'] = esc_html__( 'Views', 'geoport' );
    return $newcolumn;
}
add_filter('manage_posts_columns', 'post_column_views');

function post_custom_column_views($column_name, $id){
     
    if($column_name === 'post_views'){
        echo geoport_get_PostViews(get_the_ID());
    }
}
add_action('manage_posts_custom_column', 'post_custom_column_views',10,2);