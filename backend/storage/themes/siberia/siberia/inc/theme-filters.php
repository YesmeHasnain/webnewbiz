<?php

/*  Add Thumbnails To Admin & size them to max at 200px
/* ------------------------------------ */
add_filter('manage_posts_columns', 'add_thumbnail_column', 5);
 
function add_thumbnail_column($columns){
    $columns['new_post_thumb'] = esc_html__('Featured Image', 'siberia');
    return $columns;
}