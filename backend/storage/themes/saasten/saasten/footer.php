<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package saasten
 */
 
$scroll_top = saasten_get_option('back_top_enable', true);
$footer_nav = saasten_get_option('footer_nav');
$footer_shortcode = saasten_get_option('footer-shortcode');
$footer_copyright_text = saasten_get_option('copyright_text');
$footer_copyright_text_allowed_tags = array(
    'a' => array(
        'href' => array(),
        'title' => array()
    ),
	'img' => array(
        'alt' => array(),
        'src' => array()
    ),
    'br' => array(),
    'em' => array(),
    'strong' => array(),
);

?>


    <!-- Footer Newsletter -->
    <div
      class="footer-newsletter-v3"
      style="background-image: url('<?php echo SAASTEN_IMG ."/footer-newsletter-bg.jpg"; ?>')"
    >
      <div class="footer-newsletter-v3__shape">
        <img src="<?php echo SAASTEN_IMG ."/footer-shape.svg"; ?>" />
      </div>
      <div class="overlay-v3"></div>
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="footer-newsletter-v3-main">
              <div
                data-aos="zoom-in-right"
                class="footer-newsletter-v3__content"
              >
                <h2 class="footer-newsletter-v3__title">
                   <?php esc_html_e('Lets get started Fostering a strong connection with your
                  audience.', 'saasten'); ?>
                </h2>
                <p class="footer-newsletter-v3__text">
                  <?php esc_html_e('For furthur info & support,', 'saasten'); ?> <a href="#"><?php esc_html_e('Contact us.', 'saasten'); ?></a>
                </p>
              </div>
              <div
                data-aos="zoom-in-left"
                class="footer-newsletter-v3-subscribe"
              >
                <div class="footer-newsletter-v3-subscribe__content">


                <?php echo do_shortcode( '[contact-form-7 id="94ca21f" title="Subscribe form"]' ); ?>

                  <p class="footer-newsletter-v3-subscribe__text">
                    <?php esc_html_e('We’ll contact you shortly', 'saasten'); ?>
                  </p>
                </div>
                <div class="footer-newsletter-v3-subscribe__img">
                  <img src="<?php echo SAASTEN_IMG ."/newsletter-img.png"; ?>" alt="#" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Footer Newsletter -->

    <!-- Footer -->
    <footer
      class="footer-area footer-area--v3"
      style="background-image: url('<?php echo SAASTEN_IMG ."/footer-bg-2.png"; ?>')"
    >
      <div class="container">
        <div class="row">

        <?php if ( is_active_sidebar( 'footer-widget-1' ) ): ?>

          <div data-aos="fade-right" class="col-lg-4 col-md-4 mg-top-30">
            <div class="footer-logo-area footer-logo-area--v3">
              
              <?php dynamic_sidebar( 'footer-widget-1' ); ?>


            </div>
          </div>

          <?php endif; ?> 


          <?php if ( is_active_sidebar( 'footer-widget-2' ) ): ?>
          <div
            data-aos="fade-right"
            data-aos-delay="200"
            class="col-lg-4 col-md-4 mg-top-30"
          >
            <div class="footer-menu rl-mg-100">
              <?php dynamic_sidebar( 'footer-widget-2' ); ?>
            </div>
          </div>
          <?php endif; ?> 


          <?php if ( is_active_sidebar( 'footer-widget-3' ) ): ?>

          <div
            data-aos="fade-right"
            data-aos-delay="400"
            class="col-lg-4 col-md-4 mg-top-30"
          >
            <div class="footer-menu">
              
              <?php dynamic_sidebar( 'footer-widget-3' ); ?>


            </div>
          </div>

          <?php endif; ?> 


        </div>
      </div>
    </footer>
    <!-- End Footer -->

    <!-- Copyright  -->
    <div class="footer-copyright footer-copyright--v3">
      <div class="container">
        <div
          class="d-flex flex-wrap footer-copyright"
        >
          <div class="footer-copyright__text">
            <p><?php if( !empty($footer_copyright_text) ){
				echo wp_kses($footer_copyright_text, $footer_copyright_text_allowed_tags);
			} else {
				esc_html_e('Copyright &copy; Saasten 2024. All rights reserved', 'saasten');
			}?></p>
          </div>

          <?php if($footer_nav == true) :?>
          <div class="footer-copyright__menu">
            <?php
               if ( has_nav_menu( 'footermenu' ) ) {
               
                  wp_nav_menu( array( 
                     'theme_location' => 'footermenu', 
					 'theme_location' => 'footermenu',
                     'menu_class' => 'footer-nav', 
                     'container' => '' 
                  ) );
               }

            ?>
          </div>

          <?php endif; ?> 


        </div>
      </div>
    </div>
    <!-- End Copyright  -->



	
	</div>
	
	<?php if($scroll_top == true) :?>
	<a href="#" class="scrollToTop"><i class="fas fa-arrow-up"></i></a>
	<?php endif; ?>

   <?php wp_footer(); ?>

   </body>
</html>

