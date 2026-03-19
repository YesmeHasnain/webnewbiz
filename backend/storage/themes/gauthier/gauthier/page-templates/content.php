<?php
/* Content display template */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <div class="top-title-meta">
            <div class="submeta-singlepost">
                <?php gauthier_breadcrumb(); ?>
                <div class="head-divider"></div>
                <div class="head-date"> <?php echo get_the_date(); ?> </div>
            </div>
            <div class="adt-comment">
                <div class="features-onsinglepost">
                    <?php if (function_exists("sharing_display")) {echo sharing_display();} ?>
                </div>
            </div>
        </div>
        <h1 class="entry-title">
            <?php the_title(); ?>
        </h1>
        <div class="coauthor-wrapper">
            <?php if ( function_exists( 'get_coauthors' ) ) {
				$coauthors = get_coauthors();
				foreach ( $coauthors as $coauthor ) {
			?>
            <div class="coauthor-wrapperinside"> <?php echo coauthors_get_avatar( $coauthor, 40 ); ?>
                <div class="coauthor-desc"> <a href="<?php echo get_author_posts_url( $coauthor->ID, $coauthor->user_nicename); ?>" > <?php echo esc_html ($coauthor->display_name); ?></a> <?php echo esc_html ($coauthor->Position); ?> </div>
            </div>
            <?php } 
			} else { ?>
            <div class="coauthor-wrapperinside"> <?php echo get_avatar( get_the_author_meta( 'user_email' ), 40 ); ?>
                <div class="coauthor-desc"> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"><?php printf( __( ' %s', 'gauthier' ), get_the_author() ); ?></a> <?php echo esc_html (get_the_author_meta('Position'));?> </div>
            </div>
            <?php } ?>
        </div>
    </header>
    <?php $thumb = get_post_thumbnail_id(); 
		$img_url = wp_get_attachment_url( $thumb,'full' ); 
		$image = aq_resize( $img_url, 1100, 550, true,true,true ); ?>
    <?php if ($image) { ?>
    <div class="feature-postimg"> <img src="<?php echo esc_html($image) ?>"/>
        <?php $get_description = get_post(get_post_thumbnail_id())->post_excerpt;
		if(!empty($get_description)){
			echo '<div class="singlepost-caption">' . $get_description . '</div>';
		}?>
    </div>
    <?php } else { ?>
    <?php } ?>
    <div class="singledefault">
        <div class="entry-content singleatyledefault-right">
            <?php if(get_post_meta( get_the_ID(), 'adv_top_id' , true )){ ?>
            <div class="adv-gauthier"> <a href="<?php echo esc_html(get_post_meta(get_the_ID(), "adv_toplink", true) ); ?>" target="_blank"><?php echo  ''.$image_two = wp_get_attachment_image( get_post_meta( get_the_ID(), 'adv_top_id', 1 ), 'poster' ).''; ?></a></div>
            <?php }	?>
            <div class="postcolumns <?php echo esc_html(get_post_meta(get_the_ID(), "gauthier_post_columns", true) ); ?>">
                <?php if (get_post_meta(get_the_ID(), "post_intro", true)) { ?>
                <div class="single2-intro"><?php echo esc_html(get_post_meta(get_the_ID(), "post_intro", true) ); ?></div>
                <?php } ?>
                <?php the_content(esc_html__('Continue reading <span class="meta-nav">&rarr;</span>',"gauthier")); ?>
            </div>
            <footer class="entry-meta">
                <?php esc_html_e('TAGS: ', 'gauthier'); ?>
                <?php 
				$before = '#';
				$seperator = '#'; 
				$after = '';
				the_tags( $before, $seperator, $after );
				?>
            </footer>
            <?php if(get_post_meta( get_the_ID(), 'adv_horz_id' , true )){ ?>
            <div class="adv-bottomgauthier"><a href="<?php echo esc_html(get_post_meta(get_the_ID(), "adv_bottomlink", true) ); ?>" target="_blank"><?php echo  ''.$image_two = wp_get_attachment_image( get_post_meta( get_the_ID(), 'adv_horz_id', 1 ), 'poster' ).''; ?></a></div>
            <?php }	?>
            <nav class="nav-single"> <span class="nav-previous">
                <?php get_template_part("inc/prev"); ?>
                </span> <span class="nav-next">
                <?php get_template_part("inc/next"); ?>
                </span> </nav>
            <?php comments_template("", true); ?>
        </div>
        <div class="sidebar-default">
            <?php get_sidebar(); ?>
        </div>
    </div>
</article>