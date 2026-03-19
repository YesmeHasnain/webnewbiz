<?php
/*
* Content display template for category 5
*/
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if (is_sticky() && is_home() && !is_paged()): ?>
    <div class="sticky-text">
        <?php esc_attr_e("FEATURED", "gauthier"); ?>
    </div>
    <?php endif; ?>
    <div class="entry-content categorydefaultcontent-wrapper">
        <?php $thumb = get_post_thumbnail_id();
		$img_url = wp_get_attachment_url( $thumb,'full' ); //get full URL to image (use "large" or "medium" if the images too big)
		$image = aq_resize( $img_url, 444, 444, true ); //resize & crop the image
		?>
        <?php if($image) : ?>
        <div class="categorydefaultcontent-image"><img src="<?php echo esc_url( $image ); ?>" /></div>
        <?php endif; ?>
        <div class="categorydefaultcontent-text">
            <header class="catcontent-content">
                <h2><a class="entry-title" href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                    </a></h2>
                <div class="index-content-author">
                    <div class="module8-author1"> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"> <?php printf( __( '%s', 'gauthier' ), get_the_author() ); ?></a>
                        <?php if(get_the_author_meta('Position') ): ?>
                        <?php esc_attr_e(' / ', 'gauthier'); ?>
                        <?php echo get_the_author_meta('Position'); ?>
                        <?php else: ?>
                        <?php endif; ?>
                        <?php esc_attr_e(' - ', 'gauthier'); ?>
                        <?php echo get_the_date(); ?> </div>
                </div>
            </header>
            <?php echo gauthier_content(33); ?> </div>
        <?php wp_link_pages([
			"before" => '<div class="page-links">' . __("Pages:", "gauthier"),
			"after" => "</div>",
		]); ?>
    </div>
</article>