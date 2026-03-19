<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Xcency
 */

get_header();

if ( get_post_meta( $post->ID, 'xcency_common_meta', true ) ) {
	$common_meta = get_post_meta( $post->ID, 'xcency_common_meta', true );
} else {
	$common_meta = array();
}

if ( array_key_exists( 'layout_meta', $common_meta ) && $common_meta['layout_meta'] != 'default' ) {
	$page_layout = $common_meta['layout_meta'];
} else {
	$page_layout = xcency_option( 'page_default_layout', 'full-width' );
}

if ( array_key_exists( 'sidebar_meta', $common_meta ) && $common_meta['sidebar_meta'] != '0' ) {
	$selected_sidebar = $common_meta['sidebar_meta'];
} else {
	$selected_sidebar = xcency_option( 'page_default_sidebar', 'sidebar' );
}

if ( $page_layout == 'left-sidebar' && is_active_sidebar( $selected_sidebar ) || $page_layout == 'right-sidebar' && is_active_sidebar( $selected_sidebar ) ) {
	$page_column_class = 'col-lg-8';
} else {
	$page_column_class = 'col-lg-12';
}


if ( array_key_exists( 'enable_banner', $common_meta ) ) {
	$page_banner = $common_meta['enable_banner'];
} else {
	$page_banner = true;
}

if ( array_key_exists( 'hide_tile_meta', $common_meta ) ) {
	$hide_page_title = $common_meta['hide_tile_meta'];
} else {
	$hide_page_title = false;
}

if ( array_key_exists( 'custom_title', $common_meta ) ) {
	$custom_title = $common_meta['custom_title'];
} else {
	$custom_title = '';
}

if ( array_key_exists( 'banner_text_align_meta', $common_meta ) && $common_meta['banner_text_align_meta'] != 'default' ) {
	$banner_text_align = $common_meta['banner_text_align_meta'];
} else {
	$banner_text_align = xcency_option( 'banner_default_text_align', 'center' );
}

// btt = Banner Title Tag
$btt = xcency_option('banner_title_tag', 'h2');

?>
	<?php if($page_banner == true) : ?>
    <div class="banner-area page-banner">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-lg-12 my-auto">
                    <div class="banner-content text-<?php echo esc_attr( $banner_text_align ); ?>">
	                    <?php if($hide_page_title != true) : ?>
                        <<?php echo esc_html($btt);?> class="banner-title">
							<?php
							if ( ! empty( $custom_title ) ) {
								echo esc_html( $custom_title );
							} else {
								the_title();
							}
							?>
                        </<?php echo esc_html($btt);?>>
                        <?php endif;?>

						<?php if ( function_exists( 'bcn_display' ) ) :?>
                            <div class="breadcrumb-container">
								<?php bcn_display();?>
                            </div>
						<?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php endif; ?>

    <div id="primary" class="content-area page-content-area layout-<?php echo esc_attr( $page_layout ); ?>">
        <div class="container">
            <div class="row">
				<?php if ( $page_layout == 'left-sidebar' && is_active_sidebar( $selected_sidebar ) ) : ?>
                    <div class="col-lg-4 order-lg-0 order-last">
						<?php get_sidebar(); ?>
                    </div>
				<?php endif ?>

                <div class="<?php echo esc_attr( $page_column_class ); ?>">
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', 'page' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>
                </div>

				<?php if ( $page_layout == 'right-sidebar' && is_active_sidebar( $selected_sidebar ) ) : ?>
                    <div class="col-lg-4 order-lg-0 order-last">
						<?php get_sidebar(); ?>
                    </div>
				<?php endif ?>
            </div>
        </div>
    </div><!-- #primary -->

<?php get_footer();