<?php
global $startli_option;
$startli_shop_id = get_option('woocommerce_shop_page_id');
$header_width_meta = get_post_meta($startli_shop_id, 'header_width_custom', true);
if ($header_width_meta != '') {
    $header_width = ($header_width_meta == 'full') ? 'container-fluid' : 'container';
} else {
    $header_width = !empty($startli_option['header-grid']) ? $startli_option['header-grid'] : '';
    $header_width = ($header_width == 'full') ? 'container-fluid' : 'container';
}
?>

<?php
$header_trans = '';
if (!empty($startli_option['header_layout'])) {
    $header_style = $startli_option['header_layout'];
    if ($header_style == 'style2') {
        $header_trans = 'heads_trans';
    }
}
?>

<?php
$post_menu_type = get_post_meta($startli_shop_id, 'menu-type', true);
$post_meta_data = get_post_meta($startli_shop_id, 'banner_image', true);
$content_banner = get_post_meta($startli_shop_id, 'content_banner', true);
?>

<div class="reactheme-breadcrumbs porfolio-details <?php echo esc_attr($header_trans); ?>">
    <?php if ($post_meta_data != '') { ?>
        <div class="breadcrumbs-single" style="background-image: url('<?php echo esc_url($post_meta_data); ?>')">
            <div class="<?php echo esc_attr($header_width); ?>">
                <div class="breadcrumbs-inner bread-<?php echo esc_attr($post_menu_type); ?>">
                    <div class="row">
                        <div class="col-lg-12">

                            <?php
                            $post_meta_title = get_post_meta($startli_shop_id, 'select-title', true); ?>
                            <?php if ($post_meta_title != 'hide') {
                            ?>
                                <h1 class="page-title">
                                    <?php if ($content_banner != '') {
                                        echo esc_html($content_banner);
                                    } else {
                                        the_archive_title();
                                    }
                                    ?>
                                </h1>
                            <?php } ?>
                        </div>
                        <div class="col-lg-12">
                            <?php if (!empty($startli_option['off_breadcrumb'])) {
                                $rs_breadcrumbs = get_post_meta($startli_shop_id, 'select-bread', true);
                                if ($rs_breadcrumbs != 'hide') :
                                    if (function_exists('bcn_display')) { ?>
                                        <div class="breadcrumbs-title"> <?php bcn_display(); ?></div>
                            <?php }
                                endif;
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } elseif (!empty($startli_option['shop_banner']['url'])) {
        $shop_banner = $startli_option['shop_banner']['url']; ?>
        <div class="breadcrumbs-single" style="background-image: url('<?php echo esc_url($shop_banner); ?>')">
            <div class="<?php echo esc_attr($header_width); ?>">
                <div class="breadcrumbs-inner bread-<?php echo esc_attr($post_menu_type); ?>">
                    <div class="row">
                        <div class="col-lg-12">

                            <?php
                            $post_meta_title = get_post_meta($startli_shop_id, 'select-title', true); ?>
                            <?php if ($post_meta_title != 'hide') {
                            ?>
                                <h1 class="page-title">
                                    <?php if ($content_banner != '') {
                                        echo esc_html($content_banner);
                                    } else {
                                        the_archive_title();
                                    }
                                    ?>
                                </h1>
                            <?php } ?>
                        </div>
                        <div class="col-lg-12">
                            <?php if (!empty($startli_option['off_breadcrumb'])) {
                                $rs_breadcrumbs = get_post_meta($startli_shop_id, 'select-bread', true);
                                if ($rs_breadcrumbs != 'hide') :
                                    if (function_exists('bcn_display')) { ?>
                                        <div class="breadcrumbs-title"> <?php bcn_display(); ?></div>
                            <?php }
                                endif;
                            }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <?php } elseif (!empty($startli_option['breadcrumb_bg_color'])) {
    ?>
        <div class="breadcrumbs-single" style="background:<?php echo esc_attr($startli_option['breadcrumb_bg_color']); ?>">
            <div class="<?php echo esc_attr($header_width); ?>">
                <div class="breadcrumbs-inner bread-<?php echo esc_attr($post_menu_type); ?>">
                    <div class="row">
                        <div class="col-lg-12">

                            <?php
                            $post_meta_title = get_post_meta($startli_shop_id, 'select-title', true); ?>
                            <?php if ($post_meta_title != 'hide') {
                            ?>
                                <h1 class="page-title">
                                    <?php if ($content_banner != '') {
                                        echo esc_html($content_banner);
                                    } else {
                                        the_archive_title();
                                    }
                                    ?>
                                </h1>
                            <?php } ?>
                        </div>
                        <div class="col-lg-4 col-md-5 text-lg-end">
                            <?php if (!empty($startli_option['off_breadcrumb'])) {
                                $rs_breadcrumbs = get_post_meta($startli_shop_id, 'select-bread', true);
                                if ($rs_breadcrumbs != 'hide') :
                                    if (function_exists('bcn_display')) { ?>
                                        <div class="breadcrumbs-title"> <?php bcn_display(); ?></div>
                            <?php }
                                endif;
                            }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
</div>
<?php } else {
?>
    <div class="reactheme-breadcrumbs-inner">
        <div class="<?php echo esc_attr($header_width); ?>">
            <div class="breadcrumbs-inner bread-<?php echo esc_attr($post_menu_type); ?>">
                <div class="row">
                    <div class="col-lg-12">

                        <?php
                        $post_meta_title = get_post_meta($startli_shop_id, 'select-title', true); ?>
                        <?php if ($post_meta_title != 'hide') {
                        ?>
                            <h1 class="page-title">
                                <?php if ($content_banner != '') {
                                    echo esc_html($content_banner);
                                } else {
                                    the_archive_title();
                                }
                                ?>
                            </h1>
                        <?php } ?>
                    </div>
                    <div class="col-lg-12">
                        <?php if (!empty($startli_option['off_breadcrumb'])) {
                            $rs_breadcrumbs = get_post_meta($startli_shop_id, 'select-bread', true);
                            if ($rs_breadcrumbs != 'hide') :
                                if (function_exists('bcn_display')) { ?>
                                    <div class="breadcrumbs-title"> <?php bcn_display(); ?></div>
                        <?php }
                            endif;
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
<?php
    }
?>
</div>