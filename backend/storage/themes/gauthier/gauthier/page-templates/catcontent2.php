<?php
/*
* Content display template for category 2
*/
?>
<div class="category2-jtop">
	<div class="sticky-text">
		<?php esc_attr_e("FEATURED", "gauthier"); ?>
	</div>
	<div class="category2-jbottom">
		<div class="category1-jbottomleft">
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
			<h2><a class="entry-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<div class="module31-content">
				<div class="hide-thumb"><?php gauthier_one_excerpts(); ?></div>
			</div>
		</div>
		<div class="category1-jbottomright">
			<?php $thumb = get_post_thumbnail_id();
			$img_url = wp_get_attachment_url( $thumb,'full' ); //get full URL to image (use "large" or "medium" if the images too big)
			$image = aq_resize( $img_url, 600, 375, true ); //resize & crop the image
			?>
			<?php if($image) : ?>
			<div class="categorydefaultcontent-image">
			<img src="<?php echo esc_url( $image ); ?>"/></div>
			<?php endif; ?>
		</div>
	</div>
</div>