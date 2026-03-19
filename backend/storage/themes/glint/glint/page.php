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
 * @package WordPress
 * @subpackage Glint
 * @since 1.0.0
 */

global $post;

if (get_post_meta($post->ID, 'glint_page_meta', true)) {
   $page_meta = get_post_meta($post->ID, 'glint_page_meta', true);
} else {
   $page_meta = array();
}

if (array_key_exists('enable_title', $page_meta)) {
   $enable_title = $page_meta['enable_title'];
} else {
   $enable_title = true;
}

if (array_key_exists('enable_dark', $page_meta)) {
   $enable_dark = $page_meta['enable_dark'];
} else {
   $enable_dark = false;
}

if (array_key_exists('custom_title', $page_meta)) {
   $custom_title = $page_meta['custom_title'];
} else {
   $custom_title = '';
}

if (array_key_exists('title_bg', $page_meta)) {
   $title_bg = $page_meta['title_bg'];
} else {
   $title_bg = '';
}

$page_spacing = cs_get_option('page_spacing_enable');

$page_spacing_class = '';
if ($page_spacing == 1) {
   $page_spacing_class = '';
} else {
   $page_spacing_class = 'glint-page-containerr';
}

$page_dark_class = '';
if ($enable_dark == true) {
   $page_dark_class = 'glint-page-dark-wrap';
} else {
   $page_dark_class = '';
}

get_header();
?>

<!--:::::WELCOME ATRA START :::::::-->
<?php if ($enable_title == true) : ?>

   <div class="welcome-area-wrap" <?php if (!empty($title_bg)) : ?> style="background: url(<?php echo esc_url($title_bg); ?>);background-size: cover;background-position: center;" <?php
                                                                                                                                                                                 endif; ?>>
      <!--::::: WELCOME CAROUSEL START :::::::-->
      <div class="welcome-area inner">
         <div class="container">
            <div class="row">
               <div class="col-lg-12">
                  <div class="inner-wlc">
                     <h2>
                        <?php
                        if (!empty($custom_title)) {
                           echo esc_html($custom_title);
                        } else {
                           the_title();
                        }
                        ?>
                     </h2>
                     <h3><?php if (function_exists('bcn_display')) bcn_display(); ?></h3>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!--::::: WELCOME CAROUSEL END:::::::-->
   </div>
   <!--:::::WELCOME AREA END :::::::-->
<?php endif; ?>

<div id="primary" class="content-area <?php echo esc_attr($page_spacing_class); ?> <?php echo esc_attr($page_dark_class); ?>">
   <main id="main" class="site-main">
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <?php
               while (have_posts()) :
                  the_post();
                  get_template_part('template-parts/content', 'page');
               endwhile; // End of the loop.
               ?>
               <?php comments_template(); ?>
            </div>
         </div>
      </div>
   </main><!-- #main -->
</div><!-- #primary -->
<?php
get_footer();
