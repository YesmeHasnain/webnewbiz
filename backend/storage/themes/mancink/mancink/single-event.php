<?php

get_header();

//custom header
if (class_exists('Kirki')) {
    do_action('mancink-custom-header', 'mancink_header_start');
} else {?>

<!--Fall back if no kirki plugin installed-->
<!--HOME START-->
<div class="default-header clearfix">
    <!--HEADER START-->
    <?php get_template_part('loop/menu', 'normal');?>
    <!--HEADER END-->
</div>
<!--/home end-->
<!--HOME END-->

<?php }

if (have_posts()): while (have_posts()): the_post();

        //gallery at top
        if (get_post_meta(get_the_ID(), 'port_format', true) == 'port_standard') {?>

<div class="content page-content-wrapper">
    <div class="container-fluid clearfix">

        <div class="port-gallery-body port-top-gallery">




            <?php /* get the gallery list array */
            $lists = get_post_meta(get_the_ID(), 'gallery_list', 1);

            if (!empty($lists)) {
                foreach ((array) $lists as $list => $attachment_url) {?>
            <div class="port-item col-md-4">
                <div class="port-inner">
                    <a href="<?php echo esc_url(wp_get_attachment_image_url($list, 'full')); ?>"
                        class="popup-portfolio port-link slider-outer-box"
                        title="<?php echo esc_attr(get_the_title($list)); ?>"></a>
                    <div class="port-box"></div>
                    <div class="port-img width-img img-bg"
                        data-background="<?php echo esc_url(wp_get_attachment_image_url($list, 'full')); ?>"></div>
                </div>
                <!--/.port-inner-->

            </div>
            <!--.port-item-->

            <?php }
            }?>

            <div class="spacing40"></div>
        </div>
        <!--/.port-gallery-body-->

        <h3 class="single-work-title"><?php the_title();?></h3>

        <div class="swork-line"></div>
        <p class="event-location"><?php echo esc_html(get_post_meta(get_the_ID(), 'eventmb_location', true)); ?></p>
        <p class="event-date"><?php echo esc_html(get_post_meta(get_the_ID(), 'eventmb_date', true)); ?></p>
        <p class="event-time"><?php echo esc_html(get_post_meta(get_the_ID(), 'eventmb_time', true)); ?></p>
        <?php the_content();?>

        <?php if (get_post_meta(get_the_ID(), 'port_item_btn_text', true) != '' && get_post_meta(get_the_ID(), 'port_item_btn_link', true) != '') {?>
        <div class="spacing40 clearfix"></div>
        <a class="content-btn" href="<?php echo esc_url(get_post_meta(get_the_ID(), 'port_item_btn_link', true)); ?>">
            <?php echo esc_attr(get_post_meta(get_the_ID(), 'port_item_btn_text', true)); ?>
            <span class="content-btn-align-icon-right content-btn-button-icon">
                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
            </span>
        </a>
        <?php }?>

    </div>
    <!--/.container-fluid-->
</div>
<!--/.content-->

<?php } else { ?>
<div class="page-content-wrapper">
    <div class="home-video-box">
        <!--WORK SLIDER START-->
        <div class="home-slider ani-slider slider" data-slick='{"autoplaySpeed": 8000}'>

            <div class="slide">
                <div class="slider-mask" data-animation="slideUpReturn" data-delay="0.1s"></div>
                <div class="<?php if (get_post_meta(get_the_ID(), 'port_format', true) == 'port_two' && get_post_meta(get_the_ID(), 'top_type', true) == 'top_content_slider') {
                    echo 'work-img-bg';}?> slider-img-bg" data-animation="puffIn" data-delay="0.2s"
                    data-animation-duration="0.7s"
                    data-background="<?php echo esc_url(get_post_meta(get_the_ID(), 'port_slider_setting', true)); ?>">
                </div>
                <div class="slider-box container-fluid">
                    <div class="slider-content left-box-slider center-box-slider">
                        <?php $ridianur_taxonomy = 'event_category';
                        $ridianur_taxs = wp_get_post_terms($post->ID, $ridianur_taxonomy);?>
                        <p class="top-slider" data-animation="swashIn" data-delay="0.6s">
                            <?php $ridianur_cats = array();
                            foreach ($ridianur_taxs as $ridianur_tax) {
                                $ridianur_cats[] = $ridianur_tax->name;}
                            echo implode(', ', $ridianur_cats);?>
                        </p>

                        <div class="slider-hidden">
                            <h3 class="slider-title" data-animation="fadeInUp" data-delay="0.8s"><?php the_title();?>
                            </h3>
                        </div>
                        <!--/.slider-hidden-->

                        <div class="slider-line" data-animation="swashIn" data-delay="0.5s"></div>

                    </div>
                    <!--/.slider-content-->
                </div>
                <!--/.slider-box-->
            </div>
            <!--/.slide-->

        </div>
        <!--/.home-slider-->
        <!--WORK SLIDER END-->
    </div>


    <div class="content">
        <div class="container-fluid clearfix">
            <div class="row">
                <div class="col-lg-6">

                    <?php the_content();?>

                    <?php if (get_post_meta(get_the_ID(), 'port_item_btn_text', true) != '' && get_post_meta(get_the_ID(), 'port_item_btn_link', true) != '') {?>
                    <div class="spacing40 clearfix"></div>
                    <a class="content-btn"
                        href="<?php echo esc_url(get_post_meta(get_the_ID(), 'port_item_btn_link', true)); ?>">
                        <?php echo esc_attr(get_post_meta(get_the_ID(), 'port_item_btn_text', true)); ?>
                        <span class="content-btn-align-icon-right content-btn-button-icon">
                            <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                        </span>
                    </a>
                    <?php }?>
                    <div class="spacing40 clearfix"></div>
                </div>
                <!--/.col-lg-6-->

                <div class="col-lg-6">
                    <div class="port-gallery-body">

                        <?php /* get the gallery list array */
                        $lists = get_post_meta(get_the_ID(), 'gallery_list', 1);
                        if (!empty($lists)) {
                        foreach ((array) $lists as $list => $attachment_url) {?>
                        <div class="port-item col-md-6">
                            <div class="port-inner">
                                <a href="<?php echo esc_url(wp_get_attachment_image_url($list, 'full')); ?>"
                                    class="popup-portfolio port-link slider-outer-box"
                                    title="<?php echo esc_attr(get_the_title($list)); ?>"></a>
                                <div class="port-box"></div>
                                <div class="port-img width-img img-bg"
                                    data-background="<?php echo esc_url(wp_get_attachment_image_url($list, 'full')); ?>">
                                </div>
                            </div>
                            <!--/.port-inner-->

                        </div>
                        <!--.port-item-->

                        <?php }
                        }?>

                        <div class="spacing40"></div>
                    </div>
                    <!--/.port-gallery-body-->
                </div>
                <!--/.col-lg-6-->

            </div>
            <!--/.row-->

        </div>
        <!--/.container-fluid-->
    </div>
    <!--/.content-->
</div>
<?php }

endwhile;
endif;?>



<?php // get the custom post type's ridianur_taxonomy terms
$custom_taxterms = wp_get_object_terms($post->ID, 'event_category', array('fields' => 'ids'));
// arguments
$args = array(
    'post_type' => 'event',
    'post_status' => 'publish',
    'posts_per_page' => 4,
    'orderby' => 'rand',
    'tax_query' => array(
        array(
            'taxonomy' => 'event_category',
            'field' => 'id',
            'terms' => $custom_taxterms,
        ),
    ),
    'post__not_in' => array($post->ID),
);
$related_items = new WP_Query($args);
// loop over query
if ($related_items->have_posts()): $i = 1;?>
<div class="content gray-bg">
    <div class="container-fluid">
        <div class="other-event other-portfolio clearfix">

            <p class="op-sub"><?php if (class_exists('Kirki') && get_theme_mod('other_event_subtitle_text') != '') {
                echo esc_html(get_theme_mod('other_event_subtitle_text'));
            } else {
                esc_html_e('See our other event', 'mancink');
            }?></p>

            <h3 class="op-title">
                <?php if (class_exists('Kirki') && get_theme_mod('other_event_title_text') != '') {
        echo esc_html(get_theme_mod('other_event_title_text'));
    } else {
        esc_html_e('Other event', 'mancink');
    }?>
            </h3>

            <?php while ($related_items->have_posts()): $related_items->the_post();?>

            <div class="col-md-3 port-item">
                <div class="port-inner">
                    <a href="<?php the_permalink();?>" class="port-link"></a>
                    <div class="port-box"></div>
                    <div class="port-img width-img img-bg"
                        data-background="<?php echo get_the_post_thumbnail_url(); ?>"></div>
                    <div class="port-dbox">
                        <div class="dbox-relative">
                            <h3><?php the_title();?></h3>
                            <?php $ridianur_taxonomy = 'event_category';
        $args = array('number' => '1');
        $ridianur_taxs = wp_get_post_terms($post->ID, $ridianur_taxonomy, $args);?>
                            <p><?php $ridianur_cats = array();
        foreach ($ridianur_taxs as $ridianur_tax) {
            $ridianur_cats[] = $ridianur_tax->name;
        }
        echo implode(', ', $ridianur_cats);?></p>
                        </div>
                        <!--/.dbox-relative-->
                    </div>
                    <!--/.port-dbox-->
                </div>
                <!--/.port-inner-->
            </div>
            <!--.port-item-->

            <?php endwhile;?>


        </div>
        <!--/.other-event-->
    </div>
    <!--/container-fluid-->
</div>
<?php endif;
wp_reset_postdata();?>



<?php
//custom footer
if (class_exists('Kirki')) {
    do_action('mancink-custom-footer', 'mancink_footer_start');
} else {
    //fallback if no kirki installed
    get_template_part('loop/bottom', 'footer');
}?>



<?php get_footer();?>