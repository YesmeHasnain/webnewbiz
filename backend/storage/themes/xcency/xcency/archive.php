<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Xcency
 */

get_header();

$archive_layout = xcency_option('archive_layout', 'right-sidebar');
$archive_banner = xcency_option('archive_banner', true);
$banner_text_align = xcency_option('banner_default_text_align', 'left');
// btt = Banner Title Tag
$btt = xcency_option('banner_title_tag', 'h2');
?>

    <?php if($archive_banner == true):?>
    <div class="banner-area archive-banner">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-lg-12 my-auto">
                    <div class="banner-content text-<?php echo esc_attr( $banner_text_align ); ?>">
	                    <?php
	                    the_archive_title( '<'.$btt.' class="banner-title">', '</'.$btt.'>' );
	                    ?>

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
	<?php endif;?>

    <div id="primary" class="content-area layout-<?php echo esc_attr($archive_layout);?>">
        <div class="container">
			<?php
			if($archive_layout == 'grid'){
				get_template_part( 'template-parts/post/post-grid');
			}else{
				get_template_part( 'template-parts/post/post-sidebar');
			}
			?>
        </div>
    </div>

<?php
get_footer();
