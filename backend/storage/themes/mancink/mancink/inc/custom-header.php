<?php


//function custom header by global settings
function mancink_custom_header_global()
{

    global $post;
    $header_id = get_theme_mod('mancink_select_header');
    
    

    $mancink_header = new WP_Query(array(
        'posts_per_page' => -1,
        'post_type' => 'header',
        'p' => $header_id,
    ));

    if ($mancink_header->have_posts()) : while ($mancink_header->have_posts()) : $mancink_header->the_post(); ?>

            <nav class="mancink-custom-header clearfix <?php echo esc_attr(get_post_meta(get_the_ID(), 'mancink_header_position', true)) ?>">

                <?php the_content(); ?>
            </nav>

        <?php endwhile;
    endif;
    wp_reset_postdata();
}

//function custom header by page settings
function mancink_custom_header_page()
{
    global $post;
    $header_id = get_post_meta(get_the_ID(), 'mancink_meta_choose_header', true);

    $mancink_header = new WP_Query(array(
        'posts_per_page' => 1,
        'post_type' => 'header',
        'p' => $header_id,
    ));

    if ($mancink_header->have_posts()) : while ($mancink_header->have_posts()) : $mancink_header->the_post(); ?>

            <nav class="mancink-custom-header clearfix <?php echo esc_attr(get_post_meta($post->ID, 'mancink_header_position', true)) ?>">
                <?php the_content(); ?>
            </nav>

        <?php endwhile;
    endif;
    wp_reset_postdata();
}

//function for output custom header
function mancink_header_start()
{
    if (is_singular()) { //if single page/post
        global $post;
        if (get_post_meta($post->ID, 'mancink_header_option', true) == 'custom' && get_post_meta($post->ID, 'mancink_meta_choose_header', true)) {

            //if page setting choose header custom
            do_action('mancink-header-page', 'mancink_custom_header_page');
        }

        //if page setting choose header global
        else if (get_post_meta($post->ID, 'mancink_header_option', true) == 'global') {

            //if custom header & list are selected in theme options
            if (get_theme_mod('custom_header_setting_value') == 'custom' && get_theme_mod('mancink_select_header') != '') {

                do_action('mancink-header-global', 'mancink_custom_header_global');
            } else {
                get_template_part('loop/menu', 'normal');
            }
        }

        //if page setting choose no header
        else if (get_post_meta($post->ID, 'mancink_header_option', true) == 'none') {
            //display nothing
        }

        //if page setting choose header standard
        else { ?>

            <!--HEADER START-->
            <?php get_template_part('loop/menu'); ?>
            <!--HEADER END-->

        <?php }
    } else { //if not single page/post

        //if custom header & list are selected in theme options
        if (get_theme_mod('custom_header_setting_value') == 'custom' && get_theme_mod('mancink_select_header') != '') {

            do_action('mancink-header-global', 'mancink_custom_header_global');
        } else { //if not use normal menu
            get_template_part('loop/menu', 'normal');
        }
    }
} ?>