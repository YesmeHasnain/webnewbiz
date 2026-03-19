<?php
/*
* Content display template for category 1
*/
?>
<div class="category1-jtop">
	<div class="category1-thumbnail">
		<div class="sticky-text">
			<?php esc_attr_e("FEATURED", "gauthier"); ?>
		</div>
		<?php $thumb = get_post_thumbnail_id(); 
		$img_url = wp_get_attachment_url( $thumb,'full' ); 
		$image = aq_resize( $img_url, 930, 350, true,true,true ); ?>
		<?php if ($image) { ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<img src="<?php echo esc_html($image) ?>"/>
					</a>
        <?php $get_description = get_post(get_post_thumbnail_id())->post_excerpt;
		if(!empty($get_description)){
			echo '<div class="singlepost-caption">' . $get_description . '</div>';
		}?>
	<?php } else { ?>
<?php } ?>
	</div>
	<div class="category1-jbottom">
		<div class="category1-jbottomleft">
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
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			</div>
			<div class="module31-content">
				<div class="hide-thumb"><?php gauthier_one_excerpts(); ?></div>
			</div>
		</div>
		<div class="category1-jbottomright">
			<div class="module9-jbottomright2">
				<div class="category1-authors">				
				<div class="category1-authorwrapper">
					<div class="category1-authoravatarwrapper">
						<?php echo get_avatar(get_the_author_meta("user_email"), apply_filters("gauthier_author_bio_avatar_size", 225)); ?>
					</div>
					<div class="category1-desc10">
						<div class="category1-job10">
						<?php echo esc_html( get_the_author_meta("Position")); ?>
						</div>
						<div class="category1-name">
							<h6><a href="<?php echo get_author_posts_url(get_the_author_meta("ID")); ?>" rel="author"> <?php printf(__("%s", "gauthier"), get_the_author()); ?></a> </h6>
						</div>
						<div class="category1-job10">
							<?php the_author_posts(); ?>
							<a href="<?php echo get_author_posts_url(get_the_author_meta("ID")); ?>">
								<?php esc_attr_e("POSTS", "gauthier"); ?>
							</a>
						</div>
					</div>
					</div>	
					<div class="category1-desc"> <?php echo substr( get_the_author_meta("user_description"), 0,130); ?>
						<?php esc_attr_e(" ...", "gauthier"); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>