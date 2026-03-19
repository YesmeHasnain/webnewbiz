<?php
/*
* Content display template for Category 4
*/
?>
<div class="category3-jbottom">
     <div class="sticky-text">
          <?php esc_attr_e("FEATURED","gauthier"); ?>
     </div>

	 

		<?php $thumb = get_post_thumbnail_id(); 
		$img_url = wp_get_attachment_url( $thumb,'full' ); 
		$image = aq_resize( $img_url, 450, 225, true,true,true ); ?>
		<?php if ($image) { ?>
			<div class="category4-thumb">
			<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
			<img src="<?php echo esc_html($image) ?>"/>
			</a>
		</div>	
	<?php } else { ?>
<?php } ?>
            <div class="top-title-meta">
                <div class="submeta4-singlepost">
                    <div class="module8-author1">
                        <?php the_category(' , '); ?>
                    </div>
                    <div class="head-divider"></div>
                    <div class="head-date"> <?php echo get_the_date(); ?> </div>
                </div>
                <div class="adt-comment">
                    <div class="features-onsinglepost">
                        <?php if (function_exists("sharing_display")) {echo sharing_display();} ?>
                    </div>
                </div>
            </div>	
     <div class="module9-titlebig">
          <h2><a href="<?php the_permalink(); ?>">
               <?php the_title(); ?>
               </a></h2>
     </div>
     <div class="module31-content">
          <div class="hide-thumb">
               <?php echo gauthier_content(22); ?>
          </div>
     </div>
</div>