<?php
/*
* Content display template for category 3
*/
?>
<div class="category3-jbottom">
	<div class="sticky-text">
		<?php esc_attr_e("FEATURED", "gauthier"); ?>
	</div>
            <div class="top-title-meta">
                <div class="submeta4-singlepost">
                    <div class="module8-author1">
                        <?php the_category(' , '); ?>
                    </div>
                    <div class="head-divider"></div>
                    <div class="head-date"> <?php echo get_the_date(); ?> </div>
                </div>
            </div>	
	<div class="module9-titlebig">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	</div>
	<div class="module31-content">
		 <?php echo gauthier_content(44); ?>
	</div>
</div>