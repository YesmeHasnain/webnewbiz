<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package seacab
 */

/** 
 *
 * seacab header
 */

function seacab_check_header() {
    $seacab_header_style = function_exists( 'get_field' ) ? get_field( 'header_style' ) : NULL;
    $seacab_default_header_style = get_theme_mod( 'choose_default_header', 'header-style-1' );

    if ( $seacab_header_style == 'header-style-1' && empty($_GET['s']) ) {
        get_template_part( 'template-parts/header/header-1' );
    } 
    elseif ( $seacab_header_style == 'header-style-2' && empty($_GET['s']) ) {
        get_template_part( 'template-parts/header/header-2' );
    } else {

        /** default header style **/
        if ( $seacab_default_header_style == 'header-style-2' ) {
            get_template_part( 'template-parts/header/header-2' );
        } else {
            get_template_part( 'template-parts/header/header-1' );
        }
    }

}
add_action( 'seacab_header_style', 'seacab_check_header', 10 );


/**
 * [seacab_header_lang description]
 * @return [type] [description]
 */
function seacab_header_lang_default() {
    $seacab_header_lang = get_theme_mod( 'seacab_header_lang', false );
    if ( $seacab_header_lang ): ?>

    <ul>
        <li><a href="javascript:void(0)"><?php print esc_html__( 'English', 'seacab' );?> <i class="fas fa-angle-down"></i></a>
        <?php do_action( 'seacab_language' );?>
        </li>
    </ul>

    <?php endif;?>
<?php
}

/**
 * [seacab_language_list description]
 * @return [type] [description]
 */
function _seacab_language( $mar ) {
    return $mar;
}
function seacab_language_list() {

    $mar = '';
    $languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
    if ( !empty( $languages ) ) {
        $mar = '<ul>';
        foreach ( $languages as $lan ) {
            $active = $lan['active'] == 1 ? 'active' : '';
            $mar .= '<li class="' . $active . '"><a href="' . $lan['url'] . '">' . $lan['translated_name'] . '</a></li>';
        }
        $mar .= '</ul>';
    } else {
        //remove this code when send themeforest reviewer team
        $mar .= '<ul>';
        $mar .= '<li><a href="#">' . esc_html__( 'English', 'seacab' ) . '</a></li>';
        $mar .= '<li><a href="#">' . esc_html__( 'Dutch', 'seacab' ) . '</a></li>';
        $mar .= '<li><a href="#">' . esc_html__( 'French', 'seacab' ) . '</a></li>';
        $mar .= '<li><a href="#">' . esc_html__( 'Hindi', 'seacab' ) . '</a></li>';
        $mar .= ' </ul>';
    }
    print _seacab_language( $mar );
}
add_action( 'seacab_language', 'seacab_language_list' );


// header logo
function seacab_header_logo() { ?>
        <?php
        $seacab_logo_on = function_exists( 'get_field' ) ? get_field( 'is_enable_sec_logo' ) : NULL;
        $seacab_logo = get_template_directory_uri() . '/assets/images/resources/logo-1.png';
        $seacab_logo_black = get_template_directory_uri() . '/assets/images/resources/footer-logo.png';

        $seacab_site_logo = get_theme_mod( 'logo', $seacab_logo );
        $seacab_secondary_logo = get_theme_mod( 'seconday_logo', $seacab_logo_black );
        ?>

        <?php if ( !empty( $seacab_logo_on ) ) : ?>
            <a href="<?php print esc_url( home_url( '/' ) );?>">
                <img src="<?php print esc_url( $seacab_secondary_logo );?>" alt="<?php print esc_attr__( 'logo', 'seacab' );?>">
            </a>
        <?php else : ?>
            <a href="<?php print esc_url( home_url( '/' ) );?>">
                <img src="<?php print esc_url( $seacab_site_logo );?>" alt="<?php print esc_attr__( 'logo', 'seacab' );?>">
            </a>
        <?php endif; ?>
   <?php
}

// header logo
function seacab_header_sticky_logo() {?>
    <?php
        $seacab_logo_black = get_template_directory_uri() . '/assets/images/resources/logo-1.png';
        $seacab_secondary_logo = get_theme_mod( 'seconday_logo', $seacab_logo_black );
    ?>
      <a class="sticky-logo" href="<?php print esc_url( home_url( '/' ) );?>">
          <img src="<?php print esc_url( $seacab_secondary_logo );?>" alt="<?php print esc_attr__( 'logo', 'seacab' );?>" />
      </a>
    <?php
}

function seacab_mobile_logo() {
    // side info
    $seacab_mobile_logo_hide = get_theme_mod( 'seacab_mobile_logo_hide', false );

    $seacab_site_logo = get_theme_mod( 'logo', get_template_directory_uri() . '/assets/img/logo/logo.png' );

    ?>

    <?php if ( !empty( $seacab_mobile_logo_hide ) ): ?>
    <div class="side__logo mb-25">
        <a class="sideinfo-logo" href="<?php print esc_url( home_url( '/' ) );?>">
            <img src="<?php print esc_url( $seacab_site_logo );?>" alt="<?php print esc_attr__( 'logo', 'seacab' );?>" />
        </a>
    </div>
    <?php endif;?>



<?php }

/**
 * [seacab_header_social_profiles description]
 * @return [type] [description]
 */
function seacab_header_social_profiles() {
    $seacab_topbar_fb_url = get_theme_mod( 'seacab_topbar_fb_url', __( '#', 'seacab' ) );
    $seacab_topbar_twitter_url = get_theme_mod( 'seacab_topbar_twitter_url', __( '#', 'seacab' ) );
    $seacab_topbar_youtube_url = get_theme_mod( 'seacab_topbar_youtube_url', __( '#', 'seacab' ) );
    $seacab_topbar_pinterest_url = get_theme_mod( 'seacab_topbar_pinterest_url', __( '#', 'seacab' ) );
    $seacab_topbar_linkedin_url = get_theme_mod( 'seacab_topbar_linkedin_url', __( '#', 'seacab' ) );
    ?>
        <?php if ( !empty( $seacab_topbar_fb_url ) ): ?>
            <a href="<?php print esc_url( $seacab_topbar_fb_url );?>"><i class="fab fa-facebook"></i></a>
        <?php endif;?>
        <?php if ( !empty( $seacab_topbar_twitter_url ) ): ?>
            <a href="<?php print esc_url( $seacab_topbar_twitter_url );?>"><i class="fab fa-twitter"></i></a>
        <?php endif;?>
        <?php if ( !empty( $seacab_topbar_youtube_url ) ): ?>
            <a href="<?php print esc_url( $seacab_topbar_youtube_url );?>"><i class="fab fa-youtube"></i></a>
        <?php endif;?>
        <?php if ( !empty( $seacab_topbar_pinterest_url ) ): ?>
            <a href="<?php print esc_url( $seacab_topbar_pinterest_url );?>"><i class="fab fa-pinterest-p"></i></a>
        <?php endif;?>
        <?php if ( !empty( $seacab_topbar_linkedin_url ) ): ?>
            <a href="<?php print esc_url( $seacab_topbar_linkedin_url );?>"><i class="fab fa-linkedin"></i></a>
        <?php endif;?>
    <?php
}

/**
 * [seacab_header_menu description]
 * @return [type] [description]
 */
function seacab_header_menu() {
    ?>
    <?php
        wp_nav_menu( [
            'theme_location' => 'main-menu',
            'menu_class'     => 'main-menu__list',
            'container'      => '',
            'fallback_cb'    => 'seacab_Navwalker_Class::fallback',
            'walker'         => new seacab_Navwalker_Class,
        ] );
    ?>
    <?php
}

/**
 * [seacab_header_menu description]
 * @return [type] [description]
 */
function seacab_mobile_menu() {
    ?>
    <?php
        $seacab_menu = wp_nav_menu( [
            'theme_location' => 'main-menu',
            'menu_class'     => '',
            'container'      => '',
            'menu_id'        => 'mobile-menu-active',
            'echo'           => false,
        ] );

    $seacab_menu = str_replace( "menu-item-has-children", "menu-item-has-children has-children", $seacab_menu );
        echo wp_kses_post( $seacab_menu );
    ?>
    <?php
}

/**
 *
 * seacab footer
 */
add_action( 'seacab_footer_style', 'seacab_check_footer', 10 );

function seacab_check_footer() {
    $seacab_footer_style = function_exists( 'get_field' ) ? get_field( 'footer_style' ) : NULL;
    $seacab_default_footer_style = get_theme_mod( 'choose_default_footer', 'footer-style-1' );

    if ( $seacab_footer_style == 'footer-style-1' ) {
        get_template_part( 'template-parts/footer/footer-1' );
    } 
    elseif ( $seacab_footer_style == 'footer-style-2' ) {
        get_template_part( 'template-parts/footer/footer-2' );
    } else {

        /** default footer style **/
        if ( $seacab_default_footer_style == 'footer-style-2' ) {
            get_template_part( 'template-parts/footer/footer-2' );
        } else {
            get_template_part( 'template-parts/footer/footer-1' );
        }
    }
}

// seacab_copyright_text
function seacab_copyright_text() {
   print get_theme_mod( 'seacab_copyright', esc_html__( '© 2023 Copyright by TwinkleTheme', 'seacab' ) );
}


/**
 *
 * pagination
 */
if ( !function_exists( 'seacab_pagination' ) ) {

    function _seacab_pagi_callback( $pagination ) {
        return $pagination;
    }

    //page navegation
    function seacab_pagination( $prev, $next, $pages, $args ) {
        global $wp_query, $wp_rewrite;
        $menu = '';
        $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

        if ( $pages == '' ) {
            global $wp_query;
            $pages = $wp_query->max_num_pages;

            if ( !$pages ) {
                $pages = 1;
            }

        }

        $pagination = [
            'base'      => add_query_arg( 'paged', '%#%' ),
            'format'    => '',
            'total'     => $pages,
            'current'   => $current,
            'prev_text' => $prev,
            'next_text' => $next,
            'type'      => 'array',
        ];

        //rewrite permalinks
        if ( $wp_rewrite->using_permalinks() ) {
            $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
        }

        if ( !empty( $wp_query->query_vars['s'] ) ) {
            $pagination['add_args'] = ['s' => get_query_var( 's' )];
        }

        $pagi = '';
        if ( paginate_links( $pagination ) != '' ) {
            $paginations = paginate_links( $pagination );
            $pagi .= '<ul>';
            foreach ( $paginations as $key => $pg ) {
                $pagi .= '<li>' . $pg . '</li>';
            }
            $pagi .= '</ul>';
        }

        print _seacab_pagi_callback( $pagi );
    }
}

// header top bg color
function seacab_breadcrumb_bg_color() {
    $color_code = get_theme_mod( 'seacab_breadcrumb_bg_color', '#222' );
    wp_enqueue_style( 'seacab-custom', SEACAB_THEME_CSS_DIR . 'seacab-custom.css', [] );
    if ( $color_code != '' ) {
        $custom_css = '';
        $custom_css .= ".page-header-bg:before { background-color: " . $color_code . "!important}";

        wp_add_inline_style( 'seacab-custom', $custom_css );
    }
}
add_action( 'wp_enqueue_scripts', 'seacab_breadcrumb_bg_color' );

// scrollup
function seacab_scrollup_switch() {
    $scrollup_switch = get_theme_mod( 'seacab_scrollup_switch', false );
    wp_enqueue_style( 'seacab-custom', SEACAB_THEME_CSS_DIR . 'seacab-custom.css', [] );
    if ( $scrollup_switch ) {
        $custom_css = '';
        $custom_css .= "#scrollUp{ display: none !important;}";

        wp_add_inline_style( 'seacab-scrollup-switch', $custom_css );
    }
}
add_action( 'wp_enqueue_scripts', 'seacab_scrollup_switch' );

// theme color
function seacab_site_primary_color() {
    $color_code = get_theme_mod( 'seacab_site_primary_color', '#e82f51' );
    wp_enqueue_style( 'seacab-custom', SEACAB_THEME_CSS_DIR . 'seacab-custom.css', [] );
    if ( $color_code != '' ) {
        $custom_css = '';
        $custom_css .= ".main-header__top-left:before, .main-header__top-left:after, .header__phone-btn .icon, .main-menu .main-menu__list>li.current_page_item.active>a::before, .main-menu .main-menu__list>li:hover>a::before, .main-menu .main-menu__list>li>a::before, .sticky-header .main-menu__list>li>a::before, .main-menu .main-menu__list li ul li:hover>a, .sticky-header .main-menu__list li ul li:hover>a, .main-header__btn, .scroll-to-top, .sidebar__single .wp-block-search__button, .widget_twinkle_sidebar_support_query, .blog-details__social-list a, .blog-details__social-list a:hover, .thm-btn.comment-one__btn, .thm-btn.comment-form__btn, .sidebar__single.widget_archive li a:hover, .sidebar__single.widget_categories li a:hover, .sidebar__single.widget_archive li>span, .sidebar__single.widget_categories li>span, .sidebar__single .wp-block-categories li a:hover:before, .sidebar__single .wp-block-page-list li a:hover:before, .sidebar__single .wp-block-archives li a:hover:before, .sidebar__single.widget_meta ul li a:hover:before, .sidebar__single.widget_pages ul li a:hover:before, .sidebar__single.widget_nav_menu ul li a:hover:before, .sidebar__single #wp-calendar tbody td#today, .sidebar__search-form input, .footer__widget li a:hover:before, .footer-widget__social a:hover, .footer-widget__social a:before, .footer-contact-info li .icon { background-color: " . $color_code . "!important}";

        $custom_css .= ".main-menu .main-menu__list>li.current_page_item.active>a, .main-menu .main-menu__list>li:hover>a, .sticky-header .main-menu__list>li.current>a, .sticky-header .main-menu__list>li:hover>a, .header__phone-btn .content h4 a:hover, .main-header__top-right-social a:hover, .thm-breadcrumb .current-item, .thm-breadcrumb a.current-item span, .blog-one__single:hover .blog-one__title a, .postbox__more-btn, .postbox__more-btn i, .postbox__more-btn:hover, .blog-one__meta li a, .sidebar__single.widget_block ul.wp-block-latest-posts li a:hover, .sidebar__single.widget_block .wp-block-latest-comments article a.wp-block-latest-comments__comment-author, .blog-details .blog-details__meta li i, .sidebar__support-btn, .comment-one__content span, .sidebar__post-single:hover .sidebar__post-content-box h3 a, .sidebar__single .wp-block-categories li a:hover, .sidebar__single .wp-block-page-list li a:hover, .sidebar__single .wp-block-archives li a:hover, .sidebar__single.widget_meta ul li a:hover, .sidebar__single.widget_recent_entries ul li a:hover, .sidebar__single.widget_pages ul li a:hover, .sidebar__single.widget_nav_menu ul li a:hover, .sidebar__single.widget_recent_comments .comment-author-link a, .sidebar__single.widget_rss ul li a:hover, .sidebar__search-form button, .footer__widget li a:hover, .footer__widget .wp-calendar-table a { color: " . $color_code . "!important}";

        $custom_css .= " { border-color: " . $color_code . "!important}";

        wp_add_inline_style( 'seacab-custom', $custom_css );
    }
}
add_action( 'wp_enqueue_scripts', 'seacab_site_primary_color' );

// footer bg color
function seacab_footer_bg_color() {
    $color_code = get_theme_mod( 'seacab_footer_bg_color', '#0c0f16' );
    wp_enqueue_style( 'seacab-custom', SEACAB_THEME_CSS_DIR . 'seacab-custom.css', [] );
    if ( $color_code != '' ) {
        $custom_css = '';
        $custom_css .=".site-footer { background-color: " . $color_code . "!important}";

        wp_add_inline_style( 'seacab-custom', $custom_css );
    }
}
add_action( 'wp_enqueue_scripts', 'seacab_footer_bg_color' );

// seacab_kses_intermediate
function seacab_kses_intermediate( $string = '' ) {
    return wp_kses( $string, seacab_get_allowed_html_tags( 'intermediate' ) );
}

function seacab_get_allowed_html_tags( $level = 'basic' ) {
    $allowed_html = [
        'b'      => [],
        'i'      => [],
        'u'      => [],
        'em'     => [],
        'br'     => [],
        'abbr'   => [
            'title' => [],
        ],
        'span'   => [
            'class' => [],
        ],
        'strong' => [],
        'a'      => [
            'href'  => [],
            'title' => [],
            'class' => [],
            'id'    => [],
        ],
    ];

    if ($level === 'intermediate') {
        $allowed_html['a'] = [
            'href' => [],
            'title' => [],
            'class' => [],
            'id' => [],
        ];
        $allowed_html['div'] = [
            'class' => [],
            'id' => [],
        ];
        $allowed_html['img'] = [
            'src' => [],
            'class' => [],
            'alt' => [],
        ];
        $allowed_html['del'] = [
            'class' => [],
        ];
        $allowed_html['ins'] = [
            'class' => [],
        ];
        $allowed_html['bdi'] = [
            'class' => [],
        ];
        $allowed_html['i'] = [
            'class' => [],
            'data-rating-value' => [],
        ];
    }

    return $allowed_html;
}


// WP kses allowed tags
// ----------------------------------------------------------------------------------------
function seacab_kses($raw){

   $allowed_tags = array(
      'a'                         => array(
         'class'   => array(),
         'href'    => array(),
         'rel'  => array(),
         'title'   => array(),
         'target' => array(),
      ),
      'abbr'                      => array(
         'title' => array(),
      ),
      'b'                         => array(),
      'blockquote'                => array(
         'cite' => array(),
      ),
      'cite'                      => array(
         'title' => array(),
      ),
      'code'                      => array(),
      'del'                    => array(
         'datetime'   => array(),
         'title'      => array(),
      ),
      'dd'                     => array(),
      'div'                    => array(
         'class'   => array(),
         'title'   => array(),
         'style'   => array(),
      ),
      'dl'                     => array(),
      'dt'                     => array(),
      'em'                     => array(),
      'h1'                     => array(),
      'h2'                     => array(),
      'h3'                     => array(),
      'h4'                     => array(),
      'h5'                     => array(),
      'h6'                     => array(),
      'i'                         => array(
         'class' => array(),
      ),
      'img'                    => array(
         'alt'  => array(),
         'class'   => array(),
         'height' => array(),
         'src'  => array(),
         'width'   => array(),
      ),
      'li'                     => array(
         'class' => array(),
      ),
      'ol'                     => array(
         'class' => array(),
      ),
      'p'                         => array(
         'class' => array(),
      ),
      'q'                         => array(
         'cite'    => array(),
         'title'   => array(),
      ),
      'span'                      => array(
         'class'   => array(),
         'title'   => array(),
         'style'   => array(),
      ),
      'iframe'                 => array(
         'width'         => array(),
         'height'     => array(),
         'scrolling'     => array(),
         'frameborder'   => array(),
         'allow'         => array(),
         'src'        => array(),
      ),
      'strike'                 => array(),
      'br'                     => array(),
      'strong'                 => array(),
      'data-wow-duration'            => array(),
      'data-wow-delay'            => array(),
      'data-wallpaper-options'       => array(),
      'data-stellar-background-ratio'   => array(),
      'ul'                     => array(
         'class' => array(),
      ),
      'svg' => array(
           'class' => true,
           'aria-hidden' => true,
           'aria-labelledby' => true,
           'role' => true,
           'xmlns' => true,
           'width' => true,
           'height' => true,
           'viewbox' => true, // <= Must be lower case!
       ),
       'g'     => array( 'fill' => true ),
       'title' => array( 'title' => true ),
       'path'  => array( 'd' => true, 'fill' => true,  ),
      );

   if (function_exists('wp_kses')) { // WP is here
      $allowed = wp_kses($raw, $allowed_tags);
   } else {
      $allowed = $raw;
   }

   return $allowed;
}