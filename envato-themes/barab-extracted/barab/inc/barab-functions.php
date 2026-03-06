<?php
/**
 * @Packge     : Barab
 * @Version    : 1.0
 * @Author     : Themeholy
 * @Author URI : https://themeforest.net/user/themeholy
 *
 */


// Block direct access
if( ! defined( 'ABSPATH' ) ){
    exit;
}
 
if (!function_exists('Barab_get_nav_menu')) :
    function Barab_get_nav_menu()
    {
        $menu_list = get_terms(array(
            'taxonomy' => 'nav_menu',
            'hide_empty' => true,
        ));
        $options = [];
        if (!empty($menu_list) && !is_wp_error($menu_list)) {
            foreach ($menu_list as $menu) {
                $options[$menu->slug] = $menu->name;
            }
            return $options;
        }
    }
endif;

// Register block style
if (function_exists('register_block_style')) {
    register_block_style(
        'core/quote',
        array(
            'name'         => 'blue-quote',
            'label'        => __('Blue Quote', 'barab'),
            'is_default'   => true,
            'inline_style' => '.wp-block-quote.is-style-blue-quote { color: blue; }',
        )
    );
}

// Register block pattern 
function barab_register_my_patterns()
{
    register_block_pattern(
        'wpdocs/my-example',
        array(
            'title'         => __('Block Pattern', 'barab'),
            'description'   => _x('This is my first block pattern', 'Block pattern description', 'barab'),
            'content'       => '<!-- wp:paragraph --><p>A single paragraph block style</p><!-- /wp:paragraph -->',
            'categories'    => array('text'),
            'keywords'      => array('cta', 'demo', 'example'),
            'viewportWidth' => 800,
        )
    );
}

 // theme option callback
function barab_opt( $id = null, $url = null ){
    global $barab_opt;

    if( $id && $url ){

        if( isset( $barab_opt[$id][$url] ) && $barab_opt[$id][$url] ){
            return $barab_opt[$id][$url];
        }
    }else{
        if( isset( $barab_opt[$id] )  && $barab_opt[$id] ){ 
            return $barab_opt[$id];
        }
    }
}


// theme logo
function barab_theme_logo() {
    // escaping allow html
    $allowhtml = array(
        'a'    => array(
            'href' => array()
        ),
        'span' => array(),
        'i'    => array(
            'class' => array()
        )
    );
    $siteUrl = home_url('/');
    if( has_custom_logo() ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $siteLogo = '';
        $siteLogo .= '<a class="logo" href="'.esc_url( $siteUrl ).'">';
        $siteLogo .= barab_img_tag( array(
            "class" => "img-fluid",
            "url"   => esc_url( wp_get_attachment_image_url( $custom_logo_id, 'full') )
        ) );
        $siteLogo .= '</a>';

        return $siteLogo;
    } elseif( !barab_opt('barab_text_title') && barab_opt('barab_site_logo', 'url' )  ){

        $siteLogo = '<img class="img-fluid" src="'.esc_url( barab_opt('barab_site_logo', 'url' ) ).'" alt="'.esc_attr__( 'logo', 'barab' ).'" />';
        return '<a class="logo" href="'.esc_url( $siteUrl ).'">'.$siteLogo.'</a>';


    }elseif( barab_opt('barab_text_title') ){
        return '<h2 class="mb-0"><a class="logo" href="'.esc_url( $siteUrl ).'">'.wp_kses( barab_opt('barab_text_title'), $allowhtml ).'</a></h2>';
    }else{
        return '<h2 class="mb-0"><a class="logo" href="'.esc_url( $siteUrl ).'">'.esc_html( get_bloginfo('name') ).'</a></h2>';
    }
}

// custom meta id callback
function barab_meta( $id = '' ){
    $value = get_post_meta( get_the_ID(), '_barab_'.$id, true );
    return $value;
}


// Blog Date Permalink
function barab_blog_date_permalink() {
    $year  = get_the_time('Y');
    $month_link = get_the_time('m');
    $day   = get_the_time('d');
    $link = get_day_link( $year, $month_link, $day);
    return $link;
}

//audio format iframe match
function barab_iframe_match() {
    $audio_content = barab_embedded_media( array('audio', 'iframe') );
    $iframe_match = preg_match("/\iframe\b/i",$audio_content, $match);
    return $iframe_match;
}


//Post embedded media
function barab_embedded_media( $type = array() ){
    $content = do_shortcode( apply_filters( 'the_content', get_the_content() ) );
    $embed   = get_media_embedded_in_content( $content, $type );


    if( in_array( 'audio' , $type) ){
        if( count( $embed ) > 0 ){
            $output = str_replace( '?visual=true', '?visual=false', $embed[0] );
        }else{
           $output = '';
        }

    }else{
        if( count( $embed ) > 0 ){
            $output = $embed[0];
        }else{
           $output = '';
        }
    }
    return $output;
}


// WP post link pages
function barab_link_pages(){
    wp_link_pages( array(
        'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'barab' ) . '</span>',
        'after'       => '</div>',
        'link_before' => '<span>',
        'link_after'  => '</span>',
        'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'barab' ) . ' </span>%',
        'separator'   => '<span class="screen-reader-text">, </span>',
    ) );
}


// Data Background image attr
function barab_data_bg_attr( $imgUrl = '' ){
    return 'data-bg-img="'.esc_url( $imgUrl ).'"';
}

// image alt tag
function barab_image_alt( $url = '' ){
    if( $url != '' ){
        // attachment id by url
        $attachmentid = attachment_url_to_postid( esc_url( $url ) );
       // attachment alt tag
        $image_alt = get_post_meta( esc_html( $attachmentid ) , '_wp_attachment_image_alt', true );
        if( $image_alt ){
            return $image_alt ;
        }else{
            $filename = pathinfo( esc_url( $url ) );
            $alt = str_replace( '-', ' ', $filename['filename'] );
            return $alt;
        }
    }else{
       return;
    }
}


// Flat Content wysiwyg output with meta key and post id

function barab_get_textareahtml_output( $content ) {
    global $wp_embed;

    $content = $wp_embed->autoembed( $content );
    $content = $wp_embed->run_shortcode( $content );
    $content = wpautop( $content );
    $content = do_shortcode( $content );

    return $content;
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */

function barab_pingback_header() {
    if ( is_singular() && pings_open() ) {
        echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
    }
}
add_action( 'wp_head', 'barab_pingback_header' );


// Excerpt More
function barab_excerpt_more( $more ) {
    return '...';
}

add_filter( 'excerpt_more', 'barab_excerpt_more' );


// barab comment template callback
function barab_comment_callback( $comment, $args, $depth ) {
        $add_below = 'comment';
    ?>
    <li <?php comment_class( array('th-comment-item') ); ?>>
        <div id="comment-<?php comment_ID() ?>" class="th-post-comment">
            <?php
                if( get_avatar( $comment, 100 )  ) :
            ?>
            <!-- Author Image -->
            <div class="comment-avater">
                <?php
                    if ( $args['avatar_size'] != 0 ) {
                        echo get_avatar( $comment, 110 );
                    }
                ?>
            </div>
            <!-- Author Image -->
            <?php endif; ?>
            <!-- Comment Content -->
            <div class="comment-content"> 
                <h3 class="name"><?php echo esc_html( ucwords( get_comment_author() ) ); ?></h3>
                <span class="commented-on"><?php printf( esc_html__('%1$s %2$s', 'barab'), get_comment_date(), get_comment_time() ); ?></span>
                <p class="text"><?php echo get_comment_text(); ?></p>
                <div class="reply_and_edit">
                    <?php
                        $reply_text = wp_kses_post( '<i class="fas fa-reply"></i>Reply', 'barab' );

                        $edit_reply_text = wp_kses_post( '<i class="fas fa-pencil-alt"></i> Edit', 'barab' );

                        comment_reply_link(array_merge( $args, array( 'add_below' => $add_below, 'depth' => 3, 'max_depth' => 5, 'reply_text' => $reply_text ) ) );
                    ?>  
                </div>
                <?php if ( $comment->comment_approved == '0' ) : ?>
                <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'barab' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Comment Content -->
<?php
}

//body class
add_filter( 'body_class', 'barab_body_class' );
function barab_body_class( $classes ) {
    if( class_exists('ReduxFramework') ) { 
        $barab_blog_single_sidebar = barab_opt('barab_blog_single_sidebar');
        if( ($barab_blog_single_sidebar != '2' && $barab_blog_single_sidebar != '3' ) || ! is_active_sidebar('barab-blog-sidebar') ) {
            $classes[] = 'no-sidebar';
        }
        $new_class = is_page() ? barab_meta('custom_body_class') : null;

        if ( $new_class ) {
            $classes[] = $new_class;
        }
        $classes[] = 'show-grid';
    } else {
        if( !is_active_sidebar('barab-blog-sidebar') ) {
            $classes[] = 'no-sidebar';
        }
    }

    return $classes;
}

//Global Footer
function barab_footer_global_option(){
    // Barab Widget Enable Disable
    if( class_exists( 'ReduxFramework' ) ){
        $barab_footer_widget_enable = barab_opt( 'barab_footerwidget_enable' );
        $barab_footer_bottom_active = barab_opt( 'barab_disable_footer_bottom' );
    }else{
        $barab_footer_widget_enable = '';
        $barab_footer_bottom_active = '1';
    }

    if( $barab_footer_widget_enable == '1' || $barab_footer_bottom_active == '1' ){
        $bg = barab_opt('barab_footer_background', 'background-image' );
        $footer_bg = $bg ? $bg : '#';

        if( ( is_active_sidebar( 'barab-footer-1' ) || is_active_sidebar( 'barab-footer-2' ) || is_active_sidebar( 'barab-footer-3' ) || is_active_sidebar( 'barab-footer-4' ) )) {
            $class = 'footer-layout1';
        }else{
            $class = '';
        }
        
        echo '<!---footer-wrapper start-->';
        echo '<footer class="footer-wrapper '.esc_attr($class).' prebuilt-foo" data-bg-src="'.esc_url(  $footer_bg ).'">';
            if( $barab_footer_widget_enable == '1' ){
                if( ( is_active_sidebar( 'barab-footer-1' ) || is_active_sidebar( 'barab-footer-2' ) || is_active_sidebar( 'barab-footer-3' ) || is_active_sidebar( 'barab-footer-4' ) )) {
                    echo '<div class="widget-area">';
                        echo '<div class="container">';
                                echo '<div class="row justify-content-between">';
                                    if( is_active_sidebar( 'barab-footer-1' )){
                                    dynamic_sidebar( 'barab-footer-1' ); 
                                    }
                                    if( is_active_sidebar( 'barab-footer-2' )){
                                    dynamic_sidebar( 'barab-footer-2' ); 
                                    }
                                    if( is_active_sidebar( 'barab-footer-3' )){
                                    dynamic_sidebar( 'barab-footer-3' ); 
                                    } 
                                    if( is_active_sidebar( 'barab-footer-4' )){
                                    dynamic_sidebar( 'barab-footer-4' ); 
                                    }  
                                echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            }

            if( $barab_footer_bottom_active == '1' ){
                echo '<div class="copyright-wrap">';
                    echo '<div class="container text-center">';
                        echo '<p class="copyright-text">'.wp_kses_post( barab_opt( 'barab_copyright_text' ) ).'</p>';
                    echo '</div>';
                echo '</div>';
            }

        echo '</footer>';
        echo '<!---footer-wrapper end-->';
    }
}

// Social link = title / description
function barab_social_icon(){ 
    $barab_social_icon = barab_opt( 'barab_social_links' );
    if( ! empty( $barab_social_icon ) && isset( $barab_social_icon ) ){
        $social_item = '';
        foreach( $barab_social_icon as $social_icon ){
            if( !empty($social_icon['title']) ){
                $social_item .= '<a href="'.esc_url( $social_icon['url'] ).'"><i class="'.esc_attr( $social_icon['title'] ).'"></i></a>';
            }
        }
        return $social_item;
    }
}

// global header  
function barab_global_header_option() {

    if( class_exists( 'ReduxFramework' ) ){ 
        echo '<header class="th-header header-default prebuilt">';

            if(barab_opt('barab_header_sticky')){
                $sticky = '';
            }else{
                $sticky = '-no';
            }

            if(barab_opt('barab_menu_icon')){ 
                $menu_icon = '';
            }else{
                $menu_icon = 'hide-icon'; 
            }  

            //Mobile menu
            echo barab_mobile_menu();
            //Search Content
            echo barab_search_box();
            //Cart Content
            echo barab_header_cart_offcanvas(); 
            //Topbar Content
            echo barab_header_menu_topbar();

            echo '<div class="sticky-wrapper'.esc_attr($sticky).'">';
                echo '<!-- Main Menu Area -->';
                echo '<div class="menu-area">';
                    echo '<div class="container">';
                        echo '<div class="row align-items-center justify-content-between">';
                            echo '<div class="col-auto">';
                                echo '<div class="header-logo">';
                                    echo barab_theme_logo();
                                echo '</div>';
                            echo '</div>';
                            echo '<div class="col-auto">';
                                echo '<nav class="main-menu d-none d-lg-inline-block '.esc_attr($menu_icon).'">';
                                    wp_nav_menu( array(
                                        "theme_location"    => 'primary-menu',
                                        "container"         => '',
                                        "menu_class"        => ''
                                    ) ); 
                                echo '</nav>';
                                echo '<div class="header-button d-flex d-lg-none">';
                                    if(!empty(barab_opt( 'barab_header_cart_switcher' )) ){
                                        if( class_exists( 'woocommerce' ) ){
                                            global $woocommerce;
                                            if( ! empty( $woocommerce->cart->cart_contents_count ) ){
                                                $count = $woocommerce->cart->cart_contents_count;
                                            }else{
                                                $count = "0";
                                            }
                                            echo '<button type="button" class="icon-btn sideMenuToggler">';
                                                echo '<span class="badge">'.esc_html( $count ).'</span>';
                                                echo '<i class="fa-regular fa-cart-shopping"></i>';
                                            echo '</button>';
                                        }
                                    }
                                    echo '<button type="button" class="icon-btn th-menu-toggle"><i class="far fa-bars"></i></button>';
                                echo '</div>';
                            echo '</div>';
                            echo '<div class="col-auto d-none d-xl-block">';
                                echo '<div class="header-button">';
                                    if(!empty(barab_opt( 'barab_header_search_switcher' )) ){
                                        echo '<button type="button" class="icon-btn searchBoxToggler"><i class="far fa-search"></i></button>';
                                    }
                                    if(!empty(barab_opt( 'barab_header_cart_switcher' )) ){
                                        if( class_exists( 'woocommerce' ) ){
                                            global $woocommerce;
                                            if( ! empty( $woocommerce->cart->cart_contents_count ) ){
                                                $count = $woocommerce->cart->cart_contents_count;
                                            }else{
                                                $count = "0";
                                            }
                                            echo '<button type="button" class="icon-btn sideMenuToggler">';
                                                echo '<span class="badge cart_badge">'.esc_html( $count ).'</span>';
                                                echo '<i class="fa-regular fa-cart-shopping"></i>';
                                            echo '</button>';
                                        }
                                    }
                                    if( !empty(barab_opt( 'barab_btn_text' ) ) ){
                                        echo '<a href="'.esc_attr(barab_opt( 'barab_btn_url' )).'" class="th-btn btn-mask">'.esc_html(barab_opt( 'barab_btn_text' )).'</a>';
                                    }
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';

        echo '</header>';
    }else{
        echo barab_global_header();
        
    }
}

if( ! function_exists( 'barab_header_menu_topbar' ) ){
    function barab_header_menu_topbar(){
        if( class_exists( 'ReduxFramework' ) ){
            $barab_header_topbar_switcher  = barab_opt( 'barab_header_topbar_switcher' );
            $barab_header_social_switcher  = barab_opt( 'barab_header_social_switcher' );
            $barab_topbar_content1  = barab_opt( 'barab_topbar_content1' );
            $barab_topbar_content2  = barab_opt( 'barab_topbar_content2' );
            $barab_topbar_content3  = barab_opt( 'barab_topbar_content3' );
            $barab_topbar_phone  = barab_opt( 'barab_topbar_phone' );
            $barab_social_icon = barab_opt( 'barab_social_links' );
        }else{
            $barab_header_topbar_switcher  = '';
            $barab_header_social_switcher  = '';
            $barab_topbar_content1  = '';
            $barab_topbar_content2  = '';
            $barab_topbar_content3  = '';
            $barab_topbar_phone  = '';
            $barab_social_icon  = '';
        }

        if( $barab_header_topbar_switcher ){
            echo '<div class="header-top d-sm-block d-none">';
                echo '<div class="container">';
                    echo '<div class="row justify-content-center justify-content-lg-between align-items-center gy-2">';
                        echo '<div class="col-auto">';
                            echo '<div class="header-links">';
                                echo '<ul>';
                                    if( !empty( $barab_topbar_content1 ) ){
                                        echo '<li class="d-none d-xl-inline-block">'.wp_kses_post( $barab_topbar_content1 ).'</li>';
                                    }
                                    if( !empty( $barab_topbar_content2 ) ){
                                        echo '<li class="d-none d-md-inline-block">'.wp_kses_post( $barab_topbar_content2 ).'</li>';
                                    }
                                    if( !empty( $barab_topbar_content3 ) ){
                                        echo '<li class="d-none d-sm-inline-block">'.wp_kses_post( $barab_topbar_content3 ).'</li>';
                                    }
                                echo '</ul>';
                            echo '</div>';
                        echo '</div>';
                        echo '<div class="col-auto d-none d-lg-block">';
                            echo '<div class="header-links">';
                                echo '<ul>';
                                    if( !empty( $barab_topbar_phone ) ){
                                        echo '<li class="d-none d-xxl-inline-block header-contact">'.wp_kses_post( $barab_topbar_phone ).'</li>';
                                    }
                                    if( $barab_header_social_switcher ){
                                        echo '<li>';
                                            echo '<div class="th-social">';
                                                if( ! empty( $barab_social_icon ) && isset( $barab_social_icon ) ){
                                                    foreach( $barab_social_icon as $social_icon ){
                                                        if( !empty($social_icon['title']) ){
                                                            echo '<a href="'.esc_url( $social_icon['url'] ).'"><i class="'.esc_attr( $social_icon['title'] ).'"></i></a>';
                                                        }
                                                    }
                                                }
                                            echo '</div>';
                                        echo '</li>';
                                    }
                                echo '</ul>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';

        }
    }
}

// barab woocommerce breadcrumb
function barab_woo_breadcrumb( $args ) {
    if( class_exists( 'ReduxFramework' ) ){
        $barab_breadcrumb_home_text  = barab_opt( 'barab_breadcrumb_home_text' );
        $barab_breadcrumb_home = $barab_breadcrumb_home_text ? $barab_breadcrumb_home_text : '';
    }else{
         $barab_breadcrumb_home = '';
    }
    return array(
        'delimiter'   => '', 
        'wrap_before' => '<ul class="breadcumb-menu woo wow fadeInUp">',
        'wrap_after'  => '</ul>',
        'before'      => '<li>',
        'after'       => '</li>',
        'home'        => $barab_breadcrumb_home,
    );
}
 
add_filter( 'woocommerce_breadcrumb_defaults', 'barab_woo_breadcrumb' );

function barab_custom_search_form( $class ) {
    echo '<!-- Search Form -->';

    echo '<form method="get" action="'.esc_url( home_url( '/' ) ).'" class="'.esc_attr( $class ).'">';
        echo '<label class="searchIcon">';
            echo barab_img_tag( array(
                "url"   => esc_url( get_theme_file_uri( '/assets/img/search-2.svg' ) ),
                "class" => "svg"
            ) );
            echo '<input value="'.esc_html( get_search_query() ).'" name="s" required type="search" placeholder="'.esc_attr__('What are you looking for?', 'barab').'">';
        echo '</label>';
    echo '</form>';
    echo '<!-- End Search Form -->';
}



//Fire the wp_body_open action.
if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
}

//Remove Tag-Clouds inline style
add_filter( 'wp_generate_tag_cloud', 'barab_remove_tagcloud_inline_style',10,1 );
function barab_remove_tagcloud_inline_style( $input ){
   return preg_replace('/ style=("|\')(.*?)("|\')/','',$input );
}

/* This code filters the Categories archive widget to include the post count inside the link */
add_filter( 'wp_list_categories', 'barab_cat_count_span' );
function barab_cat_count_span( $links ) {
    $links = str_replace('</a> (', '</a> <span class="category-number">', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}

/* This code filters the Archive widget to include the post count inside the link */
add_filter( 'get_archives_link', 'barab_archive_count_span' );
function barab_archive_count_span( $links ) {
    $links = str_replace('</a>&nbsp;(', '</a> <span class="category-number">', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}

// Barab Default Header
if( ! function_exists( 'barab_global_header' ) ){
    function barab_global_header(){ ?>

        <!--Mobile menu & Search box-->
        <?php 
        echo barab_search_box(); 
        echo barab_mobile_menu();  
        
        ?>

        <!--======== Header ========-->
        <header class="th-header header-layout1 unittest-header">
            <div class="not-sticky-wrapper">
                <div class="sticky-active">
                    <div class="menu-area">
                        <div class="container">
                            <div class="row gx-20 align-items-center justify-content-between">

                                <div class="col-auto">
                                    <div class="header-logo">
                                        <div class="logo-bg"></div>
                                        <?php echo barab_theme_logo(); ?>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <?php
                                    if( has_nav_menu( 'primary-menu' ) ) { ?>
                                        <nav class="main-menu d-none d-lg-inline-block">
                                            <?php
                                            wp_nav_menu( array(
                                                "theme_location"    => 'primary-menu',
                                                "container"         => '',
                                                "menu_class"        => ''
                                            ) ); ?>
                                        </nav>
                                    <?php } ?>                                   
                                    </nav>
                                    <button type="button" class="th-menu-toggle d-inline-block d-lg-none"><i class="far fa-bars"></i></button>
                                </div>
                                <div class="col-auto d-none d-xl-block">
                                    <div class="header-button">
                                        <button type="button" class="icon-btn searchBoxToggler"><i class="far fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    <?php
    }
}

//header search box
if(! function_exists('barab_search_box')){
    function barab_search_box(){
        if( class_exists( 'ReduxFramework' ) ){
            $barab_popup_search_text = barab_opt('barab_popup_search_text') ? barab_opt('barab_popup_search_text') : '';
            $barab_popup_search_icon = barab_opt('barab_popup_search_icon') ? barab_opt('barab_popup_search_icon') : '';
        }else{
            $barab_popup_search_text  = 'What are you looking for?';
            $barab_popup_search_icon  = '<i class="fal fa-search"></i>';
        }
        echo '<div class="popup-search-box d-none d-lg-block">';
            echo '<button class="searchClose"><i class="fal fa-times"></i></button>';
            echo '<form method="get" action="'.esc_url( home_url( '/' ) ).'">';
                echo '<input value="'.esc_html( get_search_query() ).'" name="s" required type="search" placeholder="'.esc_attr($barab_popup_search_text).'">';
                echo '<button type="submit">'.wp_kses_post($barab_popup_search_icon).'</button>';
            echo '</form>';
        echo '</div>';
    }
}

//header Offcanvas
if( ! function_exists( 'barab_header_offcanvas' ) ){
    function barab_header_offcanvas(){
        echo '<div class="sidemenu-wrapper sidemenu-info">';  
            echo '<div class="sidemenu-content">';
                echo '<button class="closeButton sideMenuCls"><i class="far fa-times"></i></button>';
                if(is_active_sidebar('barab-offcanvas')){
                    dynamic_sidebar( 'barab-offcanvas' );
                }else{
                    echo '<h4 class="widget_title">No Widget Added </h4>';
                    echo '<p>Please add some widget in Offcanvs Sidebar</p>';
                }
            echo '</div>';
        echo '</div>';
    }
}

//header Cart Offcanvas
if( ! function_exists( 'barab_header_cart_offcanvas' ) ){
    function barab_header_cart_offcanvas(){
        if( class_exists( 'woocommerce' ) ){
            echo '<div class="sidemenu-wrapper sidemenu-cart">';
                echo '<div class="sidemenu-content">';
                    echo '<button class="closeButton sideMenuCls"><i class="far fa-times"></i></button>';
                    echo '<div class="widget woocommerce widget_shopping_cart">';
                        echo '<h3 class="widget_title">'.esc_html__( 'Shopping cart', 'barab' ).'</h3>';
                        echo '<div class="widget_shopping_cart_content">';
                                echo woocommerce_mini_cart();
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
    }
}

// mobile logo
function barab_mobile_logo() {
    $logo_url = barab_opt('barab_mobile_logo', 'url' );
    $mobile_menu = '';
    if( !empty($logo_url )){
        $mobile_menu = '<div class="mobile-logo"><a href="'.home_url('/').'"><img src="'.esc_url($logo_url).'" alt="'.esc_attr__( 'logo', 'barab' ).'"></a></div>';
    }else{
        $mobile_menu .= '<div class="mobile-logo">';
        $mobile_menu .= barab_theme_logo();
        $mobile_menu .= '</div>';
    }

    return $mobile_menu;
 }

//header Mobile Menu
if( ! function_exists( 'barab_mobile_menu' ) ){
    function barab_mobile_menu(){
    ?>
    <div class="th-menu-wrapper">
        <div class="th-menu-area text-center">
            <button class="th-menu-toggle"><i class="fal fa-times"></i></button>
            <?php  if( class_exists('ReduxFramework') ):?>
                <?php 
                    if(!empty(barab_opt('barab_menu_menu_show') )){
                        echo barab_mobile_logo(); 
                    }
                ?>
            <?php else: ?>
                <div class="mobile-logo">
                    <?php echo barab_theme_logo(); ?>
                </div>
            <?php endif; ?>
            <div class="th-mobile-menu allow-natural-scroll">
                <?php 
                    if( has_nav_menu( 'primary-menu' ) ){
                        wp_nav_menu( array(
                            "theme_location"    => 'primary-menu',
                            "container"         => '',
                            "menu_class"        => ''
                        ) );
                    }
                ?>
            </div>
        </div>
    </div>

<?php
    }
}



// Blog post views function
function barab_setPostViews( $postID ) {
    $count_key  = 'post_views_count';
    $count      = get_post_meta( $postID, $count_key, true );
    if( $count == '' ){
        $count = 0;
        delete_post_meta( $postID, $count_key );
        add_post_meta( $postID, $count_key, '0' );
    }else{
        $count++;
        update_post_meta( $postID, $count_key, $count );
    }
}

function barab_getPostViews( $postID ){
    $count_key  = 'post_views_count';
    $count      = get_post_meta( $postID, $count_key, true );
    if( $count == '' ){
        delete_post_meta( $postID, $count_key );
        add_post_meta( $postID, $count_key, '0' );
        return __( '0', 'barab' );
    }
    return $count;
}


// Add Extra Class On Comment Reply Button
function barab_custom_comment_reply_link( $content ) {
    $extra_classes = 'reply-btn';
    return preg_replace( '/comment-reply-link/', 'comment-reply-link ' . $extra_classes, $content);
}

add_filter('comment_reply_link', 'barab_custom_comment_reply_link', 99);

// Add Extra Class On Edit Comment Link
function barab_custom_edit_comment_link( $content ) {
    $extra_classes = 'reply-btn';
    return preg_replace( '/comment-edit-link/', 'comment-edit-link ' . $extra_classes, $content);
}

add_filter('edit_comment_link', 'barab_custom_edit_comment_link', 99);
 

function barab_post_classes( $classes, $class, $post_id ) {
    if ( get_post_type() === 'post' ) { 
        $classes[] = "th-blog blog-single has-post-thumbnail";
    }elseif( get_post_type() === 'product' ){
        // Return Class
    }elseif( get_post_type() === 'page' ){
        $classes[] = "page--item";
    }
    
    return $classes;
}
add_filter( 'post_class', 'barab_post_classes', 10, 3 );

// Contact form 7
add_filter('wpcf7_autop_or_not', '__return_false');