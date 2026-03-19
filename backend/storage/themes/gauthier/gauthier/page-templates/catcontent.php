<?php
/*
* Content display template for category default
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
		$image = aq_resize( $img_url, 500, 500, true ); //resize & crop the image
		?>
        <?php if($image) : ?>
        <div class="categorydefaultcontent-image"><img src="<?php echo esc_url( $image ); ?>" /></div>
        <?php endif; ?>
        <div class="categorydefaultcontent-text">
            <header class="catcontent-content">
        <div class="categorydefault-titlemeta">
            <div class="index-submeta">
                <div class="index-catmeta">
                    <?php the_category(' , '); ?>
                </div>
                <div class="head-divider"></div>
                <div class="head-date"><?php echo get_the_date(); ?></div>
            </div>
            <div class="adt-comment">
                <div class="features-onsinglepost">
                    <?php if (function_exists("sharing_display")) {echo sharing_display();} ?>
                </div>
            </div>
        </div>	
                <h2><a class="entry-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            </header>
            <?php gauthier_one_excerpts() ?>
        </div>
        <?php wp_link_pages([
			"before" => '<div class="page-links">' . __("Pages:", "gauthier"),
			"after" => "</div>",
		]); ?>
    </div>
</article>