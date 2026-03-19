<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package saasten
 */
 
get_header();

?>


    <!-- 404 Error -->
    <section class="section-padding error-page">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <div class="img-not-found">
              <img src="<?php echo SAASTEN_IMG ."/404.png"; ?>" alt="#" />
            </div>
            <h3 class="not-found-title"><?php esc_html_e('Oops! Page Not Found', 'saasten'); ?></h3>
            <a
              href="<?php echo esc_url(home_url('/')); ?>"
              class="saastain-btn primary-bg saastain-btn__v4 pr-lg border-radius-31 mg-top-30"
              ><?php esc_html_e('Back to Home Page', 'saasten'); ?></a
            >
          </div>
        </div>
      </div>
    </section>
    <!-- End 404 Error -->


<?php get_footer(); ?>
