<?php
/*
* Content display template.
*/
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
    <div class="feature-post">
        <?php esc_attr_e("FEATURED","gauthier"); ?>
    </div>
    <?php endif; ?>
    <?php $thumb = get_post_thumbnail_id(); 
		$img_url = wp_get_attachment_url( $thumb,'full' ); 
		$image = aq_resize( $img_url, 900, 650, true,true,true ); ?>
    <?php if ($image) { ?>
    <div class="feature4-postimg"> <img src="<?php echo esc_html($image) ?>"/>
        <?php $get_description = get_post(get_post_thumbnail_id())->post_excerpt;
		if(!empty($get_description)){
			echo '<div class="singlepost-caption">' . $get_description . '</div>';
		}?>
    </div>
    <?php } else { ?>
    <?php } ?>
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
    <?php if (get_post_meta(get_the_ID(), "post_intro", true)) { ?>
    <div class="single2-intro"><?php echo esc_html(get_post_meta(get_the_ID(), "post_intro", true) ); ?></div>
    <?php } ?>
    <div class="entry-content contentinwrapper">
        <div class="entry-contentinside">
            <?php if(get_post_meta( get_the_ID(), 'adv_top_id' , true )){ ?>
            <div class="postadv-top"> <a href="<?php echo esc_html(get_post_meta(get_the_ID(), "adv_toplink", true) ); ?>" target="_blank"><?php echo  ''.$image_two = wp_get_attachment_image( get_post_meta( get_the_ID(), 'adv_top_id', 1 ), 'poster' ).''; ?></a></div>
            <?php }	?>
            <div class="postcolumns <?php echo esc_html(get_post_meta(get_the_ID(), "gauthier_post_columns", true) ); ?>">
                <?php the_content(esc_html__('Continue reading <span class="meta-nav">&rarr;</span>',"gauthier")); ?>
            </div>
            <?php wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'gauthier' ), 'after' => '</div>' ) ); ?>
            <?php if(get_post_meta( get_the_ID(), 'adv_horz_id' , true )){ ?>
            <div class="topadv-bottomgauthier"><a href="<?php echo esc_html(get_post_meta(get_the_ID(), "adv_bottomlink", true) ); ?>" target="_blank"><?php echo  ''.$image_two = wp_get_attachment_image( get_post_meta( get_the_ID(), 'adv_horz_id', 1 ), 'poster' ).''; ?></a></div>
            <?php }	?>
        </div>
        <div class="entry-contentadv">
            <div class="metaview-wrapper">
                <?php if (function_exists("gauthier_get_post_views")) { ?>
                <span class="metaview1"><i class="fa fa-eye" aria-hidden="true"></i><?php echo esc_html(gauthier_get_post_views()); ?></span>
                <?php } else { ?>
                <?php } ?>
                <?php if (function_exists("gauthier_reading_times")) { ?>
                <span class="metaview2"><i class="fa fa-hourglass-end" aria-hidden="true"></i><?php echo esc_html(gauthier_reading_times(get_the_ID())); ?></span>
                <?php } ?>
                <span class="metaview3"> <a class="link-comments" href="#respond"><i class="fa fa-comment-o" aria-hidden="true"></i>
                <?php comments_number(__('0 Comments','gauthier'),__('1 Comment','gauthier'),__('% Comments','gauthier')); ?>
                </a></span> </div>
            <?php if(get_post_meta( get_the_ID(), 'adv_vert_id' , true )){ ?>
            <div class="adv-sidebar"><a href="<?php echo esc_html(get_post_meta(get_the_ID(), "adv_vertlink", true) ); ?>" target="_blank"><?php echo  ''.$image_two = wp_get_attachment_image( get_post_meta( get_the_ID(), 'adv_vert_id', 1 ), 'poster' ).''; ?></a></div>
            <?php }	?>
        </div>
    </div>
</article>
<footer class="entry-meta">
    <?php esc_html_e('TAGS: ', 'gauthier'); ?>
    <?php 
	$before = '#';
	$seperator = '#'; // blank instead of comma
	$after = '';
	the_tags( $before, $seperator, $after );
	?>
</footer>