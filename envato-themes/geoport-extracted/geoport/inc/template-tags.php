<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package geoport
 */

/*------------------------------------------------------------------------------------------------------------------*/
/*  Display the archive title
/*------------------------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'geoport_archive_page_title' ) ) :
/**
 * Shim for `geoport_archive_page_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function geoport_archive_page_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( esc_html__( '%s', 'geoport' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( esc_html__( '%s', 'geoport' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( esc_html__( '%s', 'geoport' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( esc_html__( '%s', 'geoport' ), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'geoport' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( esc_html__( '%s', 'geoport' ), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'geoport' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( esc_html__( '%s', 'geoport' ), get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'geoport' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = esc_html_x( 'Asides', 'post format archive title', 'geoport' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = esc_html_x( 'Galleries', 'post format archive title', 'geoport' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = esc_html_x( 'Images', 'post format archive title', 'geoport' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = esc_html_x( 'Videos', 'post format archive title', 'geoport' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = esc_html_x( 'Quotes', 'post format archive title', 'geoport' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = esc_html_x( 'Links', 'post format archive title', 'geoport' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = esc_html_x( 'Statuses', 'post format archive title', 'geoport' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = esc_html_x( 'Audio', 'post format archive title', 'geoport' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = esc_html_x( 'Chats', 'post format archive title', 'geoport' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( esc_html__( 'Archives: %s', 'geoport' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( esc_html__( '%1$s: %2$s', 'geoport' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = esc_html__( 'Archives', 'geoport' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_geoport_archive_page_title', $title );

	if ( ! empty( $title ) ) {
		echo wp_kses_stripslashes( $before . $title . $after );  // WPCS: XSS OK.
	}
}
endif;

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function geoport_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'geoport_pingback_header' );


/*------------------------------------------------------------------------------------------------------------------*/
/*  Add site icon.
/*------------------------------------------------------------------------------------------------------------------*/ 
function geoport_site_icon_header() {
    if ( ! ( function_exists( 'has_site_icon' ) && has_site_icon() ) ) {
        if( function_exists( 'geoport_framework_init' ) ) {
            $site_icon = geoport_get_option('geoport_site_icon');
            $attachment = wp_get_attachment_image_src( $site_icon, 'full' );
            $site_fav_icon    = ($attachment) ? $attachment[0] : $site_icon;
        ?>
        <link rel="shortcut icon" type="image/png" href="<?php echo esc_url( $site_fav_icon );?>">
        <link rel="apple-touch-icon" href="<?php echo esc_url( $site_fav_icon );?>">
        <?php }else{
        }
    } 
}
add_action( 'wp_head', 'geoport_site_icon_header' );
add_action( 'admin_head', 'geoport_site_icon_header' );

/*------------------------------------------------------------------------------------------------------------------*/
/*  Beardcrumb Meta Setting
/*------------------------------------------------------------------------------------------------------------------*/

function geoport_meta_breadcrumbs() {

    /* === OPTIONS === */
    $text['home']     = esc_html__( 'Home', 'geoport' ); // text for the 'Home' link
    $text['category'] = esc_html__( 'Archive by Category: %s', 'geoport' ); // text for a category page
    $text['search']   = esc_html__( 'Search Results for: %s', 'geoport' ); // text for a search results page
    $text['tag']      = esc_html__( 'Posts Tagged: %s', 'geoport' ); // text for a tag page
    $text['author']   = esc_html__( 'Posted by %s', 'geoport' ); // text for an author page
    $text['404']      = esc_html__( 'Error 404', 'geoport' ); // text for the 404 page
    $text['page']     = esc_html__( 'Page %s', 'geoport' ); // text 'Page N'
    $text['cpage']    = esc_html__( 'Comment Page %s', 'geoport' ); // text 'Comment Page N'

    $wrap_before    = '<ol class="breadcrumb">'; // the opening wrapper tag
    $wrap_after     = '</ol><!-- .breadcrumbs -->'; // the closing wrapper tag
    $show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
    $show_on_home   = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $show_current   = 1; // 1 - show current page title, 0 - don't show
    $before         = '<li class="breadcrumb-item active">'; // tag before the current crumb
    $after          = '</li>'; // tag after the current crumb
    /* === END OF OPTIONS === */

    global $post;
    $home_url       = home_url('/');
    $link_before    = '<li class="breadcrumb-item">';
    $link_after     = '</li>';
    $link_attr      = ' ';
    $link_in_before = '';
    $link_in_after  = '';
    $link           = $link_before . '<a href="%1$s"' . $link_attr . '>' . $link_in_before . '%2$s' . $link_in_after . '</a>' . $link_after;
    $frontpage_id   = get_option('page_on_front');
    $parent_id      = ($post) ? $post->post_parent : '';
    $home_link      = $link_before . '<a href="' . $home_url . '"' . $link_attr . ' class="home">' . $link_in_before . $text['home'] . $link_in_after . '</a>' . $link_after;

    if (is_home() || is_front_page()) {

        $page_for_posts_id = get_option('page_for_posts');
        $get_bloginfo = get_bloginfo( 'name' );
        if ( $page_for_posts_id ) { 
            $post = get_page($page_for_posts_id);
            setup_postdata($post);
            the_title();
            rewind_posts();
        } elseif ( $get_bloginfo ) { 
            echo esc_html( $get_bloginfo );
        } else {
        	echo wp_kses_stripslashes ( $wrap_before . $home_link . $wrap_after );
        }

    } else {

        echo wp_kses_stripslashes($wrap_before);
        if ($show_home_link) echo wp_kses_stripslashes( $home_link );

        if ( is_category() ) {
            $cat = get_category(get_query_var('cat'), false);
            if ($cat->parent != 0) {
                $cats = get_category_parents($cat->parent, TRUE );
                $cats = preg_replace("#^(.+)#", "$1", $cats);
                $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
                if ($show_home_link);
                echo wp_kses_stripslashes( $cats );
            }
            if ( get_query_var('paged') ) {
                $cat = $cat->cat_ID;
                sprintf($link, get_category_link($cat), get_cat_name($cat)) . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_current) echo wp_kses_stripslashes( $before . sprintf($text['category'], single_cat_title('', false)) . $after );
            }

        } elseif ( is_search() ) {
            if (have_posts()) {
                if ($show_home_link && $show_current);
                if ($show_current) echo wp_kses_stripslashes( $before . sprintf($text['search'], get_search_query()) . $after );
            } else {
                if ($show_home_link);
                echo wp_kses_stripslashes( $before . sprintf($text['search'], get_search_query()) . $after );
            }

        } elseif ( is_day() ) {
            if ($show_home_link);
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'));
            echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'));
            if ($show_current) echo wp_kses_stripslashes( $before . get_the_time('d') . $after );

        } elseif ( is_month() ) {
            if ($show_home_link);
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'));
            if ($show_current) echo wp_kses_stripslashes( $before . get_the_time('F') . $after );

        } elseif ( is_year() ) {
            if ($show_home_link && $show_current);
            if ($show_current) echo wp_kses_stripslashes( $before . get_the_time('Y') . $after );

        } elseif ( is_single() && !is_attachment() ) {
            if ($show_home_link);
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                printf($link, $home_url . $slug['slug'] . '/', $post_type->labels->singular_name);
                if ($show_current) echo wp_kses_stripslashes( $before . get_the_title() . $after );
            } else {
                if (has_category()) {
                    $cat    = get_the_category(); $cat = $cat[0];
                    $cats   = get_category_parents($cat, TRUE, '' );
                    if (!$show_current || get_query_var('cpage')) $cats = preg_replace("#^(.+)#", "$1", $cats);
                    $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
                    echo wp_kses_stripslashes( $cats );
                }
                if ( get_query_var('cpage') ) {
                    echo wp_kses_stripslashes( sprintf($link, get_permalink(), get_the_title()) . $before . sprintf($text['cpage'], get_query_var('cpage')) . $after );
                } else {
                    if ($show_current) echo wp_kses_stripslashes( $before . get_the_title() . $after );
                }
            }

        // custom post type
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            if ( get_query_var('paged') ) {
                echo wp_kses_stripslashes( sprintf($link, get_post_type_archive_link($post_type->name), $post_type->label) . $before . sprintf($text['page'], get_query_var('paged')) . $after );
            } elseif(!empty($post_type)) {
                if ($show_current) echo wp_kses_stripslashes( $before . $post_type->label . $after );
            } else {
            	echo wp_kses_stripslashes ( $before . esc_html__( 'There have no posts', 'geoport' ) . $after );
            }

        } elseif ( is_attachment() ) {
            if ($show_home_link);
            $parent = get_post($parent_id);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            if ($cat) {
                $cats = get_category_parents($cat, TRUE );
                $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
                echo wp_kses_stripslashes( $cats );
            }
            printf($link, get_permalink($parent), $parent->post_title);
            if ($show_current) echo wp_kses_stripslashes( $before . get_the_title() . $after );

        } elseif ( is_page() && !$parent_id ) {
            if ($show_current) echo wp_kses_stripslashes( $before . get_the_title() . $after );

        } elseif ( is_page() && $parent_id ) {
            if ($show_home_link);
            if ($parent_id != $frontpage_id) {
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    if ($parent_id != $frontpage_id) {
                        $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    }
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo wp_kses_stripslashes( $breadcrumbs[$i] );
                    if ($i != count($breadcrumbs)-1);
                }
            }
            if ($show_current) echo wp_kses_stripslashes( $before . get_the_title() . $after );

        } elseif ( is_tag() ) {
            if ( get_query_var('paged') ) {
                $tag_id = get_queried_object_id();
                $tag = get_tag($tag_id);
                echo wp_kses_stripslashes( sprintf($link, get_tag_link($tag_id), $tag->name) . $before . sprintf($text['page'], get_query_var('paged')) . $after );
            } else {
                if ($show_current) echo wp_kses_stripslashes( $before . sprintf($text['tag'], single_tag_title('', false)) . $after );
            }

        } elseif ( is_author() ) {
            global $author;
            $author = get_userdata($author);
            if ( get_query_var('paged') ) {
                if ($show_home_link);
                echo sprintf($link, get_author_posts_url($author->ID), $author->display_name) . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_home_link && $show_current);
                if ($show_current) echo wp_kses_stripslashes( $before . sprintf($text['author'], $author->display_name) . $after );
            }

        } elseif ( is_404() ) {
            if ($show_home_link && $show_current);
            if ($show_current) echo wp_kses_stripslashes( $before . $text['404'] . $after );

        } elseif ( has_post_format() && !is_singular() ) {
            if ($show_home_link);
            echo get_post_format_string( get_post_format() );
        }
        echo wp_kses_stripslashes( $wrap_after );
    }
} // end of geoport_meta_breadcrumbs()


/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function geoport_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'geoport_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
			) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'geoport_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so geoport_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so geoport_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in geoport_categorized_blog.
 */
function geoport_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'geoport_categories' );
}
add_action( 'edit_category', 'geoport_category_transient_flusher' );
add_action( 'save_post',     'geoport_category_transient_flusher' );


/*-----------------------------------------------------------------------------------*/
/*	Display geoport paging navigation.  
/*-----------------------------------------------------------------------------------*/ 

if ( ! function_exists( 'geoport_paging_nav' ) ) :
	function geoport_paging_nav($pages = '', $range = 2) {

		$showitems = ($range * 1)+1;  

		global $paged;

		if(empty($paged)) $paged = 1;

		if($pages == ''){
			global $wp_query;
			$pages = $wp_query->max_num_pages;

			if(!$pages)
			{
				$pages = 1;
			}
		}   

		if(1 != $pages){
			
			echo '<div class="col-md-12"><div class="d-flex justify-content-center pagination_waper pd-30"><nav aria-label="Page navigation ct-pagination"><ul class="pagination">';

				if($paged > 2 && $paged > $range+1 && $showitems < $pages){
					echo '<li class="page-item"><a class="bp-prev page-link" href="'.get_pagenum_link(1).'"><i class="dashicons dashicons-arrow-left-alt" aria-hidden="true"></i></a></li>';
				}

				for ($i=1; $i <= $pages; $i++){
					if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
					{
						echo wp_kses_stripslashes( $paged == $i )? "<li class=\"page-item active\"><a href='#' class='page-link activeborder'>".$i."</a></li>":"<li class=\"page-item\"><a href='".get_pagenum_link($i)."' class='page-link'>".$i."</a></li>";
					}
				}

				if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages){
					echo '<li class="page-item"><a class="page-link" href="'.get_pagenum_link($pages).'"><i class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></i></a></li>';
				}

            echo '</ul></nav></div></div>';
		}
	}
endif;

/*-----------------------------------------------------------------------------------*/
/*	Display geoport post navigation.  
/*-----------------------------------------------------------------------------------*/ 

if ( !function_exists( 'geoport_post_nav' ) ) :

	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function geoport_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
		$pre_post = $next_post = '';
		$next_post	 = get_next_post();
		$pre_post	 = get_previous_post();
		if ( !$next_post && !$pre_post ) {
			return;
		}
		if($pre_post):
			$pre_img = wp_get_attachment_url( get_post_thumbnail_id($pre_post->ID) );
		endif;
		if($next_post):
			$next_img = wp_get_attachment_url( get_post_thumbnail_id($next_post->ID) );
		endif;
		
		echo '<div class="posts-navigation bpost-navigation"><div class="row align-items-center"> 
		<div class="col-md-6"><div class="post-previous">';
		if ( !empty( $pre_post ) ):
			?>
			<div class="prev-link">
                <a href="<?php echo get_the_permalink( $pre_post->ID ); ?>">
                    <span><?php esc_html_e( 'Prev Post', 'geoport' ) ?></span>
                    <h4><?php echo get_the_title( $pre_post->ID ) ?></h4>
                </a>
            </div>

			<?php
		endif;
		echo '</div></div><div class="col-md-6"><div class="post-next">';

		if ( !empty( $next_post ) ):
			?>
			<div class="next-link text-left text-md-right">
                <a href="<?php echo get_the_permalink( $next_post->ID ); ?>">
    	            <span><?php esc_html_e( 'Next Post', 'geoport' ) ?></span>
    	            <h4><?php echo get_the_title( $next_post->ID ) ?></h4>
                </a>
	        </div>
			<?php
		endif;
		echo '</div></div></div></div>';
	}
endif;


if ( ! function_exists( 'geoport_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function geoport_posts_navigation() {
    // Don't print empty markup if there's only one page.
    if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
        return;
    }
    ?>
    <nav class="navigation posts-navigation next-prev" role="navigation">

        <div class="nav-links">

            <?php if ( get_next_posts_link() ) : ?>
                <div class="nav-previous old-entries"><i class="dashicons-arrow-left-alt"></i><?php next_posts_link( esc_html__( 'Older posts', 'geoport' ) ); ?></div>
            <?php endif; ?>

            <?php if ( get_previous_posts_link() ) : ?>
                <div class="nav-next new-entries"><?php previous_posts_link( esc_html__( 'Newer posts', 'geoport' ) ); ?> <i class="fa fa-angle-right"></i></div>
            <?php endif; ?>

        </div><!-- .nav-links -->
    </nav><!-- .navigation -->
    <?php
}
endif;