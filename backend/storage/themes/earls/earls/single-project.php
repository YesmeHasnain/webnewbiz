<?php get_header();
$data    = \EARLS\Includes\Classes\Common::instance()->data('single-project')->get();
//do_action( 'earls_banner', $data ); 
?>

<?php while (have_posts()) : the_post();
	$gall_images = (get_post_meta(get_the_id(), 'gallery_imgs', true ));
	$show_project_info = get_post_meta(get_the_id(), 'show_project_info', true);
	$term_list = wp_get_post_terms(get_the_id(), 'project_cat', array("fields" => "names"));
?>

<!-- banner-section -->
<section class="banner-style-six p_relative">
    <div class="portfolio-carousel owl-theme owl-carousel owl-dots-none nav-style-one">
        <?php 
			if ( !empty( $gall_images ) ) {
			$gall_images = explode(',', $gall_images);
			foreach ($gall_images as $gall) :
		?>
        <div class="slide-item">
            <div class="image-layer">
                <?php echo wp_get_attachment_image($gall, 'full');  ?>
            </div>
        </div>
        <?php endforeach; }; ?>
    </div>
</section>
<!-- banner-section end -->

<!-- portfolio-section end -->
<section class="portfolio__section see__pad">
    <div class="portfolio__details__contect">
        <div class="medium-container">
            <div class="row">
                <div class="<?php if($show_project_info) echo 'col-lg-8 col-md-12 col-sm-12'; else echo 'col-lg-12 col-md-12 col-sm-12' ?>">
                    <div class="portfolio__left">
                        <h4><?php the_title(); ?></h4>
                        <div class="portfolio__details__text">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
                
				<?php if($show_project_info){ ?>
                <div class="col-lg-1"></div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="portfolio__right">
                        <div class="catagory">
                            <?php if($term_list){ ?>
                            <div class="catagory__list">
                                <h6><?php echo (get_post_meta( get_the_id(), 'project_category_title', true ));?></h6>
                                <span class="right__site"><?php echo implode( ', ', (array)$term_list );?></span>
                            </div>
                            <?php } ?>
                            <?php if(get_post_meta( get_the_id(), 'project_date_title', true )){ ?>
                            <div class="catagory__list">
                                <h6><?php echo (get_post_meta( get_the_id(), 'project_date_title', true ));?></h6>
                                <span class="right__site"><?php echo get_the_date(); ?></span>
                            </div>
                            <?php } ?>
							<?php if(get_post_meta( get_the_id(), 'project_tags_title', true )){ ?>
                            <div class="catagory__list">
                                <h6><?php echo (get_post_meta( get_the_id(), 'project_tags_title', true ));?></h6>
                                <span class="right__site"><?php echo (get_post_meta( get_the_id(), 'project_tags', true ));?></span>
                            </div>
                            <?php } ?>
                            
							<?php
								$icons = get_post_meta( get_the_id(), 'project_social_profile', true );
								if ( ! empty( $icons ) ) :
							?>
                            <div class="catagory__list">
                                <h6><?php echo (get_post_meta( get_the_id(), 'project_social_title', true ));?></h6>
                                <div class="portfolio__social__midea right__site">
                                    <ul>
                                        <?php
										foreach ( $icons as $h_icon ) :
										$header_social_icons = json_decode( urldecode( earls_set( $h_icon, 'data' ) ) );
										if ( earls_set( $header_social_icons, 'enable' ) == '' ) {
											continue;
										}
										$icon_class = explode( '-', earls_set( $header_social_icons, 'icon' ) );
										?>
										<li><a href="<?php echo esc_url(earls_set( $header_social_icons, 'url' )); ?>" <?php if( earls_set( $header_social_icons, 'background' ) || earls_set( $header_social_icons, 'color' ) ):?>style="background-color:<?php echo esc_attr(earls_set( $header_social_icons, 'background' )); ?>; color: <?php echo esc_attr(earls_set( $header_social_icons, 'color' )); ?>"<?php endif;?>><span class="fab social_media <?php echo esc_attr( earls_set( $header_social_icons, 'icon' ) ); ?>"></span></a></li>
										<?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<!-- portfolio-section end -->

<?php endwhile; ?>
<?php get_footer(); ?>
