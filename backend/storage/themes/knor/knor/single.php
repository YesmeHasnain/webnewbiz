<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package knor
 */

get_header();

?>

    <div class="theme-breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner">
                        <ul>
                            <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'knor'); ?></a></li><span class="breadcrumb-divider">/</span>
                            <li><?php esc_html_e('Blog Details', 'knor'); ?></li>
                        </ul>
                    </div>
                    <h1 class="theme-breacrumb-title">
						<?php esc_html_e('Blog Details', 'knor'); ?>
					</h1>

					<h5 class="page-short-description">
						<?php esc_html_e('We will help a client\'s problems to develop the products they have with high quality Change the appearance.', 'knor'); ?>
					</h5>

                </div>
            </div>
        </div>
    </div>


	<?php 

	//Single Blog Template
	
	$knor_singleb_global = knor_get_option( 'knor_single_blog_layout' ); //for globally	
	$knor_single_post_style = get_post_meta( get_the_ID(),'knor_blog_post_meta', true );

	$theme_post_meta_single = isset($knor_single_post_style['knor_single_blog_layout']) && !empty($knor_single_post_style['knor_single_blog_layout']) ? $knor_single_post_style['knor_single_blog_layout'] : '';
	
	if( is_single() && !empty( $knor_single_post_style  ) ) {
	 
		get_template_part( 'template-parts/single/'.$theme_post_meta_single.'' ); 
	
	} elseif ( class_exists( 'CSF' ) && !empty( $knor_singleb_global ) ) {
		
		get_template_part( 'template-parts/single/'.$knor_singleb_global.'' );  
		
	} else {
		
		get_template_part( 'template-parts/single/single-one' );  
	}
		
	?>


<?php get_footer(); ?>
