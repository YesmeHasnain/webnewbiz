<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package geoport
 */

/*--------------------------------------------------------------------------------------------------*/
/*  Comments from call back function.
/*--------------------------------------------------------------------------------------------------*/

if(!function_exists('geoport_comment')):

    function geoport_comment($comment, $args, $depth) {
        
        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
            // Display trackbacks differently than normal comments.
        ?>
        <li <?php comment_class(); ?> id="submited-comment">

            <p><?php esc_html_e( 'Pingback:', 'geoport' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'geoport' ), '<span class="edit-link">', '</span>' ); ?></p>
            <?php
            break;
            default :

            global $post;
            ?>
            <li <?php comment_class(); ?>>

                <div class="bs-example" data-example-id="media-list"> 
                    <ul class="comments media-list">
                        <li class="comment-box clearfix" id="comment-<?php comment_ID(); ?>">
                            <article>
                                <div class="single-comment bd-comment-box">
                                    <div class="comments-avatar">
                                        <?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
                                    </div>
                                    <div class="comment-text">
                                        <div class="avatar-name mb-15">
                                            <h6><?php comment_author(); ?>
                                            <?php comment_reply_link( array_merge( $args, array( 'reply_text' => '<i class="fal fa-reply"></i>'.esc_html__( 'Reply', 'geoport' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>                                                
                                            </h6>
                                             <span class="ago"><?php echo (get_comment_date() . esc_html__( ' at ', 'geoport' ) .get_comment_time()); ?></span>
                                        </div>
                                        <div class="text"><?php comment_text(); ?></div>
                                    </div>
                                </div>
                            </article>
                        </li>
                    </ul>
                </div>
            <?php
        break;
        endswitch; 
    }
endif;


/*--------------------------------------------------------------------------------------------------*/
/*  Search
/*--------------------------------------------------------------------------------------------------*/
add_filter('get_search_form', 'geoport_search_form');
function geoport_search_form($form) {

    /**
     * Search form customization.
     *
     * @link http://codex.wordpress.org/Function_Reference/get_search_form
     * @since 1.0.0
     */
    $form = '<div class="ws-input"><form role="search" method="get" action="' .esc_url( home_url('/') ) . '">
                <input type="search" placeholder="'.esc_attr__( 'Enter Search Keywords', 'geoport' ).'" name="s">
                <button><i class="dashicons dashicons-search"></i></button>
            </form></div>';
    return $form;
}

/*--------------------------------------------------------------------------------------------------*/
/*   The excerpt
/*--------------------------------------------------------------------------------------------------*/
function geoport_excerpt($limit) {
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt).'';
    } else {
        $excerpt = implode(" ",$excerpt);
    }
    $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
    return $excerpt;
}

/*--------------------------------------------------------------------------------------------------*/
/* Category List count wrap by span
/*--------------------------------------------------------------------------------------------------*/
add_filter('wp_list_categories', 'geoport_cat_count_span');
function geoport_cat_count_span($links) {        
    $links = str_replace('(', '<span class="pull-right">', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}

/*--------------------------------------------------------------------------------------------------*/
/* Archive List count wrap by span
/*--------------------------------------------------------------------------------------------------*/
add_filter('get_archives_link', 'geoport_archive_cat_count_span');
function geoport_archive_cat_count_span($links) {        
    $links = str_replace('(', '<span class="pull-right">', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}

/*--------------------------------------------------------------------------------------------------*/
/*  Geoport Breadcrum
/*--------------------------------------------------------------------------------------------------*/
add_action('geoport_breadcrum', 'geoport_breadcrum_set');
function geoport_breadcrum_set() {

    if(function_exists( 'geoport_framework_init' ) ) {
        $breadcrumb_bg_condition = geoport_get_option('breadcrumb_bg_condition');
        $blog_page_breadcrumb = geoport_get_option('blog_page_breadcrumb');
        if (!empty($blog_page_breadcrumb)) {
            $blog_page_breadcrumb_title = $blog_page_breadcrumb;
        } else {
            $blog_page_breadcrumb_title = esc_html__( 'Blog Posts', 'geoport' );
        }

        $team_single_breadcrumb = geoport_get_option('team_details_breadcrumb_title');
        if (!empty($team_single_breadcrumb)) {
            $team_single_breadcrumb_title = $team_single_breadcrumb;
        } else {
            $team_single_breadcrumb_title = esc_html__( 'Team Details', 'geoport' );
        }

        $page_404_breadcrumb_title = geoport_get_option('404_breadcrumb_title');
        $bg_img_id = geoport_get_option('breadcrumb_bg_img');
        $attachment = wp_get_attachment_image_src( $bg_img_id, 'full' );
        $bg_img    = ($attachment) ? $attachment[0] : $bg_img_id;

        $geoport_breadcrumb_navigation_level = geoport_get_option('geoport_breadcrumb_navigation_level');

    } else {
        $team_single_breadcrumb_title = esc_html__( 'Team Details', 'geoport' );
        $blog_page_breadcrumb_title = esc_html__( 'Blog Posts', 'geoport' );
        $page_404_breadcrumb_title = esc_html__( '404 Error', 'geoport' );
        $breadcrumb_bg_condition = '';
        $bg_img = '';
        $geoport_breadcrumb_navigation_level = '1';
    }

    if ( has_header_image() ) {
        $bg_img = get_header_image();
    } else {
        $bg_img = $bg_img;
    }

    if (!empty($breadcrumb_bg_condition == 'image')) {
        if ( !empty($bg_img )) {
            $image_overlay = 'image-overlay';
            $breadcrumb_bg = '';
        } else {
            $image_overlay = '';
            $breadcrumb_bg = 'breadcrumb-img-none';
        }
        $bg_img = $bg_img;
    } else {
        $image_overlay = '';
        $breadcrumb_bg = '';
        $bg_img = '';
    }

    if( function_exists( 'geoport_framework_init' ) ) {
        $geoport_breadcrumb_switch = geoport_get_option('geoport_breadcrumb_switch');
    } else {
        $geoport_breadcrumb_switch = '';
    }

    if( function_exists( 'geoport_framework_init' ) ) {
        if ($geoport_breadcrumb_switch == true) {
            $breadcrumb_height = 'breadcrumb_height';
        } else {
            $breadcrumb_height = 'breadcrumb_menu_height';
        }
    } else {
        $breadcrumb_height = '';
    }

    $geoport_header_settings = get_post_meta( get_the_ID(), '_custom_page_options', true );
    
    if(!empty($geoport_header_settings['header_style'])) {
        if($geoport_header_settings['header_style'] == 'style1') {
            $hv = 'hv1';
        } elseif ($geoport_header_settings['header_style'] == 'style2')  {
            $hv = 'hv2';
        } elseif ($geoport_header_settings['header_style'] == 'style3')  {
            $hv = 'hv3';
        } else {
            $hv = 'hv1';
        }
    } elseif(function_exists( 'geoport_framework_init' ) ) {
        $default_header_style = geoport_get_option('default_header_style');
        if($default_header_style == 'style1') {
            $hv = 'hv1';
        } elseif ($default_header_style == 'style2')  {
            $hv = 'hv2';
        } elseif ($default_header_style == 'style3')  {
            $hv = 'hv3';
        } else {
            $hv = 'hv1';
        }
    } else {
        $hv = 'hv1';
    }

    $page_breadcrumb_data = get_post_meta( get_the_ID(), '_custom_page_options', true );

    if (!empty($page_breadcrumb_data['page_breadcrumb_switch'])) {
        if (!empty($page_breadcrumb_data['page_breadcrumb_bg_img'])) {
            $bg_img_id  = $page_breadcrumb_data['page_breadcrumb_bg_img'];
            $attachment = wp_get_attachment_image_src( $bg_img_id, 'full' );
            $bg_img     = ($attachment) ? $attachment[0] : $bg_img_id;
        } else {
            $bg_img = '';
        }
        if (!empty($page_breadcrumb_data['page_breadcrumb_title'])) {
            $page_title = $page_breadcrumb_data['page_breadcrumb_title'];
        } else {
            $page_title = '';
        }
    } else {
        $bg_img = $bg_img;
        $page_title = '';
    }

    if (!empty($page_breadcrumb_data['page_breadcrumb_title'])) {
        $page_bread_title = $page_breadcrumb_data['page_breadcrumb_title'];
    } elseif (!empty( $page_title )) {
        $page_bread_title = $page_title;
    } else {
        $page_bread_title = get_the_title();
    }

    if (!empty($bg_img)) {
        $image_overlay = 'image-overlay';
    } else {
         $image_overlay = '';
    }

    $logical_class = $image_overlay.' '.$breadcrumb_bg.' '.$breadcrumb_height.' '.$hv;

    if ( is_home() || is_front_page() ) { ?>

    <!-- breadcrumb-area -->
    <section class="breadcrumb-area breadcrumb-bg d-flex align-items-end <?php echo esc_attr( $logical_class ); ?>" style="background-image: url(<?php echo esc_url($bg_img); ?>);">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2><?php echo esc_html__( $blog_page_breadcrumb_title ); ?></h2>
                        <?php if (!empty($geoport_breadcrumb_navigation_level)) { ?>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'geoport') ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php geoport_meta_breadcrumbs(); ?></li>
                            </ol>
                        </nav>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb-area-end -->

    <?php } elseif ( is_singular( 'team' ) ) { ?>

    <!-- breadcrumb-area -->
    <section class="breadcrumb-area breadcrumb-bg d-flex align-items-end <?php echo esc_attr( $logical_class ); ?>" style="background-image: url(<?php echo esc_url($bg_img); ?>);">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2><?php echo esc_html( $team_single_breadcrumb_title ); ?></h2>
                        <?php if (!empty($geoport_breadcrumb_navigation_level)) { ?>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active" aria-current="page"><?php geoport_meta_breadcrumbs(); ?></li>
                                </ol>
                            </nav>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb-area-end -->

    <?php } elseif ( is_single() ) { ?>

    <!-- breadcrumb-area -->
    <section class="breadcrumb-area breadcrumb-bg d-flex align-items-end <?php echo esc_attr( $logical_class ); ?>" style="background-image: url(<?php echo esc_url($bg_img); ?>);">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2><?php the_title(); ?></h2>
                        <?php if (!empty($geoport_breadcrumb_navigation_level)) { ?>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active" aria-current="page"><?php geoport_meta_breadcrumbs(); ?></li>
                                </ol>
                            </nav>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb-area-end -->

    <?php } elseif ( is_page() || is_archive() || is_search() || is_404() ) { ?>

    <!-- breadcrumb-area -->
    <section class="breadcrumb-area breadcrumb-bg d-flex align-items-end <?php echo esc_attr( $logical_class ); ?>" style="background-image: url(<?php echo esc_url($bg_img); ?>);">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2>
                            <?php 
                                if ( is_page() ) {
                                    echo esc_html( $page_bread_title );
                                }elseif( function_exists( 'is_woocommerce' ) ){
                                    woocommerce_page_title();
                                } elseif (is_archive()) {
                                    geoport_archive_page_title();
                                } elseif (is_search()) {
                                    printf( esc_html__( 'Search for: %s', 'geoport' ), get_search_query() );
                                } elseif (is_404()) {
                                    echo esc_html( $page_404_breadcrumb_title );
                                }
                            ?>  
                        </h2>
                        <?php if (!empty($geoport_breadcrumb_navigation_level)) { ?>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page"><?php geoport_meta_breadcrumbs(); ?></li>
                            </ol>
                        </nav>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb-area-end -->
<?php }
}


/*--------------------------------------------------------------------------------------------------*/
/*  Header Style Load
/*--------------------------------------------------------------------------------------------------*/

add_action('geoport_header_style', 'geoport_header_style_load');

function geoport_header_style_load() {
    $geoport_header_settings = get_post_meta( get_the_ID(), '_custom_page_options', true );
    
    if(!empty($geoport_header_settings['header_style'])) {
        if($geoport_header_settings['header_style'] == 'style1') {
            get_template_part('headers/header', 'default' );
        } elseif ($geoport_header_settings['header_style'] == 'style2')  {
            get_template_part('headers/header', 'style2' );
        } elseif ($geoport_header_settings['header_style'] == 'style3')  {
            get_template_part('headers/header', 'style3' );
        } else {
            get_template_part('headers/header', 'default' );
        }
    } elseif(function_exists( 'geoport_framework_init' ) ) {
        $default_header_style = geoport_get_option('default_header_style');
        if($default_header_style == 'style1') {
            get_template_part('headers/header', 'default' );
        } elseif ($default_header_style == 'style2')  {
            get_template_part('headers/header', 'style2' );
        } elseif ($default_header_style == 'style3')  {
            get_template_part('headers/header', 'style3' );
        } else {
            get_template_part('headers/header', 'default' );
        }
    } else {
        get_template_part('headers/header', 'default' );
    }
}


/*--------------------------------------------------------------------------------------------------*/
/*  Footer Style Load
/*--------------------------------------------------------------------------------------------------*/

add_action('geoport_footer_style', 'geoport_footer_style_load');

function geoport_footer_style_load() {
    $geoport_footer_settings = get_post_meta( get_the_ID(), '_custom_page_options', true );
    
    if(!empty($geoport_footer_settings['footer_style'])) {
        if($geoport_footer_settings['footer_style'] == 'style1') {
            get_template_part('footers/footer', 'default' );
        } elseif ($geoport_footer_settings['footer_style'] == 'style2')  {
            get_template_part('footers/footer', 'style2' );
        } elseif ($geoport_footer_settings['footer_style'] == 'style3')  {
            get_template_part('footers/footer', 'style3' );
        } else {
            get_template_part('footers/footer', 'default' );
        }
    } elseif(function_exists( 'geoport_framework_init' ) ) {
        $default_footer_style = geoport_get_option('default_footer_style');
        if($default_footer_style == 'style1') {
            get_template_part('footers/footer', 'default' );
        } elseif ($default_footer_style == 'style2')  {
            get_template_part('footers/footer', 'style2' );
        } else {
            get_template_part('footers/footer', 'default' );
        }
    } else {
        get_template_part('footers/footer', 'default' );
    }
}

/*--------------------------------------------------------------------------------------------------*/
/*  Geoport WooCommerce Product Per Pages
/*--------------------------------------------------------------------------------------------------*/

add_filter( 'loop_shop_per_page', 'geoport_loop_shop_per_page', 20 );

function geoport_loop_shop_per_page( $products ) {
    // Return the number of products you wanna show per page.
    if( function_exists( 'geoport_framework_init' ) ) {
        $shop_posts_per_page = geoport_get_option('shop_posts_per_page');
        if (!empty($shop_posts_per_page)) {
           $shop_posts_per_page = $shop_posts_per_page;
        } else {
            $shop_posts_per_page = '12';
        }
    } else {
      $shop_posts_per_page = '12';
    }
    $products = $shop_posts_per_page;
    return $products;
}


/*--------------------------------------------------------------------------------------------------*/
/*  Geoport WooCommerce Product Column Per Pages
/*--------------------------------------------------------------------------------------------------*/ 

add_filter( 'loop_shop_columns', 'geoport_loop_shop_per_columns', 999 );

function geoport_loop_shop_per_columns( $loopcolumns ) {
    // Return the number of products you wanna show per page.
    if( function_exists( 'geoport_framework_init' ) ) {
        $shop_post_col_layout       = geoport_get_option('product_col_layout');
        if (!empty($shop_post_col_layout)) {
           $shop_post_col_layout = $shop_post_col_layout;
        } else {
            $shop_post_col_layout = '4';
        }
    } else {
      $shop_post_col_layout = '4';
    }
    $loopcolumns = $shop_post_col_layout;
    return $loopcolumns;
}

/*--------------------------------------------------------------------------------------------------*/
/*  Geoport WooCommerce Related Product Column & Post Per Pages
/*--------------------------------------------------------------------------------------------------*/

function woo_related_products_limit() {
  global $product;
    
    $args['posts_per_page'] = 6;
    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'geoports_related_products_args', 20 );
  function geoports_related_products_args( $args ) {
    if( function_exists( 'geoport_framework_init' ) ) {
        $related_products_per_page       = geoport_get_option('related_products_per_page');
        $related_product_col_layout      = geoport_get_option('related_product_col_layout');
        if (!empty($related_product_col_layout)) {
           $related_product_col_layout = $related_product_col_layout;
        } else {
            $related_product_col_layout = '3';
        }
        if (!empty($related_products_per_page)) {
           $related_products_per_page = $related_products_per_page;
        } else {
            $related_products_per_page = '3';
        }
    } else {
      $related_products_per_page    = '3';
      $related_product_col_layout   = '3';
    }

    $args['posts_per_page'] = $related_products_per_page; // 4 related products
    $args['columns'] = $related_product_col_layout; // arranged in 2 columns
    return $args;
}

/*--------------------------------------------------------------------------------------------------*/
/*  Geoport Nav Walker
/*--------------------------------------------------------------------------------------------------*/
class Geoport_Navwalker extends Walker_Nav_Menu {
 
        /**
         * Starts the list before the elements are added.
         *
         * @since WP 3.0.0
         *
         * @see Walker_Nav_Menu::start_lvl()
         *
         * @param string   $output Used to append additional content (passed by reference).
         * @param int      $depth  Depth of menu item. Used for padding.
         * @param stdClass $args   An object of wp_nav_menu() arguments.
         */
        public function start_lvl( &$output, $depth = 0, $args = array() ) {
            if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = str_repeat( $t, $depth );
            // Default class to add to the file.
            $classes = array( 'submenu' );
            /**
             * Filters the CSS class(es) applied to a menu list element.
             *
             * @since WP 4.8.0
             *
             * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
             * @param stdClass $args    An object of `wp_nav_menu()` arguments.
             * @param int      $depth   Depth of menu item. Used for padding.
             */
            $class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
            /**
             * The `.dropdown-menu` container needs to have a labelledby
             * attribute which points to it's trigger link.
             *
             * Form a string for the labelledby attribute from the the latest
             * link with an id that was added to the $output.
             */
            $labelledby = '';
            // find all links with an id in the output.
            preg_match_all( '/(<a.*?id=\"|\')(.*?)\"|\'.*?>/im', $output, $matches );
            // with pointer at end of array check if we got an ID match.
            if ( end( $matches[2] ) ) {
                // build a string to use as aria-labelledby.
                $labelledby = 'aria-labelledby="' . end( $matches[2] ) . '"';
            }
            $output .= "{$n}{$indent}<ul$class_names $labelledby role=\"menu\">{$n}";
        }

        /**
         * Starts the element output.
         *
         * @since WP 3.0.0
         * @since WP 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
         *
         * @see Walker_Nav_Menu::start_el()
         *
         * @param string   $output Used to append additional content (passed by reference).
         * @param WP_Post  $item   Menu item data object.
         * @param int      $depth  Depth of menu item. Used for padding.
         * @param stdClass $args   An object of wp_nav_menu() arguments.
         * @param int      $id     Current item ID.
         */
        public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
            if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
                $t = '';
                $n = '';
            } else {
                $t = "\t";
                $n = "\n";
            }
            $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

            $classes = empty( $item->classes ) ? array() : (array) $item->classes;

            // Initialize some holder variables to store specially handled item
            // wrappers and icons.
            $linkmod_classes = array();
            $icon_classes    = array();

            /**
             * Get an updated $classes array without linkmod or icon classes.
             *
             * NOTE: linkmod and icon class arrays are passed by reference and
             * are maybe modified before being used later in this function.
             */
            $classes = self::separate_linkmods_and_icons_from_classes( $classes, $linkmod_classes, $icon_classes, $depth );

            // Join any icon classes plucked from $classes into a string.
            $icon_class_string = join( ' ', $icon_classes );

            /**
             * Filters the arguments for a single nav menu item.
             *
             *  WP 4.4.0
             *
             * @param stdClass $args  An object of wp_nav_menu() arguments.
             * @param WP_Post  $item  Menu item data object.
             * @param int      $depth Depth of menu item. Used for padding.
             */
            $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

            // Add .dropdown or .active classes where they are needed.
            if ( isset( $args->has_children ) && $args->has_children ) {
                $classes[] = 'submenu-area';
            }
            if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-parent', $classes, true ) ) {
                $classes[] = 'active';
            }

            // Add some additional default classes to the item.
            $classes[] = 'menu-item-' . $item->ID;
            $classes[] = 'nav-item';

            // Allow filtering the classes.
            $classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );

            // Form a string of classes in format: class="class_names".
            $class_names = join( ' ', $classes );
            $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

            /**
             * Filters the ID applied to a menu item's list item element.
             *
             * @since WP 3.0.1
             * @since WP 4.1.0 The `$depth` parameter was added.
             *
             * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
             * @param WP_Post  $item    The current menu item.
             * @param stdClass $args    An object of wp_nav_menu() arguments.
             * @param int      $depth   Depth of menu item. Used for padding.
             */
            $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
            $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

            $output .= $indent . '<li' . $id . $class_names . '>';

            // initialize array for holding the $atts for the link item.
            $atts = array();

            // Set title from item to the $atts array - if title is empty then
            // default to item title.
            if ( empty( $item->attr_title ) ) {
                $atts['title'] = ! empty( $item->title ) ? strip_tags( $item->title ) : '';
            } else {
                $atts['title'] = $item->attr_title;
            }

            $atts['target'] = ! empty( $item->target ) ? $item->target : '';
            $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
            // If item has_children add atts to <a>.
            if ( isset( $args->has_children ) && $args->has_children && 0 === $depth && $args->depth > 1 ) {
                $atts['href'] = ! empty( $item->url ) ? $item->url : '';
                $atts['data-hover'] = 'submenu-area';
                $atts['aria-haspopup'] = 'true';
                $atts['aria-expanded'] = 'false';
                $atts['class']         = 'dropdown-toggle nav-link';
                $atts['id']            = 'menu-item-dropdown-' . $item->ID;
            } else {
                $atts['href'] = ! empty( $item->url ) ? $item->url : '#';
                // Items in dropdowns use .dropdown-item instead of .nav-link.
                if ( $depth > 0 ) {
                    $atts['class'] = 'dropdown-item';
                } else {
                    $atts['class'] = 'nav-link-item';
                }
            }

            // update atts of this item based on any custom linkmod classes.
            $atts = self::update_atts_for_linkmod_type( $atts, $linkmod_classes );
            // Allow filtering of the $atts array before using it.
            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

            // Build a string of html containing all the atts for the item.
            $attributes = '';
            foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                    $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            /**
             * Set a typeflag to easily test if this is a linkmod or not.
             */
            $linkmod_type = self::get_linkmod_type( $linkmod_classes );

            /**
             * START appending the internal item contents to the output.
             */
            $item_output = isset( $args->before ) ? $args->before : '';
            /**
             * This is the start of the internal nav item. Depending on what
             * kind of linkmod we have we may need different wrapper elements.
             */
            if ( '' !== $linkmod_type ) {
                // is linkmod, output the required element opener.
                $item_output .= self::linkmod_element_open( $linkmod_type, $attributes );
            } else {
                // With no link mod type set this must be a standard <a> tag.
                $item_output .= '<a' . $attributes . '>';
            }

            /**
             * Initiate empty icon var, then if we have a string containing any
             * icon classes form the icon markup with an <i> element. This is
             * output inside of the item before the $title (the link text).
             */
            $icon_html = '';
            if ( ! empty( $icon_class_string ) ) {
                // append an <i> with the icon classes to what is output before links.
                $icon_html = '<i class="' . esc_attr( $icon_class_string ) . '" aria-hidden="true"></i> ';
            }

            /** This filter is documented in wp-includes/post-template.php */
            $title = apply_filters( 'the_title', $item->title, $item->ID );

            /**
             * Filters a menu item's title.
             *
             * @since WP 4.4.0
             *
             * @param string   $title The menu item's title.
             * @param WP_Post  $item  The current menu item.
             * @param stdClass $args  An object of wp_nav_menu() arguments.
             * @param int      $depth Depth of menu item. Used for padding.
             */
            $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

            /**
             * If the .sr-only class was set apply to the nav items text only.
             */
            if ( in_array( 'sr-only', $linkmod_classes, true ) ) {
                $title         = self::wrap_for_screen_reader( $title );
                $keys_to_unset = array_keys( $linkmod_classes, 'sr-only' );
                foreach ( $keys_to_unset as $k ) {
                    unset( $linkmod_classes[ $k ] );
                }
            }

            // Put the item contents into $output.
            $item_output .= isset( $args->link_before ) ? $args->link_before . $icon_html . $title . $args->link_after : '';
            /**
             * This is the end of the internal nav item. We need to close the
             * correct element depending on the type of link or link mod.
             */
            if ( '' !== $linkmod_type ) {
                // is linkmod, output the required element opener.
                $item_output .= self::linkmod_element_close( $linkmod_type, $attributes );
            } else {
                // With no link mod type set this must be a standard <a> tag.
                $item_output .= '</a>';
            }

            $item_output .= isset( $args->after ) ? $args->after : '';

            /**
             * END appending the internal item contents to the output.
             */
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

        }

        /**
         * Traverse elements to create list from elements.
         *
         * Display one element if the element doesn't have any children otherwise,
         * display the element and its children. Will only traverse up to the max
         * depth and no ignore elements under that depth. It is possible to set the
         * max depth to include all depths, see walk() method.
         *
         * This method should not be called directly, use the walk() method instead.
         *
         * @since WP 2.5.0
         *
         * @see Walker::start_lvl()
         *
         * @param object $element           Data object.
         * @param array  $children_elements List of elements to continue traversing (passed by reference).
         * @param int    $max_depth         Max depth to traverse.
         * @param int    $depth             Depth of current element.
         * @param array  $args              An array of arguments.
         * @param string $output            Used to append additional content (passed by reference).
         */
        public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
            if ( ! $element ) {
                return; }
            $id_field = $this->db_fields['id'];
            // Display this element.
            if ( is_object( $args[0] ) ) {
                $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] ); }
            parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
        }

        /**
         * Menu Fallback
         * =============
         * If this function is assigned to the wp_nav_menu's fallback_cb variable
         * and a menu has not been assigned to the theme location in the WordPress
         * menu manager the function with display nothing to a non-logged in user,
         * and will add a link to the WordPress menu manager if logged in as an admin.
         *
         * @param array $args passed from the wp_nav_menu function.
         */
        public static function fallback( $args ) {
            if ( current_user_can( 'edit_theme_options' ) ) {

                /* Get Arguments. */
                $container       = $args['container'];
                $container_id    = $args['container_id'];
                $container_class = $args['container_class'];
                $menu_class      = $args['menu_class'];
                $menu_id         = $args['menu_id'];

                // initialize var to store fallback html.
                $fallback_output = '';

                if ( $container ) {
                    $fallback_output .= '<' . esc_attr( $container );
                    if ( $container_id ) {
                        $fallback_output .= ' id="' . esc_attr( $container_id ) . '"';
                    }
                    if ( $container_class ) {
                        $fallback_output .= ' class="' . esc_attr( $container_class ) . '"';
                    }
                    $fallback_output .= '>';
                }
                $fallback_output .= '<ul';
                if ( $menu_id ) {
                    $fallback_output .= ' id="' . esc_attr( $menu_id ) . '"'; }
                if ( $menu_class ) {
                    $fallback_output .= ' class="' . esc_attr( $menu_class ) . '"'; }
                $fallback_output .= '>';
                $fallback_output .= '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '" title="' . esc_attr__( 'Home', 'geoport' ) . '">' . esc_html__( 'Home', 'geoport' ) . '</a></li>';
                $fallback_output .= '</ul>';
                if ( $container ) {
                    $fallback_output .= '</' . esc_attr( $container ) . '>';
                }

                // if $args has 'echo' key and it's true echo, otherwise return.
                if ( array_key_exists( 'echo', $args ) && $args['echo'] ) {
                    echo  wp_kses_post($fallback_output, 'geoport');
                } else {
                    return $fallback_output;
                }
            }
        }

        /**
         * Find any custom linkmod or icon classes and store in their holder
         * arrays then remove them from the main classes array.
         *
         * Supported linkmods: .disabled, .dropdown-header, .dropdown-divider, .sr-only
         * Supported iconsets: Font Awesome 4/5, Glypicons
         *
         * NOTE: This accepts the linkmod and icon arrays by reference.
         *
         * @since 4.0.0
         *
         * @param array   $classes         an array of classes currently assigned to the item.
         * @param array   $linkmod_classes an array to hold linkmod classes.
         * @param array   $icon_classes    an array to hold icon classes.
         * @param integer $depth           an integer holding current depth level.
         *
         * @return array  $classes         a maybe modified array of classnames.
         */
        private function separate_linkmods_and_icons_from_classes( $classes, &$linkmod_classes, &$icon_classes, $depth ) {
            // Loop through $classes array to find linkmod or icon classes.
            foreach ( $classes as $key => $class ) {
                // If any special classes are found, store the class in it's
                // holder array and and unset the item from $classes.
                if ( preg_match( '/^disabled|^sr-only/i', $class ) ) {
                    // Test for .disabled or .sr-only classes.
                    $linkmod_classes[] = $class;
                    unset( $classes[ $key ] );
                } elseif ( preg_match( '/^dropdown-header|^dropdown-divider|^dropdown-item-text/i', $class ) && $depth > 0 ) {
                    // Test for .dropdown-header or .dropdown-divider and a
                    // depth greater than 0 - IE inside a dropdown.
                    $linkmod_classes[] = $class;
                    unset( $classes[ $key ] );
                } elseif ( preg_match( '/^fa-(\S*)?|^fa(s|r|l|b)?(\s?)?$/i', $class ) ) {
                    // Font Awesome.
                    $icon_classes[] = $class;
                    unset( $classes[ $key ] );
                } elseif ( preg_match( '/^glyphicon-(\S*)?|^glyphicon(\s?)$/i', $class ) ) {
                    // Glyphicons.
                    $icon_classes[] = $class;
                    unset( $classes[ $key ] );
                }
            }

            return $classes;
        }

        /**
         * Return a string containing a linkmod type and update $atts array
         * accordingly depending on the decided.
         *
         * @since 4.0.0
         *
         * @param array $linkmod_classes array of any link modifier classes.
         *
         * @return string                empty for default, a linkmod type string otherwise.
         */
        private function get_linkmod_type( $linkmod_classes = array() ) {
            $linkmod_type = '';
            // Loop through array of linkmod classes to handle their $atts.
            if ( ! empty( $linkmod_classes ) ) {
                foreach ( $linkmod_classes as $link_class ) {
                    if ( ! empty( $link_class ) ) {

                        // check for special class types and set a flag for them.
                        if ( 'dropdown-header' === $link_class ) {
                            $linkmod_type = 'dropdown-header';
                        } elseif ( 'dropdown-divider' === $link_class ) {
                            $linkmod_type = 'dropdown-divider';
                        } elseif ( 'dropdown-item-text' === $link_class ) {
                            $linkmod_type = 'dropdown-item-text';
                        }
                    }
                }
            }
            return $linkmod_type;
        }

        /**
         * Update the attributes of a nav item depending on the limkmod classes.
         *
         * @since 4.0.0
         *
         * @param array $atts            array of atts for the current link in nav item.
         * @param array $linkmod_classes an array of classes that modify link or nav item behaviors or displays.
         *
         * @return array                 maybe updated array of attributes for item.
         */
        private function update_atts_for_linkmod_type( $atts = array(), $linkmod_classes = array() ) {
            if ( ! empty( $linkmod_classes ) ) {
                foreach ( $linkmod_classes as $link_class ) {
                    if ( ! empty( $link_class ) ) {
                        // update $atts with a space and the extra classname...
                        // so long as it's not a sr-only class.
                        if ( 'sr-only' !== $link_class ) {
                            $atts['class'] .= ' ' . esc_attr( $link_class );
                        }
                        // check for special class types we need additional handling for.
                        if ( 'disabled' === $link_class ) {
                            // Convert link to '#' and unset open targets.
                            $atts['href'] = '#';
                            unset( $atts['target'] );
                        } elseif ( 'dropdown-header' === $link_class || 'dropdown-divider' === $link_class || 'dropdown-item-text' === $link_class ) {
                            // Store a type flag and unset href and target.
                            unset( $atts['href'] );
                            unset( $atts['target'] );
                        }
                    }
                }
            }
            return $atts;
        }

        /**
         * Wraps the passed text in a screen reader only class.
         *
         * @since 4.0.0
         *
         * @param string $text the string of text to be wrapped in a screen reader class.
         * @return string      the string wrapped in a span with the class.
         */
        private function wrap_for_screen_reader( $text = '' ) {
            if ( $text ) {
                $text = '<span class="sr-only">' . $text . '</span>';
            }
            return $text;
        }

        /**
         * Returns the correct opening element and attributes for a linkmod.
         *
         * @since 4.0.0
         *
         * @param string $linkmod_type a sting containing a linkmod type flag.
         * @param string $attributes   a string of attributes to add to the element.
         *
         * @return string              a string with the openign tag for the element with attribibutes added.
         */
        private function linkmod_element_open( $linkmod_type, $attributes = '' ) {
            $output = '';
            if ( 'dropdown-item-text' === $linkmod_type ) {
                $output .= '<span class="dropdown-item-text"' . $attributes . '>';
            } elseif ( 'dropdown-header' === $linkmod_type ) {
                // For a header use a span with the .h6 class instead of a real
                // header tag so that it doesn't confuse screen readers.
                $output .= '<span class="dropdown-header h6"' . $attributes . '>';
            } elseif ( 'dropdown-divider' === $linkmod_type ) {
                // this is a divider.
                $output .= '<div class="dropdown-divider"' . $attributes . '>';
            }
            return $output;
        }

        /**
         * Return the correct closing tag for the linkmod element.
         *
         * @since 4.0.0
         *
         * @param string $linkmod_type a string containing a special linkmod type.
         *
         * @return string              a string with the closing tag for this linkmod type.
         */
        private function linkmod_element_close( $linkmod_type ) {
            $output = '';
            if ( 'dropdown-header' === $linkmod_type || 'dropdown-item-text' === $linkmod_type ) {
                // For a header use a span with the .h6 class instead of a real
                // header tag so that it doesn't confuse screen readers.
                $output .= '</span>';
            } elseif ( 'dropdown-divider' === $linkmod_type ) {
                // this is a divider.
                $output .= '</div>';
            }
            return $output;
        }
}