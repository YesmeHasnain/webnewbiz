<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package seacab
 */

get_header();
?>

<!--Error Page Start-->
<section class="error-page">
   <div class="container">
         <div class="row">
            <div class="col-xl-12">
               <?php 
                  $seacab_404_bg = get_theme_mod('seacab_404_bg',get_template_directory_uri() . '/assets/images/resources/error-page-img-1.png');
                  $seacab_error_title = get_theme_mod('seacab_error_title', __('404', 'seacab'));
                  $seacab_error_subtitle = get_theme_mod('seacab_error_subtitle', __('Page not found', 'seacab'));
                  $seacab_error_link_text = get_theme_mod('seacab_error_link_text', __('Go To Home', 'seacab'));
                  $seacab_error_desc = get_theme_mod('seacab_error_desc', __('Oops! The page you are looking for does not exist. It might have been moved or deleted.', 'seacab'));
               ?>
               <div class="error-page__inner text-center">
                     <div class="error-page__img">
                        <img src="<?php echo esc_url($seacab_404_bg); ?>" alt="<?php print esc_attr__('Error 404','seacab'); ?>">
                     </div>
                     <div class="error__content">
                        <h2 class="error__title"><?php print esc_html($seacab_error_title);?></h2>
                        <h3 class="error__subtitle"><?php print esc_html($seacab_error_subtitle);?></h3>
                        <p><?php print esc_html($seacab_error_desc);?></p>
                     </div>
                     <div class="error-page__btn-box">
                        <a href="<?php print esc_url(home_url('/'));?>" class="thm-btn error-page__btn"><?php print esc_html($seacab_error_link_text);?></a>
                     </div>
               </div>
            </div>
         </div>
   </div>
</section>
<!--Error Page End-->

<?php
get_footer();
