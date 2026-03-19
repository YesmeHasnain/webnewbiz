<?php
if(is_archive()){
	$post_item_layout = xcency_option('archive_layout', 'right-sidebar');
}else if(is_search()){
	$post_item_layout = xcency_option('search_layout', 'right-sidebar');
}else{
	$post_item_layout = xcency_option('blog_layout', 'right-sidebar');
}

if($post_item_layout == 'grid'){
	$post_column = 'grid-post-item col-lg-4 col-md-6';
}else if ($post_item_layout == 'grid-ls' || $post_item_layout == 'grid-rs'){
	$post_column = 'grid-post-item col-lg-6 col-md-6';
}else{
	$post_column = 'col-lg-12';
}
?>

<div class="single-post-item <?php echo esc_attr($post_column); ?>">
    <?php
    if($post_item_layout == 'grid' || $post_item_layout == 'grid-ls' || $post_item_layout == 'grid-rs'){
	    get_template_part( 'template-parts/post/grid-post-item');
    }else{
	    get_template_part( 'template-parts/post/post-item');
    }
    ?>
</div>