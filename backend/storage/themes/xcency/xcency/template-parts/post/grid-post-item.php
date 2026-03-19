<?php
if(is_archive()){
	$post_item_layout = xcency_option('archive_layout', 'right-sidebar');
}else if(is_search()){
	$post_item_layout = xcency_option('search_layout', 'right-sidebar');
}else{
	$post_item_layout = xcency_option('blog_layout', 'right-sidebar');
}

if($post_item_layout == 'grid-ls' || $post_item_layout == 'grid-rs' || $post_item_layout == 'grid'){
	$word_count = 20;
}else{
	$word_count = 50;
}

$show_author_name = xcency_option('post_author', true);
$show_post_date = xcency_option('post_date', true);
$show_comment_number = xcency_option('cmnt_number', true);
$show_category = xcency_option('show_category', true);
$show_read_more = xcency_option('read_more_button', true);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    if(get_post_format() === 'gallery'){
        get_template_part( 'template-parts/post/post-format-gallery');
    }else if(get_post_format() === 'video'){
        get_template_part( 'template-parts/post/post-format-video');
    }else if(get_post_format() === 'audio'){
        get_template_part( 'template-parts/post/post-format-audio');
    }else{
        get_template_part( 'template-parts/post/post-format-others');
    }
    ?>

    <div class="xcency-recent-post-content">
        <div class="xcency-recent-post-title">
            <a href="<?php echo esc_url( get_the_permalink() );?>">
                <h4 class="post-title"><?php echo wp_trim_words(get_the_title(), 6, ' ...');?></h4>
            </a>
        </div>

        <div class="xcency-post-meta-and-button">
            <div class="xcency-post-meta post-meta">
                <ul class="xcency-list-style xcency-list-inline">

                    <?php if($show_author_name == true):?>
                        <li><?php xcency_posted_by(); ?></li>
                    <?php endif; ?>


                    <?php if ( get_comments_number() != 0 && $show_comment_number == true ) : ?>
                        <li class="comment-number"><?php xcency_comment_count(); ?></li>
                    <?php endif; ?>
                </ul>
            </div>

            <a class="xcency-icon-button" href="<?php echo esc_url( get_the_permalink() ) ?>">
                <i class="fas fa-arrow-right"></i>
            </a>

        </div>
    </div>
</article>





