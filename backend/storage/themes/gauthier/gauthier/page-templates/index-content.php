<?php
/*
* Content display template for index page
*/
?>
        <div class="sticky-text">
            <?php esc_attr_e("FEATURED","gauthier"); ?>
        </div>
<div class="index-innercontent">
    <div class="index-article">
<h2><a class="entry-title" href="<?php the_permalink();?>"><?php the_title(); ?></a></h2>	
        <div class="index-titlemeta">
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
		<?php gauthier_one_excerpts() ?>
		</div>
    <?php $thumb = get_post_thumbnail_id(); 
		$img_url = wp_get_attachment_url( $thumb,'full' ); 
		$image = aq_resize( $img_url, 400, 400, true,true,true ); ?>
    <?php if ($image) { ?>
    <div class="index-thumb"><img src="<?php echo esc_html($image) ?>"/>
    </div>
    <?php } else { ?>
    <?php } ?>
</div>