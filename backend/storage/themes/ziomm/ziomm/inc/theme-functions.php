<?php

/**
 * @author: VLThemes
 * @version: 1.0.5
 */


/**
 * Wrapper function to deal with backwards compatibility.
 */
if ( ! function_exists( 'ziomm_body_open' ) ) {
	function ziomm_body_open() {
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
		} else {
			do_action( 'wp_body_open' );
		}
	}
}

/**
 * Sanitize slass tag
 */
if ( ! function_exists( 'ziomm_sanitize_class' ) ) {
	function ziomm_sanitize_class( $class, $fallback = null ) {

		if ( is_string( $class ) ) {
			$class = explode( ' ', $class );
		}

		if ( is_array( $class ) && count( $class ) > 0 ) {
			$class = array_map( 'sanitize_html_class', $class );
			return implode( ' ', $class );
		} else {
			return sanitize_html_class( $class, $fallback );
		}

	}
}

/**
 * Sanitize style tag
 */
if ( ! function_exists( 'ziomm_sanitize_style' ) ) {
	function ziomm_sanitize_style( $style ) {

		$allowed_html = array(
			'style' => array ()
		);
		return wp_kses( $style, $allowed_html );

	}
}

/**
 * Get trimmed content
 */
if ( ! function_exists( 'ziomm_get_trimmed_content' ) ) {
	function ziomm_get_trimmed_content( $max_words = 18 ) {

		global $post;

		$content = $post->post_excerpt != '' ? $post->post_excerpt : $post->post_content;
		$content = preg_replace( '~(?:\[/?)[^/\]]+/?\]~s', '', $content );
		$content = strip_tags( $content );
		$content = strip_shortcodes( $content );
		$words = explode( ' ', $content, $max_words + 1 );
		if ( count( $words ) > $max_words ) {
			array_pop( $words );
			array_push( $words, '...', '' );
		}
		$content = implode( ' ', $words );
		$content = esc_html( $content );

		return apply_filters( 'ziomm/get_trimmed_content', $content );

	}
}

/**
 * Get page pagination
 */
if ( ! function_exists( 'ziomm_get_page_pagination' ) ) {
	function ziomm_get_page_pagination( $query = null, $paginated = 'numeric' ) {

		if ( $query == null ) {
			global $wp_query;
			$query = $wp_query;
		}

		$page = $query->query_vars[ 'paged' ];
		$pages = $query->max_num_pages;
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : ( get_query_var( 'page' ) ? get_query_var( 'page' ) : 1 );

		if ( $page == 0 ) {
			$page = 1;
		}

		if ( $paginated == 'none' || $pages <= 1 ) {
			return;
		}

		$class = 'vlt-pagination';
		$class .= ' vlt-pagination--' . $paginated;

		$output = '<nav class="' . ziomm_sanitize_class( $class ) . '">';

		if ( $pages > 1 ) {

			if ( $paginated == 'paged' ) {

				if ( $page - 1 >= 1 ) {
					$output .= '<a class="prev" href="' . get_pagenum_link( $page - 1 ) . '"><i class="icon-chevron-left"></i></a>';
				}

				if ( $page + 1 <= $pages ) {
					$output .= '<a class="next" href="' . get_pagenum_link( $page + 1 ) . '"><i class="icon-chevron-right"></i></a>';
				}

			}

			if ( $paginated == 'numeric' ) {

				$numeric_links = paginate_links( array(
					'type' => 'array',
					'foramt' => '',
					'add_args' => '',
					'current' => $paged,
					'total' => $pages,
					'prev_text' => '<i class="icon-chevron-left"></i>',
					'next_text' => '<i class="icon-chevron-right"></i>',
					'end_size' => 3,
					'mid_size' => 3,
				) );

				$output .= '<ul>';
				if ( is_array( $numeric_links ) ) {
					foreach ( $numeric_links as $numeric_link ) {
						$output .= '<li>' . $numeric_link . '</li>';
					}
				}
				$output .= '</ul>';

			}

		}

		$output .= '</nav>';

		return apply_filters( 'ziomm/get_page_pagination', $output );

	}
}

/**
 * Get post taxonomy
 */
if ( ! function_exists( 'ziomm_get_post_taxonomy' ) ) {
	function ziomm_get_post_taxonomy( $post_id, $taxonomy, $delimiter = ', ', $get = 'name', $link = true, $max_count = 2 ) {

		$tags = wp_get_post_terms( $post_id, $taxonomy );
		$list = '';
		$count = 0;

		foreach ( $tags as $tag ) {

			$count++;

			if ( $link ) {
				$list .= '<a href="' . get_category_link( $tag->term_id ) . '">' . $tag->$get . '</a>' . $delimiter;
			} else {
				$list .= $tag->$get . $delimiter;
			}

			if ( $count > $max_count - 1 ) {
				break;
			}

		}

		return substr( $list, 0, strlen( $delimiter ) * ( -1 ) );

	}
}

/**
 * Callback for custom comment
 */
if ( ! function_exists( 'ziomm_callback_custom_comment' ) ) {
	function ziomm_callback_custom_comment( $comment, $args, $depth ) {

		$GLOBALS['comment'] = $comment;
		global $post;

		?>

		<li <?php comment_class( 'vlt-comment-item' ); ?>>

			<div class="vlt-comment-item__inner" id="comment-<?php comment_ID(); ?>">

				<?php if ( 0 != $args['avatar_size'] && get_avatar( $comment ) ) : ?>
					<a class="vlt-comment-avatar" href="<?php echo get_comment_author_url(); ?>"><?php echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'extra_attr' => 'loading=lazy' ) ); ?></a>
					<!-- /.vlt-comment-avatar -->
				<?php endif; ?>

				<div class="vlt-comment-content">

					<div class="vlt-comment-header">

						<h4 class="vlt-comment-name"><?php comment_author(); ?></h4>

						<div class="vlt-comment-metas">

							<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">

								<time datetime="<?php comment_time( 'c' ); ?>">

									<?php echo get_comment_date(); ?>

								</time>

							</a>

						</div>

					</div>
					<!-- /.vlt-comment-header -->

					<div class="vlt-comment-text vlt-content-markup">

						<?php comment_text(); ?>

						<?php if ( '0' == $comment->comment_approved ): ?>

							<p><?php esc_html_e( 'Your comment is awaiting moderation.', 'ziomm' ); ?></p>

						<?php endif; ?>

					</div>
					<!-- /.vlt-comment-text -->

					<?php
						comment_reply_link( array_merge( $args, array(
							'depth' => $depth,
							'max_depth' => $args['max_depth'],
						) ) );
					?>

				</div>
				<!-- /.vlt-comment-content -->

			</div>
			<!-- /.vlt-comment-item__inner -->

		<!-- </li> is added by WordPress automatically -->
		<?php
	}
}

/**
 * Get comment navigation
 */
if ( ! function_exists( 'ziomm_get_comment_navigation' ) ) {
	function ziomm_get_comment_navigation() {

		$output = '';

		if ( get_comment_pages_count() > 1 ) :

			$output .= '<nav class="vlt-comments-navigation has-black-color">';
			if ( get_previous_comments_link() ) {
				$output .= get_previous_comments_link( esc_html__( 'Prev Page', 'ziomm' ) );
			}
			if ( get_next_comments_link() ) {
				$output .= get_next_comments_link( esc_html__( 'Next Page', 'ziomm' ) );
			}
			$output .= '</nav>';

		endif;

		return apply_filters( 'ziomm/get_comment_navigation', $output );

	}
}

/**
 * Get single post/work navigation
 */
if ( ! function_exists( 'ziomm_get_post_navigation' ) ) {
	function ziomm_get_post_navigation( $style = 'style-1', $source = 'work' ) {

		$navClass = 'vlt-page-navigation';
		$navClass .= ' vlt-page-navigation--' . $style;

		$nextPost = get_adjacent_post( false, '', true );
		$prevPost = get_adjacent_post( false, '', false );

		if ( ! $nextPost && ! $prevPost ) {
			return;
		}

		if ( $source == 'work' ) {
			$allLinkID = ziomm_get_theme_mod( 'portfolio_link' );
			$textLink = [
				esc_html__( 'Prev project', 'ziomm' ),
				esc_html__( 'Next project', 'ziomm' )
			];
		} else {
			$allLinkID = ziomm_get_theme_mod( 'shop_link' );
			$textLink = [
				esc_html__( 'Prev product', 'ziomm' ),
				esc_html__( 'Next product', 'ziomm' )
			];
		}

		$output = '<nav class="' . ziomm_sanitize_class( $navClass ) . '">';

			$output .= '<div class="container">';

				$output .= '<div class="row align-items-center">';

					$output .= '<div class="d-flex col">';
					if ( ! empty( $prevPost ) ) {
						$output .= '<div class="prev"><a href="' . get_permalink( $prevPost->ID ) . '"><i class="icon-arrow-left"></i>' . $textLink[0] . '</a></div>';
					}
					$output .= '</div>';

					$output .= '<div class="d-flex justify-content-center col">';
					if ( $allLinkID ) {
						$output .= '<div class="all"><a title="' . get_the_title( $allLinkID ) . '" href="' . get_permalink( $allLinkID ) . '"><span class="grid"><span></span><span></span><span></span><span></span></span></a></div>';
					} else {
						$output .= '<div class="all"><a title="' . esc_attr__( 'Back', 'ziomm' ) . '" href="#" class="btn-go-back"><span class="grid"><span></span><span></span><span></span><span></span></span></a></div>';
					}
					$output .= '</div>';

					$output .= '<div class="d-flex justify-content-end col">';
					if ( ! empty( $nextPost ) ) {
						$output .= '<div class="next"><a href="' . get_permalink( $nextPost->ID ) . '">' . $textLink[1] . '<i class="icon-arrow-right"></i></a></div>';
					}
					$output .= '</div>';

				$output .= '</div>';

			$output .= '</div>';

		$output .= '</nav>';

		return apply_filters( 'ziomm/get_post_navigation', $output );

	}
}

/**
 * Render elementor template
 */
if ( ! function_exists( 'ziomm_render_elementor_template' ) ) {
	function ziomm_render_elementor_template( $template ) {

		if ( ! $template ) {
			return;
		}

		if ( 'publish' !== get_post_status( $template ) ) {
			return;
		}

		$new_frontend = new Elementor\Frontend;
		return $new_frontend->get_builder_content_for_display( $template, false );

	}
}

/**
 * Reading time
 */
if ( ! function_exists( 'ziomm_get_reading_time' ) ) {
	function ziomm_get_reading_time() {
		global $post;
		$wpm = 200;
		$words = str_word_count( strip_tags( $post->post_content ) );
		$minutes = floor( $words / $wpm );
		if ( 1 <= $minutes ) {
			$output = $minutes . esc_html__( ' min read', 'ziomm' );
		} else {
			$output = esc_html__( '1 min read', 'ziomm' );
		}
		return apply_filters( 'ziomm/get_reading_time', $output );
	}
}

/**
 * Post views
 */
if ( ! function_exists( 'ziomm_set_post_views' ) ) {
	function ziomm_set_post_views( $postID ) {

		$count_key = 'ziomm_post_views_count';
		$count = get_post_meta( $postID, $count_key, true );
		if ( $count == '' ) {
			$count = 0;
			delete_post_meta( $postID, $count_key );
			add_post_meta( $postID, $count_key, '0' );
		} else {
			$count++;
			update_post_meta( $postID, $count_key, $count );
		}

	}
}
add_action( 'wp_head', 'ziomm_track_post_views' );

if ( ! function_exists( 'ziomm_track_post_views' ) ) {
	function ziomm_track_post_views( $postID ) {

		if ( !is_single() ) {
			return;
		}
		if ( empty( $postID ) ) {
			global $post;
			$postID = $post->ID;
		}
		ziomm_set_post_views( $postID );

	}
}

if ( ! function_exists( 'ziomm_get_post_views' ) ) {
	function ziomm_get_post_views( $postID ) {

		$count_key = 'ziomm_post_views_count';
		$count = get_post_meta( $postID, $count_key, true );
		if ( $count == '' ) {
			delete_post_meta( $postID, $count_key );
			add_post_meta( $postID, $count_key, '0' );
			return '0';
		}
		return $count;

	}
}