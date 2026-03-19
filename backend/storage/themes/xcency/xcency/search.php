<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Xcency
 */

get_header();

$search_banner = xcency_option('search_banner', true);
$search_layout = xcency_option('search_layout', 'right-sidebar');
$banner_text_align = xcency_option('banner_default_text_align', 'center');
// btt = Banner Title Tag
$btt = xcency_option('banner_title_tag', 'h2');
?>

<?php if($search_banner == true) : ?>
    <div class="banner-area search-banner">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-lg-12 my-auto">
                    <div class="banner-content text-<?php echo esc_attr( $banner_text_align ); ?>">
                        <<?php echo esc_html($btt);?> class="banner-title"><?php
		                    /* translators: %s: search query. */
		                    printf( esc_html__( 'Search Results for: %s', 'xcency' ), '<span>' . get_search_query() . '</span>' );
		                    ?>
                        </<?php echo esc_html($btt);?>>

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

    <div id="primary" class="content-area layout-<?php echo esc_attr($search_layout);?>">
        <div class="container">
			<?php
			if($search_layout == 'grid'){
				get_template_part( 'template-parts/post/post-grid');
			}else{
				get_template_part( 'template-parts/post/post-sidebar');
			}
			?>
        </div>
    </div><!-- #primary -->

<?php
get_footer();
